<?php
// app/views/certifications/request.php
// NO header/footer includes here - they're handled by BaseController
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?php echo $title ?? 'Request Certification'; ?></h1>

    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Certificate Request Form</h6>
                </div>
                <div class="card-body">
                    <form action="index.php?action=certifications&sub=create" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Certificate Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">Select Certificate Type</option>
                                    <option value="clearance" <?php echo ($old['type'] ?? '') == 'clearance' ? 'selected' : ''; ?>>Barangay Clearance</option>
                                    <option value="indigency" <?php echo ($old['type'] ?? '') == 'indigency' ? 'selected' : ''; ?>>Certificate of Indigency</option>
                                    <option value="residency" <?php echo ($old['type'] ?? '') == 'residency' ? 'selected' : ''; ?>>Certificate of Residency</option>
                                    <option value="other" <?php echo ($old['type'] ?? '') == 'other' ? 'selected' : ''; ?>>Other Certificate</option>
                                </select>
                                <div class="form-text">Select the type of certificate you need</div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="purpose" class="form-label">Purpose <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="purpose" name="purpose" rows="4" required
                                          placeholder="Please state the purpose for requesting this certificate..."><?php echo htmlspecialchars($old['purpose'] ?? ''); ?></textarea>
                                <div class="form-text">Clearly state why you need this certificate (e.g., for employment, scholarship, etc.)</div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-1"></i>Submit Request
                            </button>
                            <a href="index.php?action=certifications" class="btn btn-secondary">
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
                    <h6 class="m-0 font-weight-bold text-primary">Request Information</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle me-2"></i>Processing Time</h6>
                        <p class="mb-2">Certificates are typically processed within 3-5 working days.</p>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="bi bi-exclamation-triangle me-2"></i>Requirements</h6>
                        <ul class="mb-0">
                            <li>Valid ID may be required for verification</li>
                            <li>Be prepared to provide additional documents if needed</li>
                            <li>Requests are subject to approval</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
