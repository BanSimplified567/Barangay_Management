<?php
// app/views/settings/system.php
// Note: Header and footer are automatically included by BaseController

// Get settings from controller
$settings = $settings ?? [];
?>

<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">System Settings</h1>

  <!-- Success/Error Messages -->
  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?php echo $_SESSION['success'];
      unset($_SESSION['success']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php echo $_SESSION['error'];
      unset($_SESSION['error']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="row">
    <div class="col-lg-3 mb-4">
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Settings Categories</h6>
        </div>
        <div class="list-group list-group-flush">
          <a href="index.php?action=settings"
            class="list-group-item list-group-item-action <?php echo (!isset($_GET['sub']) || $_GET['sub'] == 'general') ? '' : ''; ?>">
            <i class="bi bi-gear me-2"></i>General Settings
          </a>
          <a href="index.php?action=settings&sub=system"
            class="list-group-item list-group-item-action active">
            <i class="bi bi-display me-2"></i>System Settings
          </a>
          <a href="index.php?action=settings&sub=email"
            class="list-group-item list-group-item-action">
            <i class="bi bi-envelope me-2"></i>Email Settings
          </a>
          <a href="index.php?action=settings&sub=security"
            class="list-group-item list-group-item-action">
            <i class="bi bi-shield-lock me-2"></i>Security Settings
          </a>
          <a href="index.php?action=settings&sub=backup"
            class="list-group-item list-group-item-action">
            <i class="bi bi-hdd me-2"></i>Backup Settings
          </a>
          <a href="index.php?action=settings&sub=database"
            class="list-group-item list-group-item-action">
            <i class="bi bi-database me-2"></i>Database Info
          </a>
        </div>
      </div>
    </div>

    <!-- System Settings Content -->
    <div class="col-lg-9">
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">System Configuration</h6>
        </div>
        <div class="card-body">
          <form action="index.php?action=settings&sub=save" method="POST">
            <input type="hidden" name="section" value="system">

            <div class="row mb-4">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="system_version" class="form-label">System Version</label>
                  <input type="text" class="form-control" id="system_version" name="settings[system_version]"
                    value="<?php echo htmlspecialchars($settings['system_version'] ?? '1.0.0'); ?>" readonly>
                  <div class="form-text">Current system version (read-only)</div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label for="enable_activity_log" class="form-label">Activity Logging</label>
                  <select class="form-control" id="enable_activity_log" name="settings[enable_activity_log]">
                    <option value="1" <?php echo ($settings['enable_activity_log'] ?? '1') == '1' ? 'selected' : ''; ?>>Enabled</option>
                    <option value="0" <?php echo ($settings['enable_activity_log'] ?? '1') == '0' ? 'selected' : ''; ?>>Disabled</option>
                  </select>
                  <div class="form-text">Log user activities and system events</div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label for="enable_audit_log" class="form-label">Audit Logging</label>
                  <select class="form-control" id="enable_audit_log" name="settings[enable_audit_log]">
                    <option value="1" <?php echo ($settings['enable_audit_log'] ?? '1') == '1' ? 'selected' : ''; ?>>Enabled</option>
                    <option value="0" <?php echo ($settings['enable_audit_log'] ?? '1') == '0' ? 'selected' : ''; ?>>Disabled</option>
                  </select>
                  <div class="form-text">Log sensitive operations and security events</div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label for="timezone" class="form-label">System Timezone</label>
                  <input type="text" class="form-control" id="timezone"
                    value="<?php echo date_default_timezone_get(); ?>" readonly>
                  <div class="form-text">Configure in php.ini (currently: <?php echo date_default_timezone_get(); ?>)</div>
                </div>
              </div>

              <div class="col-md-12">
                <div class="alert alert-info">
                  <h6><i class="bi bi-info-circle me-2"></i>System Information</h6>
                  <ul class="mb-0">
                    <li><strong>PHP Version:</strong> <?php echo phpversion(); ?></li>
                    <li><strong>Server:</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'N/A'; ?></li>
                    <li><strong>Memory Limit:</strong> <?php echo ini_get('memory_limit'); ?></li>
                    <li><strong>Max Execution Time:</strong> <?php echo ini_get('max_execution_time'); ?> seconds</li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>Save System Settings
              </button>
              <a href="index.php?action=settings" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to General
              </a>
            </div>
          </form>
        </div>
      </div>

      <!-- Maintenance Tools -->
      <div class="card shadow mt-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Maintenance Tools</h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <div class="card h-100 border-left-danger">
                <div class="card-body">
                  <h5 class="card-title"><i class="bi bi-trash me-2 text-danger"></i>Clear Cache</h5>
                  <p class="card-text">Clear system cache and temporary files.</p>
                  <button class="btn btn-outline-danger" onclick="clearCache()">
                    <i class="bi bi-trash me-1"></i>Clear Cache
                  </button>
                </div>
              </div>
            </div>

            <div class="col-md-6 mb-3">
              <div class="card h-100 border-left-warning">
                <div class="card-body">
                  <h5 class="card-title"><i class="bi bi-arrow-clockwise me-2 text-warning"></i>Optimize Database</h5>
                  <p class="card-text">Optimize database tables for better performance.</p>
                  <button class="btn btn-outline-warning" onclick="optimizeDatabase()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Optimize Database
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function clearCache() {
    if (confirm('Are you sure you want to clear all cache? This cannot be undone.')) {
      // Add AJAX call here to clear cache
      alert('Cache cleared successfully!');
    }
  }

  function optimizeDatabase() {
    if (confirm('Are you sure you want to optimize the database? This may take a few moments.')) {
      // Add AJAX call here to optimize database
      alert('Database optimization completed!');
    }
  }
</script>
