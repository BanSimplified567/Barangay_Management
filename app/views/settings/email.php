<?php
// app/views/settings/email.php
// Note: Header and footer are automatically included by BaseController

// Get settings from controller
$settings = $settings ?? [];
?>

<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Email Settings</h1>

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
            class="list-group-item list-group-item-action active">
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

    <!-- Email Settings Content -->
    <div class="col-lg-9">
      <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 font-weight-bold text-primary">Email Configuration</h6>
          <a href="index.php?action=settings&sub=test_email" class="btn btn-sm btn-info"
            onclick="return confirm('Send test email to <?php echo $_SESSION['email'] ?? 'your email'; ?>?')">
            <i class="bi bi-envelope-check me-1"></i>Test Email
          </a>
        </div>
        <div class="card-body">
          <form action="index.php?action=settings&sub=save" method="POST">
            <input type="hidden" name="section" value="email">

            <div class="row mb-4">
              <div class="col-md-12">
                <div class="mb-3">
                  <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="email_notifications" name="settings[email_notifications]" value="1"
                      <?php echo ($settings['email_notifications'] ?? '1') == '1' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="email_notifications">Enable Email Notifications</label>
                  </div>
                  <div class="form-text">Send email notifications for system events</div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label for="email_smtp_host" class="form-label">SMTP Host</label>
                  <input type="text" class="form-control" id="email_smtp_host" name="settings[email_smtp_host]"
                    value="<?php echo htmlspecialchars($settings['email_smtp_host'] ?? 'localhost'); ?>">
                  <div class="form-text">SMTP server address (e.g., smtp.gmail.com, localhost)</div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label for="email_smtp_port" class="form-label">SMTP Port</label>
                  <input type="number" class="form-control" id="email_smtp_port" name="settings[email_smtp_port]"
                    value="<?php echo htmlspecialchars($settings['email_smtp_port'] ?? '25'); ?>">
                  <div class="form-text">Common ports: 25, 587 (TLS), 465 (SSL)</div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label for="email_smtp_username" class="form-label">SMTP Username</label>
                  <input type="text" class="form-control" id="email_smtp_username" name="settings[email_smtp_username]"
                    value="<?php echo htmlspecialchars($settings['email_smtp_username'] ?? ''); ?>">
                  <div class="form-text">Username for SMTP authentication</div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label for="email_smtp_password" class="form-label">SMTP Password</label>
                  <input type="password" class="form-control" id="email_smtp_password" name="settings[email_smtp_password]"
                    value="<?php echo htmlspecialchars($settings['email_smtp_password'] ?? ''); ?>">
                  <div class="form-text">Password for SMTP authentication</div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label for="system_email" class="form-label">Sender Email</label>
                  <input type="email" class="form-control" id="system_email" name="settings[system_email]"
                    value="<?php echo htmlspecialchars($settings['system_email'] ?? 'admin@barangay.local'); ?>">
                  <div class="form-text">Email address used as sender</div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="mb-3">
                  <label for="email_sender_name" class="form-label">Sender Name</label>
                  <input type="text" class="form-control" id="email_sender_name" name="settings[email_sender_name]"
                    value="<?php echo htmlspecialchars($settings['email_sender_name'] ?? $settings['system_name'] ?? 'Barangay Bansimplified'); ?>">
                  <div class="form-text">Display name for email sender</div>
                </div>
              </div>

              <div class="col-md-12">
                <div class="alert alert-warning">
                  <h6><i class="bi bi-exclamation-triangle me-2"></i>Email Configuration Notes</h6>
                  <ul class="mb-0">
                    <li>For Gmail: Use smtp.gmail.com, port 587 with TLS</li>
                    <li>For local server: Use localhost, port 25</li>
                    <li>Some email providers require "Less Secure Apps" to be enabled</li>
                    <li>Test your configuration after saving changes</li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>Save Email Settings
              </button>
              <a href="index.php?action=settings" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to General
              </a>
            </div>
          </form>
        </div>
      </div>

      <!-- Email Test Section -->
      <div class="card shadow mt-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Email Testing</h6>
        </div>
        <div class="card-body">
          <p>Send a test email to verify your configuration is working correctly.</p>

          <form action="index.php?action=settings&sub=test_email" method="POST">
            <div class="row">
              <div class="col-md-8 mb-3">
                <label for="test_email_address" class="form-label">Test Email Address</label>
                <input type="email" class="form-control" id="test_email_address"
                  value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>" readonly>
              </div>
              <div class="col-md-4 mb-3 d-flex align-items-end">
                <button type="submit" class="btn btn-info w-100">
                  <i class="bi bi-envelope-check me-1"></i>Send Test Email
                </button>
              </div>
            </div>
            <small class="text-muted">Test email will be sent to your registered email address: <?php echo htmlspecialchars($_SESSION['email'] ?? 'Not set'); ?></small>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
