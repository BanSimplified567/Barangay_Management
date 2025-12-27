<?php
// app/views/settings/settings.php
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

  <!-- System Status -->
  <div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                System Status
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php echo ($settings['maintenance_mode'] ?? '0') == '1' ? 'Maintenance' : 'Active'; ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-server fs-2 text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                Version
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php echo htmlspecialchars($settings['system_version'] ?? '1.0.0'); ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-tag fs-2 text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                Email Notifications
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php echo ($settings['email_notifications'] ?? '1') == '1' ? 'Enabled' : 'Disabled'; ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-envelope fs-2 text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                Session Timeout
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php echo htmlspecialchars($settings['session_timeout'] ?? '30'); ?> min
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-clock-history fs-2 text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Settings Navigation -->
  <div class="row">
    <div class="col-lg-3 mb-4">
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Settings Categories</h6>
        </div>
        <div class="list-group list-group-flush">
          <a href="index.php?action=settings"
            class="list-group-item list-group-item-action <?php echo (!isset($_GET['sub']) || $_GET['sub'] == 'general') ? 'active' : ''; ?>">
            <i class="bi bi-gear me-2"></i>General Settings
          </a>
          <a href="index.php?action=settings&sub=system"
            class="list-group-item list-group-item-action <?php echo ($_GET['sub'] ?? '') == 'system' ? 'active' : ''; ?>">
            <i class="bi bi-display me-2"></i>System Settings
          </a>
          <a href="index.php?action=settings&sub=email"
            class="list-group-item list-group-item-action <?php echo ($_GET['sub'] ?? '') == 'email' ? 'active' : ''; ?>">
            <i class="bi bi-envelope me-2"></i>Email Settings
          </a>
          <a href="index.php?action=settings&sub=security"
            class="list-group-item list-group-item-action <?php echo ($_GET['sub'] ?? '') == 'security' ? 'active' : ''; ?>">
            <i class="bi bi-shield-lock me-2"></i>Security Settings
          </a>
          <a href="index.php?action=settings&sub=backup"
            class="list-group-item list-group-item-action <?php echo ($_GET['sub'] ?? '') == 'backup' ? 'active' : ''; ?>">
            <i class="bi bi-hdd me-2"></i>Backup Settings
          </a>
          <a href="index.php?action=settings&sub=database"
            class="list-group-item list-group-item-action <?php echo ($_GET['sub'] ?? '') == 'database' ? 'active' : ''; ?>">
            <i class="bi bi-database me-2"></i>Database Info
          </a>
        </div>
      </div>

      <div class="card shadow mt-4">
        <div class="card-body text-center">
          <i class="bi bi-info-circle display-4 text-primary mb-3"></i>
          <h5>Settings Guide</h5>
          <p class="small text-muted">
            Changes made here affect the entire system. Use with caution and always test changes in a safe environment.
          </p>
        </div>
      </div>
    </div>

    <!-- Main Settings Content -->
    <div class="col-lg-9">
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">General Settings</h6>
        </div>
        <div class="card-body">
          <form action="index.php?action=settings&sub=save" method="POST">
            <input type="hidden" name="section" value="general">

            <div class="row mb-4">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="system_name" class="form-label">System Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="system_name" name="settings[system_name]"
                    value="<?php echo htmlspecialchars($settings['system_name'] ?? 'Barangay Management System'); ?>" required>
                  <div class="form-text">Display name of the system shown in titles and headers.</div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label for="system_email" class="form-label">System Email</label>
                  <input type="email" class="form-control" id="system_email" name="settings[system_email]"
                    value="<?php echo htmlspecialchars($settings['system_email'] ?? 'admin@barangay.local'); ?>">
                  <div class="form-text">Default email address used for system notifications.</div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label for="default_items_per_page" class="form-label">Items Per Page</label>
                  <input type="number" class="form-control" id="default_items_per_page" name="settings[default_items_per_page]"
                    value="<?php echo htmlspecialchars($settings['default_items_per_page'] ?? '25'); ?>" min="5" max="100">
                  <div class="form-text">Default number of items to display per page in tables.</div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label for="session_timeout" class="form-label">Session Timeout (minutes)</label>
                  <input type="number" class="form-control" id="session_timeout" name="settings[session_timeout]"
                    value="<?php echo htmlspecialchars($settings['session_timeout'] ?? '30'); ?>" min="5" max="480">
                  <div class="form-text">Time in minutes before inactive users are automatically logged out.</div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label for="date_format" class="form-label">Date Format</label>
                  <select class="form-select" id="date_format" name="settings[date_format]">
                    <option value="Y-m-d" <?php echo ($settings['date_format'] ?? '') == 'Y-m-d' ? 'selected' : ''; ?>>YYYY-MM-DD (2023-12-31)</option>
                    <option value="d/m/Y" <?php echo ($settings['date_format'] ?? '') == 'd/m/Y' ? 'selected' : ''; ?>>DD/MM/YYYY (31/12/2023)</option>
                    <option value="m/d/Y" <?php echo ($settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : ''; ?>>MM/DD/YYYY (12/31/2023)</option>
                    <option value="d-M-Y" <?php echo ($settings['date_format'] ?? '') == 'd-M-Y' ? 'selected' : ''; ?>>DD-MMM-YYYY (31-Dec-2023)</option>
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label for="time_format" class="form-label">Time Format</label>
                  <select class="form-select" id="time_format" name="settings[time_format]">
                    <option value="H:i:s" <?php echo ($settings['time_format'] ?? '') == 'H:i:s' ? 'selected' : ''; ?>>24-hour (14:30:45)</option>
                    <option value="h:i:s A" <?php echo ($settings['time_format'] ?? '') == 'h:i:s A' ? 'selected' : ''; ?>>12-hour (02:30:45 PM)</option>
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label for="currency_symbol" class="form-label">Currency Symbol</label>
                  <input type="text" class="form-control" id="currency_symbol" name="settings[currency_symbol]"
                    value="<?php echo htmlspecialchars($settings['currency_symbol'] ?? '₱'); ?>" maxlength="3">
                  <div class="form-text">Symbol used for currency display (e.g., ₱, $, €).</div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label for="theme" class="form-label">Theme</label>
                  <select class="form-select" id="theme" name="settings[theme]">
                    <option value="default" <?php echo ($settings['theme'] ?? '') == 'default' ? 'selected' : ''; ?>>Default (Blue)</option>
                    <option value="dark" <?php echo ($settings['theme'] ?? '') == 'dark' ? 'selected' : ''; ?>>Dark Mode</option>
                    <option value="light" <?php echo ($settings['theme'] ?? '') == 'light' ? 'selected' : ''; ?>>Light Mode</option>
                  </select>
                </div>
              </div>

              <div class="col-md-12">
                <div class="mb-3">
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="maintenance_mode" name="settings[maintenance_mode]" value="1"
                      <?php echo ($settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="maintenance_mode">Maintenance Mode</label>
                  </div>
                  <div class="form-text">When enabled, only administrators can access the system.</div>
                </div>

                <div class="mb-3">
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="registration_allowed" name="settings[registration_allowed]" value="1"
                      <?php echo ($settings['registration_allowed'] ?? '1') == '1' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="registration_allowed">Allow User Registration</label>
                  </div>
                  <div class="form-text">Allow new users to register accounts.</div>
                </div>

                <div class="mb-3">
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="enable_audit_log" name="settings[enable_audit_log]" value="1"
                      <?php echo ($settings['enable_audit_log'] ?? '1') == '1' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="enable_audit_log">Enable Audit Logging</label>
                  </div>
                  <div class="form-text">Log all system activities for security and compliance.</div>
                </div>
              </div>
            </div>

            <div class="mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>Save Settings
              </button>
              <button type="reset" class="btn btn-secondary">
                <i class="bi bi-arrow-clockwise me-1"></i>Reset Changes
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- System Information -->
      <div class="card shadow mt-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">System Information</h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <table class="table table-sm">
                <tr>
                  <th>PHP Version</th>
                  <td><?php echo phpversion(); ?></td>
                </tr>
                <tr>
                  <th>Server Software</th>
                  <td><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'N/A'; ?></td>
                </tr>
                <tr>
                  <th>Database Driver</th>
                  <td>PDO (MySQL)</td>
                </tr>
                <tr>
                  <th>System Uptime</th>
                  <td><?php echo date('Y-m-d H:i:s'); ?></td>
                </tr>
              </table>
            </div>
            <div class="col-md-6">
              <table class="table table-sm">
                <tr>
                  <th>Memory Limit</th>
                  <td><?php echo ini_get('memory_limit'); ?></td>
                </tr>
                <tr>
                  <th>Max Execution Time</th>
                  <td><?php echo ini_get('max_execution_time'); ?> seconds</td>
                </tr>
                <tr>
                  <th>Upload Max Filesize</th>
                  <td><?php echo ini_get('upload_max_filesize'); ?></td>
                </tr>
                <tr>
                  <th>Timezone</th>
                  <td><?php echo date_default_timezone_get(); ?></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
