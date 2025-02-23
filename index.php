<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style/style.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">


    <title>Barangay Management System</title>
  </head>


  <body>
    <div class="container-fluid">
      <div class="row">
        <!-- Navbar for mobile -->
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
          <div class="position-sticky pt-3">
            <div class="text-center mb-4">
              <h5 class="text-white">Barangay Management</h5>
            </div>
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link active" href="#">
                  <i class="bi bi-speedometer2 me-2"></i>
                  Dashboard
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="residents.php">
                  <i class="bi bi-people me-2"></i>
                  Residents
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="certificates.php">
                  <i class="bi bi-file-text me-2"></i>
                  Certificates
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="projects.php">
                  <i class="bi bi-calendar-event me-2"></i>
                  Events
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="settings.php">
                  <i class="bi bi-gear me-2"></i>
                  Settings
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="login.php">
                  <i class="bi bi-box-arrow-right me-2"></i>
                  Logout
                </a>
              </li>
            </ul>
          </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 ms-sm-auto px-md-4 main-content">
          <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Dashboard</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
              </div>
            </div>
          </div>

          <!-- Dashboard Cards -->
          <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2 dashboard-card bg-primary text-white">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Total Residents</div>
                      <div class="h5 mb-0 font-weight-bold">2,500</div>
                    </div>
                    <div class="col-auto">
                      <i class="bi bi-people-fill fa-2x"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2 dashboard-card bg-success text-white">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Certificates Issued</div>
                      <div class="h5 mb-0 font-weight-bold">150</div>
                    </div>
                    <div class="col-auto">
                      <i class="bi bi-file-earmark-text-fill fa-2x"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2 dashboard-card bg-info text-white">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Ongoing Projects</div>
                      <div class="h5 mb-0 font-weight-bold">5</div>
                    </div>
                    <div class="col-auto">
                      <i class="bi bi-clipboard2-data-fill fa-2x"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2 dashboard-card bg-warning text-white">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Upcoming Events</div>
                      <div class="h5 mb-0 font-weight-bold">3</div>
                    </div>
                    <div class="col-auto">
                      <i class="bi bi-calendar-event-fill fa-2x"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Recent Activity Section -->
          <div class="row">
            <!-- Announcements -->
            <div class="col-lg-6 mb-4">
              <div class="card shadow">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Announcements</h6>
                </div>
                <div class="card-body">
                  <div class="list-group">
                    <div class="list-group-item">
                      <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">Community Meeting</h6>
                        <small class="text-muted">Tomorrow</small>
                      </div>
                      <p class="mb-1">Monthly community meeting at the Barangay Hall</p>
                    </div>
                    <div class="list-group-item">
                      <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">Vaccination Drive</h6>
                        <small class="text-muted">Next Week</small>
                      </div>
                      <p class="mb-1">Free vaccines for children under 5 years old</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>


            <!-- Quick Links -->
            <div class="col-lg-6 mb-4">
              <div class="card shadow">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Quick Links</h6>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-6 mb-3">
                      <a href="certificates.php" class="btn btn-primary btn-block w-100">
                        <i class="bi bi-file-text me-2"></i>Request Certificate
                      </a>
                    </div>
                    <div class="col-6 mb-3">
                      <a href="residents.php" class="btn btn-success btn-block w-100">
                        <i class="bi bi-person-plus me-2"></i>Register Resident
                      </a>
                    </div>
                    <div class="col-6">
                      <a href="projects.php" class="btn btn-info btn-block w-100">
                        <i class="bi bi-calendar-check me-2"></i>Schedule Meeting
                      </a>
                    </div>
                    <div class="col-6">
                      <a href="incidents.php" class="btn btn-warning btn-block w-100">
                        <i class="bi bi-exclamation-triangle me-2"></i>Report Incident
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row mt-4">
            <div class="col-12">
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Recent Activities</h6>
                </div>
                <div class="card-body">
                  <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action">
                      <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">New Resident Registration</h6>
                        <small>3 days ago</small>
                      </div>
                      <p class="mb-1">New resident registered in Purok 1</p>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                      <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">Certificate Issued</h6>



  </body>

</html>
