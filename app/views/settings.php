<?php include 'header.php'; ?>

<h1>Settings</h1>
<form method="POST" action="index.php?action=settings">
    <div class="form-group mb-3">
        <label>System Name</label>
        <input type="text" name="system_name" class="form-control" value="Barangay System">
    </div>
    <div class="form-group mb-3">
        <label>Email Notifications</label>
        <select name="email_notifications" class="form-control">
            <option value="on">On</option>
            <option value="off">Off</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Save Settings</button>
</form>

<?php include 'footer.php'; ?>
