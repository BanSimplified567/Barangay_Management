<?php
// app/controllers/Auth/RegisterController.php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = 'resident';  // Default role

    if (empty($username)) $errors[] = "Username is required.";
    if (empty($email)) $errors[] = "Email is required.";
    if (empty($password)) $errors[] = "Password is required.";

    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $hashed, $role]);
            $_SESSION['success'] = "Registration successful. Please login.";
            header("Location: index.php?action=login");
            exit();
        } catch (PDOException $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
    }
}

$pageTitle = "Create Account";
require '../app/views/auth/signup.php';
