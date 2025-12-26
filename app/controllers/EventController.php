<?php
// app/controllers/EventController.php

class EventController
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
      case 'cancel':
        $this->cancel($_GET['id'] ?? 0);
        break;
      case 'complete':
        $this->complete($_GET['id'] ?? 0);
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
                SELECT e.*, u.full_name as organizer_name
                FROM tbl_events e
                LEFT JOIN tbl_users u ON e.organizer_id = u.id
                ORDER BY e.event_date ASC, e.id DESC
            ");
      $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to load events: " . $e->getMessage();
      $events = [];
    }

    require_once '../app/views/events.php';
  }

  private function addForm()
  {
    require_once '../app/views/events/add_event.php';
  }

  private function editForm($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid event ID.";
      header("Location: index.php?action=events");
      exit();
    }

    try {
      $stmt = $this->pdo->prepare("SELECT * FROM tbl_events WHERE id = ?");
      $stmt->execute([$id]);
      $event = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$event) {
        $_SESSION['error'] = "Event not found.";
        header("Location: index.php?action=events");
        exit();
      }
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to load event: " . $e->getMessage();
      header("Location: index.php?action=events");
      exit();
    }

    require_once '../app/views/events/edit_event.php';
  }

  private function create()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header("Location: index.php?action=events&sub=add");
      exit();
    }

    $errors = [];

    // Validate inputs
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $event_date = $_POST['event_date'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $organizer_id = $_SESSION['user_id'] ?? null;
    $status = 'upcoming';

    if (empty($name)) $errors[] = "Event name is required.";
    if (empty($event_date)) $errors[] = "Event date is required.";

    // Validate date is not in the past
    if (!empty($event_date) && strtotime($event_date) < strtotime(date('Y-m-d'))) {
      $errors[] = "Event date cannot be in the past.";
    }

    if (empty($errors)) {
      try {
        $stmt = $this->pdo->prepare("
                    INSERT INTO tbl_events
                    (name, description, event_date, location, organizer_id, status)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
        $stmt->execute([
          $name,
          $description,
          $event_date,
          $location,
          $organizer_id,
          $status
        ]);

        $eventId = $this->pdo->lastInsertId();

        // Log the action
        $this->logAction($_SESSION['user_id'] ?? 0, "Created event: $name (ID: $eventId)");

        $_SESSION['success'] = "Event created successfully!";
        header("Location: index.php?action=events");
        exit();
      } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to create event: " . $e->getMessage();
        header("Location: index.php?action=events&sub=add");
        exit();
      }
    } else {
      $_SESSION['error'] = implode("<br>", $errors);
      $_SESSION['old'] = $_POST;
      header("Location: index.php?action=events&sub=add");
      exit();
    }
  }

  private function update($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid event ID.";
      header("Location: index.php?action=events");
      exit();
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header("Location: index.php?action=events&sub=edit&id=$id");
      exit();
    }

    $errors = [];

    // Validate inputs
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $event_date = $_POST['event_date'] ?? '';
    $location = trim($_POST['location'] ?? '');
    $status = $_POST['status'] ?? 'upcoming';

    if (empty($name)) $errors[] = "Event name is required.";
    if (empty($event_date)) $errors[] = "Event date is required.";

    // Validate status
    $validStatuses = ['upcoming', 'ongoing', 'completed', 'cancelled'];
    if (!in_array($status, $validStatuses)) {
      $errors[] = "Invalid status.";
    }

    if (empty($errors)) {
      try {
        $stmt = $this->pdo->prepare("
                    UPDATE tbl_events SET
                    name = ?,
                    description = ?,
                    event_date = ?,
                    location = ?,
                    status = ?
                    WHERE id = ?
                ");
        $stmt->execute([
          $name,
          $description,
          $event_date,
          $location,
          $status,
          $id
        ]);

        // Log the action
        $this->logAction($_SESSION['user_id'] ?? 0, "Updated event: $name (ID: $id)");

        $_SESSION['success'] = "Event updated successfully!";
        header("Location: index.php?action=events");
        exit();
      } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to update event: " . $e->getMessage();
        header("Location: index.php?action=events&sub=edit&id=$id");
        exit();
      }
    } else {
      $_SESSION['error'] = implode("<br>", $errors);
      $_SESSION['old'] = $_POST;
      header("Location: index.php?action=events&sub=edit&id=$id");
      exit();
    }
  }

  private function delete($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid event ID.";
      header("Location: index.php?action=events");
      exit();
    }

    try {
      // Get event name for logging
      $stmt = $this->pdo->prepare("SELECT name FROM tbl_events WHERE id = ?");
      $stmt->execute([$id]);
      $event = $stmt->fetch(PDO::FETCH_ASSOC);

      // Delete event
      $stmt = $this->pdo->prepare("DELETE FROM tbl_events WHERE id = ?");
      $stmt->execute([$id]);

      if ($stmt->rowCount() > 0) {
        // Log the action
        $this->logAction($_SESSION['user_id'] ?? 0, "Deleted event: " . ($event['name'] ?? 'Unknown') . " (ID: $id)");

        $_SESSION['success'] = "Event deleted successfully.";
      } else {
        $_SESSION['error'] = "Event not found or already deleted.";
      }
    } catch (PDOException $e) {
      $_SESSION['error'] = "Delete failed: " . $e->getMessage();
    }

    header("Location: index.php?action=events");
    exit();
  }

  private function cancel($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid event ID.";
      header("Location: index.php?action=events");
      exit();
    }

    try {
      $stmt = $this->pdo->prepare("
                UPDATE tbl_events
                SET status = 'cancelled'
                WHERE id = ?
            ");
      $stmt->execute([$id]);

      // Get event name for logging
      $stmt = $this->pdo->prepare("SELECT name FROM tbl_events WHERE id = ?");
      $stmt->execute([$id]);
      $event = $stmt->fetch(PDO::FETCH_ASSOC);

      // Log the action
      $this->logAction($_SESSION['user_id'] ?? 0, "Cancelled event: " . ($event['name'] ?? 'Unknown') . " (ID: $id)");

      $_SESSION['success'] = "Event cancelled successfully.";
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to cancel event: " . $e->getMessage();
    }

    header("Location: index.php?action=events");
    exit();
  }

  private function complete($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid event ID.";
      header("Location: index.php?action=events");
      exit();
    }

    try {
      $stmt = $this->pdo->prepare("
                UPDATE tbl_events
                SET status = 'completed'
                WHERE id = ?
            ");
      $stmt->execute([$id]);

      // Get event name for logging
      $stmt = $this->pdo->prepare("SELECT name FROM tbl_events WHERE id = ?");
      $stmt->execute([$id]);
      $event = $stmt->fetch(PDO::FETCH_ASSOC);

      // Log the action
      $this->logAction($_SESSION['user_id'] ?? 0, "Marked event as completed: " . ($event['name'] ?? 'Unknown') . " (ID: $id)");

      $_SESSION['success'] = "Event marked as completed.";
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to complete event: " . $e->getMessage();
    }

    header("Location: index.php?action=events");
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
