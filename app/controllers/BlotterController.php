<?php
// app/controllers/BlotterController.php
require_once 'BaseController.php';

class BlotterController extends BaseController
{

  public function index()
  {
    $sub = $_GET['sub'] ?? 'list';

    switch ($sub) {
      case 'add':
        $this->addForm();
        break;
      case 'edit':
        $this->editForm($_GET['id'] ?? 0);
        break;
      case 'delete':
        $this->delete($_GET['id'] ?? 0);
        break;
      case 'create':
        $this->create();
        break;
      case 'update':
        $this->update();
        break;
      case 'settle':
        $this->settle($_GET['id'] ?? 0);
        break;
      default:
        $this->list();
        break;
    }
  }

  private function list()
  {
    try {
      $stmt = $this->pdo->query("
                SELECT b.*,
                       c.full_name as complainant_name,
                       r.full_name as respondent_name
                FROM tbl_blotters b
                LEFT JOIN tbl_residents c ON b.complainant_id = c.id
                LEFT JOIN tbl_residents r ON b.respondent_id = r.id
                ORDER BY b.incident_date DESC, b.id DESC
            ");
      $blotters = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to load blotters: " . $e->getMessage();
      $blotters = [];
    }

    $this->render('blotters/blotters', [
      'blotters' => $blotters,
      'title' => 'Blotter Records'
    ]);
  }

  private function addForm()
  {
    // Get residents for dropdown
    try {
      $stmt = $this->pdo->query("SELECT id, full_name FROM tbl_residents ORDER BY full_name");
      $residents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to load residents: " . $e->getMessage();
      $residents = [];
    }

    $this->render('blotters/add_blotter', [
      'residents' => $residents,
      'title' => 'Add New Blotter'
    ]);
  }

  private function editForm($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid blotter ID.";
      $this->redirect('blotters');
    }

    try {
      // Get blotter details
      $stmt = $this->pdo->prepare("
                SELECT b.*,
                       c.full_name as complainant_name,
                       r.full_name as respondent_name
                FROM tbl_blotters b
                LEFT JOIN tbl_residents c ON b.complainant_id = c.id
                LEFT JOIN tbl_residents r ON b.respondent_id = r.id
                WHERE b.id = ?
            ");
      $stmt->execute([$id]);
      $blotter = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$blotter) {
        $_SESSION['error'] = "Blotter not found.";
        $this->redirect('blotters');
      }

      // Get residents for dropdown
      $stmt = $this->pdo->query("SELECT id, full_name FROM tbl_residents ORDER BY full_name");
      $residents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to load blotter: " . $e->getMessage();
      $this->redirect('blotters');
    }

    $this->render('blotters/edit_blotter', [
      'blotter' => $blotter,
      'residents' => $residents,
      'title' => 'Edit Blotter'
    ]);
  }

