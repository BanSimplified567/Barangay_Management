<?php
// app/views/blotters/add_blotter.php
// NO header/footer includes here - they're handled by BaseController
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?php echo $title ?? 'Add New Blotter'; ?></h1>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Blotter Information</h6>
                </div>
                <div class="card-body">
                    <form action="index.php?action=blotters&sub=create" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="complainant_id" class="form-label">Complainant <span class="text-danger">*</span></label>
                                <select class="form-select" id="complainant_id" name="complainant_id" required>
                                    <option value="">Select Complainant</option>
                                    <?php foreach ($residents as $resident): ?>
                                        <option value="<?php echo $resident['id']; ?>"
                                            <?php echo ($old['complainant_id'] ?? '') == $resident['id'] ? 'selected' : ''; ?>>
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
                                            <?php echo ($old['respondent_id'] ?? '') == $resident['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($resident['full_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Description of Incident <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($old['description'] ?? ''); ?></textarea>
                                <div class="form-text">Provide detailed description of the incident, including time, location, and what happened.</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="incident_date" class="form-label">Incident Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="incident_date" name="incident_date"
                                    value="<?php echo htmlspecialchars($old['incident_date'] ?? date('Y-m-d')); ?>" required>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Save Blotter
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
                    <h6 class="m-0 font-weight-bold text-primary">Quick Tips</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle me-2"></i>About Blotters</h6>
                        <p class="mb-2">Blotter records are official complaints filed in the barangay.</p>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="bi bi-exclamation-triangle me-2"></i>Important Notes</h6>
                        <ul class="mb-0">
                            <li>Always verify the identities of involved parties</li>
                            <li>Record incidents accurately and objectively</li>
                            <li>Maintain confidentiality of sensitive information</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
