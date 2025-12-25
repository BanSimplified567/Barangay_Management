<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Management System</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/login.css">
    <!-- Add Bootstrap or your preferred CSS framework if desired -->
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php?action=dashboard">Dashboard</a></li>

                <?php if (in_array($_SESSION['role'] ?? '', ['admin', 'staff'])): ?>
                    <li><a href="index.php?action=residents">Residents</a></li>
                    <li><a href="index.php?action=officials">Officials</a></li>
                    <li><a href="index.php?action=announcements">Announcements</a></li>
                    <li><a href="index.php?action=events">Events</a></li>
                    <li><a href="index.php?action=blotters">Blotters</a></li>
                    <li><a href="index.php?action=crimes">Crimes</a></li>
                    <li><a href="index.php?action=certifications">Certifications</a></li>
                    <li><a href="index.php?action=logs">Activity Logs</a></li>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="index.php?action=my_profile">My Profile</a></li>
                    <?php if ($_SESSION['role'] === 'resident'): ?>
                        <li><a href="index.php?action=request-certification">Request Certification</a></li>
                    <?php endif; ?>
                    <li><a href="../app/views/Auth/logout.php">Logout</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main class="container">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
