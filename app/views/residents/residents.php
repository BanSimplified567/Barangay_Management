<?php
// app/views/residents/residents.php
// NO header/footer includes here - they're handled by BaseController
?>

<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?php echo $title ?? 'Residents Management'; ?></h1>

  <!-- Success/Error Messages -->
  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?php echo $_SESSION['success'];
      unset($_SESSION['success']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php echo $_SESSION['error'];
      unset($_SESSION['error']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <!-- Add Resident Button -->
  <div class="mb-3">
    <a href="index.php?action=residents&sub=add" class="btn btn-primary">
      <i class="bi bi-person-plus me-1"></i>Add New Resident
    </a>
  </div>

  <!-- Residents Table -->
  <div class="card shadow">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Residents List</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover data-table">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Full Name</th>
              <th>Address</th>
              <th>Birthdate</th>
              <th>Contact Number</th>
              <th>Gender</th>
              <th>Civil Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($residents)): ?>
              <?php foreach ($residents as $resident): ?>
                <tr>
                  <td><?php echo htmlspecialchars($resident['id']); ?></td>
                  <td><?php echo htmlspecialchars($resident['full_name']); ?></td>
                  <td><?php echo htmlspecialchars($resident['address']); ?></td>
                  <td><?php echo date('M d, Y', strtotime($resident['birthdate'])); ?></td>
                  <td><?php echo htmlspecialchars($resident['contact_number'] ?? 'N/A'); ?></td>
                  <td>
                    <span class="badge bg-<?php echo $resident['gender'] == 'male' ? 'primary' : ($resident['gender'] == 'female' ? 'danger' : 'secondary'); ?>">
                      <?php echo ucfirst($resident['gender']); ?>
                    </span>
                  </td>
                  <td>
                    <span class="badge bg-info text-dark">
                      <?php echo ucfirst($resident['civil_status']); ?>
                    </span>
                  </td>
                  <td>
                    <a href="index.php?action=residents&sub=edit&id=<?php echo $resident['id']; ?>"
                      class="btn btn-sm btn-warning" title="Edit">
                      <i class="bi bi-pencil"></i>
                    </a>
                    <a href="index.php?action=residents&sub=delete&id=<?php echo $resident['id']; ?>"
                      class="btn btn-sm btn-danger"
                      onclick="return confirm('Are you sure you want to delete <?php echo addslashes($resident['full_name']); ?>?')"
                      title="Delete">
                      <i class="bi bi-trash"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center py-4">
                  <div class="text-muted">
                    <i class="bi bi-people display-6"></i>
                    <p class="mt-2">No residents found.</p>
                  </div>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
