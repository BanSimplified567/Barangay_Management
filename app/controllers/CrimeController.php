<?php
// app/controllers/CrimeController.php
require_once 'BaseController.php';

class CrimeController extends BaseController
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
      case 'store':  // Changed from 'create' to match your form
        $this->store();
        break;
      case 'update':
        $this->update($_GET['id'] ?? 0);
        break;
      case 'investigate':
        $this->investigate($_GET['id'] ?? 0);
        break;
      case 'resolve':
        $this->resolve($_GET['id'] ?? 0);
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
                SELECT c.*,
                       r.full_name as reporter_name,
                       b.description as blotter_description
                FROM tbl_crime_records c
                LEFT JOIN tbl_residents r ON c.reported_by = r.id
                LEFT JOIN tbl_blotters b ON c.blotter_id = b.id
                ORDER BY c.incident_date DESC, c.id DESC
            ");
      $crimes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to load crime records: " . $e->getMessage();
      $crimes = [];
    }

    $this->render('crime/crimes', [
      'crimes' => $crimes,
      'title' => 'Crime Records'
    ]);
  }

  private function addForm()
  {
    // Get blotters for linking
    try {
      $stmt = $this->pdo->query("SELECT id, description FROM tbl_blotters WHERE status = 'open' ORDER BY incident_date DESC");
      $blotters = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to load blotters: " . $e->getMessage();
      $blotters = [];
    }

    // Get residents for reporter dropdown
    try {
      $stmt = $this->pdo->query("SELECT id, full_name FROM tbl_residents ORDER BY full_name");
      $residents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to load residents: " . $e->getMessage();
      $residents = [];
    }

    $this->render('crime/add_crime', [
      'blotters' => $blotters,
      'residents' => $residents,
      'title' => 'Add New Crime Record'
    ]);
  }

  private function editForm($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid crime record ID.";
      $this->redirect('crimes');
    }

    try {
      // Get crime details
      $stmt = $this->pdo->prepare("
                SELECT c.*,
                       r.full_name as reporter_name,
                       b.description as blotter_description
                FROM tbl_crime_records c
                LEFT JOIN tbl_residents r ON c.reported_by = r.id
                LEFT JOIN tbl_blotters b ON c.blotter_id = b.id
                WHERE c.id = ?
            ");
      $stmt->execute([$id]);
      $crime = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$crime) {
        $_SESSION['error'] = "Crime record not found.";
        $this->redirect('crimes');
      }

      // Get blotters for linking
      $stmt = $this->pdo->query("SELECT id, description FROM tbl_blotters ORDER BY incident_date DESC");
      $blotters = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Get residents for reporter dropdown
      $stmt = $this->pdo->query("SELECT id, full_name FROM tbl_residents ORDER BY full_name");
      $residents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to load crime record: " . $e->getMessage();
      $this->redirect('crimes');
    }

    $this->render('crime/edit_crime', [
      'crime' => $crime,
      'blotters' => $blotters,
      'residents' => $residents,
      'title' => 'Edit Crime Record'
    ]);
  }

  private function store()  // Changed from create() to store()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirect('crimes', ['sub' => 'add']);
    }

    $errors = [];

    // Validate inputs
    $crime_type = trim($_POST['crime_type'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $incident_date = $_POST['incident_date'] ?? date('Y-m-d');
    $location = trim($_POST['location'] ?? '');
    $blotter_id = $_POST['blotter_id'] ?? null;
    $reported_by = $_POST['reported_by'] ?? null;
    $status = 'reported';

    if (empty($crime_type)) $errors[] = "Crime type is required.";
    if (empty($description)) $errors[] = "Description is required.";
    if (empty($incident_date)) $errors[] = "Incident date is required.";

    if (empty($errors)) {
      try {
        $stmt = $this->pdo->prepare("
                    INSERT INTO tbl_crime_records
                    (crime_type, description, incident_date, location, blotter_id, reported_by, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
        $stmt->execute([
          $crime_type,
          $description,
          $incident_date,
          $location,
          $blotter_id ?: NULL,
          $reported_by ?: NULL,
          $status
        ]);

        $crimeId = $this->pdo->lastInsertId();

        // Log the action
        $this->logAction($_SESSION['user_id'] ?? 0, "Added crime record: $crime_type (ID: $crimeId)");

        $_SESSION['success'] = "Crime record added successfully!";
        $this->redirect('crimes');
      } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to add crime record: " . $e->getMessage();
        $this->redirect('crimes', ['sub' => 'add']);
      }
    } else {
      $_SESSION['error'] = implode("<br>", $errors);
      $_SESSION['old'] = $_POST;
      $this->redirect('crimes', ['sub' => 'add']);
    }
  }

  private function update($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid crime record ID.";
      $this->redirect('crimes');
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirect('crimes', ['sub' => 'edit', 'id' => $id]);
    }

    $errors = [];

    // Validate inputs
    $crime_type = trim($_POST['crime_type'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $incident_date = $_POST['incident_date'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $blotter_id = $_POST['blotter_id'] ?? null;
    $reported_by = $_POST['reported_by'] ?? null;
    $status = $_POST['status'] ?? 'reported';

    if (empty($crime_type)) $errors[] = "Crime type is required.";
    if (empty($description)) $errors[] = "Description is required.";
    if (empty($incident_date)) $errors[] = "Incident date is required.";
    if (empty($status)) $errors[] = "Status is required.";

    $validStatuses = ['reported', 'under_investigation', 'resolved'];
    if (!in_array($status, $validStatuses)) {
      $errors[] = "Invalid status.";
    }

    if (empty($errors)) {
      try {
        $stmt = $this->pdo->prepare("
                    UPDATE tbl_crime_records SET
                    crime_type = ?,
                    description = ?,
                    incident_date = ?,
                    location = ?,
                    blotter_id = ?,
                    reported_by = ?,
                    status = ?
                    WHERE id = ?
                ");
        $stmt->execute([
          $crime_type,
          $description,
          $incident_date,
          $location,
          $blotter_id ?: NULL,
          $reported_by ?: NULL,
          $status,
          $id
        ]);

        // Log the action
        $this->logAction($_SESSION['user_id'] ?? 0, "Updated crime record: $crime_type (ID: $id)");

        $_SESSION['success'] = "Crime record updated successfully!";
        $this->redirect('crimes');
      } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to update crime record: " . $e->getMessage();
        $this->redirect('crimes', ['sub' => 'edit', 'id' => $id]);
      }
    } else {
      $_SESSION['error'] = implode("<br>", $errors);
      $_SESSION['old'] = $_POST;
      $this->redirect('crimes', ['sub' => 'edit', 'id' => $id]);
    }
  }

  private function delete($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid crime record ID.";
      $this->redirect('crimes');
    }

    try {
      // Get crime type for logging
      $stmt = $this->pdo->prepare("SELECT crime_type FROM tbl_crime_records WHERE id = ?");
      $stmt->execute([$id]);
      $crime = $stmt->fetch(PDO::FETCH_ASSOC);

      // Delete crime record
      $stmt = $this->pdo->prepare("DELETE FROM tbl_crime_records WHERE id = ?");
      $stmt->execute([$id]);

      if ($stmt->rowCount() > 0) {
        // Log the action
        $this->logAction($_SESSION['user_id'] ?? 0, "Deleted crime record: " . ($crime['crime_type'] ?? 'Unknown') . " (ID: $id)");

        $_SESSION['success'] = "Crime record deleted successfully.";
      } else {
        $_SESSION['error'] = "Crime record not found or already deleted.";
      }
    } catch (PDOException $e) {
      $_SESSION['error'] = "Delete failed: " . $e->getMessage();
    }

    $this->redirect('crimes');
  }

  private function investigate($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid crime record ID.";
      $this->redirect('crimes');
    }

    try {
      $stmt = $this->pdo->prepare("
                UPDATE tbl_crime_records
                SET status = 'under_investigation'
                WHERE id = ?
            ");
      $stmt->execute([$id]);

      // Get crime type for logging
      $stmt = $this->pdo->prepare("SELECT crime_type FROM tbl_crime_records WHERE id = ?");
      $stmt->execute([$id]);
      $crime = $stmt->fetch(PDO::FETCH_ASSOC);

      // Log the action
      $this->logAction($_SESSION['user_id'] ?? 0, "Marked crime as under investigation: " . ($crime['crime_type'] ?? 'Unknown') . " (ID: $id)");

      $_SESSION['success'] = "Crime marked as under investigation.";
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to update status: " . $e->getMessage();
    }

    $this->redirect('crimes');
  }

  private function resolve($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid crime record ID.";
      $this->redirect('crimes');
    }

    $resolution = trim($_POST['resolution'] ?? 'Case resolved.');

    try {
      $stmt = $this->pdo->prepare("
                UPDATE tbl_crime_records
                SET status = 'resolved'
                WHERE id = ?
            ");
      $stmt->execute([$id]);

      // Get crime type for logging
      $stmt = $this->pdo->prepare("SELECT crime_type FROM tbl_crime_records WHERE id = ?");
      $stmt->execute([$id]);
      $crime = $stmt->fetch(PDO::FETCH_ASSOC);

      // Log the action
      $this->logAction($_SESSION['user_id'] ?? 0, "Marked crime as resolved: " . ($crime['crime_type'] ?? 'Unknown') . " (ID: $id)");

      $_SESSION['success'] = "Crime marked as resolved.";
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to resolve crime: " . $e->getMessage();
    }

    $this->redirect('crimes');
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
