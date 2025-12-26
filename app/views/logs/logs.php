<?php
// app/views/logs.php
include '../header.php';

// Get users for filter dropdown
$users = $users ?? [];
?>

<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">System Activity Logs</h1>

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

  <!-- Quick Stats -->
  <div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Total Logs
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php echo count($logs ?? []); ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-journal-text fs-2 text-gray-300"></i>
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
                Today's Logs
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php
                $today = date('Y-m-d');
                $todayLogs = array_filter($logs ?? [], function ($log) use ($today) {
                  return date('Y-m-d', strtotime($log['timestamp'])) === $today;
                });
                echo count($todayLogs);
                ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-clock-history fs-2 text-gray-300"></i>
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
                Unique Users
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php
                $userIds = [];
                if (!empty($logs)) {
                  foreach ($logs as $log) {
                    $userIds[$log['user_id']] = true;
                  }
                }
                echo count($userIds);
                ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-people fs-2 text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Filters Card -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
      <h6 class="m-0 font-weight-bold text-primary">Filter Logs</h6>
      <div>
        <a href="index.php?action=logs&sub=export&format=csv" class="btn btn-sm btn-success me-2">
          <i class="bi bi-download me-1"></i>Export CSV
        </a>
        <?php if ($_SESSION['role'] === 'admin'): ?>
          <a href="index.php?action=logs&sub=clear" class="btn btn-sm btn-danger">
            <i class="bi bi-trash me-1"></i>Clear Logs
          </a>
        <?php endif; ?>
      </div>
    </div>
    <div class="card-body">
      <form method="GET" action="index.php" class="row g-3">
        <input type="hidden" name="action" value="logs">
        <input type="hidden" name="sub" value="filter">

        <div class="col-md-3">
          <label for="user_id" class="form-label">Filter by User</label>
          <select class="form-select" id="user_id" name="user_id">
            <option value="">All Users</option>
            <?php foreach ($users as $user): ?>
              <option value="<?php echo $user['id']; ?>"
                <?php echo ($_GET['user_id'] ?? '') == $user['id'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($user['full_name']); ?> (<?php echo $user['role']; ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-3">
          <label for="date_from" class="form-label">Date From</label>
          <input type="date" class="form-control" id="date_from" name="date_from"
            value="<?php echo htmlspecialchars($_GET['date_from'] ?? ''); ?>">
        </div>

        <div class="col-md-3">
          <label for="date_to" class="form-label">Date To</label>
          <input type="date" class="form-control" id="date_to" name="date_to"
            value="<?php echo htmlspecialchars($_GET['date_to'] ?? ''); ?>">
        </div>

        <div class="col-md-3">
          <label for="search" class="form-label">Search Action</label>
          <input type="text" class="form-control" id="search" name="search"
            value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
            placeholder="Search in actions...">
        </div>

        <div class="col-md-12">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-filter me-1"></i>Apply Filters
          </button>
          <a href="index.php?action=logs" class="btn btn-secondary">
            <i class="bi bi-x-circle me-1"></i>Clear Filters
          </a>
        </div>
      </form>
    </div>
  </div>

  <!-- Logs Table -->
  <div class="card shadow">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Activity Logs</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover" id="logsTable">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Timestamp</th>
              <th>User</th>
              <th>Role</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($logs)): ?>
              <?php foreach ($logs as $log): ?>
                <tr>
                  <td><?php echo htmlspecialchars($log['id']); ?></td>
                  <td>
                    <div><?php echo date('M d, Y', strtotime($log['timestamp'])); ?></div>
                    <small class="text-muted"><?php echo date('h:i A', strtotime($log['timestamp'])); ?></small>
                  </td>
                  <td>
                    <div class="fw-bold"><?php echo htmlspecialchars($log['user_name']); ?></div>
                    <small class="text-muted">ID: <?php echo $log['user_id']; ?></small>
                  </td>
                  <td>
                    <?php
                    $roleClass = [
                      'admin' => 'danger',
                      'staff' => 'primary',
                      'resident' => 'success'
                    ][$log['user_role']] ?? 'secondary';
                    ?>
                    <span class="badge bg-<?php echo $roleClass; ?>">
                      <?php echo ucfirst(htmlspecialchars($log['user_role'])); ?>
                    </span>
                  </td>
                  <td>
                    <div class="log-action">
                      <?php echo htmlspecialchars($log['action']); ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center py-4">
                  <div class="text-muted">
                    <i class="bi bi-journal-x display-6"></i>
                    <p class="mt-2">No activity logs found.</p>
                    <?php if (isset($_GET['user_id']) || isset($_GET['date_from']) || isset($_GET['date_to']) || isset($_GET['search'])): ?>
                      <p>Try clearing your filters or search for different criteria.</p>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <?php if (count($logs ?? []) > 50): ?>
        <nav aria-label="Logs navigation" class="mt-4">
          <ul class="pagination justify-content-center">
            <li class="page-item <?php echo ($currentPage ?? 1) <= 1 ? 'disabled' : ''; ?>">
              <a class="page-link" href="index.php?action=logs&page=<?php echo ($currentPage ?? 1) - 1; ?>">Previous</a>
            </li>
            <?php for ($i = 1; $i <= ($totalPages ?? 1); $i++): ?>
              <li class="page-item <?php echo ($currentPage ?? 1) == $i ? 'active' : ''; ?>">
                <a class="page-link" href="index.php?action=logs&page=<?php echo $i; ?>"><?php echo $i; ?></a>
              </li>
            <?php endfor; ?>
            <li class="page-item <?php echo ($currentPage ?? 1) >= ($totalPages ?? 1) ? 'disabled' : ''; ?>">
              <a class="page-link" href="index.php?action=logs&page=<?php echo ($currentPage ?? 1) + 1; ?>">Next</a>
            </li>
          </ul>
        </nav>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTables if available
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
      $('#logsTable').DataTable({
        "order": [
          [0, "desc"]
        ], // Sort by ID descending (newest first)
        "pageLength": 50,
        "language": {
          "search": "Search logs:",
          "lengthMenu": "Show _MENU_ logs per page",
          "zeroRecords": "No logs found",
          "info": "Showing _START_ to _END_ of _TOTAL_ logs",
          "infoEmpty": "No logs available",
          "infoFiltered": "(filtered from _MAX_ total logs)"
        }
      });
    }

    // Set today's date as default for date_to
    const dateToField = document.querySelector('#date_to');
    if (dateToField && !dateToField.value) {
      dateToField.value = '<?php echo date('Y-m-d'); ?>';
    }

    // Set 7 days ago as default for date_from
    const dateFromField = document.querySelector('#date_from');
    if (dateFromField && !dateFromField.value) {
      const weekAgo = new Date();
      weekAgo.setDate(weekAgo.getDate() - 7);
      dateFromField.value = weekAgo.toISOString().split('T')[0];
    }
  });
</script>

<?php include '../footer.php'; ?>
