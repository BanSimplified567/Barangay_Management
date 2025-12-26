<?php
// app/controllers/LogController.php

class LogController
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
      case 'clear':
        $this->clearLogs();
        break;
      case 'export':
        $this->exportLogs();
        break;
      case 'filter':
        $this->filterLogs();
        break;
      default:
        $this->list();
        break;
    }
  }

  private function list()
  {
    // Get filter parameters
    $user_id = $_GET['user_id'] ?? null;
    $date_from = $_GET['date_from'] ?? null;
    $date_to = $_GET['date_to'] ?? null;
    $search = $_GET['search'] ?? '';

    try {
      // Build query with filters
      $sql = "
                SELECT l.*, u.full_name as user_name, u.role as user_role
                FROM tbl_logs l
                JOIN tbl_users u ON l.user_id = u.id
                WHERE 1=1
            ";
      $params = [];

      // Filter by user
      if ($user_id) {
        $sql .= " AND l.user_id = ?";
        $params[] = $user_id;
      }

      // Filter by date range
      if ($date_from) {
        $sql .= " AND DATE(l.timestamp) >= ?";
        $params[] = $date_from;
      }

      if ($date_to) {
        $sql .= " AND DATE(l.timestamp) <= ?";
        $params[] = $date_to;
      }

      // Search in action
      if ($search) {
        $sql .= " AND l.action LIKE ?";
        $params[] = "%$search%";
      }

      $sql .= " ORDER BY l.timestamp DESC, l.id DESC";

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($params);
      $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to load logs: " . $e->getMessage();
      $logs = [];
    }

    // Get users for filter dropdown
    try {
      $stmt = $this->pdo->query("SELECT id, full_name, role FROM tbl_users ORDER BY full_name");
      $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $users = [];
    }

    require_once '../app/views/logs.php';
  }

  private function filterLogs()
  {
    // This is handled in the list() method with GET parameters
    $this->list();
  }

  private function clearLogs()
  {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
      $_SESSION['error'] = "Only administrators can clear logs.";
      header("Location: index.php?action=logs");
      exit();
    }

    $confirmation = $_POST['confirmation'] ?? '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $confirmation === 'DELETE') {
      try {
        // Count logs before deletion for logging
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM tbl_logs");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $logCount = $result['count'] ?? 0;

        // Clear all logs
        $stmt = $this->pdo->prepare("DELETE FROM tbl_logs");
        $stmt->execute();

        // Log the action (this won't appear in cleared logs obviously)
        // But we can log it manually
        $this->logAction($_SESSION['user_id'], "Cleared all system logs ($logCount records)");

        $_SESSION['success'] = "All system logs have been cleared successfully.";
      } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to clear logs: " . $e->getMessage();
      }

      header("Location: index.php?action=logs");
      exit();
    } else {
      // Show confirmation form
      require_once '../app/views/logs/clear_logs.php';
    }
  }

  private function exportLogs()
  {
    // Check if user is admin or staff
    if (!in_array($_SESSION['role'], ['admin', 'staff'])) {
      $_SESSION['error'] = "Permission denied for export.";
      header("Location: index.php?action=logs");
      exit();
    }

    $format = $_GET['format'] ?? 'csv';

    try {
      // Get logs with user information
      $stmt = $this->pdo->query("
                SELECT l.*, u.full_name as user_name, u.role as user_role, u.email as user_email
                FROM tbl_logs l
                JOIN tbl_users u ON l.user_id = u.id
                ORDER BY l.timestamp DESC
            ");
      $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if ($format === 'csv') {
        $this->exportCSV($logs);
      } elseif ($format === 'pdf') {
        $this->exportPDF($logs);
      } else {
        $this->exportCSV($logs); // Default to CSV
      }
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to export logs: " . $e->getMessage();
      header("Location: index.php?action=logs");
      exit();
    }
  }

  private function exportCSV($logs)
  {
    // Set headers for CSV download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=system_logs_' . date('Y-m-d') . '.csv');

    $output = fopen('php://output', 'w');

    // Add CSV header
    fputcsv($output, ['ID', 'Timestamp', 'User Name', 'User Role', 'User Email', 'Action']);

    // Add data rows
    foreach ($logs as $log) {
      fputcsv($output, [
        $log['id'],
        $log['timestamp'],
        $log['user_name'],
        $log['user_role'],
        $log['user_email'] ?? '',
        $log['action']
      ]);
    }

    fclose($output);
    exit();
  }

  private function exportPDF($logs)
  {
    // This would require a PDF library like TCPDF or Dompdf
    // For now, we'll redirect to CSV
    header("Location: index.php?action=logs&sub=export&format=csv");
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
