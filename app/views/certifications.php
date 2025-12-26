<?php
// app/views/certifications.php
include 'header.php';
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Certifications Management</h1>

    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Pending
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                    $pending = array_filter($certifications, function($c) {
                                        return $c['status'] === 'pending';
                                    });
                                    echo count($pending);
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clock fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Issued
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                    $issued = array_filter($certifications, function($c) {
                                        return $c['status'] === 'issued';
                                    });
                                    echo count($issued);
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Rejected
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                    $rejected = array_filter($certifications, function($c) {
                                        return $c['status'] === 'rejected';
                                    });
                                    echo count($rejected);
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-x-circle fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Certifications Table -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Certifications</h6>
            <?php if ($_SESSION['role'] === 'resident'): ?>
                <a href="index.php?action=certifications&sub=request" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>Request New Certificate
                </a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="certificationsTable">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Resident</th>
                            <th>Type</th>
                            <th>Purpose</th>
                            <th>Request Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($certifications)): ?>
                            <?php foreach ($certifications as $cert): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($cert['id']); ?></td>
                                    <td><?php echo htmlspecialchars($cert['resident_name']); ?></td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            <?php echo ucfirst(htmlspecialchars($cert['type'])); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($cert['purpose'] ?? 'N/A'); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($cert['issue_date'])); ?></td>
                                    <td>
                                        <?php
                                            $statusClass = [
                                                'pending' => 'warning',
                                                'issued' => 'success',
                                                'rejected' => 'danger'
                                            ][$cert['status']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $statusClass; ?>">
                                            <?php echo ucfirst(htmlspecialchars($cert['status'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="index.php?action=certifications&sub=view&id=<?php echo $cert['id']; ?>"
                                           class="btn btn-sm btn-info" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staff'): ?>
                                            <?php if ($cert['status'] === 'pending'): ?>
                                                <a href="index.php?action=certifications&sub=approve&id=<?php echo $cert['id']; ?>"
                                                   class="btn btn-sm btn-success"
                                                   onclick="return confirm('Approve this certificate request?')"
                                                   title="Approve">
                                                    <i class="bi bi-check-circle"></i>
                                                </a>

                                                <button type="button" class="btn btn-sm btn-danger"
                                                        data-bs-toggle="modal" data-bs-target="#rejectModal<?php echo $cert['id']; ?>"
                                                        title="Reject">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>

                                                <!-- Reject Modal -->
                                                <div class="modal fade" id="rejectModal<?php echo $cert['id']; ?>" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Reject Certificate</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <form action="index.php?action=certifications&sub=reject&id=<?php echo $cert['id']; ?>" method="POST">
                                                                <div class="modal-body">
                                                                    <p>Are you sure you want to reject this certificate request?</p>
                                                                    <div class="mb-3">
                                                                        <label for="reason<?php echo $cert['id']; ?>" class="form-label">Reason (optional):</label>
                                                                        <textarea class="form-control" id="reason<?php echo $cert['id']; ?>" name="reason" rows="3"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-danger">Reject Certificate</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-file-earmark-text display-6"></i>
                                        <p class="mt-2">No certification requests found.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTables if available
        if (typeof $ !== 'undefined' && $.fn.DataTable) {
            $('#certificationsTable').DataTable({
                "order": [[0, "desc"]], // Sort by ID descending (newest first)
                "pageLength": 25,
                "language": {
                    "search": "Search certifications:",
                    "lengthMenu": "Show _MENU_ certifications per page",
                    "zeroRecords": "No certifications found",
                    "info": "Showing _START_ to _END_ of _TOTAL_ certifications",
                    "infoEmpty": "No certifications available",
                    "infoFiltered": "(filtered from _MAX_ total certifications)"
                }
            });
        }
    });
</script>

<?php include 'footer.php'; ?>
