<?php
// app/views/profile/change_password.php
?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Change Password</h1>

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

  <div class="card shadow">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Update Your Password</h6>
    </div>
    <div class="card-body">
      <form action="index.php?action=profile&sub=update_password" method="POST">
        <div class="mb-3">
          <label for="current_password" class="form-label">Current Password *</label>
          <input type="password" class="form-control" id="current_password" name="current_password" required>
          <div class="form-text">Enter your current password for verification</div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="new_password" class="form-label">New Password *</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
            <div class="form-text">Minimum 6 characters</div>
          </div>

          <div class="col-md-6 mb-3">
            <label for="confirm_password" class="form-label">Confirm New Password *</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            <div class="form-text">Re-enter your new password</div>
          </div>
        </div>

        <!-- Password Requirements -->
        <div class="alert alert-info">
          <h6 class="alert-heading"><i class="bi bi-shield-check me-1"></i>Password Requirements</h6>
          <ul class="mb-0">
            <li>Must be at least 6 characters long</li>
            <li>Should contain a mix of letters and numbers</li>
            <li>Avoid using easily guessable passwords</li>
            <li>Do not reuse old passwords</li>
          </ul>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-shield-lock me-1"></i>Change Password
          </button>
          <a href="index.php?action=profile" class="btn btn-secondary">
            <i class="bi bi-x-circle me-1"></i>Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');
    const currentPassword = document.getElementById('current_password');

    // Real-time password validation
    newPassword.addEventListener('input', function() {
      validatePassword();
    });

    confirmPassword.addEventListener('input', function() {
      validatePassword();
    });

    form.addEventListener('submit', function(e) {
      if (!validateForm()) {
        e.preventDefault();
      }
    });

    function validatePassword() {
      const password = newPassword.value;
      const confirm = confirmPassword.value;
      const passwordError = document.getElementById('password-error');

      // Remove existing error message
      if (passwordError) {
        passwordError.remove();
      }

      // Check password length
      if (password.length > 0 && password.length < 6) {
        showError(newPassword, 'Password must be at least 6 characters long');
        return false;
      }

      // Check if passwords match
      if (password !== confirm && confirm.length > 0) {
        showError(confirmPassword, 'Passwords do not match');
        return false;
      }

      // Clear errors if everything is valid
      clearError(newPassword);
      clearError(confirmPassword);
      return true;
    }

    function validateForm() {
      let isValid = true;

      // Validate current password
      if (currentPassword.value.trim() === '') {
        showError(currentPassword, 'Current password is required');
        isValid = false;
      } else {
        clearError(currentPassword);
      }

      // Validate new password
      if (newPassword.value.trim() === '') {
        showError(newPassword, 'New password is required');
        isValid = false;
      } else if (newPassword.value.length < 6) {
        showError(newPassword, 'Password must be at least 6 characters long');
        isValid = false;
      } else {
        clearError(newPassword);
      }

      // Validate confirm password
      if (confirmPassword.value.trim() === '') {
        showError(confirmPassword, 'Please confirm your new password');
        isValid = false;
      } else if (newPassword.value !== confirmPassword.value) {
        showError(confirmPassword, 'Passwords do not match');
        isValid = false;
      } else {
        clearError(confirmPassword);
      }

      return isValid;
    }

    function showError(input, message) {
      clearError(input);
      const errorDiv = document.createElement('div');
      errorDiv.id = 'password-error';
      errorDiv.className = 'invalid-feedback d-block';
      errorDiv.textContent = message;
      input.classList.add('is-invalid');
      input.parentNode.appendChild(errorDiv);
    }

    function clearError(input) {
      input.classList.remove('is-invalid');
      const errorDiv = input.parentNode.querySelector('.invalid-feedback');
      if (errorDiv) {
        errorDiv.remove();
      }
    }
  });
</script>

<style>
  .is-invalid {
    border-color: #dc3545;
  }

  .invalid-feedback {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
  }

  .form-text {
    font-size: 0.875rem;
    color: #6c757d;
  }
</style>
