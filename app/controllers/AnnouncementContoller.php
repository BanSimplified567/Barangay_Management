<?php
// app/controllers/AnnouncementController.php

class AnnouncementController
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
      default:
        $this->list();
        break;
    }
  }

  private function list()
  {
    try {
      $stmt = $this->pdo->query("
                SELECT a.*, u.full_name as posted_by_name
                FROM tbl_announcements a
                JOIN tbl_users u ON a.posted_by = u.id
                ORDER BY a.post_date DESC, a.id DESC
            ");
      $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to load announcements: " . $e->getMessage();
      $announcements = [];
    }

    require_once '../app/views/announcements.php';
  }

  private function addForm()
  {
    require_once '../app/views/announcements/add_announcement.php';
  }

  private function editForm($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid announcement ID.";
      header("Location: index.php?action=announcements");
      exit();
    }

    try {
      $stmt = $this->pdo->prepare("SELECT * FROM tbl_announcements WHERE id = ?");
      $stmt->execute([$id]);
      $announcement = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$announcement) {
        $_SESSION['error'] = "Announcement not found.";
        header("Location: index.php?action=announcements");
        exit();
      }
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to load announcement: " . $e->getMessage();
      header("Location: index.php?action=announcements");
      exit();
    }

    require_once '../app/views/announcements/edit_announcement.php';
  }

  private function view($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid announcement ID.";
      header("Location: index.php?action=announcements");
      exit();
    }

    try {
      $stmt = $this->pdo->prepare("
                SELECT a.*, u.full_name as posted_by_name
                FROM tbl_announcements a
                JOIN tbl_users u ON a.posted_by = u.id
                WHERE a.id = ?
            ");
      $stmt->execute([$id]);
      $announcement = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$announcement) {
        $_SESSION['error'] = "Announcement not found.";
        header("Location: index.php?action=announcements");
        exit();
      }
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to load announcement: " . $e->getMessage();
      header("Location: index.php?action=announcements");
      exit();
    }

    require_once '../app/views/announcements/view_announcement.php';
  }

  private function create()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header("Location: index.php?action=announcements&sub=add");
      exit();
    }

    $errors = [];

    // Validate inputs
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $post_date = $_POST['post_date'] ?? date('Y-m-d');
    $posted_by = $_SESSION['user_id'] ?? null;

    if (empty($title)) $errors[] = "Title is required.";
    if (empty($content)) $errors[] = "Content is required.";
    if (empty($post_date)) $errors[] = "Post date is required.";

    if (empty($errors)) {
      try {
        $stmt = $this->pdo->prepare("
                    INSERT INTO tbl_announcements
                    (title, content, post_date, posted_by)
                    VALUES (?, ?, ?, ?)
                ");
        $stmt->execute([
          $title,
          $content,
          $post_date,
          $posted_by
        ]);

        $announcementId = $this->pdo->lastInsertId();

        // Log the action
        $this->logAction($_SESSION['user_id'] ?? 0, "Posted announcement: $title (ID: $announcementId)");

        $_SESSION['success'] = "Announcement posted successfully!";
        header("Location: index.php?action=announcements");
        exit();
      } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to post announcement: " . $e->getMessage();
        header("Location: index.php?action=announcements&sub=add");
        exit();
      }
    } else {
      $_SESSION['error'] = implode("<br>", $errors);
      $_SESSION['old'] = $_POST;
      header("Location: index.php?action=announcements&sub=add");
      exit();
    }
  }

  private function update($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid announcement ID.";
      header("Location: index.php?action=announcements");
      exit();
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header("Location: index.php?action=announcements&sub=edit&id=$id");
      exit();
    }

    $errors = [];

    // Validate inputs
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $post_date = $_POST['post_date'] ?? '';

    if (empty($title)) $errors[] = "Title is required.";
    if (empty($content)) $errors[] = "Content is required.";
    if (empty($post_date)) $errors[] = "Post date is required.";

    if (empty($errors)) {
      try {
        $stmt = $this->pdo->prepare("
                    UPDATE tbl_announcements SET
                    title = ?,
                    content = ?,
                    post_date = ?
                    WHERE id = ?
                ");
        $stmt->execute([
          $title,
          $content,
          $post_date,
          $id
        ]);

        // Log the action
        $this->logAction($_SESSION['user_id'] ?? 0, "Updated announcement: $title (ID: $id)");

        $_SESSION['success'] = "Announcement updated successfully!";
        header("Location: index.php?action=announcements");
        exit();
      } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to update announcement: " . $e->getMessage();
        header("Location: index.php?action=announcements&sub=edit&id=$id");
        exit();
      }
    } else {
      $_SESSION['error'] = implode("<br>", $errors);
      $_SESSION['old'] = $_POST;
      header("Location: index.php?action=announcements&sub=edit&id=$id");
      exit();
    }
  }

  private function delete($id)
  {
    if (!$id) {
      $_SESSION['error'] = "Invalid announcement ID.";
      header("Location: index.php?action=announcements");
      exit();
    }

    try {
      // Get announcement title for logging
      $stmt = $this->pdo->prepare("SELECT title FROM tbl_announcements WHERE id = ?");
      $stmt->execute([$id]);
      $announcement = $stmt->fetch(PDO::FETCH_ASSOC);

      // Delete announcement
      $stmt = $this->pdo->prepare("DELETE FROM tbl_announcements WHERE id = ?");
      $stmt->execute([$id]);

      if ($stmt->rowCount() > 0) {
        // Log the action
        $this->logAction($_SESSION['user_id'] ?? 0, "Deleted announcement: " . ($announcement['title'] ?? 'Unknown') . " (ID: $id)");

        $_SESSION['success'] = "Announcement deleted successfully.";
      } else {
        $_SESSION['error'] = "Announcement not found or already deleted.";
      }
    } catch (PDOException $e) {
      $_SESSION['error'] = "Delete failed: " . $e->getMessage();
    }

    header("Location: index.php?action=announcements");
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
