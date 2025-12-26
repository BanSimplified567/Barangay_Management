<?php
// app/controllers/Auth/LoginController.php

class LoginController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        // If user is already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?action=dashboard');
            exit();
        }

        // Handle POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleLogin();
        } else {
            // Show login form
            $this->showLoginForm();
        }
    }

    private function handleLogin() {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validate input
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Please enter both email and password.";
            header('Location: index.php?action=login');
            exit();
        }

        try {
            // Query user from database
            $stmt = $this->pdo->prepare("SELECT * FROM tbl_users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Regenerate session ID for security
                session_regenerate_id(true);

                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['full_name'] = $user['full_name'];

                // Log the login action
                $this->logLogin($user['id']);

                // Clear any previous error messages
                unset($_SESSION['error']);

                // Set success message
                $_SESSION['success'] = 'Welcome back, ' . htmlspecialchars($user['full_name']) . '!';

                // Redirect to dashboard
                header('Location: index.php?action=dashboard');
                exit();
            } else {
                $_SESSION['error'] = "Invalid email or password.";
                header('Location: index.php?action=login');
                exit();
            }
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            $_SESSION['error'] = "System error. Please try again later.";
            header('Location: index.php?action=login');
            exit();
        }
    }

    private function logLogin($userId) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO tbl_logs (user_id, action) VALUES (?, ?)");
            $stmt->execute([$userId, "User logged in"]);
        } catch (PDOException $e) {
            // Don't break login if logging fails
            error_log("Failed to log login: " . $e->getMessage());
        }
    }

    private function showLoginForm() {
        require_once '../app/views/Auth/login.php';
    }
}
