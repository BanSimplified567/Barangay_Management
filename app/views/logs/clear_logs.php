<?php
// app/views/logs/clear_logs.php
?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Clear System Logs</h1>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php echo $_SESSION['error'];
      unset($_SESSION['error']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="card shadow">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-danger">⚠️ Warning: Critical Action</h6>
    </div>
    <div class="card-body">
      <div class="alert alert-warning">
        <h5 class="alert-heading"><i class="bi bi-exclamation-triangle me-2"></i>Are you absolutely sure?</h5>
        <p>
          This action will <strong>permanently delete all system activity logs</strong>. This includes:
        </p>
        <ul>
          <li>All user login records</li>
          <li>All system actions and changes</li>
          <li>All audit trails</li>
          <li>All activity history</li>
        </ul>
        <p class="mb-0">
          <strong>This action cannot be undone.</strong> Once logs are cleared, they cannot be recovered.
        </p>
      </div>

      <div class="card mb-4">
        <div class="card-body">
          <h6><i class="bi bi-info-circle me-2"></i>Before proceeding, consider:</h6>
          <div class="row mt-3">
            <div class="col-md-6">
              <div class="d-flex align-items-center mb-3">
                <i class="bi bi-download text-primary fs-4 me-3"></i>
                <div>
                  <h6 class="mb-1">Export First</h6>
                  <p class="text-muted mb-0">Export logs to CSV before clearing for record keeping.</p>
                  <a href="index.php?action=logs&sub=export&format=csv" class="btn btn-sm btn-outline-primary mt-2">
                    <i class="bi bi-download me-1"></i>Export to CSV
                  </a>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex align-items-center mb-3">
                <i class="bi bi-shield-exclamation text-warning fs-4 me-3"></i>
                <div>
                  <h6 class="mb-1">Admin Only</h6>
                  <p class="text-muted mb-0">This action requires administrator privileges.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <form action="index.php?action=logs&sub=clear" method="POST" id="clearLogsForm">
        <div class="mb-4">
          <label for="confirmation" class="form-label">
            To confirm, type <code>DELETE</code> in the box below:
          </label>
          <input type="text" class="form-control form-control-lg"
            id="confirmation" name="confirmation"
            placeholder="Type DELETE to confirm"
            required
            style="font-family: monospace;">
          <div class="form-text">This is case-sensitive. You must type exactly: DELETE</div>
        </div>

        <div class="d-flex justify-content-between">
          <a href="index.php?action=logs" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Cancel and Go Back
          </a>
          <button type="submit" class="btn btn-danger" id="clearButton">
            <i class="bi bi-trash me-1"></i>Permanently Clear All Logs
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('clearLogsForm');
    const confirmationInput = document.getElementById('confirmation');
    const clearButton = document.getElementById('clearButton');

    // Disable button initially
    clearButton.disabled = true;

    // Enable button only when DELETE is typed
    confirmationInput.addEventListener('input', function() {
      clearButton.disabled = this.value !== 'DELETE';
    });

    // Double confirmation
    form.addEventListener('submit', function(e) {
      if (confirmationInput.value !== 'DELETE') {
        e.preventDefault();
        alert('You must type DELETE exactly to confirm.');
        return false;
      }

      if (!confirm('⚠️ FINAL WARNING: This will permanently delete ALL system logs. This action cannot be undone. Are you absolutely sure?')) {
        e.preventDefault();
        return false;
      }

      // Change button text to show processing
      clearButton.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Clearing Logs...';
      clearButton.disabled = true;
    });
  });
</script>
