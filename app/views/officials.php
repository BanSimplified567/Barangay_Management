<?php include 'header.php'; ?>
<!-- Assume $officials from controller -->

<h1>Officials</h1>
<form method="POST" action="index.php?action=officials" class="mb-4">
    <div class="row">
        <div class="col-md-4">
            <input type="text" name="name" class="form-control" placeholder="Name" required>
        </div>
        <div class="col-md-4">
            <input type="text" name="position" class="form-control" placeholder="Position" required>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary">Add Official</button>
        </div>
    </div>
</form>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Position</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($officials ?? [] as $official): ?>
            <tr>
                <td><?php echo $official['id']; ?></td>
                <td><?php echo $official['name']; ?></td>
                <td><?php echo $official['position']; ?></td>
                <td>
                    <a href="index.php?action=officials&sub=edit&id=<?php echo $official['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="index.php?action=officials&sub=delete&id=<?php echo $official['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>
