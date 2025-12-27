<?php
// app/controllers/CertificationController.php
require_once 'BaseController.php';

class CertificationController extends BaseController
{

  public function index()
  {
    $subAction = $_GET['sub'] ?? 'index';

    switch ($subAction) {
      case 'request':
        $this->requestForm();
        break;
      case 'approve':
        $this->approve($_GET['id'] ?? 0);
        break;
      case 'reject':
        $this->reject($_GET['id'] ?? 0);
        break;
      case 'create':
        $this->createRequest();
        break;
      case 'view':
        $this->view($_GET['id'] ?? 0);
        break;
      default:
        $this->listCertifications();
        break;
    }
  }

  private function listCertifications()
  {
    try {
      // Fetch all certifications with resident names
      $stmt = $this->pdo->query("
                SELECT c.*, r.full_name as resident_name
                FROM tbl_certifications c
                JOIN tbl_residents r ON c.resident_id = r.id
                ORDER BY c.issue_date DESC, c.id DESC
            ");
      $certifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to load certifications: " . $e->getMessage();
      $certifications = [];
    }

    $this->render('certifications/certifications', [
      'certifications' => $certifications,
      'title' => 'Certifications Management'
    ]);
  }

  public function requestForm()
  {
    // Check if user is a resident
    if ($_SESSION['role'] !== 'resident') {
      $_SESSION['error'] = "Only residents can request certifications.";
      $this->redirect('dashboard');
    }

    // Get resident ID from session (assuming user is linked to resident)
    $residentId = $this->getResidentIdFromUser();

    if (!$residentId) {
      $_SESSION['error'] = "Resident profile not found.";
      $this->redirect('dashboard');
    }

    $this->render('certifications/request', [
      'title' => 'Request Certification',
      'residentId' => $residentId
    ]);
  }

  private function createRequest()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirect('certifications', ['sub' => 'request']);
    }

    if ($_SESSION['role'] !== 'resident') {
      $_SESSION['error'] = "Only residents can request certifications.";
      $this->redirect('dashboard');
    }

    $errors = [];

    $residentId = $this->getResidentIdFromUser();
    $type = $_POST['type'] ?? '';
    $purpose = trim($_POST['purpose'] ?? '');

    if (!$residentId) {
      $errors[] = "Resident profile not found.";
    }

    if (empty($type)) {
      $errors[] = "Certificate type is required.";
    }

    if (empty($purpose)) {
      $errors[] = "Purpose is required.";
    }

    $validTypes = ['clearance', 'indigency', 'residency', 'other'];
    if (!in_array($type, $validTypes)) {
      $errors[] = "Invalid certificate type.";
    }

    if (empty($errors)) {
      try {
        $stmt = $this->pdo->prepare("
                    INSERT INTO tbl_certifications
                    (resident_id, type, purpose, issue_date, status)
                    VALUES (?, ?, ?, CURDATE(), 'pending')
                ");
        $stmt->execute([$residentId, $type, $purpose]);

        // Get resident name for logging
        $stmt = $this->pdo->prepare("SELECT full_name FROM tbl_residents WHERE id = ?");
        $stmt->execute([$residentId]);
        $resident = $stmt->fetch(PDO::FETCH_ASSOC);

        // Log the action
        $this->logAction(
          $_SESSION['user_id'],
          "Requested " . $type . " certification for " . ($resident['full_name'] ?? 'Unknown')
        );

        $_SESSION['success'] = "Certificate request submitted successfully!";
        $this->redirect('certifications');
      } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to submit request: " . $e->getMessage();
        $this->redirect('certifications', ['sub' => 'request']);
      }
    } else {
      $_SESSION['error'] = implode("<br>", $errors);
      $_SESSION['old'] = $_POST;
      $this->redirect('certifications', ['sub' => 'request']);
    }
  }

  private function approve($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid certification ID.";
      $this->redirect('certifications');
    }

    try {
      $stmt = $this->pdo->prepare("
                UPDATE tbl_certifications
                SET status = 'issued', issued_by = ?, issue_date = CURDATE()
                WHERE id = ?
            ");
      $stmt->execute([$_SESSION['user_id'], $id]);

      // Get certification details for logging
      $stmt = $this->pdo->prepare("
                SELECT c.type, r.full_name
                FROM tbl_certifications c
                JOIN tbl_residents r ON c.resident_id = r.id
                WHERE c.id = ?
            ");
      $stmt->execute([$id]);
      $cert = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($cert) {
        $this->logAction(
          $_SESSION['user_id'],
          "Approved " . $cert['type'] . " certification for " . $cert['full_name']
        );
      }

      $_SESSION['success'] = "Certificate approved and issued successfully!";
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to approve certificate: " . $e->getMessage();
    }

    $this->redirect('certifications');
  }

  private function reject($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid certification ID.";
      $this->redirect('certifications');
    }

    $reason = trim($_POST['reason'] ?? 'No reason provided');

    try {
      $stmt = $this->pdo->prepare("
                UPDATE tbl_certifications
                SET status = 'rejected'
                WHERE id = ?
            ");
      $stmt->execute([$id]);

      // Get certification details for logging
      $stmt = $this->pdo->prepare("
                SELECT c.type, r.full_name
                FROM tbl_certifications c
                JOIN tbl_residents r ON c.resident_id = r.id
                WHERE c.id = ?
            ");
      $stmt->execute([$id]);
      $cert = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($cert) {
        $this->logAction(
          $_SESSION['user_id'],
          "Rejected " . $cert['type'] . " certification for " . $cert['full_name'] . ". Reason: " . $reason
        );
      }

      $_SESSION['success'] = "Certificate rejected.";
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to reject certificate: " . $e->getMessage();
    }

    $this->redirect('certifications');
  }

  private function view($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid certification ID.";
      $this->redirect('certifications');
    }

    try {
      $stmt = $this->pdo->prepare("
                SELECT c.*, r.full_name as resident_name, r.address, r.birthdate,
                       u.full_name as issued_by_name
                FROM tbl_certifications c
                JOIN tbl_residents r ON c.resident_id = r.id
                LEFT JOIN tbl_users u ON c.issued_by = u.id
                WHERE c.id = ?
            ");
      $stmt->execute([$id]);
      $certification = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$certification) {
        $_SESSION['error'] = "Certificate not found.";
        $this->redirect('certifications');
      }
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to load certificate: " . $e->getMessage();
      $this->redirect('certifications');
    }

    $this->render('certifications/view', [
      'certification' => $certification,
      'title' => 'View Certification'
    ]);
  }

  private function getResidentIdFromUser()
  {
    try {
      // Assuming there's a link between users and residents
      $stmt = $this->pdo->prepare("
                SELECT id FROM tbl_residents
                WHERE user_id = ?
                LIMIT 1
            ");
      $stmt->execute([$_SESSION['user_id']]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result ? $result['id'] : null;
    } catch (PDOException $e) {
      error_log("Failed to get resident ID: " . $e->getMessage());
      return null;
    }
  }

  private function logAction($userId, $action)
  {
    try {
      $stmt = $this->pdo->prepare("INSERT INTO tbl_logs (user_id, action) VALUES (?, ?)");
      $stmt->execute([$userId, $action]);
    } catch (PDOException $e) {
      error_log("Failed to log action: " . $e->getMessage());
    }
  }
}
