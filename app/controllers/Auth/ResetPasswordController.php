<?php
// app/controllers/Auth/ResetPasswordController.php

class ResetPasswordController {
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

        // Handle POST request (password reset submission)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePasswordReset();
        } else {
            // Show reset password form (GET request)
            $this->showResetForm();
        }
    }

    private function handlePasswordReset() {
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Validate input
        if (empty($token) || empty($password) || empty($confirm_password)) {
            $_SESSION['error'] = "Please fill in all fields.";
            $_SESSION['old'] = ['token' => $token];
            header('Location: index.php?action=reset-password&token=' . urlencode($token));
            exit();
        }

        if ($password !== $confirm_password) {
            $_SESSION['error'] = "Passwords do not match.";
            $_SESSION['old'] = ['token' => $token];
            header('Location: index.php?action=reset-password&token=' . urlencode($token));
            exit();
        }

        if (strlen($password) < 6) {
            $_SESSION['error'] = "Password must be at least 6 characters.";
            $_SESSION['old'] = ['token' => $token];
            header('Location: index.php?action=reset-password&token=' . urlencode($token));
            exit();
        }

        try {
            // Verify token and get email
            $stmt = $this->pdo->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()");
            $stmt->execute([$token]);
            $resetRequest = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$resetRequest) {
                $_SESSION['error'] = "Invalid or expired reset token. Please request a new password reset.";
                header('Location: index.php?action=forgot-password');
                exit();
            }

            $email = $resetRequest['email'];

            // Get user ID
            $stmt = $this->pdo->prepare("SELECT id FROM tbl_users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $_SESSION['error'] = "User not found.";
                header('Location: index.php?action=forgot-password');
                exit();
            }

            $userId = $user['id'];

            // Hash the new password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Start transaction
            $this->pdo->beginTransaction();

            try {
                // Update user's password
                $stmt = $this->pdo->prepare("UPDATE tbl_users SET password = ? WHERE email = ?");
                $stmt->execute([$hashed_password, $email]);

                // Delete the used token
                $stmt = $this->pdo->prepare("DELETE FROM password_resets WHERE token = ?");
                $stmt->execute([$token]);

                // Log the password reset
                $stmt = $this->pdo->prepare("INSERT INTO tbl_logs (user_id, action) VALUES (?, ?)");
                $stmt->execute([$userId, "Password reset completed"]);

                // Commit transaction
                $this->pdo->commit();

                // Clear any old data
                unset($_SESSION['old']);

                // Set success message
                $_SESSION['success'] = "Password has been reset successfully! You can now login with your new password.";

                // Redirect to login page
                header('Location: index.php?action=login');
                exit();

            } catch (Exception $e) {
                $this->pdo->rollBack();
                throw $e;
            }

        } catch (PDOException $e) {
            error_log("Password reset error: " . $e->getMessage());
            $_SESSION['error'] = "System error. Please try again later.";
            header('Location: index.php?action=reset-password&token=' . urlencode($token));
            exit();
        }
    }

    private function showResetForm() {
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            $_SESSION['error'] = "Invalid reset link. Please request a new password reset.";
            header('Location: index.php?action=forgot-password');
            exit();
        }

        try {
            // Verify token is valid and not expired
            $stmt = $this->pdo->prepare("SELECT email, expires_at FROM password_resets WHERE token = ?");
            $stmt->execute([$token]);
            $resetRequest = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$resetRequest) {
                $_SESSION['error'] = "Invalid reset token.";
                header('Location: index.php?action=forgot-password');
                exit();
            }

            // Check if token has expired
            $expiresAt = new DateTime($resetRequest['expires_at']);
            $now = new DateTime();

            if ($expiresAt < $now) {
                // Delete expired token
                $stmt = $this->pdo->prepare("DELETE FROM password_resets WHERE token = ?");
                $stmt->execute([$token]);

                $_SESSION['error'] = "Reset token has expired. Please request a new password reset.";
                header('Location: index.php?action=forgot-password');
                exit();
            }

            // Token is valid, show reset form
            require_once '../app/views/Auth/reset-password.php';

        } catch (PDOException $e) {
            error_log("Token verification error: " . $e->getMessage());
            $_SESSION['error'] = "System error. Please try again later.";
            header('Location: index.php?action=forgot-password');
            exit();
        }
    }
}
