<?php
// app/views/events/add_event.php
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Add New Event</h1>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php echo $_SESSION['error'];
      unset($_SESSION['error']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="card shadow">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Event Information</h6>
    </div>
    <div class="card-body">
      <form action="index.php?action=events&sub=store" method="POST">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Event Name *</label>
            <input type="text" class="form-control" id="name" name="name"
              value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>" required>
            <div class="form-text">e.g., Barangay Fiesta, Clean-up Drive, etc.</div>
          </div>

          <div class="col-md-6 mb-3">
            <label for="event_date" class="form-label">Event Date *</label>
            <input type="date" class="form-control" id="event_date" name="event_date"
              value="<?php echo htmlspecialchars($old['event_date'] ?? date('Y-m-d')); ?>" required>
            <div class="form-text">Date cannot be in the past</div>
          </div>
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">Description</label>
          <textarea class="form-control" id="description" name="description" rows="4"><?php echo htmlspecialchars($old['description'] ?? ''); ?></textarea>
          <div class="form-text">Details about the event (optional)</div>
        </div>

        <div class="mb-3">
          <label for="location" class="form-label">Location</label>
          <input type="text" class="form-control" id="location" name="location"
            value="<?php echo htmlspecialchars($old['location'] ?? ''); ?>">
          <div class="form-text">Where the event will be held</div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i>Save Event
          </button>
          <a href="index.php?action=events" class="btn btn-secondary">
            <i class="bi bi-x-circle me-1"></i>Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Set min date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('event_date').min = today;

    // Validate date on form submit
    document.querySelector('form').addEventListener('submit', function(e) {
      const eventDate = document.getElementById('event_date').value;
      const selectedDate = new Date(eventDate);
      const currentDate = new Date();
      currentDate.setHours(0, 0, 0, 0);

      if (selectedDate < currentDate) {
        e.preventDefault();
        alert('Event date cannot be in the past.');
        return false;
      }
    });
  });
</script>
