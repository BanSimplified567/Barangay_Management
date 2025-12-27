<?php
// app/views/announcements/add_announcement.php
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Post New Announcement</h1>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php echo $_SESSION['error'];
      unset($_SESSION['error']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="card shadow">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Announcement Details</h6>
    </div>
    <div class="card-body">
      <form action="index.php?action=announcements&sub=store" method="POST">
        <div class="mb-3">
          <label for="title" class="form-label">Title *</label>
          <input type="text" class="form-control" id="title" name="title"
            value="<?php echo htmlspecialchars($old['title'] ?? ''); ?>" required>
          <div class="form-text">Enter a clear and descriptive title</div>
        </div>

        <div class="mb-3">
          <label for="content" class="form-label">Content *</label>
          <textarea class="form-control" id="content" name="content" rows="6" required><?php echo htmlspecialchars($old['content'] ?? ''); ?></textarea>
          <div class="form-text">Enter the full announcement content</div>
        </div>

        <div class="mb-3">
          <label for="post_date" class="form-label">Post Date *</label>
          <input type="date" class="form-control" id="post_date" name="post_date"
            value="<?php echo htmlspecialchars($old['post_date'] ?? date('Y-m-d')); ?>" required>
          <div class="form-text">Date when announcement is posted</div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-send me-1"></i>Post Announcement
          </button>
          <a href="index.php?action=announcements" class="btn btn-secondary">
            <i class="bi bi-x-circle me-1"></i>Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
