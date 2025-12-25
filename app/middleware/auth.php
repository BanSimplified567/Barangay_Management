<?php
if (!function_exists('authorize')) {
    function authorize(array $allowedRoles) {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
                  header("Location: /Barangay_Management/public/index.php?action=dashboard");
exit();


        }

        if (!in_array($_SESSION['role'], $allowedRoles)) {
            $_SESSION['error'] = "Access denied. You don't have permission to view this page.";
      header("Location: /Barangay_Management/public/index.php?action=dashboard");
exit();

        }
    }
}

// Optional: redirect if already logged in (for login/signup pages)
if (!function_exists('guest_only')) {
    function guest_only() {
        if (isset($_SESSION['user_id'])) {
 header("Location: /Barangay_Management/public/index.php?action=dashboard");
exit();

        }
    }
}
