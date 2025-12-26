<?php
// app/views/blotters/settle_blotter.php
include '../header.php';
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Mark Blotter as Settled</h1>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Settle Blotter #<?php echo $blotter['id']; ?></h6>
                </div>
                <div class="card-body">
                    <form action="index.php?action=blotters&sub=settle&id=<?php echo $blotter['id']; ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Complainant:</label>
                            <p class="form-control-plaintext"><?php echo htmlspecialchars($blotter['complainant_name'] ?? 'N/A'); ?></p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Respondent:</label>
                            <p class="form-control-plaintext"><?php echo htmlspecialchars($blotter['respondent_name'] ?? 'N/A'); ?></p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Incident Description:</label>
                            <p class="form-control-plaintext"><?php echo htmlspecialchars($blotter['description']); ?></p>
                        </div>

                        <div class="mb-3">
                            <label for="resolution" class="form-label">Resolution Details <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="resolution" name="resolution" rows="5" required
                                      placeholder="Enter the resolution details, settlement terms, or how the case was resolved..."></textarea>
                            <div class="form-text">Describe how this blotter case was resolved or settled.</div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-1"></i>Mark as Settled
                            </button>
                            <a href="index.php?action=blotters" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Blotter Details</h6>
                </div>
                <div class="card-body">
                    <p><strong>Blotter ID:</strong> <?php echo $blotter['id']; ?></p>
                    <p><strong>Incident Date:</strong> <?php echo date('M d, Y', strtotime($blotter['incident_date'])); ?></p>
                    <p><strong>Current Status:</strong>
                        <span class="badge bg-danger">Open</span>
                    </p>
                    <p><strong>Date Created:</strong> <?php echo date('M d, Y h:i A', strtotime($blotter['created_at'])); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
