<?php
// app/views/crimes/add_crime.php
include '../header.php';
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Report New Crime</h1>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Crime Details</h6>
                </div>
                <div class="card-body">
                    <form action="index.php?action=crimes&sub=create" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="crime_type" class="form-label">Crime Type <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="crime_type" name="crime_type"
                                       value="<?php echo htmlspecialchars($old['crime_type'] ?? ''); ?>" required
                                       placeholder="e.g., Theft, Assault, Vandalism">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="incident_date" class="form-label">Incident Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="incident_date" name="incident_date"
                                       value="<?php echo htmlspecialchars($old['incident_date'] ?? date('Y-m-d')); ?>" required>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($old['description'] ?? ''); ?></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="location" name="location"
                                       value="<?php echo htmlspecialchars($old['location'] ?? ''); ?>"
                                       placeholder="e.g., Street name, Barangay area">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="blotter_id" class="form-label">Related Blotter (Optional)</label>
                                <select class="form-select" id="blotter_id" name="blotter_id">
                                    <option value="">Select related blotter</option>
                                    <?php if (!empty($blotters)): ?>
                                        <?php foreach ($blotters as $blotter): ?>
                                            <option value="<?php echo $blotter['id']; ?>"
                                                <?php echo ($old['blotter_id'] ?? '') == $blotter['id'] ? 'selected' : ''; ?>>
                                                Blotter #<?php echo $blotter['id']; ?>: <?php echo htmlspecialchars(substr($blotter['description'], 0, 50)); ?>...
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Report Crime
                            </button>
                            <a href="index.php?action=crimes" class="btn btn-secondary">
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
                    <h6 class="m-0 font-weight-bold text-primary">Reporting Guidelines</h6>
                </div>
                <div class="card-body">
                    <ul>
                        <li>Provide accurate and detailed information</li>
                        <li>Include specific dates and times</li>
                        <li>Mention exact locations if known</li>
                        <li>Link to related blotter records if applicable</li>
                        <li>All reports are confidential</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
