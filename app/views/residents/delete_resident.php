<?php
// app/controllers/residents/delete_resident.php
session_start();
require_once '../../config/dbcon.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Unauthorized access.';
    header("Location: ../../public/index.php?action=login");
    exit();
}

// Check user role
$role = $_SESSION['role'] ?? '';
if (!in_array($role, ['admin', 'staff'])) {
    $_SESSION['error'] = 'Permission denied.';
    header("Location: ../../public/index.php?action=dashboard");
    exit();
}

// Check if ID is provided
if (!isset($_GET['id'])) {
    $_SESSION['error'] = 'No resident ID specified.';
    header("Location: ../../public/index.php?action=residents");
    exit();
}

$id = (int)$_GET['id'];

try {
    // Get resident name for logging
    $stmt = $pdo->prepare("SELECT full_name FROM tbl_residents WHERE id = ?");
    $stmt->execute([$id]);
    $resident = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$resident) {
        $_SESSION['error'] = 'Resident not found.';
        header("Location: ../../public/index.php?action=residents");
        exit();
    }

    // Delete resident
    $stmt = $pdo->prepare("DELETE FROM tbl_residents WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        // Log the action
        $logStmt = $pdo->prepare("INSERT INTO tbl_logs (user_id, action) VALUES (?, ?)");
        $logStmt->execute([$_SESSION['user_id'], "Deleted resident: " . $resident['full_name'] . " (ID: $id)"]);

        $_SESSION['success'] = "Resident deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete resident.";
    }
} catch (PDOException $e) {
    error_log("Delete resident error: " . $e->getMessage());
    $_SESSION['error'] = "Database error occurred. Please try again.";
}

// Redirect back to residents page
header("Location: ../../public/index.php?action=residents");
exit();
?>
