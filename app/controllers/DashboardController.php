<?php
// app/controllers/DashboardController.php
require_once 'BaseController.php';

class DashboardController extends BaseController
{

  public function index()
  {
    try {
      // Count total residents
      $stmt = $this->pdo->query("SELECT COUNT(*) FROM tbl_residents");
      $total_residents = $stmt->fetchColumn();

      // Count pending certifications
      $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM tbl_certifications WHERE status = 'pending'");
      $stmt->execute();
      $pending_certifications = $stmt->fetchColumn();

      // Count upcoming events (today and future)
      $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM tbl_events WHERE event_date >= CURDATE() AND status = 'upcoming'");
      $stmt->execute();
      $upcoming_events = $stmt->fetchColumn();

      // Count open blotters
      $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM tbl_blotters WHERE status = 'open'");
      $stmt->execute();
      $open_blotters = $stmt->fetchColumn();

      // Count current officials (optional - assuming term_end is null or future)
      $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM tbl_officials WHERE term_end IS NULL OR term_end >= CURDATE()");
      $stmt->execute();
      $total_officials = $stmt->fetchColumn();

      // Get recent activities (last 5 logs)
      $stmt = $this->pdo->prepare("
                SELECT l.*, u.full_name
                FROM tbl_logs l
                JOIN tbl_users u ON l.user_id = u.id
                ORDER BY l.timestamp DESC
                LIMIT 5
            ");
      $stmt->execute();
      $recent_logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Get recent announcements
      $stmt = $this->pdo->prepare("
                SELECT a.*, u.full_name as posted_by_name
                FROM tbl_announcements a
                JOIN tbl_users u ON a.posted_by = u.id
                ORDER BY a.post_date DESC
                LIMIT 3
            ");
      $stmt->execute();
      $recent_announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Dashboard data error: " . $e->getMessage());
      $_SESSION['error'] = "Unable to load dashboard data at this time.";

      // Set safe defaults
      $total_residents = 0;
      $pending_certifications = 0;
      $upcoming_events = 0;
      $open_blotters = 0;
      $total_officials = 0;
      $recent_logs = [];
      $recent_announcements = [];
    }

    // Render the dashboard view with data
    $this->render('dashboard', [
      'total_residents' => $total_residents,
      'pending_certifications' => $pending_certifications,
      'upcoming_events' => $upcoming_events,
      'open_blotters' => $open_blotters,
      'total_officials' => $total_officials,
      'recent_logs' => $recent_logs,
      'recent_announcements' => $recent_announcements,
      'title' => 'Dashboard'
    ]);
  }
}
