<?php include 'header.php'; ?>
<!-- Assume $blotters from controller -->

<h1>Blotters</h1>
<a href="index.php?action=blotters&sub=add" class="btn btn-primary mb-3">Add Blotter</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Complainant</th>
            <th>Incident</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($blotters ?? [] as $blotter): ?>
            <tr>
                <td><?php echo $blotter['id']; ?></td>
                <td><?php echo $blotter['complainant']; ?></td>
                <td><?php echo $blotter['incident']; ?></td>
                <td><?php echo $blotter['date']; ?></td>
                <td><?php echo $blotter['status']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>
