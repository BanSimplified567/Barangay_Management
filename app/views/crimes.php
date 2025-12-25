<?php include 'header.php'; ?>
<!-- Assume $crimes from controller -->

<h1>Crimes</h1>
<a href="index.php?action=crimes&sub=add" class="btn btn-primary mb-3">Report Crime</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Location</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($crimes ?? [] as $crime): ?>
            <tr>
                <td><?php echo $crime['id']; ?></td>
                <td><?php echo $crime['type']; ?></td>
                <td><?php echo $crime['location']; ?></td>
                <td><?php echo $crime['date']; ?></td>
                <td>
                    <a href="index.php?action=crimes&sub=edit&id=<?php echo $crime['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>
