<?php
// app/controllers/Auth/RegisterController.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    $full_name = trim($_POST['full_name'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';
    $confirm   = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($full_name)) $errors[] = "Full name is required.";
    if (empty($email)) $errors[] = "Email is required.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";
    if (empty($password)) $errors[] = "Password is required.";
    elseif (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
    if ($password !== $confirm) $errors[] = "Passwords do not match.";

    // Check if email exists
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM tbl_users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                $errors[] = "Email is already registered.";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error: Unable to check email.";
        }
    }

    // Register user
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = 'resident';

        try {
            $stmt = $pdo->prepare("INSERT INTO tbl_users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$full_name, $email, $hashed_password, $role]);

            $_SESSION['success'] = "Registration successful! You can now log in.";
            header("Location: index.php?action=login"); // Fixed path
            exit();
        } catch (PDOException $e) {
            $errors[] = "Registration failed. Please try again.";
        }
    }

    // If errors, store them for display
    if (!empty($errors)) {
        $_SESSION['error'] = implode("<br>", $errors);
        // Optional: preserve form data
        $_SESSION['old'] = $_POST;
    }

    // Redirect back to register on error to show messages
    header("Location: index.php?action=register");
    exit();
}

// GET request → show registration form
require_once '../app/views/auth/register.php';
