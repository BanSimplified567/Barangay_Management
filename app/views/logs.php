<?php include 'header.php'; ?>
<!-- Assume $logs from controller -->

<h1>System Logs</h1>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Action</th>
            <th>Timestamp</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($logs ?? [] as $log): ?>
            <tr>
                <td><?php echo $log['id']; ?></td>
                <td><?php echo $log['user']; ?></td>
                <td><?php echo $log['action']; ?></td>
                <td><?php echo $log['timestamp']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>
