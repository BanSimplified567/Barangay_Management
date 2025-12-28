<?php
// app/controllers/Auth/ForgotController.php

class ForgotController {
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
            $this->handlePasswordReset();
        } else {
            // Show forgot password form
            $this->showForgotForm();
        }
    }

    private function handlePasswordReset() {
        $email = trim($_POST['email'] ?? '');

        // Validate input
        if (empty($email)) {
            $_SESSION['error'] = "Please enter your email address.";
            header('Location: index.php?action=forgot-password');
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Please enter a valid email address.";
            $_SESSION['old'] = ['email' => $email];
            header('Location: index.php?action=forgot-password');
            exit();
        }

        try {
            // Check if user exists
            $stmt = $this->pdo->prepare("SELECT id, full_name FROM tbl_users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Generate reset token
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Check if password_resets table exists, create if not
                $this->createPasswordResetsTable();

                // Delete any existing tokens for this user
                $stmt = $this->pdo->prepare("DELETE FROM password_resets WHERE email = ?");
                $stmt->execute([$email]);

                // Store token in database
                $stmt = $this->pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
                $stmt->execute([$email, $token, $expires]);

                // Send reset email
                $sent = $this->sendResetEmail($email, $user['full_name'], $token);

                if ($sent) {
                    // Log the password reset request
                    $this->logPasswordReset($user['id']);

                    $_SESSION['success'] = "Password reset instructions have been sent to your email. Please check your inbox and spam folder.";
                    // Clear old form data
                    unset($_SESSION['old']);
                } else {
                    $_SESSION['error'] = "Failed to send reset email. Please try again or contact support.";
                }
            } else {
                // For security, don't reveal if email doesn't exist
                $_SESSION['success'] = "If an account exists with this email, you will receive password reset instructions shortly.";
                // Clear old form data
                unset($_SESSION['old']);
            }

            header('Location: index.php?action=forgot-password');
            exit();

        } catch (PDOException $e) {
            error_log("Password reset error: " . $e->getMessage());
            $_SESSION['error'] = "System error. Please try again later.";
            header('Location: index.php?action=forgot-password');
            exit();
        }
    }

    private function createPasswordResetsTable() {
        // Check if table exists
        $checkTable = $this->pdo->query("SHOW TABLES LIKE 'password_resets'");
        if ($checkTable->rowCount() == 0) {
            $sql = "CREATE TABLE password_resets (
                id INT PRIMARY KEY AUTO_INCREMENT,
                email VARCHAR(255) NOT NULL,
                token VARCHAR(64) NOT NULL,
                expires_at DATETIME NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_token (token),
                INDEX idx_email (email)
            )";

            $this->pdo->exec($sql);
        }
    }

    private function sendResetEmail($email, $name, $token) {
        // Create reset link
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];

        // Get the base path (handles subdirectory installations)
        $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
        $basePath = $scriptPath === '/' ? '' : $scriptPath;

        $resetLink = $protocol . $host . $basePath . "/index.php?action=reset-password&token=" . urlencode($token);

        // Email subject
        $subject = "Barangay Sibonga - Password Reset Request";

        // Email body (HTML)
        $htmlBody = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #003893 0%, #002a6e 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #e0e6f0; }
                .button { display: inline-block; padding: 12px 30px; background: linear-gradient(135deg, #003893 0%, #002a6e 100%); color: white; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 20px 0; }
                .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e6f0; color: #666; font-size: 12px; }
                .warning { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Barangay Sibonga Management System</h2>
                </div>
                <div class='content'>
                    <h3>Hello " . htmlspecialchars($name) . ",</h3>
                    <p>We received a request to reset your password for your Barangay Sibonga account.</p>

                    <p><strong>Click the button below to reset your password:</strong></p>

                    <div style='text-align: center;'>
                        <a href='$resetLink' class='button'>Reset Password</a>
                    </div>

                    <p>Or copy and paste this link into your browser:</p>
                    <p><code style='background: #f8f9fa; padding: 10px; border-radius: 5px; display: block; word-break: break-all;'>$resetLink</code></p>

                    <div class='warning'>
                        <p><strong>⚠️ Important:</strong></p>
                        <ul>
                            <li>This link will expire in 1 hour</li>
                            <li>If you didn't request this password reset, you can safely ignore this email</li>
                            <li>For security reasons, never share this link with anyone</li>
                        </ul>
                    </div>

                    <p>If you continue to have issues, please contact Barangay Sibonga support.</p>
                </div>
                <div class='footer'>
                    <p>© " . date('Y') . " Barangay Sibonga. All rights reserved.</p>
                    <p>This is an automated message, please do not reply to this email.</p>
                </div>
            </div>
        </body>
        </html>
        ";

        // Plain text version
        $textBody = "Password Reset Request\n\n";
        $textBody .= "Hello $name,\n\n";
        $textBody .= "We received a request to reset your password for your Barangay Sibonga account.\n\n";
        $textBody .= "To reset your password, visit this link:\n";
        $textBody .= "$resetLink\n\n";
        $textBody .= "This link will expire in 1 hour.\n\n";
        $textBody .= "If you didn't request this password reset, you can safely ignore this email.\n\n";
        $textBody .= "© " . date('Y') . " Barangay Sibonga";

        // Headers
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: Barangay Sibonga <noreply@sibonga.gov.ph>\r\n";
        $headers .= "Reply-To: Barangay Sibonga <barangay@sibonga.gov.ph>\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        try {
            // Send email
            $sent = mail($email, $subject, $htmlBody, $headers);

            if (!$sent) {
                error_log("Failed to send password reset email to: $email");
                // Try plain text version as fallback
                $sent = mail($email, $subject, $textBody, str_replace('text/html', 'text/plain', $headers));
            }

            return $sent;

        } catch (Exception $e) {
            error_log("Email sending error: " . $e->getMessage());
            return false;
        }
    }

    private function logPasswordReset($userId) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO tbl_logs (user_id, action) VALUES (?, ?)");
            $stmt->execute([$userId, "Password reset requested"]);
        } catch (PDOException $e) {
            // Don't break the process if logging fails
            error_log("Failed to log password reset: " . $e->getMessage());
        }
    }

    private function showForgotForm() {
        require_once '../app/views/Auth/forgot-password.php';
    }
}
