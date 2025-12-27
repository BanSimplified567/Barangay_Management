<?php
// app/views/profile/change_password.php
include '../layout/header.php';
?>

<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Change Password</h1>

  <div class="row">
    <div class="col-md-8">
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Update Password</h6>
        </div>
        <div class="card-body">
          <form action="index.php?action=profile&sub=update_password" method="POST" id="passwordForm">
            <div class="mb-3">
              <label for="current_password" class="form-label">Current Password <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="password" class="form-control" id="current_password" name="current_password" required>
                <button class="btn btn-outline-secondary" type="button" id="toggleCurrentPassword">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>

            <div class="mb-3">
              <label for="new_password" class="form-label">New Password <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6">
                <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
              <div class="form-text">Must be at least 6 characters long.</div>
              <div class="password-strength mt-2">
                <small>Password strength: <span id="strengthText" class="text-muted">None</span></small>
                <div class="progress" style="height: 5px;">
                  <div id="strengthBar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label for="confirm_password" class="form-label">Confirm New Password <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6">
                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
              <div id="passwordMatch" class="form-text"></div>
            </div>

            <div class="mt-4">
              <button type="submit" class="btn btn-primary" id="submitBtn">
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

    <div class="col-md-4">
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Password Requirements</h6>
        </div>
        <div class="card-body">
          <ul>
            <li>Minimum 6 characters</li>
            <li>Use a mix of letters and numbers</li>
            <li>Avoid common words</li>
            <li>Don't reuse old passwords</li>
            <li>Change password regularly</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Password visibility toggles
    function setupPasswordToggle(buttonId, fieldId) {
      const button = document.getElementById(buttonId);
      const field = document.getElementById(fieldId);

      if (button && field) {
        button.addEventListener('click', function() {
          const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
          field.setAttribute('type', type);
          const icon = this.querySelector('i');
          if (icon) {
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
          }
        });
      }
    }

    setupPasswordToggle('toggleCurrentPassword', 'current_password');
    setupPasswordToggle('toggleNewPassword', 'new_password');
    setupPasswordToggle('toggleConfirmPassword', 'confirm_password');

    // Password strength checker
    const newPasswordField = document.getElementById('new_password');
    const confirmPasswordField = document.getElementById('confirm_password');
    const strengthText = document.getElementById('strengthText');
    const strengthBar = document.getElementById('strengthBar');
    const passwordMatch = document.getElementById('passwordMatch');
    const submitBtn = document.getElementById('submitBtn');

    if (newPasswordField && confirmPasswordField) {
      function checkPasswordStrength(password) {
        let score = 0;

        // Length check
        if (password.length >= 6) score += 1;
        if (password.length >= 8) score += 1;

        // Character variety checks
        if (/[A-Z]/.test(password)) score += 1;
        if (/[0-9]/.test(password)) score += 1;
        if (/[^A-Za-z0-9]/.test(password)) score += 1;

        return score;
      }

      function updatePasswordStrength() {
        const password = newPasswordField.value;
        const strength = checkPasswordStrength(password);

        const levels = [{
            text: 'Very Weak',
            class: 'danger',
            width: '20%'
          },
          {
            text: 'Weak',
            class: 'warning',
            width: '40%'
          },
          {
            text: 'Fair',
            class: 'info',
            width: '60%'
          },
          {
            text: 'Good',
            class: 'primary',
            width: '80%'
          },
          {
            text: 'Strong',
            class: 'success',
            width: '100%'
          }
        ];

        const level = levels[Math.min(strength, 4)];

        if (strengthText) {
          strengthText.textContent = level.text;
          strengthText.className = `text-${level.class}`;
        }

        if (strengthBar) {
          strengthBar.style.width = level.width;
          strengthBar.className = `progress-bar bg-${level.class}`;
        }
      }

      function checkPasswordMatch() {
        const password = newPasswordField.value;
        const confirm = confirmPasswordField.value;

        if (passwordMatch) {
          if (confirm) {
            if (password === confirm) {
              passwordMatch.innerHTML = '<span class="text-success">✓ Passwords match</span>';
            } else {
              passwordMatch.innerHTML = '<span class="text-danger">✗ Passwords do not match</span>';
            }
          } else {
            passwordMatch.innerHTML = '';
          }
        }
      }

      newPasswordField.addEventListener('input', function() {
        updatePasswordStrength();
        checkPasswordMatch();
      });

      confirmPasswordField.addEventListener('input', checkPasswordMatch);

      // Form validation
      const form = document.getElementById('passwordForm');
      if (form) {
        form.addEventListener('submit', function(e) {
          const password = newPasswordField.value;
          const confirm = confirmPasswordField.value;

          if (password !== confirm) {
            e.preventDefault();
            alert('Passwords do not match!');
            return;
          }

          if (password.length < 6) {
            e.preventDefault();
            alert('Password must be at least 6 characters long.');
            return;
          }

          // Optional: Show loading state
          if (submitBtn) {
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Changing Password...';
            submitBtn.disabled = true;
          }
        });
      }
    }

    // Auto-focus on current password field
    const currentPasswordField = document.getElementById('current_password');
    if (currentPasswordField) {
      currentPasswordField.focus();
    }
  });
</script>

<?php include '../layout/footer.php'; ?>
