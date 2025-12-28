<?php
// app/controllers/DashboardController.php
require_once 'BaseController.php';

class DashboardController extends BaseController
{
  public function index()
  {
    try {
      // Get current month and year for statistics
      $currentMonth = date('m');
      $currentYear = date('Y');

      // 1. RESIDENTS STATISTICS
      $stmt = $this->pdo->query("SELECT COUNT(*) FROM tbl_residents");
      $total_residents = $stmt->fetchColumn();

      // Residents by gender - using COALESCE to handle NULL values
      $stmt = $this->pdo->query("
                SELECT
                    COALESCE(SUM(gender = 'male'), 0) as male_count,
                    COALESCE(SUM(gender = 'female'), 0) as female_count,
                    COALESCE(SUM(gender IS NULL OR gender = '' OR gender = 'other'), 0) as unspecified_count
                FROM tbl_residents
            ");
      $gender_stats = $stmt->fetch(PDO::FETCH_ASSOC);

      // New residents this month
      $stmt = $this->pdo->prepare("
                SELECT COUNT(*)
                FROM tbl_residents
                WHERE YEAR(created_at) = ? AND MONTH(created_at) = ?
            ");
      $stmt->execute([$currentYear, $currentMonth]);
      $new_residents_month = $stmt->fetchColumn();

      // 2. CERTIFICATIONS STATISTICS
      $certification_stats = $this->pdo->query("
                SELECT
                    COALESCE(SUM(status = 'pending'), 0) as pending,
                    COALESCE(SUM(status = 'issued'), 0) as approved,
                    COALESCE(SUM(status = 'rejected'), 0) as rejected,
                    COALESCE(SUM(status = 'issued'), 0) as completed
                FROM tbl_certifications
            ")->fetch(PDO::FETCH_ASSOC);

      // 3. EVENTS STATISTICS
      $event_stats = $this->pdo->query("
                SELECT
                    COALESCE(SUM(status = 'upcoming'), 0) as upcoming,
                    COALESCE(SUM(status = 'ongoing'), 0) as ongoing,
                    COALESCE(SUM(status = 'completed'), 0) as completed,
                    COALESCE(SUM(status = 'cancelled'), 0) as cancelled
                FROM tbl_events
            ")->fetch(PDO::FETCH_ASSOC);

      // 4. BLOTTERS STATISTICS
      $blotter_stats = $this->pdo->query("
                SELECT
                    COALESCE(SUM(status = 'open'), 0) as open,
                    COALESCE(SUM(status = 'settled'), 0) as settled,
                    COALESCE(SUM(status = 'escalated'), 0) as escalated,
                    COUNT(*) as total
                FROM tbl_blotters
            ")->fetch(PDO::FETCH_ASSOC);

      // 5. CRIME RECORDS STATISTICS
      $crime_stats = $this->pdo->query("
                SELECT
                    COALESCE(SUM(status = 'reported'), 0) as reported,
                    COALESCE(SUM(status = 'under_investigation'), 0) as investigating,
                    COALESCE(SUM(status = 'resolved'), 0) as resolved,
                    COUNT(*) as total
                FROM tbl_crime_records
            ")->fetch(PDO::FETCH_ASSOC);

      // 6. OFFICIALS STATISTICS
      // Note: Your tbl_officials doesn't have 'status' column, so we check only term dates
      $stmt = $this->pdo->query("
                SELECT COUNT(*)
                FROM tbl_officials
                WHERE (term_end IS NULL OR term_end >= CURDATE())
            ");
      $total_officials = $stmt->fetchColumn();

      // Officials by position
      $position_stats = $this->pdo->query("
                SELECT position, COUNT(*) as count
                FROM tbl_officials
                WHERE (term_end IS NULL OR term_end >= CURDATE())
                GROUP BY position
                ORDER BY count DESC
                LIMIT 5
            ")->fetchAll(PDO::FETCH_ASSOC);

      // 7. RECENT ACTIVITIES (Last 10) - Your logs table has 'timestamp' column
      $recent_logs = $this->pdo->query("
                SELECT l.*, u.full_name, u.role
                FROM tbl_logs l
                JOIN tbl_users u ON l.user_id = u.id
                ORDER BY l.timestamp DESC
                LIMIT 2
            ")->fetchAll(PDO::FETCH_ASSOC);

      // 8. RECENT ANNOUNCEMENTS - Your table doesn't have 'status' column
      $recent_announcements = $this->pdo->query("
                SELECT a.*, u.full_name as posted_by_name
                FROM tbl_announcements a
                JOIN tbl_users u ON a.posted_by = u.id
                ORDER BY a.post_date DESC
                LIMIT 5
            ")->fetchAll(PDO::FETCH_ASSOC);

      // 9. UPCOMING EVENTS DETAILS
      $upcoming_events_list = $this->pdo->query("
                SELECT e.*, u.full_name as organizer_name
                FROM tbl_events e
                LEFT JOIN tbl_users u ON e.organizer_id = u.id
                WHERE e.event_date >= CURDATE()
                AND e.status IN ('upcoming', 'ongoing')
                ORDER BY e.event_date ASC
                LIMIT 5
            ")->fetchAll(PDO::FETCH_ASSOC);

      // 10. PENDING REQUESTS
      $pending_requests = $this->pdo->query("
                SELECT 'certification' as type, COUNT(*) as count
                FROM tbl_certifications
                WHERE status = 'pending'
                UNION ALL
                SELECT 'blotter' as type, COUNT(*) as count
                FROM tbl_blotters
                WHERE status = 'open'
                UNION ALL
                SELECT 'crime' as type, COUNT(*) as count
                FROM tbl_crime_records
                WHERE status = 'reported'
            ")->fetchAll(PDO::FETCH_ASSOC);

      // 11. SYSTEM STATISTICS
      $system_stats = [
        'users' => $this->pdo->query("SELECT COUNT(*) FROM tbl_users")->fetchColumn(),
        'logs_today' => $this->pdo->query("SELECT COUNT(*) FROM tbl_logs WHERE DATE(timestamp) = CURDATE()")->fetchColumn(),
        'active_sessions' => 0, // You can implement session tracking
      ];

      // 12. PROJECTS STATISTICS (from your projects table)
      $project_stats = $this->pdo->query("
                SELECT
                    COALESCE(SUM(status = 'planning'), 0) as planning,
                    COALESCE(SUM(status = 'ongoing'), 0) as ongoing,
                    COALESCE(SUM(status = 'completed'), 0) as completed,
                    COALESCE(SUM(status = 'cancelled'), 0) as cancelled,
                    COUNT(*) as total
                FROM tbl_projects
            ")->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Dashboard data error: " . $e->getMessage());
      error_log("Error details: " . $e->getTraceAsString());
      $_SESSION['error'] = "Unable to load dashboard data at this time. Error: " . $e->getMessage();

      // Set safe defaults
      $total_residents = 0;
      $gender_stats = ['male_count' => 0, 'female_count' => 0, 'unspecified_count' => 0];
      $new_residents_month = 0;
      $certification_stats = ['pending' => 0, 'approved' => 0, 'rejected' => 0, 'completed' => 0];
      $event_stats = ['upcoming' => 0, 'ongoing' => 0, 'completed' => 0, 'cancelled' => 0];
      $blotter_stats = ['open' => 0, 'settled' => 0, 'escalated' => 0, 'total' => 0];
      $crime_stats = ['reported' => 0, 'investigating' => 0, 'resolved' => 0, 'total' => 0];
      $total_officials = 0;
      $position_stats = [];
      $recent_logs = [];
      $recent_announcements = [];
      $upcoming_events_list = [];
      $pending_requests = [];
      $system_stats = ['users' => 0, 'logs_today' => 0, 'active_sessions' => 0];
      $project_stats = ['planning' => 0, 'ongoing' => 0, 'completed' => 0, 'cancelled' => 0, 'total' => 0];
    }

    // Render the dashboard view with all data
    $this->render('dashboard', [
      'total_residents' => $total_residents,
      'gender_stats' => $gender_stats,
      'new_residents_month' => $new_residents_month,
      'certification_stats' => $certification_stats,
      'event_stats' => $event_stats,
      'blotter_stats' => $blotter_stats,
      'crime_stats' => $crime_stats,
      'total_officials' => $total_officials,
      'position_stats' => $position_stats,
      'recent_logs' => $recent_logs,
      'recent_announcements' => $recent_announcements,
      'upcoming_events_list' => $upcoming_events_list,
      'pending_requests' => $pending_requests,
      'system_stats' => $system_stats,
      'project_stats' => $project_stats,
      'title' => 'Dashboard Overview'
    ]);
  }
}
