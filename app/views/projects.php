<?php include 'header.php'; ?>
<!-- Assume $projects hardcoded or from DB -->

<h1>Projects</h1>
<div class="list-group">
    <?php foreach ($projects ?? [] as $project): ?>
        <div class="list-group-item">
            <h5><?php echo $project['title']; ?></h5>
            <p><?php echo $project['description']; ?></p>
            <small>Status: <?php echo $project['status']; ?></small>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'footer.php'; ?>
