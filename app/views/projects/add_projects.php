<?php
// app/views/projects/add_project.php
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Add New Project</h1>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php echo $_SESSION['error'];
      unset($_SESSION['error']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="card shadow">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Project Details</h6>
    </div>
    <div class="card-body">
      <form action="index.php?action=projects&sub=store" method="POST">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Project Name *</label>
            <input type="text" class="form-control" id="name" name="name"
              value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>" required>
            <div class="form-text">Enter a descriptive project name</div>
          </div>

          <div class="col-md-6 mb-3">
            <label for="budget" class="form-label">Budget (₱) *</label>
            <input type="number" step="0.01" class="form-control" id="budget" name="budget"
              value="<?php echo htmlspecialchars($old['budget'] ?? '0'); ?>" required>
            <div class="form-text">Total project budget in Philippine Pesos</div>
          </div>
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">Description *</label>
          <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($old['description'] ?? ''); ?></textarea>
          <div class="form-text">Detailed description of the project</div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="start_date" class="form-label">Start Date *</label>
            <input type="date" class="form-control" id="start_date" name="start_date"
              value="<?php echo htmlspecialchars($old['start_date'] ?? date('Y-m-d')); ?>" required>
          </div>

          <div class="col-md-6 mb-3">
            <label for="end_date" class="form-label">End Date (Optional)</label>
            <input type="date" class="form-control" id="end_date" name="end_date"
              value="<?php echo htmlspecialchars($old['end_date'] ?? ''); ?>">
            <div class="form-text">Leave empty for ongoing projects</div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" class="form-control" id="location" name="location"
              value="<?php echo htmlspecialchars($old['location'] ?? ''); ?>">
            <div class="form-text">Where the project will be implemented</div>
          </div>

          <div class="col-md-6 mb-3">
            <label for="project_lead" class="form-label">Project Lead</label>
            <input type="text" class="form-control" id="project_lead" name="project_lead"
              value="<?php echo htmlspecialchars($old['project_lead'] ?? ''); ?>">
            <div class="form-text">Name of person in charge</div>
          </div>
        </div>

        <div class="mb-3">
          <label for="funding_source" class="form-label">Funding Source</label>
          <input type="text" class="form-control" id="funding_source" name="funding_source"
            value="<?php echo htmlspecialchars($old['funding_source'] ?? ''); ?>">
          <div class="form-text">e.g., Barangay Funds, DOH, DPWH, etc.</div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i>Save Project
          </button>
          <a href="index.php?action=projects" class="btn btn-secondary">
            <i class="bi bi-x-circle me-1"></i>Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Validate date range
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');

    document.querySelector('form').addEventListener('submit', function(e) {
      if (endDate.value && new Date(endDate.value) < new Date(startDate.value)) {
        e.preventDefault();
        alert('End date cannot be before start date.');
        return false;
      }

      const budget = document.getElementById('budget').value;
      if (budget < 0) {
        e.preventDefault();
        alert('Budget cannot be negative.');
        return false;
      }
    });
  });
</script>
