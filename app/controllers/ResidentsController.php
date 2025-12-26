<?php
// app/controllers/ResidentsController.php

class ResidentsController
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

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
        $this->update($_GET['id'] ?? 0);
        break;
      default:
        $this->list();
        break;
    }
  }

  public function list()
  {  // Changed to public
    try {
      $stmt = $this->pdo->query("
                SELECT id, full_name, address, birthdate, contact_number, gender, civil_status
                FROM tbl_residents
                ORDER BY full_name
            ");
      $residents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to load residents: " . $e->getMessage();
      $residents = [];
    }

    require_once '../app/views/residents/residents.php';
  }

  public function addForm()
  {  // Changed to public
    require_once '../app/views/residents/add_residents.php';
  }

  public function editForm($id)
  {  // Changed to public
    if (!$id) {
      $_SESSION['error'] = "Invalid resident ID.";
      header("Location: index.php?action=residents");
      exit();
    }

    try {
      $stmt = $this->pdo->prepare("SELECT * FROM tbl_residents WHERE id = ?");
      $stmt->execute([$id]);
      $resident = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$resident) {
        $_SESSION['error'] = "Resident not found.";
        header("Location: index.php?action=residents");
        exit();
      }
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to load resident: " . $e->getMessage();
      header("Location: index.php?action=residents");
      exit();
    }

    require_once '../app/views/residents/edit_residents.php';
  }

  public function create()
  {  // Changed to public
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header("Location: index.php?action=residents&sub=add");
      exit();
    }

    $errors = [];

    // Validate inputs
    $full_name = trim($_POST['full_name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $birthdate = $_POST['birthdate'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $contact_number = trim($_POST['contact_number'] ?? '');
    $occupation = trim($_POST['occupation'] ?? '');
    $civil_status = $_POST['civil_status'] ?? '';

    if (empty($full_name)) $errors[] = "Full name is required.";
    if (empty($address)) $errors[] = "Address is required.";
    if (empty($birthdate)) $errors[] = "Birthdate is required.";
    if (empty($gender)) $errors[] = "Gender is required.";
    if (empty($civil_status)) $errors[] = "Civil status is required.";

    if (empty($errors)) {
      try {
        $stmt = $this->pdo->prepare("
                    INSERT INTO tbl_residents
                    (full_name, address, birthdate, gender, contact_number, occupation, civil_status)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
        $stmt->execute([
          $full_name,
          $address,
          $birthdate,
          $gender,
          $contact_number,
          $occupation,
          $civil_status
        ]);

        $residentId = $this->pdo->lastInsertId();

        // Log the action
        $this->logAction($_SESSION['user_id'] ?? 0, "Added resident: $full_name");

        $_SESSION['success'] = "Resident added successfully!";
        header("Location: index.php?action=residents");
        exit();
      } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to add resident: " . $e->getMessage();
        header("Location: index.php?action=residents&sub=add");
        exit();
      }
    } else {
      $_SESSION['error'] = implode("<br>", $errors);
      $_SESSION['old'] = $_POST;
      header("Location: index.php?action=residents&sub=add");
      exit();
    }
  }

  public function update($id = null)
  {  // Changed to public, added default parameter
    // Get ID from POST if not provided as parameter
    if (!$id) {
      $id = $_POST['id'] ?? 0;
    }

    if (!$id) {
      $_SESSION['error'] = "Invalid resident ID.";
      header("Location: index.php?action=residents");
      exit();
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header("Location: index.php?action=residents&sub=edit&id=$id");
      exit();
    }

    $errors = [];

    // Validate inputs
    $full_name = trim($_POST['full_name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $birthdate = $_POST['birthdate'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $contact_number = trim($_POST['contact_number'] ?? '');
    $occupation = trim($_POST['occupation'] ?? '');
    $civil_status = $_POST['civil_status'] ?? '';

    if (empty($full_name)) $errors[] = "Full name is required.";
    if (empty($address)) $errors[] = "Address is required.";
    if (empty($birthdate)) $errors[] = "Birthdate is required.";
    if (empty($gender)) $errors[] = "Gender is required.";
    if (empty($civil_status)) $errors[] = "Civil status is required.";

    if (empty($errors)) {
      try {
        $stmt = $this->pdo->prepare("
                    UPDATE tbl_residents SET
                    full_name = ?, address = ?, birthdate = ?, gender = ?,
                    contact_number = ?, occupation = ?, civil_status = ?
                    WHERE id = ?
                ");
        $stmt->execute([
          $full_name,
          $address,
          $birthdate,
          $gender,
          $contact_number,
          $occupation,
          $civil_status,
          $id
        ]);

        // Log the action
        $this->logAction($_SESSION['user_id'] ?? 0, "Updated resident: $full_name (ID: $id)");

        $_SESSION['success'] = "Resident updated successfully!";
        header("Location: index.php?action=residents");
        exit();
      } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to update resident: " . $e->getMessage();
        header("Location: index.php?action=residents&sub=edit&id=$id");
        exit();
      }
    } else {
      $_SESSION['error'] = implode("<br>", $errors);
      $_SESSION['old'] = $_POST;
      header("Location: index.php?action=residents&sub=edit&id=$id");
      exit();
    }
  }

  public function delete($id)
  {  // Changed to public
    if (!$id) {
      $_SESSION['error'] = "Invalid resident ID.";
      header("Location: index.php?action=residents");
      exit();
    }

    try {
      // Get resident name for logging
      $stmt = $this->pdo->prepare("SELECT full_name FROM tbl_residents WHERE id = ?");
      $stmt->execute([$id]);
      $resident = $stmt->fetch(PDO::FETCH_ASSOC);

      // Delete resident
      $stmt = $this->pdo->prepare("DELETE FROM tbl_residents WHERE id = ?");
      $stmt->execute([$id]);

      if ($stmt->rowCount() > 0) {
        // Log the action
        $this->logAction($_SESSION['user_id'] ?? 0, "Deleted resident: " . ($resident['full_name'] ?? 'Unknown'));

        $_SESSION['success'] = "Resident deleted successfully.";
      } else {
        $_SESSION['error'] = "Resident not found or already deleted.";
      }
    } catch (PDOException $e) {
      $_SESSION['error'] = "Delete failed: " . $e->getMessage();
    }

    header("Location: index.php?action=residents");
    exit();
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
