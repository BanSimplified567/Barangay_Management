<?php include 'header.php'; ?>
<!-- Assume $user from controller -->

<h1>My Profile</h1>
<form method="POST" action="index.php?action=my_profile">
    <div class="form-group mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" value="<?php echo $user['name'] ?? ''; ?>" required>
    </div>
    <div class="form-group mb-3">
        <label>Address</label>
        <input type="text" name="address" class="form-control" value="<?php echo $user['address'] ?? ''; ?>" required>
    </div>
    <!-- Add more fields -->
    <button type="submit" class="btn btn-primary">Update Profile</button>
</form>

<?php include 'footer.php'; ?>
