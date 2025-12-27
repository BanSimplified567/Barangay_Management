<?php
// app/views/settings/database.php
// Note: Header and footer are automatically included by BaseController

// Get data from controller
$tables = $tables ?? [];
$dbInfo = $dbInfo ?? ['total_size' => 0, 'table_count' => 0];

// Helper function to format bytes
function formatBytes($bytes, $decimals = 2)
{
  if ($bytes === 0) return '0 Bytes';
  $k = 1024;
  $dm = $decimals < 0 ? 0 : $decimals;
  $sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
  $i = floor(log($bytes) / log($k));
  return number_format($bytes / pow($k, $i), $dm) . ' ' . $sizes[$i];
}
?>

<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Database Information</h1>

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
            class="list-group-item list-group-item-action">
            <i class="bi bi-shield-lock me-2"></i>Security Settings
          </a>
          <a href="index.php?action=settings&sub=backup"
            class="list-group-item list-group-item-action">
            <i class="bi bi-hdd me-2"></i>Backup Settings
          </a>
          <a href="index.php?action=settings&sub=database"
            class="list-group-item list-group-item-action active">
            <i class="bi bi-database me-2"></i>Database Info
          </a>
        </div>
      </div>
    </div>

    <!-- Database Information Content -->
    <div class="col-lg-9">
      <!-- Database Overview -->
      <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
          <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                    Total Tables
                  </div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">
                    <?php echo $dbInfo['table_count'] ?? 0; ?>
                  </div>
                </div>
                <div class="col-auto">
                  <i class="bi bi-table fs-2 text-gray-300"></i>
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
                    Database Size
                  </div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">
                    <?php echo formatBytes($dbInfo['total_size'] ?? 0); ?>
                  </div>
                </div>
                <div class="col-auto">
                  <i class="bi bi-hdd fs-2 text-gray-300"></i>
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
                    Total Rows
                  </div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">
                    <?php
                    $totalRows = 0;
                    foreach ($tables as $table) {
                      $totalRows += $table['TABLE_ROWS'] ?? 0;
                    }
                    echo number_format($totalRows);
                    ?>
                  </div>
                </div>
                <div class="col-auto">
                  <i class="bi bi-list-ol fs-2 text-gray-300"></i>
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
                    MySQL Version
                  </div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">
                    <?php
                    try {
                      $version = $pdo->query('SELECT VERSION()')->fetchColumn();
                      echo explode('-', $version)[0];
                    } catch (Exception $e) {
                      echo 'Unknown';
                    }
                    ?>
                  </div>
                </div>
                <div class="col-auto">
                  <i class="bi bi-database fs-2 text-gray-300"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Database Tables -->
      <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 font-weight-bold text-primary">Database Tables</h6>
          <div>
            <button class="btn btn-sm btn-info" onclick="optimizeAllTables()">
              <i class="bi bi-arrow-clockwise me-1"></i>Optimize All
            </button>
            <button class="btn btn-sm btn-warning" onclick="repairAllTables()">
              <i class="bi bi-tools me-1"></i>Repair All
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-hover" id="databaseTable">
              <thead>
                <tr>
                  <th>Table Name</th>
                  <th>Rows</th>
                  <th>Data Size</th>
                  <th>Index Size</th>
                  <th>Total Size</th>
                  <th>Created</th>
                  <th>Updated</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($tables)): ?>
                  <?php foreach ($tables as $table): ?>
                    <?php
                    $dataSize = $table['DATA_LENGTH'] ?? 0;
                    $indexSize = $table['INDEX_LENGTH'] ?? 0;
                    $totalSize = $dataSize + $indexSize;
                    ?>
                    <tr>
                      <td>
                        <strong><?php echo htmlspecialchars($table['TABLE_NAME']); ?></strong>
                      </td>
                      <td><?php echo number_format($table['TABLE_ROWS'] ?? 0); ?></td>
                      <td><?php echo formatBytes($dataSize); ?></td>
                      <td><?php echo formatBytes($indexSize); ?></td>
                      <td>
                        <span class="badge bg-<?php echo $totalSize > 104857600 ? 'danger' : ($totalSize > 52428800 ? 'warning' : 'success'); ?>">
                          <?php echo formatBytes($totalSize); ?>
                        </span>
                      </td>
                      <td><?php echo !empty($table['CREATE_TIME']) ? date('Y-m-d', strtotime($table['CREATE_TIME'])) : 'N/A'; ?></td>
                      <td><?php echo !empty($table['UPDATE_TIME']) ? date('Y-m-d', strtotime($table['UPDATE_TIME'])) : 'N/A'; ?></td>
                      <td>
                        <button class="btn btn-sm btn-info" onclick="optimizeTable('<?php echo htmlspecialchars($table['TABLE_NAME']); ?>')"
                          title="Optimize Table">
                          <i class="bi bi-arrow-clockwise"></i>
                        </button>
                        <button class="btn btn-sm btn-warning" onclick="repairTable('<?php echo htmlspecialchars($table['TABLE_NAME']); ?>')"
                          title="Repair Table">
                          <i class="bi bi-tools"></i>
                        </button>
                        <button class="btn btn-sm btn-secondary" onclick="showTableInfo('<?php echo htmlspecialchars($table['TABLE_NAME']); ?>')"
                          title="Table Info">
                          <i class="bi bi-info-circle"></i>
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="8" class="text-center py-4">
                      <div class="text-muted">
                        <i class="bi bi-database-slash display-6"></i>
                        <p class="mt-2">No table information available.</p>
                      </div>
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
              <tfoot>
                <tr class="table-dark">
                  <td><strong>Totals</strong></td>
                  <td><?php
                      $totalRows = 0;
                      foreach ($tables as $table) {
                        $totalRows += $table['TABLE_ROWS'] ?? 0;
                      }
                      echo number_format($totalRows);
                      ?></td>
                  <td><?php
                      $totalData = 0;
                      foreach ($tables as $table) {
                        $totalData += $table['DATA_LENGTH'] ?? 0;
                      }
                      echo formatBytes($totalData);
                      ?></td>
                  <td><?php
                      $totalIndex = 0;
                      foreach ($tables as $table) {
                        $totalIndex += $table['INDEX_LENGTH'] ?? 0;
                      }
                      echo formatBytes($totalIndex);
                      ?></td>
                  <td><strong><?php echo formatBytes($dbInfo['total_size'] ?? 0); ?></strong></td>
                  <td colspan="3"></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>

      <!-- Database Status -->
      <div class="card shadow mt-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Database Status</h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h6>Connection Information</h6>
              <table class="table table-sm">
                <tr>
                  <th>Database Name</th>
                  <td>
                    <?php
                    try {
                      echo htmlspecialchars($pdo->query('SELECT DATABASE()')->fetchColumn());
                    } catch (Exception $e) {
                      echo 'Unknown';
                    }
                    ?>
                  </td>
                </tr>
                <tr>
                  <th>Character Set</th>
                  <td>
                    <?php
                    try {
                      echo htmlspecialchars($pdo->query("SHOW VARIABLES LIKE 'character_set_database'")->fetch(PDO::FETCH_ASSOC)['Value'] ?? 'UTF8');
                    } catch (Exception $e) {
                      echo 'UTF8';
                    }
                    ?>
                  </td>
                </tr>
                <tr>
                  <th>Collation</th>
                  <td>
                    <?php
                    try {
                      echo htmlspecialchars($pdo->query("SHOW VARIABLES LIKE 'collation_database'")->fetch(PDO::FETCH_ASSOC)['Value'] ?? 'utf8_general_ci');
                    } catch (Exception $e) {
                      echo 'utf8_general_ci';
                    }
                    ?>
                  </td>
                </tr>
              </table>
            </div>
            <div class="col-md-6">
              <h6>Performance</h6>
              <table class="table table-sm">
                <tr>
                  <th>Query Cache</th>
                  <td>
                    <?php
                    try {
                      $qc = $pdo->query("SHOW VARIABLES LIKE 'query_cache_size'")->fetch(PDO::FETCH_ASSOC);
                      echo formatBytes($qc['Value'] ?? 0);
                    } catch (Exception $e) {
                      echo 'Not available';
                    }
                    ?>
                  </td>
                </tr>
                <tr>
                  <th>Max Connections</th>
                  <td>
                    <?php
                    try {
                      $mc = $pdo->query("SHOW VARIABLES LIKE 'max_connections'")->fetch(PDO::FETCH_ASSOC);
                      echo $mc['Value'] ?? 'Unknown';
                    } catch (Exception $e) {
                      echo 'Unknown';
                    }
                    ?>
                  </td>
                </tr>
                <tr>
                  <th>Uptime</th>
                  <td>
                    <?php
                    try {
                      $uptime = $pdo->query("SHOW GLOBAL STATUS LIKE 'Uptime'")->fetch(PDO::FETCH_ASSOC);
                      echo formatUptime($uptime['Value'] ?? 0);
                    } catch (Exception $e) {
                      echo 'Unknown';
                    }
                    ?>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function optimizeTable(tableName) {
    if (confirm('Optimize table: ' + tableName + '?')) {
      // Add AJAX call to optimize table
      alert('Table optimization would be implemented here for: ' + tableName);
    }
  }

  function repairTable(tableName) {
    if (confirm('Repair table: ' + tableName + '?')) {
      // Add AJAX call to repair table
      alert('Table repair would be implemented here for: ' + tableName);
    }
  }

  function optimizeAllTables() {
    if (confirm('Optimize all database tables? This may take a while.')) {
      // Add AJAX call to optimize all tables
      alert('All tables optimization would be implemented here.');
    }
  }

  function repairAllTables() {
    if (confirm('Repair all database tables? This may take a while.')) {
      // Add AJAX call to repair all tables
      alert('All tables repair would be implemented here.');
    }
  }

  function showTableInfo(tableName) {
    // Add AJAX call to show table structure
    alert('Table structure for: ' + tableName + ' would be displayed here.');
  }
</script>

<?php
// Helper function to format uptime
if (!function_exists('formatUptime')) {
  function formatUptime($seconds)
  {
    $days = floor($seconds / 86400);
    $hours = floor(($seconds % 86400) / 3600);
    $minutes = floor(($seconds % 3600) / 60);

    $parts = [];
    if ($days > 0) $parts[] = $days . ' day' . ($days > 1 ? 's' : '');
    if ($hours > 0) $parts[] = $hours . ' hour' . ($hours > 1 ? 's' : '');
    if ($minutes > 0) $parts[] = $minutes . ' minute' . ($minutes > 1 ? 's' : '');

    return implode(', ', $parts);
  }
}
?>
