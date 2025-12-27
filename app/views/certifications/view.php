<?php
// app/views/certifications/view.php
// NO header/footer includes here - they're handled by BaseController
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?php echo $title ?? 'View Certification'; ?></h1>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Certificate Details</h6>
                    <div>
                        <span class="badge bg-<?php
                            $statusClass = [
                                'pending' => 'warning',
                                'issued' => 'success',
                                'rejected' => 'danger'
                            ][$certification['status']] ?? 'secondary';
                            echo $statusClass;
                        ?>">
                            <?php echo ucfirst($certification['status']); ?>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Certificate ID:</strong> <?php echo htmlspecialchars($certification['id']); ?></p>
                            <p><strong>Type:</strong>
                                <span class="badge bg-info text-dark">
                                    <?php echo ucfirst(htmlspecialchars($certification['type'])); ?>
                                </span>
                            </p>
                            <p><strong>Request Date:</strong> <?php echo date('F d, Y', strtotime($certification['issue_date'])); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Resident Name:</strong> <?php echo htmlspecialchars($certification['resident_name']); ?></p>
                            <p><strong>Address:</strong> <?php echo htmlspecialchars($certification['address']); ?></p>
                            <p><strong>Birthdate:</strong> <?php echo date('F d, Y', strtotime($certification['birthdate'])); ?></p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="font-weight-bold">Purpose:</h6>
                        <p class="border p-3 rounded"><?php echo nl2br(htmlspecialchars($certification['purpose'])); ?></p>
                    </div>

                    <?php if ($certification['status'] === 'issued'): ?>
                        <div class="alert alert-success">
                            <h6><i class="bi bi-check-circle me-2"></i>Issued Information</h6>
                            <p class="mb-1"><strong>Issued By:</strong> <?php echo htmlspecialchars($certification['issued_by_name'] ?? 'N/A'); ?></p>
                            <p class="mb-0"><strong>Issue Date:</strong> <?php echo date('F d, Y', strtotime($certification['issue_date'])); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if ($certification['status'] === 'rejected'): ?>
                        <div class="alert alert-danger">
                            <h6><i class="bi bi-x-circle me-2"></i>Rejected</h6>
                            <p class="mb-0">This certificate request has been rejected.</p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer">
                    <a href="index.php?action=certifications" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to List
                    </a>

                    <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staff'): ?>
                        <?php if ($certification['status'] === 'pending'): ?>
                            <a href="index.php?action=certifications&sub=approve&id=<?php echo $certification['id']; ?>"
                               class="btn btn-success float-end ms-2"
                               onclick="return confirm('Approve this certificate request?')">
                                <i class="bi bi-check-circle me-1"></i>Approve
                            </a>

                            <button type="button" class="btn btn-danger float-end"
                                    data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bi bi-x-circle me-1"></i>Reject
                            </button>

                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Certificate</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="index.php?action=certifications&sub=reject&id=<?php echo $certification['id']; ?>" method="POST">
                                            <div class="modal-body">
                                                <p>Are you sure you want to reject this certificate request?</p>
                                                <div class="mb-3">
                                                    <label for="reason" class="form-label">Reason (optional):</label>
                                                    <textarea class="form-control" id="reason" name="reason" rows="3"></textarea>
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
                </div>
            </div>
        </div>
    </div>
</div>
