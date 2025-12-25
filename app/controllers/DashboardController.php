<?php
// app/controllers/DashboardController.php

if (!isset($pdo)) {
    // If $pdo is not available (shouldn't happen if dbcon.php is required in index.php)
    require_once '../../config/dbcon.php';
}

try {
// app/controllers/DashboardController.php

require_once '../config/dbcon.php'; // Make sure $pdo is available

// Count total residents
$stmt = $pdo->query("SELECT COUNT(*) FROM tbl_residents");
$total_residents = $stmt->fetchColumn();

// Count pending certifications
$stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_certifications WHERE status = 'pending'");
$stmt->execute();
$pending_certifications = $stmt->fetchColumn();

// Count upcoming events (today and future)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_events WHERE event_date >= CURDATE() AND status = 'upcoming'");
$stmt->execute();
$upcoming_events = $stmt->fetchColumn();

// Count open blotters
$stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_blotters WHERE status = 'open'");
$stmt->execute();
$open_blotters = $stmt->fetchColumn();

// Count current officials (optional - assuming term_end is null or future)
$stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_officials WHERE term_end IS NULL OR term_end >= CURDATE()");
$stmt->execute();
$total_officials = $stmt->fetchColumn();

} catch (PDOException $e) {
    // Log error in production, show user-friendly message
    error_log("Dashboard data error: " . $e->getMessage());
    $_SESSION['error'] = "Unable to load dashboard data at this time.";

    // Set safe defaults
    $total_residents = $total_officials = $certificates_issued = $ongoing_events = $upcoming_events = 0;
    $announcements = $logs = [];
}
