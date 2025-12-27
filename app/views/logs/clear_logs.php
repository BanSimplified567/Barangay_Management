<?php
// app/views/logs/clear_logs.php
include '../layout/header.php';
?>

<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800 text-danger">Clear System Logs</h1>

  <div class="row">
    <div class="col-md-8 offset-md-2">
      <div class="card shadow border-danger">
        <div class="card-header py-3 bg-danger text-white">
          <h6 class="m-0 font-weight-bold">
            <i class="bi bi-exclamation-triangle me-2"></i>Warning: Irreversible Action
          </h6>
        </div>
        <div class="card-body">
          <div class="alert alert-warning">
            <h5><i class="bi bi-exclamation-octagon me-2"></i>Attention!</h5>
            <p>You are about to permanently delete all system activity logs. This action cannot be undone.</p>
            <ul class="mb-0">
              <li>All audit trails will be lost</li>
              <li>System activity history will be erased</li>
              <li>This may affect compliance requirements</li>
              <li>Consider exporting logs before clearing</li>
            </ul>
          </div>

          <div class="mb-4">
            <h6>Current Log Statistics:</h6>
            <div class="row">
              <div class="col-md-6">
                <div class="card mb-3">
                  <div class="card-body">
                    <h6 class="card-title">Total Logs</h6>
                    <p class="card-text display-6">
                      <?php
                      try {
                        $stmt = $pdo->query("SELECT COUNT(*) as count FROM tbl_logs");
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        echo number_format($result['count'] ?? 0);
                      } catch (PDOException $e) {
                        echo 'N/A';
                      }
                      ?>
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card mb-3">
                  <div class="card-body">
                    <h6 class="card-title">Oldest Log</h6>
                    <p class="card-text">
                      <?php
                      try {
                        $stmt = $pdo->query("SELECT MIN(timestamp) as oldest FROM tbl_logs");
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        echo $result['oldest'] ? date('M d, Y', strtotime($result['oldest'])) : 'N/A';
                      } catch (PDOException $e) {
                        echo 'N/A';
                      }
                      ?>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <form action="index.php?action=logs&sub=clear" method="POST">
            <div class="mb-3">
              <label for="confirmation" class="form-label">
                Type <strong>DELETE</strong> to confirm:
              </label>
              <input type="text" class="form-control" id="confirmation" name="confirmation"
                placeholder="Type DELETE here" required>
              <div class="form-text">This is case-sensitive and must match exactly.</div>
            </div>

            <div class="mt-4">
              <button type="submit" class="btn btn-danger" id="confirmBtn" disabled>
                <i class="bi bi-trash me-1"></i>Permanently Delete All Logs
              </button>
              <a href="index.php?action=logs" class="btn btn-secondary">
                <i class="bi bi-x-circle me-1"></i>Cancel
              </a>
            </div>
          </form>
        </div>
      </div>

      <div class="card shadow mt-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Recommended Alternatives</h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="d-grid gap-2">
                <a href="index.php?action=logs&sub=export&format=csv" class="btn btn-success">
                  <i class="bi bi-download me-1"></i>Export Logs to CSV
                </a>
                <small class="text-muted">Download all logs before clearing</small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-grid gap-2">
                <a href="index.php?action=logs" class="btn btn-primary">
                  <i class="bi bi-arrow-left me-1"></i>Return to Logs View
                </a>
                <small class="text-muted">View and filter logs instead</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const confirmationField = document.querySelector('#confirmation');
    const confirmBtn = document.querySelector('#confirmBtn');

    if (confirmationField && confirmBtn) {
      confirmationField.addEventListener('input', function() {
        confirmBtn.disabled = this.value !== 'DELETE';
      });

      // Auto-focus on confirmation field
      confirmationField.focus();
    }

    // Prevent accidental navigation
    window.addEventListener('beforeunload', function(e) {
      if (confirmationField && confirmationField.value === 'DELETE') {
        e.preventDefault();
        e.returnValue = 'You have entered DELETE. Are you sure you want to leave?';
        return e.returnValue;
      }
    });
  });
</script>

<?php include '../layout/footer.php'; ?>
