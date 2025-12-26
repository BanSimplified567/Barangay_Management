<?php
// app/views/profile/edit_profile.php
include '../header.php';
$old = $_SESSION['old'] ?? $profile ?? [];
unset($_SESSION['old']);
?>

<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Edit Profile</h1>

  <div class="row">
    <div class="col-md-8">
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Update Profile Information</h6>
        </div>
        <div class="card-body">
          <form action="index.php?action=profile&sub=update" method="POST">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="full_name" name="full_name"
                  value="<?php echo htmlspecialchars($old['full_name'] ?? ''); ?>" required>
              </div>

              <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email"
                  value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" required>
              </div>

              <div class="col-md-12 mb-3">
                <label for="address" class="form-label">Complete Address</label>
                <textarea class="form-control" id="address" name="address" rows="2"><?php echo htmlspecialchars($old['address'] ?? ''); ?></textarea>
              </div>

              <div class="col-md-6 mb-3">
                <label for="birthdate" class="form-label">Birthdate</label>
                <input type="date" class="form-control" id="birthdate" name="birthdate"
                  value="<?php echo htmlspecialchars($old['birthdate'] ?? ''); ?>">
              </div>

              <div class="col-md-6 mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-select" id="gender" name="gender">
                  <option value="">Select Gender</option>
                  <option value="male" <?php echo ($old['gender'] ?? '') == 'male' ? 'selected' : ''; ?>>Male</option>
                  <option value="female" <?php echo ($old['gender'] ?? '') == 'female' ? 'selected' : ''; ?>>Female</option>
                  <option value="other" <?php echo ($old['gender'] ?? '') == 'other' ? 'selected' : ''; ?>>Other</option>
                </select>
              </div>

              <div class="col-md-6 mb-3">
                <label for="contact_number" class="form-label">Contact Number</label>
                <input type="tel" class="form-control" id="contact_number" name="contact_number"
                  value="<?php echo htmlspecialchars($old['contact_number'] ?? ''); ?>">
              </div>

              <div class="col-md-6 mb-3">
                <label for="occupation" class="form-label">Occupation</label>
                <input type="text" class="form-control" id="occupation" name="occupation"
                  value="<?php echo htmlspecialchars($old['occupation'] ?? ''); ?>">
              </div>

              <div class="col-md-6 mb-3">
                <label for="civil_status" class="form-label">Civil Status</label>
                <select class="form-select" id="civil_status" name="civil_status">
                  <option value="">Select Status</option>
                  <option value="single" <?php echo ($old['civil_status'] ?? '') == 'single' ? 'selected' : ''; ?>>Single</option>
                  <option value="married" <?php echo ($old['civil_status'] ?? '') == 'married' ? 'selected' : ''; ?>>Married</option>
                  <option value="widowed" <?php echo ($old['civil_status'] ?? '') == 'widowed' ? 'selected' : ''; ?>>Widowed</option>
                  <option value="divorced" <?php echo ($old['civil_status'] ?? '') == 'divorced' ? 'selected' : ''; ?>>Divorced</option>
                </select>
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

    <div class="col-md-4">
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Profile Tips</h6>
        </div>
        <div class="card-body">
          <ul>
            <li>Keep your information up to date</li>
            <li>Use a valid email address</li>
            <li>Provide accurate personal details</li>
            <li>Contact number helps for verification</li>
            <li>Profile updates are logged for security</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../footer.php'; ?>
