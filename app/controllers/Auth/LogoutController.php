<?php
// app/controllers/Auth/LogoutController.php

class LogoutController {
    public function __construct($pdo = null) {
        // Optional: accept PDO for logging if needed
    }

    public function index() {
        // Clear session data
        $_SESSION = array();

        // Destroy session
        session_destroy();

        // Redirect to login
        header("Location: index.php?action=login");
        exit();
    }
}
