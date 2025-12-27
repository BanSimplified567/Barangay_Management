<?php
// app/views/dashboard.php
// Note: Header and footer are automatically included by BaseController
?>

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo $title ?? 'Dashboard Overview'; ?></h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="bi bi-download me-1"></i> Generate Report
        </a>
    </div>

    <!-- Display success/error messages -->
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

    <!-- Welcome Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-left-primary shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Welcome Back, <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Administrator'); ?>!
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Barangay Management System Dashboard
                            </div>
                            <div class="text-muted">
                                <i class="bi bi-calendar me-1"></i> <?php echo date('l, F j, Y'); ?>
                                <span class="mx-2">|</span>
                                <i class="bi bi-clock me-1"></i> <?php echo date('h:i A'); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-house-door fs-1 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Row 1 -->
    <div class="row">
        <!-- Total Residents -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Residents
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo number_format($total_residents); ?>
                            </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="text-success mr-2">
                                    <i class="bi bi-arrow-up"></i> <?php echo $new_residents_month; ?> new this month
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fs-2 text-primary"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo min(100, ($gender_stats['male_count'] / max(1, $total_residents)) * 100); ?>%"></div>
                        </div>
                        <div class="small text-muted mt-1">
                            <i class="bi bi-gender-male text-primary me-1"></i> Male: <?php echo $gender_stats['male_count']; ?> |
                            <i class="bi bi-gender-female text-danger me-1"></i> Female: <?php echo $gender_stats['female_count']; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

      <!-- Pending Requests -->
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Pending Requests
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?php
                        $total_pending = 0;
                        if (!empty($pending_requests)) {
                            foreach ($pending_requests as $request) {
                                $total_pending += $request['count'] ?? 0;
                            }
                        }
                        echo $total_pending;
                        ?>
                    </div>
                    <div class="mt-2 mb-0 text-muted text-xs">
                        <span class="text-warning mr-2">Requires Attention</span>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="bi bi-clock-history fs-2 text-warning"></i>
                </div>
            </div>
            <div class="mt-3">
                <?php if (!empty($pending_requests)): ?>
                    <?php foreach ($pending_requests as $request): ?>
                        <div class="small text-muted mb-1">
                            <i class="bi bi-circle-fill text-warning me-1"></i>
                            <?php echo ucfirst($request['type'] ?? 'unknown'); ?>: <?php echo $request['count'] ?? 0; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

        <!-- Upcoming Events -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Upcoming Events
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $event_stats['upcoming']; ?>
                            </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="text-success mr-2">
                                    <i class="bi bi-calendar-check"></i> <?php echo $event_stats['ongoing']; ?> ongoing
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar-event fs-2 text-success"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="small text-muted">
                            Next event:
                            <?php if (!empty($upcoming_events_list)): ?>
                                <strong><?php echo htmlspecialchars($upcoming_events_list[0]['name']); ?></strong>
                                <br>
                                <i class="bi bi-clock me-1"></i> <?php echo date('M d, Y', strtotime($upcoming_events_list[0]['event_date'])); ?>
                            <?php else: ?>
                                No upcoming events
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Open Cases -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Open Cases
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                        <?php echo $blotter_stats['open'] + $crime_stats['reported']; ?>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-danger" role="progressbar"
                                             style="width: <?php echo min(100, (($blotter_stats['open'] + $crime_stats['reported']) / max(1, $blotter_stats['total'] + $crime_stats['total'])) * 100); ?>%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="mr-2">Blotters: <?php echo $blotter_stats['open']; ?></span>
                                <span>Crimes: <?php echo $crime_stats['reported']; ?></span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-shield-exclamation fs-2 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Row 2 -->
    <div class="row">
        <!-- Barangay Officials -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Current Officials
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $total_officials; ?>
                            </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                Active Positions
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-person-badge fs-2 text-info"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <?php if (!empty($position_stats)): ?>
                            <?php foreach ($position_stats as $position): ?>
                                <div class="small text-muted mb-1">
                                    <i class="bi bi-person-circle me-1"></i>
                                    <?php echo htmlspecialchars($position['position']); ?>: <?php echo $position['count']; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="small text-muted">No position data available</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Certifications Status -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Certifications
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $certification_stats['pending']; ?> Pending
                            </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="text-success mr-2">Approved: <?php echo $certification_stats['approved']; ?></span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-file-earmark-text fs-2 text-secondary"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress progress-sm">
                            <?php
                            $total_certs = array_sum($certification_stats);
                            $approved_pct = $total_certs > 0 ? ($certification_stats['approved'] / $total_certs) * 100 : 0;
                            ?>
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $approved_pct; ?>%"></div>
                            <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo $total_certs > 0 ? ($certification_stats['pending'] / $total_certs) * 100 : 0; ?>%"></div>
                        </div>
                        <div class="small text-muted mt-1">
                            <span class="text-success">● Approved</span>
                            <span class="text-warning mx-2">● Pending</span>
                            <span class="text-danger">● Rejected</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Users -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                System Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $system_stats['users']; ?>
                            </div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="text-info mr-2">
                                    <i class="bi bi-activity"></i> <?php echo $system_stats['logs_today']; ?> logs today
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill fs-2 text-dark"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="small text-muted">
                            Current role: <span class="badge bg-<?php echo ($_SESSION['role'] ?? '') == 'admin' ? 'danger' : (($_SESSION['role'] ?? '') == 'staff' ? 'primary' : 'success'); ?>">
                                <?php echo ucfirst($_SESSION['role'] ?? 'guest'); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-purple shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-purple text-uppercase mb-1">
                                Quick Actions
                            </div>
                            <div class="h6 mb-2 text-gray-800">
                                Common Tasks
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-lightning fs-2 text-purple"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="d-grid gap-2">
                            <a href="index.php?action=residents&sub=add" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-person-plus me-1"></i> Add Resident
                            </a>
                            <a href="index.php?action=certifications&sub=request" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-file-earmark-plus me-1"></i> New Certificate
                            </a>
                            <a href="index.php?action=blotters&sub=add" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-journal-plus me-1"></i> New Blotter
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Information Row -->
    <div class="row">
        <!-- Recent Activities -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-activity me-2"></i>Recent System Activities
                    </h6>
                    <a href="index.php?action=logs" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <?php if (!empty($recent_logs)): ?>
                            <?php foreach ($recent_logs as $log): ?>
                                <div class="timeline-item mb-3">
                                    <div class="timeline-marker">
                                        <i class="bi bi-circle-fill text-<?php
                                            echo strpos(strtolower($log['action']), 'added') !== false ? 'success' :
                                                 (strpos(strtolower($log['action']), 'updated') !== false ? 'primary' :
                                                 (strpos(strtolower($log['action']), 'deleted') !== false ? 'danger' : 'info'));
                                        ?>"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-1"><?php echo htmlspecialchars($log['full_name']); ?></h6>
                                            <small class="text-muted"><?php echo date('h:i A', strtotime($log['timestamp'])); ?></small>
                                        </div>
                                        <p class="mb-1 small"><?php echo htmlspecialchars($log['action']); ?></p>
                                        <small class="text-muted">
                                            <span class="badge bg-light text-dark"><?php echo ucfirst($log['role']); ?></span>
                                            • <?php echo date('M d', strtotime($log['timestamp'])); ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="bi bi-activity text-muted display-6"></i>
                                <p class="mt-2 text-muted">No recent activities</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Events & Announcements -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-megaphone me-2"></i>Announcements & Events
                    </h6>
                    <div>
                        <a href="index.php?action=announcements" class="btn btn-sm btn-outline-primary me-2">Announcements</a>
                        <a href="index.php?action=events" class="btn btn-sm btn-outline-success">Events</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Upcoming Events -->
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-calendar2-event text-success me-2"></i>Upcoming Events
                            </h6>
                            <?php if (!empty($upcoming_events_list)): ?>
                                <?php foreach ($upcoming_events_list as $event): ?>
                                    <div class="event-item mb-3 p-2 border rounded">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1"><?php echo htmlspecialchars($event['name']); ?></h6>
                                                <small class="text-muted d-block">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    <?php echo date('M d, Y', strtotime($event['event_date'])); ?>
                                                </small>
                                                <small class="text-muted">
                                                    <i class="bi bi-geo-alt me-1"></i>
                                                    <?php echo htmlspecialchars($event['location'] ?? 'TBA'); ?>
                                                </small>
                                            </div>
                                            <span class="badge bg-<?php echo $event['status'] == 'upcoming' ? 'success' : 'warning'; ?>">
                                                <?php echo ucfirst($event['status']); ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-3">
                                    <i class="bi bi-calendar-x text-muted"></i>
                                    <p class="small text-muted mt-2">No upcoming events</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Recent Announcements -->
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-bell text-warning me-2"></i>Latest Announcements
                            </h6>
                            <?php if (!empty($recent_announcements)): ?>
                                <?php foreach ($recent_announcements as $announcement): ?>
                                    <div class="announcement-item mb-3 p-2 border rounded">
                                        <h6 class="mb-1"><?php echo htmlspecialchars($announcement['title']); ?></h6>
                                        <p class="small text-muted mb-2">
                                            <?php echo substr(htmlspecialchars($announcement['content']), 0, 80); ?>...
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="bi bi-person me-1"></i>
                                                <?php echo htmlspecialchars($announcement['posted_by_name']); ?>
                                            </small>
                                            <small class="text-muted">
                                                <?php echo date('M d', strtotime($announcement['post_date'])); ?>
                                            </small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-3">
                                    <i class="bi bi-bell-slash text-muted"></i>
                                    <p class="small text-muted mt-2">No announcements</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Summary -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-bar-chart me-2"></i>System Statistics Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3">
                            <div class="stat-circle bg-primary rounded-circle d-inline-flex align-items-center justify-content-center"
                                 style="width: 80px; height: 80px;">
                                <i class="bi bi-people fs-3 text-white"></i>
                            </div>
                            <h4 class="mt-2 mb-0"><?php echo number_format($total_residents); ?></h4>
                            <p class="text-muted mb-0">Total Residents</p>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="stat-circle bg-success rounded-circle d-inline-flex align-items-center justify-content-center"
                                 style="width: 80px; height: 80px;">
                                <i class="bi bi-file-earmark-check fs-3 text-white"></i>
                            </div>
                            <h4 class="mt-2 mb-0"><?php echo $certification_stats['completed']; ?></h4>
                            <p class="text-muted mb-0">Completed Certifications</p>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="stat-circle bg-warning rounded-circle d-inline-flex align-items-center justify-content-center"
                                 style="width: 80px; height: 80px;">
                                <i class="bi bi-journal-check fs-3 text-white"></i>
                            </div>
                            <h4 class="mt-2 mb-0"><?php echo $blotter_stats['settled']; ?></h4>
                            <p class="text-muted mb-0">Settled Blotters</p>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="stat-circle bg-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                 style="width: 80px; height: 80px;">
                                <i class="bi bi-shield-check fs-3 text-white"></i>
                            </div>
                            <h4 class="mt-2 mb-0"><?php echo $crime_stats['resolved']; ?></h4>
                            <p class="text-muted mb-0">Resolved Crimes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline-item {
        position: relative;
    }

    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 5px;
    }

    .timeline-content {
        padding-bottom: 15px;
        border-bottom: 1px solid #f1f1f1;
    }

    .timeline-item:last-child .timeline-content {
        border-bottom: none;
        padding-bottom: 0;
    }

    .stat-circle {
        transition: transform 0.3s;
    }

    .stat-circle:hover {
        transform: scale(1.1);
    }

    .border-left-purple {
        border-left: 0.25rem solid #6f42c1 !important;
    }

    .text-purple {
        color: #6f42c1 !important;
    }

    .event-item:hover {
        background-color: #f8f9fa;
    }

    .announcement-item:hover {
        background-color: #f8f9fa;
    }

    .progress {
        height: 0.5rem;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-refresh dashboard every 5 minutes
        setTimeout(function() {
            location.reload();
        }, 300000); // 5 minutes

        // Add animation to stats cards on load
        const statsCards = document.querySelectorAll('.card.border-left-primary, .card.border-left-warning, .card.border-left-success, .card.border-left-danger');
        statsCards.forEach((card, index) => {
            card.style.animationDelay = (index * 0.1) + 's';
            card.classList.add('animate__animated', 'animate__fadeInUp');
        });
    });
</script>
