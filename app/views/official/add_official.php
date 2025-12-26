<?php
// app/views/officials/add_official.php
include '../header.php';
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Add New Official</h1>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Official Information</h6>
                </div>
                <div class="card-body">
                    <form action="index.php?action=officials&sub=create" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="resident_id" class="form-label">Select Resident <span class="text-danger">*</span></label>
                                <select class="form-select" id="resident_id" name="resident_id" required>
                                    <option value="">Choose a resident...</option>
                                    <?php if (!empty($residents)): ?>
                                        <?php foreach ($residents as $resident): ?>
                                            <option value="<?php echo $resident['id']; ?>"
                                                <?php echo ($old['resident_id'] ?? '') == $resident['id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($resident['full_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>No available residents (all are already officials)</option>
                                    <?php endif; ?>
                                </select>
                                <div class="form-text">Only residents not currently holding positions are listed.</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="position" class="form-label">Position <span class="text-danger">*</span></label>
                                <select class="form-select" id="position" name="position" required>
                                    <option value="">Select Position</option>
                                    <option value="Barangay Captain" <?php echo ($old['position'] ?? '') == 'Barangay Captain' ? 'selected' : ''; ?>>Barangay Captain</option>
                                    <option value="Barangay Secretary" <?php echo ($old['position'] ?? '') == 'Barangay Secretary' ? 'selected' : ''; ?>>Barangay Secretary</option>
                                    <option value="Barangay Treasurer" <?php echo ($old['position'] ?? '') == 'Barangay Treasurer' ? 'selected' : ''; ?>>Barangay Treasurer</option>
                                    <option value="Barangay Councilor" <?php echo ($old['position'] ?? '') == 'Barangay Councilor' ? 'selected' : ''; ?>>Barangay Councilor</option>
                                    <option value="SK Chairman" <?php echo ($old['position'] ?? '') == 'SK Chairman' ? 'selected' : ''; ?>>SK Chairman</option>
                                    <option value="Committee Chairman" <?php echo ($old['position'] ?? '') == 'Committee Chairman' ? 'selected' : ''; ?>>Committee Chairman</option>
                                    <option value="Barangay Tanod" <?php echo ($old['position'] ?? '') == 'Barangay Tanod' ? 'selected' : ''; ?>>Barangay Tanod</option>
                                    <option value="Other" <?php echo ($old['position'] ?? '') == 'Other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="term_start" class="form-label">Term Start <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="term_start" name="term_start"
                                       value="<?php echo htmlspecialchars($old['term_start'] ?? date('Y-m-d')); ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="term_end" class="form-label">Term End (Optional)</label>
                                <input type="date" class="form-control" id="term_end" name="term_end"
                                       value="<?php echo htmlspecialchars($old['term_end'] ?? ''); ?>">
                                <div class="form-text">Leave empty for indefinite term.</div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Add Official
                            </button>
                            <a href="index.php?action=officials" class="btn btn-secondary">
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
                    <h6 class="m-0 font-weight-bold text-primary">Guidelines</h6>
                </div>
                <div class="card-body">
                    <ul>
                        <li>Officials must be registered residents</li>
                        <li>Each resident can hold only one position</li>
                        <li>Set appropriate term dates</li>
                        <li>Indefinite terms can be left without end date</li>
                        <li>Update positions as needed</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
