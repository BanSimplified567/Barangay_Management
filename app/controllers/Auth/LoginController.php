<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM tbl_users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email']   = $user['email'];
        $_SESSION['role']    = $user['role'];

        header("Location: /Barangay_Management/public/index.php?action=dashboard");
        exit();
    }

    $_SESSION['error'] = "Invalid email or password.";
    header("Location: /Barangay_Management/public/index.php?action=login");
    exit();
}

require_once '../app/views/Auth/login.php';
