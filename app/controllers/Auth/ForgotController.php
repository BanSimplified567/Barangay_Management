<?php
// app/controllers/Auth/ForgotController.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle password reset logic (e.g., send email)
    $email = $_POST['email'] ?? '';
    // Placeholder: Check if email exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        // Send reset link (placeholder)
        $_SESSION['success'] = "Reset link sent to your email.";
    } else {
        $_SESSION['error'] = "Email not found.";
    }
    header("Location: index.php?action=forgot-password");
    exit();
}

$pageTitle = "Forgot Password";
require '../app/views/auth/forgot-password.php';
