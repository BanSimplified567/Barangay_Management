<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Certificates</title>
    <link rel="stylesheet" href="style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>


    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h5 class="text-white">Barangay Management</h5>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">
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
                            <a class="nav-link active" href="certificates.php">
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
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Barangay Certificates</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newCertificateModal">
                        <i class="bi bi-plus-circle me-2"></i>New Certificate
                    </button>
                </div>

                <!-- Certificate Types -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title">Barangay Clearance</h5>
                                <p class="card-text">Issue barangay clearance certificates for residents.</p>
                                <button class="btn btn-outline-primary">Generate Certificate</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title">Certificate of Residency</h5>
                                <p class="card-text">Issue proof of residency certificates.</p>
                                <button class="btn btn-outline-primary">Generate Certificate</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title">Business Permit</h5>
                                <p class="card-text">Issue business permits for local establishments.</p>
                                <button class="btn btn-outline-primary">Generate Certificate</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Certificates Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Recently Issued Certificates</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Certificate ID</th>
                                        <th>Type</th>
                                        <th>Resident Name</th>
                                        <th>Issue Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>CERT-001</td>
                                        <td>Barangay Clearance</td>
                                        <td>John Doe</td>
                                        <td>2023-12-01</td>
                                        <td><span class="badge bg-success">Issued</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-info"><i class="bi bi-printer"></i></button>
                                            <button class="btn btn-sm btn-secondary"><i class="bi bi-eye"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>CERT-002</td>
                                        <td>Certificate of Residency</td>
                                        <td>Jane Smith</td>
                                        <td>2023-12-02</td>
                                        <td><span class="badge bg-warning">Pending</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-info"><i class="bi bi-printer"></i></button>
                                            <button class="btn btn-sm btn-secondary"><i class="bi bi-eye"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Certificate Modal -->
    <div class="modal fade" id="newCertificateModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Generate New Certificate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Certificate Type</label>
                                <select class="form-select" required>
                                    <option value="">Select Certificate Type</option>
                                    <option>Barangay Clearance</option>
                                    <option>Certificate of Residency</option>
                                    <option>Business Permit</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Resident Name</label>
                                <select class="form-select" required>
                                    <option value="">Select Resident</option>
                                    <option>John Doe</option>
                                    <option>Jane Smith</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Purpose</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">OR Number</label>
                                <input type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Additional Remarks</label>
                            <textarea class="form-control" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Generate Certificate</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
