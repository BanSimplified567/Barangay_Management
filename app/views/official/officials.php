<?php
// app/views/officials.php
include 'header.php';
?>

<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Barangay Officials</h1>

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

  <!-- Add Official Button -->
  <div class="mb-3">
    <a href="index.php?action=officials&sub=add" class="btn btn-primary">
      <i class="bi bi-person-plus me-1"></i>Add New Official
    </a>
  </div>

  <!-- Officials Table -->
  <div class="card shadow">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Current Barangay Officials</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover" id="officialsTable">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Official Name</th>
              <th>Position</th>
              <th>Term Start</th>
              <th>Term End</th>
              <th>Status</th>
              <th>Contact</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($officials)): ?>
              <?php foreach ($officials as $official): ?>
                <?php
                $termEnd = $official['term_end'];
                $isActive = !$termEnd || strtotime($termEnd) >= strtotime(date('Y-m-d'));
                $statusClass = $isActive ? 'success' : 'secondary';
                $statusText = $isActive ? 'Active' : 'Inactive';
                ?>
                <tr>
                  <td><?php echo htmlspecialchars($official['id']); ?></td>
                  <td>
                    <strong><?php echo htmlspecialchars($official['official_name']); ?></strong>
                  </td>
                  <td>
                    <?php
                    $positionClass = [
                      'Barangay Captain' => 'danger',
                      'Barangay Secretary' => 'primary',
                      'Barangay Treasurer' => 'info',
                      'Barangay Councilor' => 'warning'
                    ][$official['position']] ?? 'secondary';
                    ?>
                    <span class="badge bg-<?php echo $positionClass; ?>">
                      <?php echo htmlspecialchars($official['position']); ?>
                    </span>
                  </td>
                  <td><?php echo date('M d, Y', strtotime($official['term_start'])); ?></td>
                  <td>
                    <?php if ($official['term_end']): ?>
                      <?php echo date('M d, Y', strtotime($official['term_end'])); ?>
                    <?php else: ?>
                      <span class="text-muted">Ongoing</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <span class="badge bg-<?php echo $statusClass; ?>">
                      <?php echo $statusText; ?>
                    </span>
                  </td>
                  <td><?php echo htmlspecialchars($official['contact_number'] ?? 'N/A'); ?></td>
                  <td>
                    <a href="index.php?action=officials&sub=edit&id=<?php echo $official['id']; ?>"
                      class="btn btn-sm btn-warning" title="Edit">
                      <i class="bi bi-pencil"></i>
                    </a>
                    <a href="index.php?action=officials&sub=delete&id=<?php echo $official['id']; ?>"
                      class="btn btn-sm btn-danger"
                      onclick="return confirm('Are you sure you want to remove <?php echo addslashes($official['official_name']); ?> as <?php echo addslashes($official['position']); ?>?')"
                      title="Remove">
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
                    <p class="mt-2">No officials assigned yet.</p>
                  </div>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Official Positions Info -->
  <div class="card shadow mt-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Barangay Official Positions</h6>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-3">
          <div class="d-flex align-items-center mb-2">
            <span class="badge bg-danger me-2">●</span>
            <span>Barangay Captain</span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="d-flex align-items-center mb-2">
            <span class="badge bg-primary me-2">●</span>
            <span>Barangay Secretary</span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="d-flex align-items-center mb-2">
            <span class="badge bg-info me-2">●</span>
            <span>Barangay Treasurer</span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="d-flex align-items-center mb-2">
            <span class="badge bg-warning me-2">●</span>
            <span>Barangay Councilor</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTables if available
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
      $('#officialsTable').DataTable({
        "order": [
          [0, "desc"]
        ], // Sort by ID descending
        "pageLength": 25,
        "language": {
          "search": "Search officials:",
          "lengthMenu": "Show _MENU_ officials per page",
          "zeroRecords": "No officials found",
          "info": "Showing _START_ to _END_ of _TOTAL_ officials",
          "infoEmpty": "No officials available",
          "infoFiltered": "(filtered from _MAX_ total officials)"
        }
      });
    }
  });
</script>

<?php include 'footer.php'; ?>
