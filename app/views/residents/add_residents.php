<?php
// app/views/residents/add_residents.php
// NO header/footer includes here - they're handled by BaseController
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
?>

<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?php echo $title ?? 'Add New Resident'; ?></h1>

  <!-- Success/Error Messages -->
  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php echo $_SESSION['error'];
      unset($_SESSION['error']); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="row">
    <div class="col-md-8">
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Resident Information</h6>
        </div>
        <div class="card-body">
          <form action="index.php?action=residents&sub=create" method="POST">
            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="full_name" name="full_name"
                  value="<?php echo htmlspecialchars($old['full_name'] ?? ''); ?>" required>
                <div class="form-text">Enter the complete name (First Name, Middle Name, Last Name)</div>
              </div>

              <div class="col-md-12 mb-3">
                <label for="address" class="form-label">Complete Address <span class="text-danger">*</span></label>
                <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($old['address'] ?? ''); ?></textarea>
                <div class="form-text">House No., Street, Barangay, City/Municipality</div>
              </div>

              <div class="col-md-6 mb-3">
                <label for="birthdate" class="form-label">Birthdate <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="birthdate" name="birthdate"
                  value="<?php echo htmlspecialchars($old['birthdate'] ?? ''); ?>" required>
                <div class="form-text">Date of birth</div>
              </div>

              <div class="col-md-6 mb-3">
                <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                <select class="form-select" id="gender" name="gender" required>
                  <option value="">Select Gender</option>
                  <option value="male" <?php echo ($old['gender'] ?? '') == 'male' ? 'selected' : ''; ?>>Male</option>
                  <option value="female" <?php echo ($old['gender'] ?? '') == 'female' ? 'selected' : ''; ?>>Female</option>
                  <option value="other" <?php echo ($old['gender'] ?? '') == 'other' ? 'selected' : ''; ?>>Other</option>
                </select>
              </div>

              <div class="col-md-6 mb-3">
                <label for="civil_status" class="form-label">Civil Status <span class="text-danger">*</span></label>
                <select class="form-select" id="civil_status" name="civil_status" required>
                  <option value="">Select Status</option>
                  <option value="single" <?php echo ($old['civil_status'] ?? '') == 'single' ? 'selected' : ''; ?>>Single</option>
                  <option value="married" <?php echo ($old['civil_status'] ?? '') == 'married' ? 'selected' : ''; ?>>Married</option>
                  <option value="widowed" <?php echo ($old['civil_status'] ?? '') == 'widowed' ? 'selected' : ''; ?>>Widowed</option>
                  <option value="divorced" <?php echo ($old['civil_status'] ?? '') == 'divorced' ? 'selected' : ''; ?>>Divorced</option>
                </select>
              </div>

              <div class="col-md-6 mb-3">
                <label for="contact_number" class="form-label">Contact Number</label>
                <input type="tel" class="form-control" id="contact_number" name="contact_number"
                  value="<?php echo htmlspecialchars($old['contact_number'] ?? ''); ?>"
                  pattern="[0-9]{11}" placeholder="09XXXXXXXXX">
                <div class="form-text">11-digit mobile number (optional)</div>
              </div>

              <div class="col-md-12 mb-3">
                <label for="occupation" class="form-label">Occupation</label>
                <input type="text" class="form-control" id="occupation" name="occupation"
                  value="<?php echo htmlspecialchars($old['occupation'] ?? ''); ?>">
                <div class="form-text">Current job/profession (optional)</div>
              </div>
            </div>

            <div class="mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-person-plus me-1"></i>Add Resident
              </button>
              <a href="index.php?action=residents" class="btn btn-secondary">
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
          <h6 class="m-0 font-weight-bold text-primary">Quick Tips</h6>
        </div>
        <div class="card-body">
          <div class="alert alert-info">
            <h6><i class="bi bi-info-circle me-2"></i>Required Fields</h6>
            <p class="mb-2">Fields marked with <span class="text-danger">*</span> are required.</p>
          </div>

          <div class="alert alert-warning">
            <h6><i class="bi bi-exclamation-triangle me-2"></i>Data Accuracy</h6>
            <p class="mb-0">Please ensure all information is accurate before submitting.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Set maximum date to today for birthdate
  document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('birthdate').setAttribute('max', today);

    // Auto-format contact number
    const contactInput = document.getElementById('contact_number');
    contactInput.addEventListener('input', function(e) {
      // Remove non-numeric characters
      this.value = this.value.replace(/\D/g, '');

      // Limit to 11 characters
      if (this.value.length > 11) {
        this.value = this.value.slice(0, 11);
      }
    });
  });
</script>
