<?php
// app/views/projects/view_project.php
?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Project Details</h1>

    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h4 class="m-0 font-weight-bold text-primary"><?php echo htmlspecialchars($project['name']); ?></h4>
            <span class="badge bg-<?php
                echo [
                    'planning' => 'secondary',
                    'ongoing' => 'warning',
                    'completed' => 'success',
                    'cancelled' => 'danger'
                ][$project['status']] ?? 'secondary';
            ?>">
                <?php echo ucfirst($project['status']); ?>
            </span>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-8">
                    <h5 class="mb-3">Description</h5>
                    <div class="p-3 bg-light rounded">
                        <?php echo nl2br(htmlspecialchars($project['description'])); ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Project Information</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <strong>Budget:</strong><br>
                                    ₱<?php echo number_format($project['budget'], 2); ?>
                                </li>
                                <li class="mb-2">
                                    <strong>Location:</strong><br>
                                    <?php echo htmlspecialchars($project['location'] ?? 'N/A'); ?>
                                </li>
                                <li class="mb-2">
                                    <strong>Project Lead:</strong><br>
                                    <?php echo htmlspecialchars($project['project_lead'] ?? 'N/A'); ?>
                                </li>
                                <li class="mb-2">
                                    <strong>Funding Source:</strong><br>
                                    <?php echo htmlspecialchars($project['funding_source'] ?? 'N/A'); ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Timeline</h6>
                            <div class="timeline">
                                <div class="d-flex justify-content-between mb-3">
                                    <div class="text-center">
                                        <div class="text-muted small">Start Date</div>
                                        <div class="fw-bold"><?php echo date('M d, Y', strtotime($project['start_date'])); ?></div>
                                    </div>
                                    <div class="align-self-center">→</div>
                                    <div class="text-center">
                                        <div class="text-muted small">End Date</div>
                                        <div class="fw-bold">
                                            <?php echo $project['end_date'] ? date('M d, Y', strtotime($project['end_date'])) : 'Ongoing'; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($project['end_date']): ?>
                                    <?php
                                    $start = strtotime($project['start_date']);
                                    $end = strtotime($project['end_date']);
                                    $today = strtotime(date('Y-m-d'));
                                    
                                    if ($today < $start) {
                                        $progress = 0;
                                        $statusText = 'Not Started';
                                    } elseif ($today > $end) {
                                        $progress = 100;
                                        $statusText = 'Completed';
                                    } else {
                                        $totalDays = $end - $start;
                                        $elapsedDays = $today - $start;
                                        $progress = min(100, ($elapsedDays / $totalDays) * 100);
                                        $statusText = 'In Progress';
                                    }
                                    ?>
                                    <div class="progress mb-2" style="height: 20px;">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: <?php echo $progress; ?>%" 
                                             aria-valuenow="<?php echo $progress; ?>" 
                                             aria-valuemin="0" aria-valuemax="100">
                                            <?php echo round($progress); ?>%
                                        </div>
                                    </div>
                                    <div class="text-center text-muted small"><?php echo $statusText; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Actions</h6>
                            <div class="d-grid gap-2">
                                <a href="index.php?action=projects&sub=edit&id=<?php echo $project['id']; ?>" 
                                   class="btn btn-warning">
                                    <i class="bi bi-pencil me-2"></i>Edit Project
                                </a>
                                
                                <?php if ($project['status'] === 'planning' || $project['status'] === 'ongoing'): ?>
                                    <a href="index.php?action=projects&sub=complete&id=<?php echo $project['id']; ?>" 
                                       class="btn btn-success"
                                       onclick="return confirm('Mark this project as completed?')">
                                        <i class="bi bi-check-circle me-2"></i>Mark as Completed
                                    </a>
                                    <a href="index.php?action=projects&sub=cancel&id=<?php echo $project['id']; ?>" 
                                       class="btn btn-danger"
                                       onclick="return confirm('Cancel this project?')">
                                        <i class="bi bi-x-circle me-2"></i>Cancel Project
                                    </a>
                                <?php endif; ?>
                                
                                <a href="index.php?action=projects&sub=delete&id=<?php echo $project['id']; ?>" 
                                   class="btn btn-outline-danger"
                                   onclick="return confirm('Are you sure you want to delete this project?')">
                                    <i class="bi bi-trash me-2"></i>Delete Project
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="index.php?action=projects" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Projects
            </a>
        </div>
    </div>
</div>
