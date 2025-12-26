<?php
// app/views/crimes.php
include '../header.php';
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Crime Records</h1>

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
                                Reported
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                    $reported = array_filter($crimes, function($c) {
                                        return $c['status'] === 'reported';
                                    });
                                    echo count($reported);
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-flag fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Under Investigation
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                    $investigating = array_filter($crimes, function($c) {
                                        return $c['status'] === 'under_investigation';
                                    });
                                    echo count($investigating);
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-search fs-2 text-gray-300"></i>
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
                                Resolved
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                    $resolved = array_filter($crimes, function($c) {
                                        return $c['status'] === 'resolved';
                                    });
                                    echo count($resolved);
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
    </div>

    <!-- Report Crime Button -->
    <div class="mb-3">
        <a href="index.php?action=crimes&sub=add" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Report New Crime
        </a>
    </div>

    <!-- Crimes Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Crime Records</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="crimesTable">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Location</th>
                            <th>Incident Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($crimes)): ?>
                            <?php foreach ($crimes as $crime): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($crime['id']); ?></td>
                                    <td>
                                        <span class="badge bg-danger">
                                            <?php echo htmlspecialchars($crime['crime_type']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;"
                                             title="<?php echo htmlspecialchars($crime['description']); ?>">
                                            <?php echo htmlspecialchars($crime['description']); ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($crime['location'] ?? 'N/A'); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($crime['incident_date'])); ?></td>
                                    <td>
                                        <?php
                                            $statusClass = [
                                                'reported' => 'primary',
                                                'under_investigation' => 'warning',
                                                'resolved' => 'success'
                                            ][$crime['status']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $statusClass; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', htmlspecialchars($crime['status']))); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="index.php?action=crimes&sub=edit&id=<?php echo $crime['id']; ?>"
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <?php if ($crime['status'] === 'reported'): ?>
                                            <a href="index.php?action=crimes&sub=investigate&id=<?php echo $crime['id']; ?>"
                                               class="btn btn-sm btn-info"
                                               onclick="return confirm('Mark this crime as under investigation?')"
                                               title="Start Investigation">
                                                <i class="bi bi-search"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($crime['status'] === 'under_investigation'): ?>
                                            <button type="button" class="btn btn-sm btn-success"
                                                    data-bs-toggle="modal" data-bs-target="#resolveModal<?php echo $crime['id']; ?>"
                                                    title="Mark as Resolved">
                                                <i class="bi bi-check-circle"></i>
                                            </button>

                                            <!-- Resolve Modal -->
                                            <div class="modal fade" id="resolveModal<?php echo $crime['id']; ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Mark as Resolved</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="index.php?action=crimes&sub=resolve&id=<?php echo $crime['id']; ?>" method="POST">
                                                            <div class="modal-body">
                                                                <p>Are you sure you want to mark this crime as resolved?</p>
                                                                <div class="mb-3">
                                                                    <label for="resolution<?php echo $crime['id']; ?>" class="form-label">Resolution Notes (optional):</label>
                                                                    <textarea class="form-control" id="resolution<?php echo $crime['id']; ?>" name="resolution" rows="3"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-success">Mark as Resolved</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <a href="index.php?action=crimes&sub=delete&id=<?php echo $crime['id']; ?>"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this crime record?')"
                                           title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-shield-exclamation display-6"></i>
                                        <p class="mt-2">No crime records found.</p>
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
            $('#crimesTable').DataTable({
                "order": [[4, "desc"]], // Sort by incident date descending
                "pageLength": 25,
                "language": {
                    "search": "Search crimes:",
                    "lengthMenu": "Show _MENU_ crimes per page",
                    "zeroRecords": "No crimes found",
                    "info": "Showing _START_ to _END_ of _TOTAL_ crimes",
                    "infoEmpty": "No crimes available",
                    "infoFiltered": "(filtered from _MAX_ total crimes)"
                }
            });
        }
    });
</script>

<?php include '../footer.php'; ?>
