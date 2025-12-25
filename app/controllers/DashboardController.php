<?php
// app/controllers/DashboardController.php

if (!isset($pdo)) {
    // If $pdo is not available (shouldn't happen if dbcon.php is required in index.php)
    require_once '../../config/dbcon.php';
}

try {
    // Dashboard statistics
    $total_residents = $pdo->query("SELECT COUNT(*) FROM residents")->fetchColumn();
    $total_officials = $pdo->query("SELECT COUNT(*) FROM tbl_officials")->fetchColumn();

    $certificates_issued = $pdo->query("SELECT COUNT(*) FROM tbl_certifications WHERE status = 'issued'")->fetchColumn();

    $ongoing_events = $pdo->query("SELECT COUNT(*) FROM tbl_events WHERE status = 'ongoing'")->fetchColumn();

    $upcoming_events = $pdo->query("SELECT COUNT(*) FROM tbl_events WHERE status = 'upcoming'")->fetchColumn();

    // Recent announcements
    $stmt = $pdo->query("SELECT title, content, post_date FROM tbl_announcements ORDER BY post_date DESC LIMIT 5");
    $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Recent activity logs
    $stmt = $pdo->query("SELECT action, timestamp FROM tbl_logs ORDER BY timestamp DESC LIMIT 5");
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Log error in production, show user-friendly message
    error_log("Dashboard data error: " . $e->getMessage());
    $_SESSION['error'] = "Unable to load dashboard data at this time.";

    // Set safe defaults
    $total_residents = $total_officials = $certificates_issued = $ongoing_events = $upcoming_events = 0;
    $announcements = $logs = [];
}
