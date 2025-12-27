<?php
// app/views/settings/backup.php
// Note: Header and footer are automatically included by BaseController

// Get settings from controller
$settings = $settings ?? [];

// Check for existing backup files
$backupDir = BASE_PATH . '/backups/';
$backupFiles = [];
if (is_dir($backupDir)) {
  $files = scandir($backupDir);
  foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
      $filepath = $backupDir . $file;
      $backupFiles[] = [
        'name' => $file,
        'size' => filesize($filepath),
        'modified' => filemtime($filepath)
      ];
    }
  }
  // Sort by modification time (newest first)
  usort($backupFiles, function ($a, $b) {
    return $b['modified'] - $a['modified'];
  });
}

// Helper function to format file sizes - MOVED TO TOP
if (!function_exists('formatBytes')) {
  function formatBytes($bytes, $decimals = 2)
  {
    if ($bytes === 0) return '0 Bytes';
    $k = 1024;
    $dm = $decimals < 0 ? 0 : $decimals;
    $sizes = ['Bytes', 'KB', 'MB', 'GB'];
    $i = floor(log($bytes) / log($k));
    return number_format($bytes / pow($k, $i), $dm) . ' ' . $sizes[$i];
  }
}
?>

<div class="container-fluid">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="h3 mb-0 text-gray-800">Backup Settings</h1>
      <p class="text-muted mb-0">Manage database backups and automated backup schedules</p>
    </div>
    <div>
      <a href="index.php?action=settings&sub=perform_backup"
        class="btn btn-primary"
        onclick="return confirm('Create database backup now? This may take a moment.')">
        <i class="bi bi-database-down me-2"></i>Create Backup Now
      </a>
    </div>
  </div>

  <!-- Success/Error Messages -->
  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <div class="d-flex align-items-center">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
        <div>
          <?php echo $_SESSION['success'];
          unset($_SESSION['success']); ?>
        </div>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <div class="d-flex align-items-center">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
        <div>
          <?php echo $_SESSION['error'];
          unset($_SESSION['error']); ?>
        </div>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="row">
    <!-- Settings Navigation -->
    <div class="col-lg-3 mb-4">
      <div class="card border-left-primary shadow h-100">
        <div class="card-header bg-primary text-white py-3">
          <h6 class="m-0 font-weight-bold">Settings Categories</h6>
        </div>
        <div class="list-group list-group-flush rounded-bottom">
          <a href="index.php?action=settings"
            class="list-group-item list-group-item-action d-flex align-items-center py-3">
            <i class="bi bi-gear me-3 text-primary"></i>
            <span>General Settings</span>
          </a>
          <a href="index.php?action=settings&sub=system"
            class="list-group-item list-group-item-action d-flex align-items-center py-3">
            <i class="bi bi-display me-3 text-primary"></i>
            <span>System Settings</span>
          </a>
          <a href="index.php?action=settings&sub=email"
            class="list-group-item list-group-item-action d-flex align-items-center py-3">
            <i class="bi bi-envelope me-3 text-primary"></i>
            <span>Email Settings</span>
          </a>
          <a href="index.php?action=settings&sub=security"
            class="list-group-item list-group-item-action d-flex align-items-center py-3">
            <i class="bi bi-shield-lock me-3 text-primary"></i>
            <span>Security Settings</span>
          </a>
          <a href="index.php?action=settings&sub=backup"
            class="list-group-item list-group-item-action d-flex align-items-center py-3 active bg-primary text-white border-primary">
            <i class="bi bi-hdd me-3"></i>
            <span>Backup Settings</span>
          </a>
          <a href="index.php?action=settings&sub=database"
            class="list-group-item list-group-item-action d-flex align-items-center py-3">
            <i class="bi bi-database me-3 text-primary"></i>
            <span>Database Info</span>
          </a>
        </div>
      </div>
    </div>

    <!-- Main Content Area -->
    <div class="col-lg-9">
      <!-- Manual Backup Card -->
      <div class="card shadow mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="bi bi-database-add me-2"></i>Create Manual Backup
          </h6>
          <button type="button" class="btn btn-outline-info btn-sm" onclick="checkDatabaseSize()">
            <i class="bi bi-pie-chart me-1"></i>Check Database Size
          </button>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-8">
              <p class="mb-3">Create a complete backup of your database immediately. This backup will include all tables and data.</p>

              <div class="alert alert-info border-start border-info border-3">
                <div class="d-flex">
                  <i class="bi bi-info-circle-fill text-info me-3 fs-5"></i>
                  <div>
                    <h6 class="alert-heading mb-2">Backup Information</h6>
                    <ul class="mb-0 ps-3">
                      <li>Backup includes all database tables and data</li>
                      <li>Backup files are saved in <code class="bg-light p-1 rounded">/backups/</code> directory</li>
                      <li>Files are named: <code class="bg-light p-1 rounded">backup_YYYY-MM-DD_HHMMSS.sql</code></li>
                      <li>Always download backups and store them securely</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="card bg-light border-0">
                <div class="card-body text-center">
                  <i class="bi bi-database-fill-gear display-4 text-primary mb-3"></i>
                  <h5 class="card-title">Quick Backup</h5>
                  <p class="card-text text-muted small">Click the button below to create an immediate backup</p>
                  <a href="index.php?action=settings&sub=perform_backup"
                    class="btn btn-primary w-100"
                    onclick="return confirm('Create database backup now? This may take a moment.')">
                    <i class="bi bi-lightning-fill me-2"></i>Backup Now
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Automated Backup Settings -->
      <div class="card shadow mb-4">
        <div class="card-header bg-white py-3">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="bi bi-clock-history me-2"></i>Automated Backup Settings
          </h6>
        </div>
        <div class="card-body">
          <form action="index.php?action=settings&sub=save" method="POST">
            <input type="hidden" name="section" value="backup">

            <div class="row">
              <!-- Auto Backup Toggle -->
              <div class="col-md-12 mb-4">
                <div class="form-check form-switch form-switch-lg">
                  <input class="form-check-input" type="checkbox" id="backup_auto" name="settings[backup_auto]" value="1"
                    <?php echo ($settings['backup_auto'] ?? '0') == '1' ? 'checked' : ''; ?>>
                  <label class="form-check-label fw-bold" for="backup_auto">
                    Enable Automated Backups
                  </label>
                  <div class="form-text text-muted">Automatically create database backups on a schedule</div>
                </div>
              </div>

              <!-- Interval Settings -->
              <div class="col-md-6 mb-3">
                <label for="backup_interval" class="form-label fw-bold">
                  <i class="bi bi-calendar-week me-1 text-primary"></i>Backup Interval
                </label>
                <div class="input-group">
                  <input type="number" class="form-control" id="backup_interval" name="settings[backup_interval]"
                    value="<?php echo htmlspecialchars($settings['backup_interval'] ?? '7'); ?>" min="1" max="30">
                  <span class="input-group-text">days</span>
                </div>
                <div class="form-text text-muted">How often to create automatic backups</div>
              </div>

              <!-- Retention Settings -->
              <div class="col-md-6 mb-3">
                <label for="backup_keep" class="form-label fw-bold">
                  <i class="bi bi-archive me-1 text-primary"></i>Backup Retention
                </label>
                <div class="input-group">
                  <input type="number" class="form-control" id="backup_keep" name="settings[backup_keep]"
                    value="<?php echo htmlspecialchars($settings['backup_keep'] ?? '5'); ?>" min="1" max="50">
                  <span class="input-group-text">files</span>
                </div>
                <div class="form-text text-muted">Maximum number of backup files to keep (oldest deleted first)</div>
              </div>

              <!-- Time Settings -->
              <div class="col-md-6 mb-3">
                <label for="backup_time" class="form-label fw-bold">
                  <i class="bi bi-clock me-1 text-primary"></i>Preferred Backup Time
                </label>
                <select class="form-select" id="backup_time" name="settings[backup_time]">
                  <option value="00:00" <?php echo ($settings['backup_time'] ?? '') == '00:00' ? 'selected' : ''; ?>>Midnight (00:00)</option>
                  <option value="02:00" <?php echo ($settings['backup_time'] ?? '') == '02:00' ? 'selected' : ''; ?>>Early Morning (02:00)</option>
                  <option value="04:00" <?php echo ($settings['backup_time'] ?? '') == '04:00' ? 'selected' : ''; ?>>Dawn (04:00)</option>
                </select>
                <div class="form-text text-muted">Time to perform automatic backups (server time)</div>
              </div>

              <!-- Compression Settings -->
              <div class="col-md-6 mb-3">
                <label for="backup_compress" class="form-label fw-bold">
                  <i class="bi bi-file-earmark-zip me-1 text-primary"></i>Compression
                </label>
                <select class="form-select" id="backup_compress" name="settings[backup_compress]">
                  <option value="0" <?php echo ($settings['backup_compress'] ?? '0') == '0' ? 'selected' : ''; ?>>No Compression</option>
                  <option value="1" <?php echo ($settings['backup_compress'] ?? '0') == '1' ? 'selected' : ''; ?>>GZIP Compression</option>
                </select>
                <div class="form-text text-muted">Compress backup files to save disk space</div>
              </div>

              <!-- Important Notes -->
              <div class="col-md-12 mt-4">
                <div class="alert alert-warning border-start border-warning border-3">
                  <div class="d-flex">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-3 fs-5"></i>
                    <div>
                      <h6 class="alert-heading mb-2">Important Notes</h6>
                      <ul class="mb-0 ps-3">
                        <li>Automated backups require cron job or scheduled task setup</li>
                        <li>Ensure backup directory has proper write permissions</li>
                        <li>Regularly test backup restoration process</li>
                        <li>Store backups in a secure, off-site location</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Form Actions -->
              <div class="col-md-12 mt-4 pt-3 border-top">
                <div class="d-flex justify-content-between">
                  <a href="index.php?action=settings" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to General
                  </a>
                  <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-2"></i>Save Backup Settings
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Existing Backups -->
      <div class="card shadow">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 font-weight-bold text-primary">
            <i class="bi bi-files me-2"></i>Existing Backup Files
          </h6>
          <span class="badge bg-primary rounded-pill"><?php echo count($backupFiles); ?> files</span>
        </div>
        <div class="card-body">
          <?php if (empty($backupFiles)): ?>
            <div class="text-center py-5">
              <i class="bi bi-database-slash display-1 text-muted mb-3"></i>
              <h5 class="text-muted">No backup files found</h5>
              <p class="text-muted">Create your first backup using the "Create Backup Now" button above.</p>
            </div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="table-light">
                  <tr>
                    <th class="ps-4">File Name</th>
                    <th>Size</th>
                    <th>Modified</th>
                    <th class="text-end pe-4">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($backupFiles as $file): ?>
                    <tr>
                      <td class="ps-4">
                        <div class="d-flex align-items-center">
                          <i class="bi bi-file-earmark-binary text-primary me-3"></i>
                          <div>
                            <div class="fw-medium"><?php echo htmlspecialchars($file['name']); ?></div>
                            <small class="text-muted"><?php echo date('F j, Y', $file['modified']); ?></small>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="badge bg-light text-dark border">
                          <?php echo formatBytes($file['size']); ?>
                        </span>
                      </td>
                      <td>
                        <small><?php echo date('H:i:s', $file['modified']); ?></small>
                      </td>
                      <td class="text-end pe-4">
                        <div class="btn-group btn-group-sm" role="group">
                          <a href="download_backup.php?file=<?php echo urlencode($file['name']); ?>"
                            class="btn btn-outline-success"
                            title="Download">
                            <i class="bi bi-download"></i>
                          </a>
                          <button class="btn btn-outline-danger"
                            onclick="deleteBackup('<?php echo htmlspecialchars($file['name']); ?>')"
                            title="Delete">
                            <i class="bi bi-trash"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function checkDatabaseSize() {
    // Add AJAX call to check database size
    alert('Database size check would be implemented here.');
  }

  function deleteBackup(filename) {
    if (confirm('Are you sure you want to delete backup file: ' + filename + '?\nThis action cannot be undone.')) {
      // Add AJAX call to delete backup
      fetch('index.php?action=settings&sub=delete_backup&file=' + encodeURIComponent(filename))
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            location.reload();
          } else {
            alert('Error: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('An error occurred while deleting the backup.');
        });
    }
  }

  // JavaScript version of formatBytes - removed duplicate definition
  /*
  function formatBytes(bytes, decimals = 2) {
      if (bytes === 0) return '0 Bytes';
      const k = 1024;
      const dm = decimals < 0 ? 0 : decimals;
      const sizes = ['Bytes', 'KB', 'MB', 'GB'];
      const i = Math.floor(Math.log(bytes) / Math.log(k));
      return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
  }
  */
</script>
