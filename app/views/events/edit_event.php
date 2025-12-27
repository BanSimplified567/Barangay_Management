<?php
// app/views/events/edit_event.php
$old = $_SESSION['old'] ?? $event ?? [];
unset($_SESSION['old']);
?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Edit Event</h1>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php echo $_SESSION['error'];
      unset($_SESSION['error']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="card shadow">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Edit Event Information</h6>
    </div>
    <div class="card-body">
      <form action="index.php?action=events&sub=update&id=<?php echo $event['id']; ?>" method="POST">
        <input type="hidden" name="id" value="<?php echo $event['id']; ?>">

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Event Name *</label>
            <input type="text" class="form-control" id="name" name="name"
                   value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>" required>
          </div>

          <div class="col-md-6 mb-3">
            <label for="event_date" class="form-label">Event Date *</label>
            <input type="date" class="form-control" id="event_date" name="event_date"
                   value="<?php echo htmlspecialchars($old['event_date'] ?? ''); ?>" required>
          </div>
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">Description</label>
          <textarea class="form-control" id="description" name="description" rows="4"><?php echo htmlspecialchars($old['description'] ?? ''); ?></textarea>
        </div>

        <div class="mb-3">
          <label for="location" class="form-label">Location</label>
          <input type="text" class="form-control" id="location" name="location"
                 value="<?php echo htmlspecialchars($old['location'] ?? ''); ?>">
        </div>

        <div class="mb-3">
          <label for="status" class="form-label">Status *</label>
          <select class="form-control" id="status" name="status" required>
            <option value="upcoming" <?php echo ($old['status'] ?? '') == 'upcoming' ? 'selected' : ''; ?>>Upcoming</option>
            <option value="ongoing" <?php echo ($old['status'] ?? '') == 'ongoing' ? 'selected' : ''; ?>>Ongoing</option>
            <option value="completed" <?php echo ($old['status'] ?? '') == 'completed' ? 'selected' : ''; ?>>Completed</option>
            <option value="cancelled" <?php echo ($old['status'] ?? '') == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
          </select>
          <div class="form-text">Change event status as needed</div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i>Update Event
          </button>
          <a href="index.php?action=events" class="btn btn-secondary">
            <i class="bi bi-x-circle me-1"></i>Cancel
          </a>

          <?php if (($old['status'] ?? '') === 'upcoming'): ?>
            <a href="index.php?action=events&sub=complete&id=<?php echo $event['id']; ?>"
               class="btn btn-success"
               onclick="return confirm('Mark this event as completed?')">
              <i class="bi bi-check-circle me-1"></i>Mark as Completed
            </a>

            <a href="index.php?action=events&sub=cancel&id=<?php echo $event['id']; ?>"
               class="btn btn-danger"
               onclick="return confirm('Cancel this event?')">
              <i class="bi bi-x-circle me-1"></i>Cancel Event
            </a>
          <?php endif; ?>

          <?php if (($old['status'] ?? '') === 'ongoing'): ?>
            <a href="index.php?action=events&sub=complete&id=<?php echo $event['id']; ?>"
               class="btn btn-success"
               onclick="return confirm('Mark this event as completed?')">
              <i class="bi bi-check-circle me-1"></i>Mark as Completed
            </a>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Validate date on form submit for edit
    document.querySelector('form').addEventListener('submit', function(e) {
      const status = document.getElementById('status').value;
      const eventDate = document.getElementById('event_date').value;

      // Only validate date for upcoming events
      if (status === 'upcoming') {
        const selectedDate = new Date(eventDate);
        const currentDate = new Date();
        currentDate.setHours(0, 0, 0, 0);

        if (selectedDate < currentDate) {
          e.preventDefault();
          alert('For upcoming events, the date cannot be in the past.');
          return false;
        }
      }
    });
  });
</script>
