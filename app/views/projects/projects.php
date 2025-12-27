<?php
// app/views/projects.php
// Note: Header and footer are automatically included by BaseController
?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Barangay Projects</h1>

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
                Total Projects
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php echo count($projects ?? []); ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-clipboard-check fs-2 text-gray-300"></i>
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
                Ongoing
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php
                $ongoing = array_filter($projects ?? [], function ($p) {
                  return $p['status'] === 'ongoing';
                });
                echo count($ongoing);
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
                Completed
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php
                $completed = array_filter($projects ?? [], function ($p) {
                  return $p['status'] === 'completed';
                });
                echo count($completed);
                ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-check-circle fs-2 text-gray-300"></i>
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
                Total Budget
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                ₱<?php
                  $totalBudget = array_reduce($projects ?? [], function ($carry, $p) {
                    return $carry + floatval($p['budget'] ?? 0);
                  }, 0);
                  echo number_format($totalBudget, 2);
                  ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-cash-coin fs-2 text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Project Button -->
  <div class="mb-3">
    <a href="index.php?action=projects&sub=add" class="btn btn-primary">
      <i class="bi bi-plus-circle me-1"></i>Add New Project
    </a>
  </div>

  <!-- Projects Cards View -->
  <div class="row">
    <?php if (!empty($projects)): ?>
      <?php foreach ($projects as $project): ?>
        <div class="col-md-4 mb-4">
          <div class="card shadow h-100 project-card">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
              <h6 class="m-0 font-weight-bold text-primary"><?php echo htmlspecialchars($project['name']); ?></h6>
              <span class="badge bg-<?php
                                    echo [
                                      'planning' => 'secondary',
                                      'ongoing' => 'warning',
                                      'completed' => 'success',
                                      'cancelled' => 'danger'
                                    ][$project['status']] ?? 'secondary';
                                    ?>">
                <?php echo ucfirst($project['status']); ?>
              </span>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <p class="card-text"><?php echo nl2br(htmlspecialchars(substr($project['description'], 0, 150))); ?><?php echo strlen($project['description']) > 150 ? '...' : ''; ?></p>
              </div>

              <div class="project-details mb-3">
                <div class="row">
                  <div class="col-6">
                    <small class="text-muted">Budget:</small><br>
                    <strong>₱<?php echo number_format($project['budget'], 2); ?></strong>
                  </div>
                  <div class="col-6">
                    <small class="text-muted">Location:</small><br>
                    <strong><?php echo htmlspecialchars($project['location'] ?? 'N/A'); ?></strong>
                  </div>
                </div>
              </div>

              <div class="project-timeline mb-3">
                <small class="text-muted">Timeline:</small><br>
                <div class="d-flex justify-content-between">
                  <span><?php echo date('M d', strtotime($project['start_date'])); ?></span>
                  <span>→</span>
                  <span>
                    <?php echo $project['end_date'] ? date('M d, Y', strtotime($project['end_date'])) : 'Ongoing'; ?>
                  </span>
                </div>
              </div>

              <div class="project-lead mb-3">
                <small class="text-muted">Project Lead:</small><br>
                <strong><?php echo htmlspecialchars($project['project_lead'] ?? 'N/A'); ?></strong>
              </div>

              <div class="project-funding">
                <small class="text-muted">Funding Source:</small><br>
                <strong><?php echo htmlspecialchars($project['funding_source'] ?? 'N/A'); ?></strong>
              </div>
            </div>
            <div class="card-footer">
              <div class="d-flex justify-content-between">
                <a href="index.php?action=projects&sub=view&id=<?php echo $project['id']; ?>"
                  class="btn btn-sm btn-info" title="View Details">
                  <i class="bi bi-eye"></i>
                </a>
                <a href="index.php?action=projects&sub=edit&id=<?php echo $project['id']; ?>"
                  class="btn btn-sm btn-warning" title="Edit">
                  <i class="bi bi-pencil"></i>
                </a>

                <?php if ($project['status'] === 'planning' || $project['status'] === 'ongoing'): ?>
                  <a href="index.php?action=projects&sub=complete&id=<?php echo $project['id']; ?>"
                    class="btn btn-sm btn-success"
                    onclick="return confirm('Mark this project as completed?')"
                    title="Mark as Completed">
                    <i class="bi bi-check-circle"></i>
                  </a>
                  <a href="index.php?action=projects&sub=cancel&id=<?php echo $project['id']; ?>"
                    class="btn btn-sm btn-danger"
                    onclick="return confirm('Cancel this project?')"
                    title="Cancel Project">
                    <i class="bi bi-x-circle"></i>
                  </a>
                <?php endif; ?>

                <a href="index.php?action=projects&sub=delete&id=<?php echo $project['id']; ?>"
                  class="btn btn-sm btn-danger"
                  onclick="return confirm('Are you sure you want to delete this project?')"
                  title="Delete">
                  <i class="bi bi-trash"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-md-12">
        <div class="card shadow">
          <div class="card-body text-center py-5">
            <i class="bi bi-clipboard-data display-1 text-muted"></i>
            <h4 class="mt-3">No projects yet</h4>
            <p class="text-muted">Start by adding your first barangay project.</p>
            <a href="index.php?action=projects&sub=add" class="btn btn-primary">
              <i class="bi bi-plus-circle me-1"></i>Add First Project
            </a>
            <p class="text-muted mt-3 small">
              The system will automatically create sample projects if this is your first time.
            </p>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <!-- Table View Toggle (Optional) -->
  <div class="mt-4 text-end">
    <button class="btn btn-sm btn-outline-secondary" onclick="toggleView()">
      <i class="bi bi-list me-1"></i>Toggle Table View
    </button>
  </div>

  <!-- Projects Table (Hidden by default) -->
  <div class="card shadow mt-4 d-none" id="projectsTable">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Projects Table View</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Project Name</th>
              <th>Budget</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Status</th>
              <th>Project Lead</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($projects)): ?>
              <?php foreach ($projects as $project): ?>
                <tr>
                  <td><?php echo $project['id']; ?></td>
                  <td><?php echo htmlspecialchars($project['name']); ?></td>
                  <td>₱<?php echo number_format($project['budget'], 2); ?></td>
                  <td><?php echo date('M d, Y', strtotime($project['start_date'])); ?></td>
                  <td><?php echo $project['end_date'] ? date('M d, Y', strtotime($project['end_date'])) : 'Ongoing'; ?></td>
                  <td>
                    <span class="badge bg-<?php
                                          echo [
                                            'planning' => 'secondary',
                                            'ongoing' => 'warning',
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                          ][$project['status']] ?? 'secondary';
                                          ?>">
                      <?php echo ucfirst($project['status']); ?>
                    </span>
                  </td>
                  <td><?php echo htmlspecialchars($project['project_lead'] ?? 'N/A'); ?></td>
                  <td>
                    <a href="index.php?action=projects&sub=view&id=<?php echo $project['id']; ?>"
                      class="btn btn-sm btn-info" title="View">
                      <i class="bi bi-eye"></i>
                    </a>
                    <a href="index.php?action=projects&sub=edit&id=<?php echo $project['id']; ?>"
                      class="btn btn-sm btn-warning" title="Edit">
                      <i class="bi bi-pencil"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<style>
  .project-card {
    transition: transform 0.2s, box-shadow 0.2s;
  }

  .project-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  }

  .project-details,
  .project-timeline,
  .project-lead,
  .project-funding {
    border-top: 1px solid #eee;
    padding-top: 10px;
    margin-top: 10px;
  }
</style>

<script>
  function toggleView() {
    const table = document.getElementById('projectsTable');
    const cards = document.querySelector('.row:not(.mb-4)'); // The row containing cards

    if (table.classList.contains('d-none')) {
      table.classList.remove('d-none');
      if (cards) cards.classList.add('d-none');
      event.target.innerHTML = '<i class="bi bi-grid me-1"></i>Toggle Card View';
    } else {
      table.classList.add('d-none');
      if (cards) cards.classList.remove('d-none');
      event.target.innerHTML = '<i class="bi bi-list me-1"></i>Toggle Table View';
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips if Bootstrap is available
    if (typeof bootstrap !== 'undefined') {
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
      var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    }
  });
</script>
