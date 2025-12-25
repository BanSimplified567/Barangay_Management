<?php include 'header.php'; ?>
<!-- Assume $certifications from controller -->

<h1>Certifications</h1>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Resident</th>
            <th>Type</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($certifications ?? [] as $cert): ?>
            <tr>
                <td><?php echo $cert['id']; ?></td>
                <td><?php echo $cert['resident_name']; ?></td>
                <td><?php echo $cert['type']; ?></td>
                <td><?php echo $cert['status']; ?></td>
                <td>
                    <?php if ($cert['status'] === 'pending'): ?>
                        <form method="POST" action="index.php?action=certifications">
                            <input type="hidden" name="id" value="<?php echo $cert['id']; ?>">
                            <button type="submit" name="approve" class="btn btn-sm btn-success">Approve</button>
                            <button type="submit" name="reject" class="btn btn-sm btn-danger">Reject</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>
