<?php
// app/views/announcements.php
include 'header.php';
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Barangay Announcements</h1>

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
        <div class="col-md-12">
            <div class="card border-left-primary shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Announcements
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo count($announcements ?? []); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-megaphone fs-2 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Announcement Button -->
    <div class="mb-3">
        <a href="index.php?action=announcements&sub=add" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Post New Announcement
        </a>
    </div>

    <!-- Announcements List -->
    <div class="row">
        <?php if (!empty($announcements)): ?>
            <?php foreach ($announcements as $ann): ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary"><?php echo htmlspecialchars($ann['title']); ?></h6>
                            <div>
                                <a href="index.php?action=announcements&sub=edit&id=<?php echo $ann['id']; ?>"
                                   class="btn btn-sm btn-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="index.php?action=announcements&sub=delete&id=<?php echo $ann['id']; ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this announcement?')"
                                   title="Delete">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <p class="card-text"><?php echo nl2br(htmlspecialchars($ann['content'])); ?></p>
                            </div>
                        </div>
                        <div class="card-footer text-muted">
                            <div class="row">
                                <div class="col-md-6">
                                    <small>
                                        <i class="bi bi-person me-1"></i>
                                        Posted by: <?php echo htmlspecialchars($ann['posted_by_name']); ?>
                                    </small>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <small>
                                        <i class="bi bi-calendar me-1"></i>
                                        <?php echo date('M d, Y', strtotime($ann['post_date'])); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-megaphone display-1 text-muted"></i>
                        <h4 class="mt-3">No announcements yet</h4>
                        <p class="text-muted">Be the first to post an announcement!</p>
                        <a href="index.php?action=announcements&sub=add" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>Post First Announcement
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination (if needed for many announcements) -->
    <?php if (count($announcements ?? []) > 6): ?>
        <nav aria-label="Announcements navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
