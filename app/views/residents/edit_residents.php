<?php
// app/views/residents/edit_residents.php
// NO header/footer includes here
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
?>

<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?php echo $title ?? 'Edit Resident'; ?></h1>

  <div class="row">
    <div class="col-md-8">
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Edit Resident Information</h6>
        </div>
        <div class="card-body">
          <form action="index.php?action=residents&sub=update" method="POST">
            <input type="hidden" name="id" value="<?php echo $resident['id']; ?>">
            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="full_name" name="full_name"
                  value="<?php echo htmlspecialchars($old['full_name'] ?? $resident['full_name']); ?>" required>
              </div>

              <div class="col-md-12 mb-3">
                <label for="address" class="form-label">Complete Address <span class="text-danger">*</span></label>
                <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($old['address'] ?? $resident['address']); ?></textarea>
              </div>

              <div class="col-md-6 mb-3">
                <label for="birthdate" class="form-label">Birthdate <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="birthdate" name="birthdate"
                  value="<?php echo htmlspecialchars($old['birthdate'] ?? $resident['birthdate']); ?>" required>
              </div>

              <div class="col-md-6 mb-3">
                <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                <select class="form-select" id="gender" name="gender" required>
                  <option value="">Select Gender</option>
                  <option value="male" <?php echo ($old['gender'] ?? $resident['gender']) == 'male' ? 'selected' : ''; ?>>Male</option>
                  <option value="female" <?php echo ($old['gender'] ?? $resident['gender']) == 'female' ? 'selected' : ''; ?>>Female</option>
                  <option value="other" <?php echo ($old['gender'] ?? $resident['gender']) == 'other' ? 'selected' : ''; ?>>Other</option>
                </select>
              </div>

              <div class="col-md-6 mb-3">
                <label for="civil_status" class="form-label">Civil Status <span class="text-danger">*</span></label>
                <select class="form-select" id="civil_status" name="civil_status" required>
                  <option value="">Select Status</option>
                  <option value="single" <?php echo ($old['civil_status'] ?? $resident['civil_status']) == 'single' ? 'selected' : ''; ?>>Single</option>
                  <option value="married" <?php echo ($old['civil_status'] ?? $resident['civil_status']) == 'married' ? 'selected' : ''; ?>>Married</option>
                  <option value="widowed" <?php echo ($old['civil_status'] ?? $resident['civil_status']) == 'widowed' ? 'selected' : ''; ?>>Widowed</option>
                  <option value="divorced" <?php echo ($old['civil_status'] ?? $resident['civil_status']) == 'divorced' ? 'selected' : ''; ?>>Divorced</option>
                </select>
              </div>

              <div class="col-md-6 mb-3">
                <label for="contact_number" class="form-label">Contact Number</label>
                <input type="tel" class="form-control" id="contact_number" name="contact_number"
                  value="<?php echo htmlspecialchars($old['contact_number'] ?? $resident['contact_number']); ?>">
              </div>

              <div class="col-md-12 mb-3">
                <label for="occupation" class="form-label">Occupation</label>
                <input type="text" class="form-control" id="occupation" name="occupation"
                  value="<?php echo htmlspecialchars($old['occupation'] ?? $resident['occupation'] ?? ''); ?>">
              </div>
            </div>

            <div class="mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>Update Resident
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
          <h6 class="m-0 font-weight-bold text-primary">Resident Details</h6>
        </div>
        <div class="card-body">
          <p><strong>Resident ID:</strong> <?php echo $resident['id']; ?></p>
          <p><strong>Current Status:</strong>
            <span class="badge bg-<?php echo $resident['gender'] == 'male' ? 'primary' : ($resident['gender'] == 'female' ? 'danger' : 'secondary'); ?>">
              <?php echo ucfirst($resident['gender']); ?>
            </span>
          </p>
          <p><strong>Civil Status:</strong>
            <span class="badge bg-info text-dark">
              <?php echo ucfirst($resident['civil_status']); ?>
            </span>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
