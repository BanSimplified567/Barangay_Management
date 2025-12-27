<?php
// app/views/official/edit_official.php
$old = $_SESSION['old'] ?? $official ?? [];
unset($_SESSION['old']);
?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Edit Official</h1>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php echo $_SESSION['error'];
      unset($_SESSION['error']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="card shadow">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Edit Official Information</h6>
    </div>
    <div class="card-body">
      <form action="index.php?action=officials&sub=update&id=<?php echo $official['id']; ?>" method="POST">
        <div class="row">
          <div class="col-md-12 mb-3">
            <label class="form-label">Resident</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($official['official_name'] ?? ''); ?>" readonly>
            <div class="form-text">Resident cannot be changed after assignment</div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="position" class="form-label">Position *</label>
            <select class="form-control" id="position" name="position" required>
              <option value="">-- Select Position --</option>
              <option value="Barangay Captain" <?php echo ($old['position'] ?? '') == 'Barangay Captain' ? 'selected' : ''; ?>>Barangay Captain</option>
              <option value="Barangay Secretary" <?php echo ($old['position'] ?? '') == 'Barangay Secretary' ? 'selected' : ''; ?>>Barangay Secretary</option>
              <option value="Barangay Treasurer" <?php echo ($old['position'] ?? '') == 'Barangay Treasurer' ? 'selected' : ''; ?>>Barangay Treasurer</option>
              <option value="Barangay Councilor" <?php echo ($old['position'] ?? '') == 'Barangay Councilor' ? 'selected' : ''; ?>>Barangay Councilor</option>
            </select>
          </div>

          <div class="col-md-6 mb-3">
            <label for="term_start" class="form-label">Term Start *</label>
            <input type="date" class="form-control" id="term_start" name="term_start"
              value="<?php echo htmlspecialchars($old['term_start'] ?? ''); ?>" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="term_end" class="form-label">Term End (Optional)</label>
            <input type="date" class="form-control" id="term_end" name="term_end"
              value="<?php echo htmlspecialchars($old['term_end'] ?? ''); ?>">
            <div class="form-text">Leave empty for ongoing term</div>
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i>Update Official
          </button>
          <a href="index.php?action=officials" class="btn btn-secondary">
            <i class="bi bi-x-circle me-1"></i>Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Validate term dates
    document.querySelector('form').addEventListener('submit', function(e) {
      const termStart = document.getElementById('term_start').value;
      const termEnd = document.getElementById('term_end').value;

      if (termEnd && new Date(termEnd) < new Date(termStart)) {
        e.preventDefault();
        alert('Term end date cannot be before term start date.');
        return false;
      }
    });
  });
</script>
