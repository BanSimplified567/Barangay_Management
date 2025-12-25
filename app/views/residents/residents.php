<?php include '../header.php'; ?>
<!-- Assume $residents array from controller -->

<h1>Residents Management</h1>
<a href="index.php?action=residents&sub=add" class="btn btn-primary mb-3">Add Resident</a>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($residents ?? [] as $resident): ?>
            <tr>
                <td><?php echo $resident['id']; ?></td>
                <td><?php echo $resident['name']; ?></td>
                <td><?php echo $resident['address']; ?></td>
                <td>
                    <a href="index.php?action=residents&sub=edit&id=<?php echo $resident['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="index.php?action=residents&sub=delete&id=<?php echo $resident['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>
