<?php
// app/views/settings/security.php
// Note: Header and footer are automatically included by BaseController

// Get settings from controller
$settings = $settings ?? [];
?>

<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Security Settings</h1>

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
            class="list-group-item list-group-item-action">
            <i class="bi bi-gear me-2"></i>General Settings
          </a>
          <a href="index.php?action=settings&sub=system"
            class="list-group-item list-group-item-action">
            <i class="bi bi-display me-2"></i>System Settings
          </a>
          <a href="index.php?action=settings&sub=email"
            class="list-group-item list-group-item-action">
            <i class="bi bi-envelope me-2"></i>Email Settings
          </a>
          <a href="index.php?action=settings&sub=security"
            class="list-group-item list-group-item-action active">
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

    <!-- Security Settings Content -->
    <div class="col-lg-9">
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Security Configuration</h6>
        </div>
        <div class="card-body">
          <form action="index.php?action=settings&sub=save" method="POST">
            <input type="hidden" name="section" value="security">

            <div class="row mb-4">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="login_attempts" class="form-label">Max Login Attempts</label>
                  <input type="number" class="form-control" id="login_attempts" name="settings[login_attempts]"
                    value="<?php echo htmlspecialchars($settings['login_attempts'] ?? '5'); ?>" min="1" max="10">
                  <div class="form-text">Number of failed login attempts before account lockout</div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label for="lockout_time" class="form-label">Account Lockout Time (minutes)</label>
                  <input type="number" class="form-control" id="lockout_time" name="settings[lockout_time]"
                    value="<?php echo htmlspecialchars($settings['lockout_time'] ?? '15'); ?>" min="1" max="1440">
                  <div class="form-text">How long to lock account after failed attempts</div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label for="password_expiry" class="form-label">Password Expiry (days)</label>
                  <input type="number" class="form-control" id="password_expiry" name="settings[password_expiry]"
                    value="<?php echo htmlspecialchars($settings['password_expiry'] ?? '90'); ?>" min="0" max="365">
                  <div class="form-text">Days before password expires (0 = never expire)</div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label for="session_timeout" class="form-label">Session Timeout (minutes)</label>
                  <input type="number" class="form-control" id="session_timeout" name="settings[session_timeout]"
                    value="<?php echo htmlspecialchars($settings['session_timeout'] ?? '30'); ?>" min="1" max="480">
                  <div class="form-text">Inactive session timeout</div>
                </div>
              </div>

              <div class="col-md-12">
                <div class="mb-3">
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="require_strong_password" name="settings[require_strong_password]" value="1"
                      <?php echo ($settings['require_strong_password'] ?? '0') == '1' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="require_strong_password">Require Strong Passwords</label>
                  </div>
                  <div class="form-text">Passwords must contain uppercase, lowercase, numbers, and special characters</div>
                </div>

                <div class="mb-3">
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="prevent_password_reuse" name="settings[prevent_password_reuse]" value="1"
                      <?php echo ($settings['prevent_password_reuse'] ?? '1') == '1' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="prevent_password_reuse">Prevent Password Reuse</label>
                  </div>
                  <div class="form-text">Users cannot reuse their last 3 passwords</div>
                </div>

                <div class="mb-3">
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="two_factor_auth" name="settings[two_factor_auth]" value="1"
                      <?php echo ($settings['two_factor_auth'] ?? '0') == '1' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="two_factor_auth">Enable Two-Factor Authentication (2FA)</label>
                  </div>
                  <div class="form-text">Require verification code sent via email/SMS</div>
                </div>

                <div class="mb-3">
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="ip_restriction" name="settings[ip_restriction]" value="1"
                      <?php echo ($settings['ip_restriction'] ?? '0') == '1' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="ip_restriction">Enable IP Restriction</label>
                  </div>
                  <div class="form-text">Restrict login to specific IP addresses</div>
                </div>
              </div>

              <div class="col-md-12">
                <div class="alert alert-info">
                  <h6><i class="bi bi-shield-check me-2"></i>Security Recommendations</h6>
                  <ul class="mb-0">
                    <li>Enable Two-Factor Authentication for administrator accounts</li>
                    <li>Set password expiry to 90 days or less</li>
                    <li>Limit login attempts to prevent brute force attacks</li>
                    <li>Use strong password requirements</li>
                    <li>Regularly review system logs for suspicious activity</li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>Save Security Settings
              </button>
              <a href="index.php?action=settings" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to General
              </a>
            </div>
          </form>
        </div>
      </div>

      <!-- Security Audit Log -->
      <div class="card shadow mt-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 font-weight-bold text-primary">Security Audit</h6>
          <a href="index.php?action=logs" class="btn btn-sm btn-info">
            <i class="bi bi-list-check me-1"></i>View Full Logs
          </a>
        </div>
        <div class="card-body">
          <p class="card-text">Monitor security events and suspicious activities.</p>
          <div class="table-responsive">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>Event</th>
                  <th>User</th>
                  <th>IP Address</th>
                  <th>Time</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><span class="badge bg-success">Successful Login</span></td>
                  <td>Admin User</td>
                  <td>192.168.1.100</td>
                  <td>Just now</td>
                </tr>
                <tr>
                  <td><span class="badge bg-warning">Failed Login</span></td>
                  <td>Unknown</td>
                  <td>10.0.0.5</td>
                  <td>5 minutes ago</td>
                </tr>
                <tr>
                  <td><span class="badge bg-info">Settings Changed</span></td>
                  <td>Admin User</td>
                  <td>192.168.1.100</td>
                  <td>1 hour ago</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
