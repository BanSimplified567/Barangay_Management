<?php
if (!function_exists('authorize')) {
    /**
     * Restrict access to specific roles
     * @param array $allowedRoles Allowed user roles (e.g., ['admin', 'staff'])
     */
    function authorize(array $allowedRoles)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
            $_SESSION['error'] = "Please log in to access this page.";
            header("Location: index.php?action=login");
            exit();
        }

        // Check if user's role is allowed
        if (!in_array($_SESSION['role'], $allowedRoles)) {
            $_SESSION['error'] = "Access denied. You don't have permission to view this page.";
            header("Location: index.php?action=dashboard");
            exit();
        }
    }
}

if (!function_exists('guest_only')) {
    /**
     * Allow access only to guests (not logged in users)
     * Useful for login/register pages
     */
    function guest_only()
    {
        if (isset($_SESSION['user_id'])) {
            $_SESSION['info'] = "You are already logged in.";
            header("Location: index.php?action=dashboard");
            exit();
        }
    }
}
