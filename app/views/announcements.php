<?php include 'header.php'; ?>
<!-- Assume $announcements from controller -->

<h1>Announcements</h1>
<form method="POST" action="index.php?action=announcements" class="mb-4">
    <div class="form-group">
        <textarea name="content" class="form-control" placeholder="Announcement Content" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Post Announcement</button>
</form>
<div class="list-group">
    <?php foreach ($announcements ?? [] as $ann): ?>
        <div class="list-group-item">
            <h5><?php echo $ann['title']; ?></h5>
            <p><?php echo $ann['content']; ?></p>
            <small><?php echo $ann['date']; ?></small>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'footer.php'; ?>
