<?php
session_start();
include '../config/dbcon.php';
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}

$stmt = $pdo->prepare("SELECT role FROM tbl_users WHERE email = ?");
$stmt->execute([$_SESSION['email']]);
$role = $stmt->fetchColumn();
if ($role !== 'admin' && $role !== 'staff') {
  header("Location: dashboard.php");
  exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM residents WHERE id = ?");
$stmt->execute([$id]);
$resident = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <title>Barangay Management System - Edit Resident</title>
    <style>
        body { background-color: #f8f9fa; font-family: Arial, sans-serif; }
        .sidebar { background-color: #003893; color: white; min-height: 100vh; padding: 20px; }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 10px; }
        .sidebar a:hover { background-color: #002a6e; }
        .main-content { padding: 20px; }
        .card-header { background-color: #003893; color: white; }
        .btn-primary { background-color: #003893; border: none; }
        .btn-primary:hover { background-color: #002a6e; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Mobile Navbar -->
            <nav class="navbar navbar-dark bg-dark d-md-none">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <span class="navbar-brand">Barangay Management</span>
                </div>
            </nav>

            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse" id="sidebar">
                <!-- Same sidebar code as above -->
                <!-- Omitted for brevity, copy from ../residents/residents.php -->
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 ms-sm-auto px-md-4 main-content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Edit Resident</h1>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="update_resident.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $resident['id']; ?>">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($resident['full_name']); ?>" required>
                            </div>
                            <!-- Similar fields for address, birthdate, etc., pre-filled with $resident values -->
                            <button type="submit" class="btn btn-primary">Update Resident</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
