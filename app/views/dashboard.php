<?php
// app/views/dashboard.php
// NO header/footer includes here - they're handled by BaseController
?>

<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?php echo $title ?? 'Dashboard'; ?></h1>

  <!-- Display success/error messages -->
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

  <!-- Stats Cards -->
  <div class="row">
    <!-- Total Residents -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Total Residents
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php echo number_format($total_residents); ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-people fs-2 text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pending Certifications -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                Pending Certifications
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php echo $pending_certifications; ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-file-earmark-check fs-2 text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Upcoming Events -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                Upcoming Events
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php echo $upcoming_events; ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-calendar-event fs-2 text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Open Blotters -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                Open Blotters
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php echo $open_blotters; ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-journal-text fs-2 text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Additional Row -->
  <div class="row">
    <!-- Barangay Officials -->
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                Current Officials
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php echo $total_officials; ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-person-badge fs-2 text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Activities and Announcements -->
  <div class="row">
    <!-- Recent Activities -->
    <div class="col-lg-6 mb-4">
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Recent Activities</h6>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>User</th>
                  <th>Action</th>
                  <th>Time</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($recent_logs)): ?>
                  <?php foreach ($recent_logs as $log): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($log['full_name']); ?></td>
                      <td><?php echo htmlspecialchars($log['action']); ?></td>
                      <td><?php echo date('M d, Y h:i A', strtotime($log['timestamp'])); ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="3" class="text-center">No recent activities</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Announcements -->
    <div class="col-lg-6 mb-4">
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Recent Announcements</h6>
        </div>
        <div class="card-body">
          <?php if (!empty($recent_announcements)): ?>
            <?php foreach ($recent_announcements as $announcement): ?>
              <div class="card mb-3">
                <div class="card-body">
                  <h6 class="card-title"><?php echo htmlspecialchars($announcement['title']); ?></h6>
                  <p class="card-text small"><?php echo substr(htmlspecialchars($announcement['content']), 0, 100); ?>...</p>
                  <p class="card-text">
                    <small class="text-muted">
                      Posted by: <?php echo htmlspecialchars($announcement['posted_by_name']); ?>
                      on <?php echo date('M d, Y', strtotime($announcement['post_date'])); ?>
                    </small>
                  </p>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="text-center">No recent announcements</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
