<?php
// app/views/announcements/view_announcement.php
?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Announcement Details</h1>

  <div class="card shadow">
    <div class="card-header py-3">
      <h4 class="m-0 font-weight-bold text-primary"><?php echo htmlspecialchars($announcement['title']); ?></h4>
    </div>
    <div class="card-body">
      <div class="mb-4">
        <div class="announcement-content">
          <?php echo nl2br(htmlspecialchars($announcement['content'])); ?>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="d-flex align-items-center mb-3">
            <i class="bi bi-person me-2"></i>
            <div>
              <small class="text-muted">Posted by:</small>
              <div><?php echo htmlspecialchars($announcement['posted_by_name']); ?></div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="d-flex align-items-center mb-3">
            <i class="bi bi-calendar me-2"></i>
            <div>
              <small class="text-muted">Post date:</small>
              <div><?php echo date('M d, Y', strtotime($announcement['post_date'])); ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-footer">
      <a href="index.php?action=announcements" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Announcements
      </a>
    </div>
  </div>
</div>
