<?php
session_start();
include '../config/dbcon.php';

if (!isset($_SESSION['email'])) {
    die('Unauthorized access.');
}

// Check user role
$stmtUser = $pdo->prepare("SELECT role FROM users WHERE email = ?");
$stmtUser->execute([$_SESSION['email']]);
$role = $stmtUser->fetchColumn();

if ($role !== 'admin' && $role !== 'staff') {
    die('Permission denied.');
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM residents WHERE id = ?");
    $stmt->execute([$id]);

    // Log action
    $stmtUser = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmtUser->execute([$_SESSION['email']]);
    $user_id = $stmtUser->fetchColumn();
    
    if ($user_id) {
        $pdo->prepare("INSERT INTO logs (user_id, action) VALUES (?, ?)")->execute([$user_id, "Deleted resident ID: " . $id]);
    }
}

header("Location: ../residents/residents.php");
exit();
?>
