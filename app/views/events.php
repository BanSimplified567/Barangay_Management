<?php include 'header.php'; ?>
<!-- Assume $events from controller -->

<h1>Events</h1>
<a href="index.php?action=events&sub=add" class="btn btn-primary mb-3">Add Event</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Date</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($events ?? [] as $event): ?>
            <tr>
                <td><?php echo $event['id']; ?></td>
                <td><?php echo $event['title']; ?></td>
                <td><?php echo $event['date']; ?></td>
                <td><?php echo $event['description']; ?></td>
                <td>
                    <a href="index.php?action=events&sub=edit&id=<?php echo $event['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="index.php?action=events&sub=delete&id=<?php echo $event['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>
