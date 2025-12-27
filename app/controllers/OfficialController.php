<?php
// app/controllers/OfficialController.php
require_once 'BaseController.php';

class OfficialController extends BaseController
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
      case 'store': // Changed from 'create' to match form action
        $this->store();
        break;
      case 'update':
        $this->update($_GET['id'] ?? 0);
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
                SELECT o.*, r.full_name as official_name, r.contact_number
                FROM tbl_officials o
                JOIN tbl_residents r ON o.resident_id = r.id
                ORDER BY
                    CASE position
                        WHEN 'Barangay Captain' THEN 1
                        WHEN 'Barangay Secretary' THEN 2
                        WHEN 'Barangay Treasurer' THEN 3
                        WHEN 'Barangay Councilor' THEN 4
                        ELSE 5
                    END,
                    o.term_start DESC
            ");
      $officials = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to load officials: " . $e->getMessage();
      $officials = [];
    }

    $this->render('official/officials', [
      'officials' => $officials,
      'title' => 'Barangay Officials'
    ]);
  }

  private function addForm()
  {
    // Get residents who are not yet officials
    try {
      $stmt = $this->pdo->query("
                SELECT r.id, r.full_name
                FROM tbl_residents r
                LEFT JOIN tbl_officials o ON r.id = o.resident_id
                WHERE o.id IS NULL
                ORDER BY r.full_name
            ");
      $residents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to load residents: " . $e->getMessage();
      $residents = [];
    }

    $this->render('official/add_official', [
      'residents' => $residents,
      'title' => 'Add New Official'
    ]);
  }

  private function editForm($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid official ID.";
      $this->redirect('officials');
    }

    try {
      $stmt = $this->pdo->prepare("
                SELECT o.*, r.full_name as official_name
                FROM tbl_officials o
                JOIN tbl_residents r ON o.resident_id = r.id
                WHERE o.id = ?
            ");
      $stmt->execute([$id]);
      $official = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$official) {
        $_SESSION['error'] = "Official not found.";
        $this->redirect('officials');
      }
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to load official: " . $e->getMessage();
      $this->redirect('officials');
    }

    $this->render('official/edit_official', [
      'official' => $official,
      'title' => 'Edit Official'
    ]);
  }

  private function store() // Changed from create() to store()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirect('officials', ['sub' => 'add']);
    }

    $errors = [];

    // Validate inputs
    $resident_id = $_POST['resident_id'] ?? 0;
    $position = trim($_POST['position'] ?? '');
    $term_start = $_POST['term_start'] ?? date('Y-m-d');
    $term_end = $_POST['term_end'] ?? '';

    if (empty($resident_id)) $errors[] = "Resident is required.";
    if (empty($position)) $errors[] = "Position is required.";
    if (empty($term_start)) $errors[] = "Term start date is required.";

    // Validate term dates
    if (!empty($term_end) && strtotime($term_end) < strtotime($term_start)) {
      $errors[] = "Term end date cannot be before term start date.";
    }

    if (empty($errors)) {
      try {
        // Check if resident is already an official
        $stmt = $this->pdo->prepare("SELECT id FROM tbl_officials WHERE resident_id = ?");
        $stmt->execute([$resident_id]);

        if ($stmt->fetch()) {
          $errors[] = "This resident is already an official.";
        }

        if (empty($errors)) {
          $stmt = $this->pdo->prepare("
                        INSERT INTO tbl_officials
                        (resident_id, position, term_start, term_end)
                        VALUES (?, ?, ?, ?)
                    ");
          $stmt->execute([
            $resident_id,
            $position,
            $term_start,
            !empty($term_end) ? $term_end : NULL
          ]);

          $officialId = $this->pdo->lastInsertId();

          // Get resident name for logging
          $stmt = $this->pdo->prepare("SELECT full_name FROM tbl_residents WHERE id = ?");
          $stmt->execute([$resident_id]);
          $resident = $stmt->fetch(PDO::FETCH_ASSOC);

          // Log the action
          $this->logAction($_SESSION['user_id'] ?? 0, "Added official: " . ($resident['full_name'] ?? 'Unknown') . " as $position");

          $_SESSION['success'] = "Official added successfully!";
          $this->redirect('officials');
        }
      } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to add official: " . $e->getMessage();
        $this->redirect('officials', ['sub' => 'add']);
      }
    }

    if (!empty($errors)) {
      $_SESSION['error'] = implode("<br>", $errors);
      $_SESSION['old'] = $_POST;
      $this->redirect('officials', ['sub' => 'add']);
    }
  }

  private function update($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid official ID.";
      $this->redirect('officials');
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirect('officials', ['sub' => 'edit', 'id' => $id]);
    }

    $errors = [];

    // Validate inputs
    $position = trim($_POST['position'] ?? '');
    $term_start = $_POST['term_start'] ?? '';
    $term_end = $_POST['term_end'] ?? '';

    if (empty($position)) $errors[] = "Position is required.";
    if (empty($term_start)) $errors[] = "Term start date is required.";

    // Validate term dates
    if (!empty($term_end) && strtotime($term_end) < strtotime($term_start)) {
      $errors[] = "Term end date cannot be before term start date.";
    }

    if (empty($errors)) {
      try {
        $stmt = $this->pdo->prepare("
                    UPDATE tbl_officials SET
                    position = ?,
                    term_start = ?,
                    term_end = ?
                    WHERE id = ?
                ");
        $stmt->execute([
          $position,
          $term_start,
          !empty($term_end) ? $term_end : NULL,
          $id
        ]);

        // Get official details for logging
        $stmt = $this->pdo->prepare("
                    SELECT o.position, r.full_name
                    FROM tbl_officials o
                    JOIN tbl_residents r ON o.resident_id = r.id
                    WHERE o.id = ?
                ");
        $stmt->execute([$id]);
        $official = $stmt->fetch(PDO::FETCH_ASSOC);

        // Log the action
        $this->logAction($_SESSION['user_id'] ?? 0, "Updated official: " . ($official['full_name'] ?? 'Unknown') . " - $position (ID: $id)");

        $_SESSION['success'] = "Official updated successfully!";
        $this->redirect('officials');
      } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to update official: " . $e->getMessage();
        $this->redirect('officials', ['sub' => 'edit', 'id' => $id]);
      }
    } else {
      $_SESSION['error'] = implode("<br>", $errors);
      $_SESSION['old'] = $_POST;
      $this->redirect('officials', ['sub' => 'edit', 'id' => $id]);
    }
  }

  private function delete($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid official ID.";
      $this->redirect('officials');
    }

    try {
      // Get official details for logging
      $stmt = $this->pdo->prepare("
                SELECT o.position, r.full_name
                FROM tbl_officials o
                JOIN tbl_residents r ON o.resident_id = r.id
                WHERE o.id = ?
            ");
      $stmt->execute([$id]);
      $official = $stmt->fetch(PDO::FETCH_ASSOC);

      // Delete official
      $stmt = $this->pdo->prepare("DELETE FROM tbl_officials WHERE id = ?");
      $stmt->execute([$id]);

      if ($stmt->rowCount() > 0) {
        // Log the action
        $this->logAction($_SESSION['user_id'] ?? 0, "Removed official: " . ($official['full_name'] ?? 'Unknown') . " from " . ($official['position'] ?? 'position'));

        $_SESSION['success'] = "Official removed successfully.";
      } else {
        $_SESSION['error'] = "Official not found or already removed.";
      }
    } catch (PDOException $e) {
      $_SESSION['error'] = "Delete failed: " . $e->getMessage();
    }

    $this->redirect('officials');
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
