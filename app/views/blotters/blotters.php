

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?php echo $title ?? 'Blotter Records'; ?></h1>

    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Open Cases
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                    $open = array_filter($blotters, function($b) {
                                        return $b['status'] === 'open';
                                    });
                                    echo count($open);
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-exclamation-circle fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Settled Cases
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                    $settled = array_filter($blotters, function($b) {
                                        return $b['status'] === 'settled';
                                    });
                                    echo count($settled);
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Escalated Cases
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                    $escalated = array_filter($blotters, function($b) {
                                        return $b['status'] === 'escalated';
                                    });
                                    echo count($escalated);
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-arrow-up-right-circle fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Blotter Button -->
    <div class="mb-3">
        <a href="index.php?action=blotters&sub=add" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Add New Blotter
        </a>
    </div>

    <!-- Blotters Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Blotter Records</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover data-table">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Complainant</th>
                            <th>Respondent</th>
                            <th>Description</th>
                            <th>Incident Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($blotters)): ?>
                            <?php foreach ($blotters as $blotter): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($blotter['id']); ?></td>
                                    <td><?php echo htmlspecialchars($blotter['complainant_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($blotter['respondent_name'] ?? 'N/A'); ?></td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;"
                                             title="<?php echo htmlspecialchars($blotter['description']); ?>">
                                            <?php echo htmlspecialchars($blotter['description']); ?>
                                        </div>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($blotter['incident_date'])); ?></td>
                                    <td>
                                        <?php
                                            $statusClass = [
                                                'open' => 'danger',
                                                'settled' => 'success',
                                                'escalated' => 'warning'
                                            ][$blotter['status']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?php echo $statusClass; ?>">
                                            <?php echo ucfirst(htmlspecialchars($blotter['status'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="index.php?action=blotters&sub=edit&id=<?php echo $blotter['id']; ?>"
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <?php if ($blotter['status'] === 'open'): ?>
                                            <a href="index.php?action=blotters&sub=settle&id=<?php echo $blotter['id']; ?>"
                                               class="btn btn-sm btn-success" title="Mark as Settled">
                                                <i class="bi bi-check-circle"></i>
                                            </a>
                                        <?php endif; ?>

                                        <a href="index.php?action=blotters&sub=delete&id=<?php echo $blotter['id']; ?>"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this blotter record?')"
                                           title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-journal-text display-6"></i>
                                        <p class="mt-2">No blotter records found.</p>
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
