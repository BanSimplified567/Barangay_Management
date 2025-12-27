<?php
// app/views/blotters/edit_blotter.php
// NO header/footer includes here - they're handled by BaseController
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?php echo $title ?? 'Edit Blotter'; ?></h1>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Blotter Information</h6>
                </div>
                <div class="card-body">
                    <form action="index.php?action=blotters&sub=update" method="POST">
                        <input type="hidden" name="id" value="<?php echo $blotter['id']; ?>">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="complainant_id" class="form-label">Complainant <span class="text-danger">*</span></label>
                                <select class="form-select" id="complainant_id" name="complainant_id" required>
                                    <option value="">Select Complainant</option>
                                    <?php foreach ($residents as $resident): ?>
                                        <option value="<?php echo $resident['id']; ?>"
                                            <?php echo ($old['complainant_id'] ?? $blotter['complainant_id']) == $resident['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($resident['full_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="respondent_id" class="form-label">Respondent (Optional)</label>
                                <select class="form-select" id="respondent_id" name="respondent_id">
                                    <option value="">Select Respondent (Optional)</option>
                                    <?php foreach ($residents as $resident): ?>
                                        <option value="<?php echo $resident['id']; ?>"
                                            <?php echo ($old['respondent_id'] ?? $blotter['respondent_id']) == $resident['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($resident['full_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Description of Incident <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($old['description'] ?? $blotter['description']); ?></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="incident_date" class="form-label">Incident Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="incident_date" name="incident_date"
                                    value="<?php echo htmlspecialchars($old['incident_date'] ?? $blotter['incident_date']); ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="open" <?php echo ($old['status'] ?? $blotter['status']) == 'open' ? 'selected' : ''; ?>>Open</option>
                                    <option value="settled" <?php echo ($old['status'] ?? $blotter['status']) == 'settled' ? 'selected' : ''; ?>>Settled</option>
                                    <option value="escalated" <?php echo ($old['status'] ?? $blotter['status']) == 'escalated' ? 'selected' : ''; ?>>Escalated</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="resolution" class="form-label">Resolution Details</label>
                                <textarea class="form-control" id="resolution" name="resolution" rows="3"><?php echo htmlspecialchars($old['resolution'] ?? $blotter['resolution'] ?? ''); ?></textarea>
                                <div class="form-text">Details of how the case was resolved (if settled or escalated)</div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Update Blotter
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
                    <p><strong>Current Status:</strong>
                        <span class="badge bg-<?php
                            echo $blotter['status'] === 'open' ? 'danger' :
                                   ($blotter['status'] === 'settled' ? 'success' : 'warning');
                        ?>">
                            <?php echo ucfirst($blotter['status']); ?>
                        </span>
                    </p>
                    <p><strong>Date Created:</strong> <?php echo date('M d, Y h:i A', strtotime($blotter['created_at'])); ?></p>
                    <?php if ($blotter['updated_at']): ?>
                        <p><strong>Last Updated:</strong> <?php echo date('M d, Y h:i A', strtotime($blotter['updated_at'])); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
