<?php
// Assume user role is in session from auth
$role = $_SESSION['user_role'] ?? 'guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style/style.css"> <!-- Your custom CSS -->
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?action=dashboard">Barangay System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="index.php?action=dashboard">Dashboard</a></li>
                    <?php if (in_array($role, ['admin', 'staff'])): ?>
                        <li class="nav-item"><a class="nav-link" href="index.php?action=residents">Residents</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?action=certifications">Certifications</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?action=blotters">Blotters</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?action=crimes">Crimes</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?action=events">Events</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?action=officials">Officials</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?action=announcements">Announcements</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?action=logs">Logs</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?action=projects">Projects</a></li>
                    <?php endif; ?>
                    <?php if ($role === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="index.php?action=settings">Settings</a></li>
                    <?php endif; ?>
                    <?php if (in_array($role, ['resident', 'admin', 'staff'])): ?>
                        <li class="nav-item"><a class="nav-link" href="index.php?action=my_profile">My Profile</a></li>
                    <?php endif; ?>
                    <?php if ($role === 'resident'): ?>
                        <li class="nav-item"><a class="nav-link" href="index.php?action=profile">Profile</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?action=request-certification">Request Certification</a></li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php?action=logout">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
