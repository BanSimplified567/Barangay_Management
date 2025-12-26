<?php
// app/controllers/CrimeController.php

class CrimeController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
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

    private function list() {
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

        require_once '../app/views/crimes.php';
    }

    private function addForm() {
        // Get blotters for linking
        try {
            $stmt = $this->pdo->query("SELECT id, description FROM tbl_blotters WHERE status = 'open' ORDER BY incident_date DESC");
            $blotters = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $_SESSION['error'] = "Failed to load blotters: " . $e->getMessage();
            $blotters = [];
        }

        require_once '../app/views/crimes/add_crime.php';
    }

    private function editForm($id) {
        if (!$id) {
            $_SESSION['error'] = "Invalid crime record ID.";
            header("Location: index.php?action=crimes");
            exit();
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
                header("Location: index.php?action=crimes");
                exit();
            }

            // Get blotters for linking
            $stmt = $this->pdo->query("SELECT id, description FROM tbl_blotters ORDER BY incident_date DESC");
            $blotters = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            $_SESSION['error'] = "Failed to load crime record: " . $e->getMessage();
            header("Location: index.php?action=crimes");
            exit();
        }

        require_once '../app/views/crimes/edit_crime.php';
    }

    private function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=crimes&sub=add");
            exit();
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
                header("Location: index.php?action=crimes");
                exit();

            } catch (PDOException $e) {
                $_SESSION['error'] = "Failed to add crime record: " . $e->getMessage();
                header("Location: index.php?action=crimes&sub=add");
                exit();
            }
        } else {
            $_SESSION['error'] = implode("<br>", $errors);
            $_SESSION['old'] = $_POST;
            header("Location: index.php?action=crimes&sub=add");
            exit();
        }
    }

    private function update($id) {
        if (!$id) {
            $_SESSION['error'] = "Invalid crime record ID.";
            header("Location: index.php?action=crimes");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?action=crimes&sub=edit&id=$id");
            exit();
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
                header("Location: index.php?action=crimes");
                exit();

            } catch (PDOException $e) {
                $_SESSION['error'] = "Failed to update crime record: " . $e->getMessage();
                header("Location: index.php?action=crimes&sub=edit&id=$id");
                exit();
            }
        } else {
            $_SESSION['error'] = implode("<br>", $errors);
            $_SESSION['old'] = $_POST;
            header("Location: index.php?action=crimes&sub=edit&id=$id");
            exit();
        }
    }

    private function delete($id) {
        if (!$id) {
            $_SESSION['error'] = "Invalid crime record ID.";
            header("Location: index.php?action=crimes");
            exit();
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

        header("Location: index.php?action=crimes");
        exit();
    }

    private function investigate($id) {
        if (!$id) {
            $_SESSION['error'] = "Invalid crime record ID.";
            header("Location: index.php?action=crimes");
            exit();
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

        header("Location: index.php?action=crimes");
        exit();
    }

    private function resolve($id) {
        if (!$id) {
            $_SESSION['error'] = "Invalid crime record ID.";
            header("Location: index.php?action=crimes");
            exit();
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

        header("Location: index.php?action=crimes");
        exit();
    }

    private function logAction($userId, $action) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO tbl_logs (user_id, action) VALUES (?, ?)");
            $stmt->execute([$userId, $action]);
        } catch (PDOException $e) {
            error_log("Failed to log action: " . $e->getMessage());
        }
    }
}
