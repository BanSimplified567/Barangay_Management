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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $pdo->prepare("UPDATE residents SET full_name = ?, address = ?, birthdate = ?, gender = ?, contact_number = ?, occupation = ?, civil_status = ? WHERE id = ?");
    $stmt->execute([$_POST['full_name'], $_POST['address'], $_POST['birthdate'], $_POST['gender'], $_POST['contact_number'], $_POST['occupation'], $_POST['civil_status'], $_POST['id']]);

    // Log action
    $stmtUser = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmtUser->execute([$_SESSION['email']]);
    $user_id = $stmtUser->fetchColumn();

    if ($user_id) {
        $pdo->prepare("INSERT INTO tbl_logs (user_id, action) VALUES (?, ?)")->execute([$user_id, "Updated resident ID: " . $_POST['id']]);
    }

    header("Location: ../residents/residents.php");
    exit();
}
?>
