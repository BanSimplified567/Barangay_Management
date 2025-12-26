<?php
// app/views/my_profile.php
include '../header.php';

// Get profile data from controller
$profile = $profile ?? [];
?>

<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">My Profile</h1>

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

  <div class="row">
    <!-- Left Column - Profile Information -->
    <div class="col-md-8">
      <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
          <h6 class="m-0 font-weight-bold text-primary">Personal Information</h6>
          <a href="index.php?action=profile&sub=edit" class="btn btn-sm btn-primary">
            <i class="bi bi-pencil me-1"></i>Edit Profile
          </a>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label text-muted">Full Name</label>
                <p class="form-control-plaintext fw-bold"><?php echo htmlspecialchars($profile['full_name'] ?? 'Not set'); ?></p>
              </div>

              <div class="mb-3">
                <label class="form-label text-muted">Email Address</label>
                <p class="form-control-plaintext"><?php echo htmlspecialchars($profile['email'] ?? 'Not set'); ?></p>
              </div>

              <div class="mb-3">
                <label class="form-label text-muted">Account Role</label>
                <p>
                  <span class="badge bg-<?php
                                        echo ($profile['role'] ?? '') == 'admin' ? 'danger' : (($profile['role'] ?? '') == 'staff' ? 'primary' : 'success');
                                        ?>">
                    <?php echo ucfirst(htmlspecialchars($profile['role'] ?? 'Not set')); ?>
                  </span>
                </p>
              </div>

              <div class="mb-3">
                <label class="form-label text-muted">Account Created</label>
                <p class="form-control-plaintext"><?php echo date('M d, Y', strtotime($profile['created_at'] ?? '')); ?></p>
              </div>
            </div>

            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label text-muted">Address</label>
                <p class="form-control-plaintext"><?php echo htmlspecialchars($profile['address'] ?? 'Not set'); ?></p>
              </div>

              <div class="mb-3">
                <label class="form-label text-muted">Contact Number</label>
                <p class="form-control-plaintext"><?php echo htmlspecialchars($profile['contact_number'] ?? 'Not set'); ?></p>
              </div>

              <div class="mb-3">
                <label class="form-label text-muted">Occupation</label>
                <p class="form-control-plaintext"><?php echo htmlspecialchars($profile['occupation'] ?? 'Not set'); ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Additional Information Card -->
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Additional Information</h6>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-4">
              <div class="mb-3">
                <label class="form-label text-muted">Birthdate</label>
                <p class="form-control-plaintext">
                  <?php
                  if (!empty($profile['birthdate']) && $profile['birthdate'] != '0000-00-00') {
                    echo date('M d, Y', strtotime($profile['birthdate']));
                  } else {
                    echo 'Not set';
                  }
                  ?>
                </p>
              </div>
            </div>

            <div class="col-md-4">
              <div class="mb-3">
                <label class="form-label text-muted">Gender</label>
                <p class="form-control-plaintext">
                  <?php if (!empty($profile['gender'])): ?>
                    <span class="badge bg-<?php echo $profile['gender'] == 'male' ? 'primary' : ($profile['gender'] == 'female' ? 'danger' : 'secondary'); ?>">
                      <?php echo ucfirst(htmlspecialchars($profile['gender'])); ?>
                    </span>
                  <?php else: ?>
                    Not set
                  <?php endif; ?>
                </p>
              </div>
            </div>

            <div class="col-md-4">
              <div class="mb-3">
                <label class="form-label text-muted">Civil Status</label>
                <p class="form-control-plaintext">
                  <?php if (!empty($profile['civil_status'])): ?>
                    <span class="badge bg-info text-dark">
                      <?php echo ucfirst(htmlspecialchars($profile['civil_status'])); ?>
                    </span>
                  <?php else: ?>
                    Not set
                  <?php endif; ?>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column - Actions & Quick Stats -->
    <div class="col-md-4">
      <!-- Change Password Card -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Account Security</h6>
        </div>
        <div class="card-body">
          <p class="card-text">Keep your account secure by regularly updating your password.</p>
          <a href="index.php?action=profile&sub=change_password" class="btn btn-warning btn-block">
            <i class="bi bi-shield-lock me-1"></i>Change Password
          </a>
        </div>
      </div>

      <!-- Account Status Card -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Account Status</h6>
        </div>
        <div class="card-body">
          <div class="mb-2">
            <i class="bi bi-check-circle-fill text-success me-2"></i>
            <span>Account Active</span>
          </div>
          <div class="mb-2">
            <i class="bi bi-person-check-fill text-primary me-2"></i>
            <span>Verified User</span>
          </div>
          <div class="mb-2">
            <i class="bi bi-calendar-check-fill text-info me-2"></i>
            <span>Member since: <?php echo date('M Y', strtotime($profile['created_at'] ?? date('Y-m-d'))); ?></span>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
        </div>
        <div class="card-body">
          <div class="d-grid gap-2">
            <?php if ($_SESSION['role'] === 'resident'): ?>
              <a href="index.php?action=certifications&sub=request" class="btn btn-outline-primary">
                <i class="bi bi-file-earmark-plus me-1"></i>Request Certificate
              </a>
            <?php endif; ?>

            <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'staff'): ?>
              <a href="index.php?action=dashboard" class="btn btn-outline-primary">
                <i class="bi bi-speedometer2 me-1"></i>Dashboard
              </a>
            <?php endif; ?>

            <a href="index.php?action=logout" class="btn btn-outline-danger">
              <i class="bi bi-box-arrow-right me-1"></i>Logout
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../footer.php'; ?>
