<?php
// app/controllers/ProjectController.php

class ProjectController
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
      case 'view':
        $this->view($_GET['id'] ?? 0);
        break;
      case 'complete':
        $this->complete($_GET['id'] ?? 0);
        break;
      case 'cancel':
        $this->cancel($_GET['id'] ?? 0);
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
                SELECT * FROM tbl_projects
                ORDER BY
                    CASE status
                        WHEN 'planning' THEN 1
                        WHEN 'ongoing' THEN 2
                        WHEN 'completed' THEN 3
                        WHEN 'cancelled' THEN 4
                        ELSE 5
                    END,
                    start_date DESC,
                    id DESC
            ");
      $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      // If table doesn't exist, create it
      if (strpos($e->getMessage(), "tbl_projects doesn't exist") !== false) {
        $this->createProjectsTable();
        $projects = [];
      } else {
        $_SESSION['error'] = "Failed to load projects: " . $e->getMessage();
        $projects = [];
      }
    }

    require_once '../app/views/projects.php';
  }

  private function createProjectsTable()
  {
    try {
      $sql = "
                CREATE TABLE IF NOT EXISTS tbl_projects (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    name VARCHAR(255) NOT NULL,
                    description TEXT,
                    budget DECIMAL(15,2) DEFAULT 0.00,
                    start_date DATE,
                    end_date DATE,
                    status ENUM('planning', 'ongoing', 'completed', 'cancelled') DEFAULT 'planning',
                    location VARCHAR(500),
                    project_lead VARCHAR(255),
                    funding_source VARCHAR(255),
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )
            ";
      $this->pdo->exec($sql);

      // Insert sample projects
      $this->insertSampleProjects();
    } catch (PDOException $e) {
      error_log("Failed to create projects table: " . $e->getMessage());
    }
  }

  private function insertSampleProjects()
  {
    $sampleProjects = [
      [
        'name' => 'Barangay Health Center Renovation',
        'description' => 'Renovation and upgrading of the barangay health center with new medical equipment',
        'budget' => 500000,
        'start_date' => date('Y-m-d', strtotime('+1 month')),
        'end_date' => date('Y-m-d', strtotime('+4 months')),
        'status' => 'planning',
        'location' => 'Barangay Hall Compound',
        'project_lead' => 'Dr. Maria Santos',
        'funding_source' => 'DOH & Barangay Funds'
      ],
      [
        'name' => 'Road Concreting Project',
        'description' => 'Concreting of 500-meter barangay road in Purok 3',
        'budget' => 750000,
        'start_date' => date('Y-m-d'),
        'end_date' => date('Y-m-d', strtotime('+3 months')),
        'status' => 'ongoing',
        'location' => 'Purok 3, Sitio Maligaya',
        'project_lead' => 'Engr. Juan Dela Cruz',
        'funding_source' => 'DPWH & Barangay Funds'
      ],
      [
        'name' => 'Solar Street Lights Installation',
        'description' => 'Installation of 50 solar-powered street lights in major barangay roads',
        'budget' => 300000,
        'start_date' => date('Y-m-d', strtotime('-2 months')),
        'end_date' => date('Y-m-d', strtotime('-1 week')),
        'status' => 'completed',
        'location' => 'Main Barangay Roads',
        'project_lead' => 'Kag. Pedro Reyes',
        'funding_source' => 'DILG & Barangay Funds'
      ]
    ];

    try {
      $stmt = $this->pdo->prepare("
                INSERT INTO tbl_projects
                (name, description, budget, start_date, end_date, status, location, project_lead, funding_source)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

      foreach ($sampleProjects as $project) {
        $stmt->execute([
          $project['name'],
          $project['description'],
          $project['budget'],
          $project['start_date'],
          $project['end_date'],
          $project['status'],
          $project['location'],
          $project['project_lead'],
          $project['funding_source']
        ]);
      }
    } catch (PDOException $e) {
      error_log("Failed to insert sample projects: " . $e->getMessage());
    }
  }

  private function addForm()
  {
    require_once '../app/views/projects/add_project.php';
  }

  private function editForm($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid project ID.";
      header("Location: index.php?action=projects");
      exit();
    }

    try {
      $stmt = $this->pdo->prepare("SELECT * FROM tbl_projects WHERE id = ?");
      $stmt->execute([$id]);
      $project = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$project) {
        $_SESSION['error'] = "Project not found.";
        header("Location: index.php?action=projects");
        exit();
      }
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to load project: " . $e->getMessage();
      header("Location: index.php?action=projects");
      exit();
    }

    require_once '../app/views/projects/edit_project.php';
  }

  private function view($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid project ID.";
      header("Location: index.php?action=projects");
      exit();
    }

    try {
      $stmt = $this->pdo->prepare("SELECT * FROM tbl_projects WHERE id = ?");
      $stmt->execute([$id]);
      $project = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$project) {
        $_SESSION['error'] = "Project not found.";
        header("Location: index.php?action=projects");
        exit();
      }
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to load project: " . $e->getMessage();
      header("Location: index.php?action=projects");
      exit();
    }

    require_once '../app/views/projects/view_project.php';
  }

  private function create()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header("Location: index.php?action=projects&sub=add");
      exit();
    }

    $errors = [];

    // Validate inputs
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $budget = $_POST['budget'] ?? 0;
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $project_lead = trim($_POST['project_lead'] ?? '');
    $funding_source = trim($_POST['funding_source'] ?? '');
    $status = 'planning';

    if (empty($name)) $errors[] = "Project name is required.";
    if (empty($description)) $errors[] = "Description is required.";
    if (empty($start_date)) $errors[] = "Start date is required.";

    // Validate dates
    if (!empty($end_date) && strtotime($end_date) < strtotime($start_date)) {
      $errors[] = "End date cannot be before start date.";
    }

    // Validate budget
    if (!is_numeric($budget) || $budget < 0) {
      $errors[] = "Budget must be a valid positive number.";
    }

    if (empty($errors)) {
      try {
        $stmt = $this->pdo->prepare("
                    INSERT INTO tbl_projects
                    (name, description, budget, start_date, end_date, location, project_lead, funding_source, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
        $stmt->execute([
          $name,
          $description,
          $budget,
          $start_date,
          !empty($end_date) ? $end_date : NULL,
          $location,
          $project_lead,
          $funding_source,
          $status
        ]);

        $projectId = $this->pdo->lastInsertId();

        // Log the action
        $this->logAction($_SESSION['user_id'] ?? 0, "Created project: $name (ID: $projectId)");

        $_SESSION['success'] = "Project created successfully!";
        header("Location: index.php?action=projects");
        exit();
      } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to create project: " . $e->getMessage();
        header("Location: index.php?action=projects&sub=add");
        exit();
      }
    } else {
      $_SESSION['error'] = implode("<br>", $errors);
      $_SESSION['old'] = $_POST;
      header("Location: index.php?action=projects&sub=add");
      exit();
    }
  }

  private function update($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid project ID.";
      header("Location: index.php?action=projects");
      exit();
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header("Location: index.php?action=projects&sub=edit&id=$id");
      exit();
    }

    $errors = [];

    // Validate inputs
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $budget = $_POST['budget'] ?? 0;
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $project_lead = trim($_POST['project_lead'] ?? '');
    $funding_source = trim($_POST['funding_source'] ?? '');
    $status = $_POST['status'] ?? 'planning';

    if (empty($name)) $errors[] = "Project name is required.";
    if (empty($description)) $errors[] = "Description is required.";
    if (empty($start_date)) $errors[] = "Start date is required.";

    // Validate status
    $validStatuses = ['planning', 'ongoing', 'completed', 'cancelled'];
    if (!in_array($status, $validStatuses)) {
      $errors[] = "Invalid project status.";
    }

    // Validate dates
    if (!empty($end_date) && strtotime($end_date) < strtotime($start_date)) {
      $errors[] = "End date cannot be before start date.";
    }

    // Validate budget
    if (!is_numeric($budget) || $budget < 0) {
      $errors[] = "Budget must be a valid positive number.";
    }

    if (empty($errors)) {
      try {
        $stmt = $this->pdo->prepare("
                    UPDATE tbl_projects SET
                    name = ?,
                    description = ?,
                    budget = ?,
                    start_date = ?,
                    end_date = ?,
                    location = ?,
                    project_lead = ?,
                    funding_source = ?,
                    status = ?
                    WHERE id = ?
                ");
        $stmt->execute([
          $name,
          $description,
          $budget,
          $start_date,
          !empty($end_date) ? $end_date : NULL,
          $location,
          $project_lead,
          $funding_source,
          $status,
          $id
        ]);

        // Log the action
        $this->logAction($_SESSION['user_id'] ?? 0, "Updated project: $name (ID: $id)");

        $_SESSION['success'] = "Project updated successfully!";
        header("Location: index.php?action=projects");
        exit();
      } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to update project: " . $e->getMessage();
        header("Location: index.php?action=projects&sub=edit&id=$id");
        exit();
      }
    } else {
      $_SESSION['error'] = implode("<br>", $errors);
      $_SESSION['old'] = $_POST;
      header("Location: index.php?action=projects&sub=edit&id=$id");
      exit();
    }
  }

  private function delete($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid project ID.";
      header("Location: index.php?action=projects");
      exit();
    }

    try {
      // Get project name for logging
      $stmt = $this->pdo->prepare("SELECT name FROM tbl_projects WHERE id = ?");
      $stmt->execute([$id]);
      $project = $stmt->fetch(PDO::FETCH_ASSOC);

      // Delete project
      $stmt = $this->pdo->prepare("DELETE FROM tbl_projects WHERE id = ?");
      $stmt->execute([$id]);

      if ($stmt->rowCount() > 0) {
        // Log the action
        $this->logAction($_SESSION['user_id'] ?? 0, "Deleted project: " . ($project['name'] ?? 'Unknown') . " (ID: $id)");

        $_SESSION['success'] = "Project deleted successfully.";
      } else {
        $_SESSION['error'] = "Project not found or already deleted.";
      }
    } catch (PDOException $e) {
      $_SESSION['error'] = "Delete failed: " . $e->getMessage();
    }

    header("Location: index.php?action=projects");
    exit();
  }

  private function complete($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid project ID.";
      header("Location: index.php?action=projects");
      exit();
    }

    try {
      $stmt = $this->pdo->prepare("
                UPDATE tbl_projects
                SET status = 'completed', end_date = CURDATE()
                WHERE id = ?
            ");
      $stmt->execute([$id]);

      // Get project name for logging
      $stmt = $this->pdo->prepare("SELECT name FROM tbl_projects WHERE id = ?");
      $stmt->execute([$id]);
      $project = $stmt->fetch(PDO::FETCH_ASSOC);

      // Log the action
      $this->logAction($_SESSION['user_id'] ?? 0, "Marked project as completed: " . ($project['name'] ?? 'Unknown') . " (ID: $id)");

      $_SESSION['success'] = "Project marked as completed.";
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to complete project: " . $e->getMessage();
    }

    header("Location: index.php?action=projects");
    exit();
  }

  private function cancel($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid project ID.";
      header("Location: index.php?action=projects");
      exit();
    }

    try {
      $stmt = $this->pdo->prepare("
                UPDATE tbl_projects
                SET status = 'cancelled'
                WHERE id = ?
            ");
      $stmt->execute([$id]);

      // Get project name for logging
      $stmt = $this->pdo->prepare("SELECT name FROM tbl_projects WHERE id = ?");
      $stmt->execute([$id]);
      $project = $stmt->fetch(PDO::FETCH_ASSOC);

      // Log the action
      $this->logAction($_SESSION['user_id'] ?? 0, "Cancelled project: " . ($project['name'] ?? 'Unknown') . " (ID: $id)");

      $_SESSION['success'] = "Project cancelled.";
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to cancel project: " . $e->getMessage();
    }

    header("Location: index.php?action=projects");
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
