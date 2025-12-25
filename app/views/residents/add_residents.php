<?php
session_start();
include '../config/dbcon.php';

if (!isset($_SESSION['email'])) {
    die('Unauthorized access.');
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Insert resident
    $stmt = $pdo->prepare(
        "INSERT INTO residents
        (full_name, address, birthdate, gender, contact_number, occupation, civil_status)
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->execute([
        $_POST['full_name'],
        $_POST['address'],
        $_POST['birthdate'],
        $_POST['gender'],
        $_POST['contact_number'],
        $_POST['occupation'],
        $_POST['civil_status']
    ]);

    // Resolve user_id safely
    $stmtUser = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmtUser->execute([$_SESSION['email']]);
    $user_id = $stmtUser->fetchColumn();

    if (!$user_id) {
        die('User not found.');
    }

    // Log action
    $stmtLog = $pdo->prepare(
        "INSERT INTO tbl_logs (user_id, action) VALUES (?, ?)"
    );
    $stmtLog->execute([
        $user_id,
        "Added resident: " . $_POST['full_name']
    ]);

    header("Location: ../residents/residents.php");
    exit();
}
?>
