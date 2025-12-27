<?php
// app/views/profile/edit_profile.php
$old = $_SESSION['old'] ?? $profile ?? [];
unset($_SESSION['old']);
?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Edit Profile</h1>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php echo $_SESSION['error'];
      unset($_SESSION['error']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="card shadow">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Edit Personal Information</h6>
    </div>
    <div class="card-body">
      <form action="index.php?action=profile&sub=update" method="POST">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="full_name" class="form-label">Full Name *</label>
            <input type="text" class="form-control" id="full_name" name="full_name"
                   value="<?php echo htmlspecialchars($old['full_name'] ?? ''); ?>" required>
          </div>

          <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Email Address *</label>
            <input type="email" class="form-control" id="email" name="email"
                   value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" required>
          </div>
        </div>

        <div class="mb-3">
          <label for="address" class="form-label">Address</label>
          <textarea class="form-control" id="address" name="address" rows="2"><?php echo htmlspecialchars($old['address'] ?? ''); ?></textarea>
        </div>

        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="birthdate" class="form-label">Birthdate</label>
            <input type="date" class="form-control" id="birthdate" name="birthdate"
                   value="<?php echo htmlspecialchars($old['birthdate'] ?? ''); ?>">
          </div>

          <div class="col-md-4 mb-3">
            <label for="gender" class="form-label">Gender</label>
            <select class="form-control" id="gender" name="gender">
              <option value="">Select Gender</option>
              <option value="male" <?php echo ($old['gender'] ?? '') == 'male' ? 'selected' : ''; ?>>Male</option>
              <option value="female" <?php echo ($old['gender'] ?? '') == 'female' ? 'selected' : ''; ?>>Female</option>
              <option value="other" <?php echo ($old['gender'] ?? '') == 'other' ? 'selected' : ''; ?>>Other</option>
            </select>
          </div>

          <div class="col-md-4 mb-3">
            <label for="civil_status" class="form-label">Civil Status</label>
            <select class="form-control" id="civil_status" name="civil_status">
              <option value="">Select Status</option>
              <option value="single" <?php echo ($old['civil_status'] ?? '') == 'single' ? 'selected' : ''; ?>>Single</option>
              <option value="married" <?php echo ($old['civil_status'] ?? '') == 'married' ? 'selected' : ''; ?>>Married</option>
              <option value="widowed" <?php echo ($old['civil_status'] ?? '') == 'widowed' ? 'selected' : ''; ?>>Widowed</option>
              <option value="separated" <?php echo ($old['civil_status'] ?? '') == 'separated' ? 'selected' : ''; ?>>Separated</option>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="contact_number" class="form-label">Contact Number</label>
            <input type="text" class="form-control" id="contact_number" name="contact_number"
                   value="<?php echo htmlspecialchars($old['contact_number'] ?? ''); ?>">
          </div>

          <div class="col-md-6 mb-3">
            <label for="occupation" class="form-label">Occupation</label>
            <input type="text" class="form-control" id="occupation" name="occupation"
                   value="<?php echo htmlspecialchars($old['occupation'] ?? ''); ?>">
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i>Update Profile
          </button>
          <a href="index.php?action=profile" class="btn btn-secondary">
            <i class="bi bi-x-circle me-1"></i>Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
