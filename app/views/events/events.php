<?php
// app/views/events/events.php
// Note: Header and footer are automatically included by BaseController
?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Barangay Events</h1>

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
                Upcoming Events
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php
                $upcoming = array_filter($events, function ($e) {
                  return $e['status'] === 'upcoming';
                });
                echo count($upcoming);
                ?>
              </div>
            </div>
            <div class="col-auto">
              <i class="bi bi-calendar-event fs-2 text-gray-300"></i>
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
                Ongoing Events
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php
                $ongoing = array_filter($events, function ($e) {
                  return $e['status'] === 'ongoing';
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
                Completed Events
              </div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php
                $completed = array_filter($events, function ($e) {
                  return $e['status'] === 'completed';
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
  </div>

  <!-- Add Event Button -->
  <div class="mb-3">
    <a href="index.php?action=events&sub=add" class="btn btn-primary">
      <i class="bi bi-plus-circle me-1"></i>Add New Event
    </a>
  </div>

  <!-- Events Table -->
  <div class="card shadow">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Barangay Events</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover" id="eventsTable">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Event Name</th>
              <th>Date</th>
              <th>Location</th>
              <th>Organizer</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($events)): ?>
              <?php foreach ($events as $event): ?>
                <tr>
                  <td><?php echo htmlspecialchars($event['id']); ?></td>
                  <td>
                    <strong><?php echo htmlspecialchars($event['name']); ?></strong>
                    <?php if (!empty($event['description'])): ?>
                      <br><small class="text-muted"><?php echo htmlspecialchars(substr($event['description'], 0, 50)); ?>...</small>
                    <?php endif; ?>
                  </td>
                  <td><?php echo date('M d, Y', strtotime($event['event_date'])); ?></td>
                  <td><?php echo htmlspecialchars($event['location'] ?? 'TBA'); ?></td>
                  <td><?php echo htmlspecialchars($event['organizer_name'] ?? 'N/A'); ?></td>
                  <td>
                    <?php
                    $statusClass = [
                      'upcoming' => 'primary',
                      'ongoing' => 'warning',
                      'completed' => 'success',
                      'cancelled' => 'danger'
                    ][$event['status']] ?? 'secondary';
                    ?>
                    <span class="badge bg-<?php echo $statusClass; ?>">
                      <?php echo ucfirst(htmlspecialchars($event['status'])); ?>
                    </span>
                  </td>
                  <td>
                    <a href="index.php?action=events&sub=edit&id=<?php echo $event['id']; ?>"
                      class="btn btn-sm btn-warning" title="Edit">
                      <i class="bi bi-pencil"></i>
                    </a>

                    <?php if ($event['status'] === 'upcoming'): ?>
                      <a href="index.php?action=events&sub=complete&id=<?php echo $event['id']; ?>"
                        class="btn btn-sm btn-success"
                        onclick="return confirm('Mark this event as completed?')"
                        title="Mark as Completed">
                        <i class="bi bi-check-circle"></i>
                      </a>

                      <a href="index.php?action=events&sub=cancel&id=<?php echo $event['id']; ?>"
                        class="btn btn-sm btn-danger"
                        onclick="return confirm('Cancel this event?')"
                        title="Cancel Event">
                        <i class="bi bi-x-circle"></i>
                      </a>
                    <?php endif; ?>

                    <?php if ($event['status'] === 'ongoing'): ?>
                      <a href="index.php?action=events&sub=complete&id=<?php echo $event['id']; ?>"
                        class="btn btn-sm btn-success"
                        onclick="return confirm('Mark this event as completed?')"
                        title="Mark as Completed">
                        <i class="bi bi-check-circle"></i>
                      </a>
                    <?php endif; ?>

                    <a href="index.php?action=events&sub=delete&id=<?php echo $event['id']; ?>"
                      class="btn btn-sm btn-danger"
                      onclick="return confirm('Are you sure you want to delete this event?')"
                      title="Delete">
                      <i class="bi bi-trash"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="7" class="text-center py-4">
                  <div class="text-muted">
                    <i class="bi bi-calendar display-6"></i>
                    <p class="mt-2">No events scheduled yet.</p>
                  </div>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTables if available
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
      $('#eventsTable').DataTable({
        "order": [
          [2, "asc"]
        ], // Sort by event date ascending
        "pageLength": 25,
        "language": {
          "search": "Search events:",
          "lengthMenu": "Show _MENU_ events per page",
          "zeroRecords": "No events found",
          "info": "Showing _START_ to _END_ of _TOTAL_ events",
          "infoEmpty": "No events available",
          "infoFiltered": "(filtered from _MAX_ total events)"
        }
      });
    }
  });
</script>
