<?php
// app/views/crime/edit_crime.php
$old = $_SESSION['old'] ?? $crime ?? [];
unset($_SESSION['old']);
?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Edit Crime Record</h1>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php echo $_SESSION['error'];
      unset($_SESSION['error']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="card shadow">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Edit Crime Information</h6>
    </div>
    <div class="card-body">
      <form action="index.php?action=crimes&sub=update&id=<?php echo $crime['id']; ?>" method="POST">
        <input type="hidden" name="id" value="<?php echo $crime['id']; ?>">

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="crime_type" class="form-label">Crime Type *</label>
            <input type="text" class="form-control" id="crime_type" name="crime_type"
              value="<?php echo htmlspecialchars($old['crime_type'] ?? ''); ?>" required>
          </div>

          <div class="col-md-6 mb-3">
            <label for="incident_date" class="form-label">Incident Date *</label>
            <input type="date" class="form-control" id="incident_date" name="incident_date"
              value="<?php echo htmlspecialchars($old['incident_date'] ?? ''); ?>" required>
          </div>
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">Description *</label>
          <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($old['description'] ?? ''); ?></textarea>
        </div>

        <div class="mb-3">
          <label for="location" class="form-label">Location</label>
          <input type="text" class="form-control" id="location" name="location"
            value="<?php echo htmlspecialchars($old['location'] ?? ''); ?>">
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="blotter_id" class="form-label">Related Blotter</label>
            <select class="form-control" id="blotter_id" name="blotter_id">
              <option value="">-- Select Blotter --</option>
              <?php foreach ($blotters as $blotter): ?>
                <option value="<?php echo $blotter['id']; ?>"
                  <?php echo ($old['blotter_id'] ?? '') == $blotter['id'] ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($blotter['description']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-md-6 mb-3">
            <label for="reported_by" class="form-label">Reported By</label>
            <select class="form-control" id="reported_by" name="reported_by">
              <option value="">-- Select Resident --</option>
              <?php foreach ($residents as $resident): ?>
                <option value="<?php echo $resident['id']; ?>"
                  <?php echo ($old['reported_by'] ?? '') == $resident['id'] ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($resident['full_name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="mb-3">
          <label for="status" class="form-label">Status *</label>
          <select class="form-control" id="status" name="status" required>
            <option value="reported" <?php echo ($old['status'] ?? '') == 'reported' ? 'selected' : ''; ?>>Reported</option>
            <option value="under_investigation" <?php echo ($old['status'] ?? '') == 'under_investigation' ? 'selected' : ''; ?>>Under Investigation</option>
            <option value="resolved" <?php echo ($old['status'] ?? '') == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
          </select>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i>Update Crime Record
          </button>
          <a href="index.php?action=crimes" class="btn btn-secondary">
            <i class="bi bi-x-circle me-1"></i>Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
