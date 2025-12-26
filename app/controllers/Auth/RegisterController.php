<?php
// app/controllers/Auth/RegisterController.php

class RegisterController {
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
            $this->handleRegistration();
        } else {
            // Show registration form
            $this->showRegistrationForm();
        }
    }

    private function handleRegistration() {
        $errors = [];

        $full_name = trim($_POST['full_name'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $password  = $_POST['password'] ?? '';
        $confirm   = $_POST['confirm_password'] ?? '';

        // Validation
        if (empty($full_name)) $errors[] = "Full name is required.";
        elseif (strlen($full_name) < 2) $errors[] = "Full name must be at least 2 characters.";

        if (empty($email)) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        if (empty($password)) {
            $errors[] = "Password is required.";
        } elseif (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters.";
        } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter and one number.";
        }

        if ($password !== $confirm) {
            $errors[] = "Passwords do not match.";
        }

        // Check if email exists
        if (empty($errors)) {
            try {
                $stmt = $this->pdo->prepare("SELECT id FROM tbl_users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->rowCount() > 0) {
                    $errors[] = "Email is already registered.";
                }
            } catch (PDOException $e) {
                error_log("Registration email check error: " . $e->getMessage());
                $errors[] = "System error. Please try again.";
            }
        }

        // Register user
        if (empty($errors)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'resident'; // Default role for new registrations

            try {
                // Start transaction
                $this->pdo->beginTransaction();

                // Insert user
                $stmt = $this->pdo->prepare("INSERT INTO tbl_users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
                $stmt->execute([$full_name, $email, $hashed_password, $role]);
                $userId = $this->pdo->lastInsertId();

                // Create a resident record linked to this user
                $stmt = $this->pdo->prepare("
                    INSERT INTO tbl_residents (user_id, full_name, address, birthdate, gender, civil_status)
                    VALUES (?, ?, '', CURDATE(), 'other', 'single')
                ");
                $stmt->execute([$userId, $full_name]);

                // Log the registration
                $stmt = $this->pdo->prepare("INSERT INTO tbl_logs (user_id, action) VALUES (?, ?)");
                $stmt->execute([$userId, "New user registration: " . $full_name]);

                // Commit transaction
                $this->pdo->commit();

                // Clear any old form data
                unset($_SESSION['old']);

                // Set success message
                $_SESSION['success'] = "Registration successful! You can now log in.";
                header("Location: index.php?action=login");
                exit();

            } catch (PDOException $e) {
                $this->pdo->rollBack();
                error_log("Registration error: " . $e->getMessage());
                $errors[] = "Registration failed. Please try again.";
            }
        }

        // If errors, store them for display
        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);
            // Preserve form data
            $_SESSION['old'] = [
                'full_name' => $full_name,
                'email' => $email
            ];

            // Redirect back to register to show errors
            header("Location: index.php?action=register");
            exit();
        }
    }

    private function showRegistrationForm() {
        require_once '../app/views/Auth/register.php';
    }
}
