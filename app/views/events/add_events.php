<?php
// app/views/events/add_event.php
include '../header.php';
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
?>

<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Add New Event</h1>

  <div class="row">
    <div class="col-md-8">
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Event Details</h6>
        </div>
        <div class="card-body">
          <form action="index.php?action=events&sub=create" method="POST">
            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="name" class="form-label">Event Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name"
                  value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>" required
                  placeholder="e.g., Barangay General Assembly, Clean-up Drive">
              </div>

              <div class="col-md-6 mb-3">
                <label for="event_date" class="form-label">Event Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="event_date" name="event_date"
                  value="<?php echo htmlspecialchars($old['event_date'] ?? date('Y-m-d', strtotime('+7 days'))); ?>" required>
              </div>

              <div class="col-md-6 mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location"
                  value="<?php echo htmlspecialchars($old['location'] ?? ''); ?>"
                  placeholder="e.g., Barangay Hall, Covered Court">
              </div>

              <div class="col-md-12 mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4"><?php echo htmlspecialchars($old['description'] ?? ''); ?></textarea>
                <div class="form-text">Describe the event, its purpose, and any important details.</div>
              </div>
            </div>

            <div class="mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>Create Event
              </button>
              <a href="index.php?action=events" class="btn btn-secondary">
                <i class="bi bi-x-circle me-1"></i>Cancel
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Event Guidelines</h6>
        </div>
        <div class="card-body">
          <ul>
            <li>Events are visible to all staff members</li>
            <li>Set realistic dates for planning</li>
            <li>Include clear location information</li>
            <li>Provide detailed descriptions</li>
            <li>Update event status as needed</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../footer.php'; ?>
