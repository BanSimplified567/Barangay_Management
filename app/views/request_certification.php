<?php include 'header.php'; ?>

<h1>Request Certification</h1>
<form method="POST" action="index.php?action=request-certification">
    <div class="form-group mb-3">
        <label>Certification Type</label>
        <select name="type" class="form-control" required>
            <option value="residency">Residency</option>
            <option value="indigency">Indigency</option>
            <!-- Add options -->
        </select>
    </div>
    <div class="form-group mb-3">
        <label>Purpose</label>
        <textarea name="purpose" class="form-control" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit Request</button>
</form>

<?php include 'footer.php'; ?>