  private function create()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirect('blotters', ['sub' => 'add']);
    }

    $errors = [];

    // Validate inputs
    $complainant_id = $_POST['complainant_id'] ?? 0;
    $respondent_id = $_POST['respondent_id'] ?? 0;
    $description = trim($_POST['description'] ?? '');
    $incident_date = $_POST['incident_date'] ?? date('Y-m-d');
    $status = 'open';

    if (empty($complainant_id)) $errors[] = "Complainant is required.";
    if (empty($description)) $errors[] = "Description is required.";
    if (empty($incident_date)) $errors[] = "Incident date is required.";

    if (empty($errors)) {
      try {
        $stmt = $this->pdo->prepare("
                    INSERT INTO tbl_blotters
                    (complainant_id, respondent_id, description, incident_date, status)
                    VALUES (?, ?, ?, ?, ?)
                ");
        $stmt->execute([
          $complainant_id,
          $respondent_id ?: NULL,
          $description,
          $incident_date,
          $status
        ]);

        $blotterId = $this->pdo->lastInsertId();

        // Log the action
        $this->logAction($_SESSION['user_id'] ?? 0, "Added new blotter record (ID: $blotterId)");

        $_SESSION['success'] = "Blotter record added successfully!";
        $this->redirect('blotters');
      } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to add blotter: " . $e->getMessage();
        $this->redirect('blotters', ['sub' => 'add']);
      }
    } else {
      $_SESSION['error'] = implode("<br>", $errors);
      $_SESSION['old'] = $_POST;
      $this->redirect('blotters', ['sub' => 'add']);
    }
  }

  private function update()
  {
    // Get ID from POST
    $id = $_POST['id'] ?? 0;

    if (!$id) {
      $_SESSION['error'] = "Invalid blotter ID.";
      $this->redirect('blotters');
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirect('blotters', ['sub' => 'edit', 'id' => $id]);
    }

    $errors = [];

    // Validate inputs
    $complainant_id = $_POST['complainant_id'] ?? 0;
    $respondent_id = $_POST['respondent_id'] ?? 0;
    $description = trim($_POST['description'] ?? '');
    $incident_date = $_POST['incident_date'] ?? '';
    $status = $_POST['status'] ?? 'open';
    $resolution = trim($_POST['resolution'] ?? '');

    if (empty($complainant_id)) $errors[] = "Complainant is required.";
    if (empty($description)) $errors[] = "Description is required.";
    if (empty($incident_date)) $errors[] = "Incident date is required.";
    if (empty($status)) $errors[] = "Status is required.";

    $validStatuses = ['open', 'settled', 'escalated'];
    if (!in_array($status, $validStatuses)) {
      $errors[] = "Invalid status.";
    }

    if (empty($errors)) {
      try {
        $stmt = $this->pdo->prepare("
                    UPDATE tbl_blotters SET
                    complainant_id = ?,
                    respondent_id = ?,
                    description = ?,
                    incident_date = ?,
                    status = ?,
                    resolution = ?
                    WHERE id = ?
                ");
        $stmt->execute([
          $complainant_id,
          $respondent_id ?: NULL,
          $description,
          $incident_date,
          $status,
          $resolution,
          $id
        ]);

        // Log the action
        $this->logAction($_SESSION['user_id'] ?? 0, "Updated blotter record (ID: $id)");

        $_SESSION['success'] = "Blotter updated successfully!";
        $this->redirect('blotters');
      } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to update blotter: " . $e->getMessage();
        $this->redirect('blotters', ['sub' => 'edit', 'id' => $id]);
      }
    } else {
      $_SESSION['error'] = implode("<br>", $errors);
      $_SESSION['old'] = $_POST;
      $this->redirect('blotters', ['sub' => 'edit', 'id' => $id]);
    }
  }

  private function delete($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid blotter ID.";
      $this->redirect('blotters');
    }

    try {
      // Delete blotter
      $stmt = $this->pdo->prepare("DELETE FROM tbl_blotters WHERE id = ?");
      $stmt->execute([$id]);

      if ($stmt->rowCount() > 0) {
        // Log the action
        $this->logAction($_SESSION['user_id'] ?? 0, "Deleted blotter record (ID: $id)");

        $_SESSION['success'] = "Blotter deleted successfully.";
      } else {
        $_SESSION['error'] = "Blotter not found or already deleted.";
      }
    } catch (PDOException $e) {
      $_SESSION['error'] = "Delete failed: " . $e->getMessage();
    }

    $this->redirect('blotters');
  }

  private function settle($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid blotter ID.";
      $this->redirect('blotters');
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      // Show settle form
      try {
        $stmt = $this->pdo->prepare("SELECT * FROM tbl_blotters WHERE id = ?");
        $stmt->execute([$id]);
        $blotter = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$blotter) {
          $_SESSION['error'] = "Blotter not found.";
          $this->redirect('blotters');
        }
      } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to load blotter: " . $e->getMessage();
        $this->redirect('blotters');
      }

      $this->render('blotters/settle_blotter', [
        'blotter' => $blotter,
        'title' => 'Mark Blotter as Settled'
      ]);
      return;
    }

    // Handle POST request
    $resolution = trim($_POST['resolution'] ?? 'No resolution provided');

    try {
      $stmt = $this->pdo->prepare("
                UPDATE tbl_blotters
                SET status = 'settled', resolution = ?
                WHERE id = ?
            ");
      $stmt->execute([$resolution, $id]);

      // Log the action
      $this->logAction($_SESSION['user_id'] ?? 0, "Settled blotter record (ID: $id)");

      $_SESSION['success'] = "Blotter marked as settled.";
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to settle blotter: " . $e->getMessage();
    }

    $this->redirect('blotters');
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
