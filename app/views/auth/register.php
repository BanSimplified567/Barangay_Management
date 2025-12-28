<?php
// app/views/Auth/register.php
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Barangay Management System | Register</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --ph-blue: #003893;
      --ph-red: #ce1126;
      --ph-yellow: #fcd116;
      --ph-dark-blue: #002a6e;
      --ph-light-blue: #e8f1ff;
    }

    body {
      background: linear-gradient(135deg,
          rgba(0, 56, 147, 0.05) 0%,
          rgba(206, 17, 38, 0.05) 100%),
        url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="white"/><path d="M0,0 L100,100 M100,0 L0,100" stroke="rgba(0,56,147,0.03)" stroke-width="2"/></svg>');
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      padding: 20px;
    }

    .register-grid-container {
      max-width: 1100px;
      width: 100%;
      background-color: white;
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0, 56, 147, 0.15);
      overflow: hidden;
      animation: fadeInUp 0.6s ease-out;
      border: 1px solid rgba(0, 56, 147, 0.1);
      display: grid;
      grid-template-columns: 1fr 1fr;
      min-height: 700px;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Left Side - Welcome/Info Section */
    .register-welcome {
      background: linear-gradient(135deg, var(--ph-blue) 0%, var(--ph-dark-blue) 100%);
      color: white;
      padding: 50px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .register-welcome::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
      background-size: 20px 20px;
      opacity: 0.1;
    }

    .welcome-logo-container {
      position: relative;
      width: 140px;
      height: 140px;
      margin-bottom: 40px;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      border: 5px solid var(--ph-yellow);
      z-index: 1;
    }

    .welcome-logo-container i {
      font-size: 3.5rem;
      color: var(--ph-blue);
    }

    .welcome-text {
      position: relative;
      z-index: 1;
    }

    .welcome-title {
      font-size: 2.2rem;
      font-weight: 700;
      margin-bottom: 15px;
      letter-spacing: 0.5px;
    }

    .barangay-name-large {
      font-size: clamp(1.3rem, 1.1rem + 0.8vw, 1.4rem);
      opacity: 0.95;
      margin-bottom: 30px;
      font-weight: 400;
    }

    .welcome-benefits {
      margin-top: 50px;
      text-align: left;
      width: 100%;
      max-width: 350px;
    }

    .benefit-item {
      display: flex;
      align-items: center;
      margin-bottom: 25px;
      padding: 15px 20px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      backdrop-filter: blur(5px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      transition: transform 0.3s ease;
    }

    .benefit-item:hover {
      transform: translateX(5px);
      background: rgba(255, 255, 255, 0.15);
    }

    .benefit-icon {
      font-size: 1.8rem;
      color: var(--ph-yellow);
      margin-right: 20px;
      width: 50px;
      text-align: center;
    }

    .benefit-text {
      flex: 1;
    }

    .benefit-title {
      font-weight: 600;
      margin-bottom: 5px;
      font-size: 1.1rem;
    }

    .benefit-desc {
      opacity: 0.9;
      font-size: 0.9rem;
      line-height: 1.4;
    }

    /* Right Side - Registration Form */
    .register-form-container {
      padding: 60px 50px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      background-color: white;
      overflow-y: auto;
      max-height: 700px;
    }

    .form-header {
      text-align: center;
      margin-bottom: 40px;
    }

    .form-title {
      font-size: 2rem;
      color: var(--ph-dark-blue);
      font-weight: 700;
      margin-bottom: 10px;
    }

    .form-subtitle {
      color: #666;
      font-size: 1rem;
    }

    .alert {
      border-radius: 10px;
      border: none;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      margin-bottom: 25px;
    }

    .alert-success {
      background-color: #e8f7ef;
      color: #006442;
      border-left: 4px solid #28a745;
    }

    .alert-danger {
      background-color: #ffe6e9;
      color: #b00020;
      border-left: 4px solid var(--ph-red);
    }

    .form-label {
      color: var(--ph-dark-blue);
      font-weight: 600;
      margin-bottom: 8px;
      font-size: 0.95rem;
    }

    .input-group {
      box-shadow: 0 3px 6px rgba(0, 56, 147, 0.05);
      border-radius: 10px;
      overflow: hidden;
      border: 2px solid #e0e6f0;
      transition: all 0.3s ease;
    }

    .input-group:focus-within {
      border-color: var(--ph-blue);
      box-shadow: 0 0 0 0.25rem rgba(0, 56, 147, 0.15);
    }

    .welcome-logo-container img {
      width: 80%;
      height: auto;
      border-radius: 50%;
      object-fit: cover;
    }

    .form-control {
      border: none;
      padding: 14px 15px;
      font-size: 1rem;
    }

    .form-control:focus {
      box-shadow: none;
    }

    .input-group-text {
      background-color: white;
      border: none;
      padding: 0 15px;
      cursor: pointer;
      color: var(--ph-blue);
    }

    .btn-register {
      background: linear-gradient(135deg, var(--ph-blue) 0%, var(--ph-dark-blue) 100%);
      color: white;
      width: 100%;
      padding: 15px;
      border: none;
      border-radius: 10px;
      font-weight: 600;
      font-size: 1.1rem;
      transition: all 0.3s ease;
      margin-top: 10px;
      box-shadow: 0 5px 15px rgba(0, 56, 147, 0.2);
      letter-spacing: 0.5px;
    }

    .btn-register:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(0, 56, 147, 0.3);
    }

    .btn-register:active {
      transform: translateY(-1px);
    }

    .register-footer {
      text-align: center;
      margin-top: 30px;
      padding-top: 25px;
      border-top: 1px solid #eaeff5;
    }

    .register-footer a {
      color: var(--ph-blue);
      text-decoration: none;
      font-weight: 500;
      transition: all 0.2s ease;
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }

    .register-footer a:hover {
      color: var(--ph-red);
      text-decoration: underline;
    }

    .register-footer p {
      margin-bottom: 12px;
      color: #666;
    }

    .form-check {
      margin-top: 15px;
    }

    .form-check-input:checked {
      background-color: var(--ph-blue);
      border-color: var(--ph-blue);
    }

    .form-check-label {
      color: #555;
    }

    .form-text {
      color: #6c757d;
      font-size: 0.875rem;
      margin-top: 5px;
    }

    .password-strength {
      margin-top: 5px;
      font-size: 0.875rem;
    }

    /* Password strength indicators */
    .strength-weak {
      color: #dc3545;
    }

    .strength-fair {
      color: #fd7e14;
    }

    .strength-good {
      color: #20c997;
    }

    .strength-strong {
      color: #198754;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
      .register-grid-container {
        grid-template-columns: 1fr;
        max-width: 600px;
        min-height: auto;
      }

      .register-welcome {
        padding: 40px 30px;
      }

      .welcome-title {
        font-size: 1.8rem;
      }

      .register-form-container {
        padding: 40px 35px;
        max-height: none;
      }
    }

    @media (max-width: 576px) {
      .register-grid-container {
        border-radius: 15px;
      }

      .register-welcome {
        padding: 30px 20px;
      }

      .welcome-logo-container {
        width: 120px;
        height: 120px;
        margin-bottom: 30px;
      }

      .welcome-logo-container i {
        font-size: 2.8rem;
      }

      .welcome-title {
        font-size: clamp(1.3rem, 1.1rem + 0.8vw, 1.4rem);
      }

      .register-form-container {
        padding: 30px 25px;
      }

      .form-title {
        font-size: clamp(1.3rem, 1.1rem + 0.8vw, 1.4rem);
      }

      body {
        padding: 15px;
      }
    }

    /* Custom animations */
    .pulse {
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% {
        box-shadow: 0 0 0 0 rgba(0, 56, 147, 0.2);
      }

      70% {
        box-shadow: 0 0 0 10px rgba(0, 56, 147, 0);
      }

      100% {
        box-shadow: 0 0 0 0 rgba(0, 56, 147, 0);
      }
    }

    /* Benefit icons animation */
    @keyframes float {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-5px);
      }
    }

    .benefit-icon {
      animation: float 3s ease-in-out infinite;
    }

    .benefit-item:nth-child(2) .benefit-icon {
      animation-delay: 0.5s;
    }

    .benefit-item:nth-child(3) .benefit-icon {
      animation-delay: 1s;
    }

    .benefit-item:nth-child(4) .benefit-icon {
      animation-delay: 1.5s;
    }

    /* Modal styles */
    .modal-content {
      border-radius: 15px;
      border: none;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
      background: linear-gradient(135deg, var(--ph-blue) 0%, var(--ph-dark-blue) 100%);
      color: white;
      border-bottom: none;
      border-radius: 15px 15px 0 0;
    }

    .modal-title {
      font-weight: 600;
    }

    .modal-body ul {
      padding-left: 20px;
    }

    .modal-body li {
      margin-bottom: 10px;
      color: #555;
    }
  </style>
</head>

<body>
  <div class="register-grid-container">
    <!-- Left Column: Welcome/Benefits Section -->
    <div class="register-welcome">
      <div class="welcome-logo-container">
        <img src="../assets/Sibonga.jpg" alt="Sibonga Barangay Seal">
      </div> <!-- FIXED: Added closing div here -->

      <div class="welcome-text">
        <h1 class="welcome-title">Join Our Community</h1>
        <p class="barangay-name-large">Barangay Sibonga Management System</p>
      </div>

      <div class="welcome-benefits">
        <div class="benefit-item">
          <div class="benefit-icon">
            <i class="bi bi-shield-check"></i>
          </div>
          <div class="benefit-text">
            <div class="benefit-title">Secure Registration</div>
            <div class="benefit-desc">Your personal information is protected with government-grade security</div>
          </div>
        </div>

        <div class="benefit-item">
          <div class="benefit-icon">
            <i class="bi bi-speedometer2"></i>
          </div>
          <div class="benefit-text">
            <div class="benefit-title">Quick Access</div>
            <div class="benefit-desc">Fast-track barangay services and document processing</div>
          </div>
        </div>

        <div class="benefit-item">
          <div class="benefit-icon">
            <i class="bi bi-bell-fill"></i>
          </div>
          <div class="benefit-text">
            <div class="benefit-title">Stay Updated</div>
            <div class="benefit-desc">Receive important barangay announcements and updates</div>
          </div>
        </div>

        <div class="benefit-item">
          <div class="benefit-icon">
            <i class="bi bi-file-earmark-text-fill"></i>
          </div>
          <div class="benefit-text">
            <div class="benefit-title">Easy Documentation</div>
            <div class="benefit-desc">Request and manage barangay documents online</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column: Registration Form -->
    <div class="register-form-container">
      <div class="form-header">
        <h2 class="form-title">Create Account</h2>
        <p class="form-subtitle">Register for barangay services and management</p>
      </div>

      <!-- Success or Error Messages -->
      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
          <i class="bi bi-check-circle-fill me-2"></i>
          <div><?php echo htmlspecialchars($_SESSION['success']);
                unset($_SESSION['success']); ?></div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          <div><?php echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']); ?></div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <!-- Registration Form -->
      <form action="index.php?action=register" method="POST" id="registerForm">
        <div class="mb-4">
          <label for="full_name" class="form-label">
            Full Name <span class="text-danger">*</span>
          </label>

          <div class="input-group">
            <span class="input-group-text d-flex align-items-center justify-content-center">
              <i class="bi bi-person-fill"></i>
            </span>

            <input type="text"
              class="form-control"
              id="full_name"
              name="full_name"
              value="<?php echo htmlspecialchars($old['full_name'] ?? ''); ?>"
              autocomplete="name"
              required
              minlength="2"
              placeholder="Enter your complete name">
          </div>

          <div class="form-text">
            As it will appear on official barangay documents
          </div>
        </div>


        <div class="mb-4">
          <label for="email" class="form-label">
            Email Address <span class="text-danger">*</span>
          </label>

          <div class="input-group">
            <span class="input-group-text d-flex align-items-center justify-content-center">
              <i class="bi bi-envelope-fill"></i>
            </span>

            <input type="email"
              class="form-control"
              id="email"
              name="email"
              value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>"
              autocomplete="email"
              required
              placeholder="your.email@example.com">
          </div>

          <div class="form-text">
            We'll never share your email with anyone else
          </div>
        </div>


        <div class="mb-4">
          <label for="password" class="form-label">
            Password <span class="text-danger">*</span>
          </label>

          <div class="input-group">
            <span class="input-group-text d-flex align-items-center justify-content-center">
              <i class="bi bi-lock-fill"></i>
            </span>

            <input type="password"
              class="form-control"
              id="password"
              name="password"
              autocomplete="new-password"
              required
              minlength="6"
              placeholder="Create a strong password">

            <button class="btn input-group-text d-flex align-items-center justify-content-center"
              type="button"
              id="togglePassword">
              <i class="bi bi-eye"></i>
            </button>
          </div>

          <div class="password-strength" id="passwordStrength">
            <small>Password strength: <span class="strength-weak">Weak</span></small>
          </div>
        </div>


        <div class="mb-4">
          <label for="confirm_password" class="form-label">
            Confirm Password <span class="text-danger">*</span>
          </label>

          <div class="input-group">
            <span class="input-group-text d-flex align-items-center justify-content-center">
              <i class="bi bi-lock-fill"></i>
            </span>

            <input type="password"
              class="form-control"
              id="confirm_password"
              name="confirm_password"
              autocomplete="new-password"
              required
              minlength="6"
              placeholder="Confirm your password">

            <button class="btn input-group-text d-flex align-items-center justify-content-center"
              type="button"
              id="toggleConfirmPassword">
              <i class="bi bi-eye"></i>
            </button>
          </div>

          <div id="passwordMatch" class="form-text"></div>
        </div>


        <div class="form-check mb-4">
          <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
          <label class="form-check-label" for="terms">
            I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
          </label>
        </div>

        <button type="submit" class="btn btn-register pulse">
          <i class="bi bi-person-plus-fill me-2"></i>Create Account
        </button>
      </form>

      <div class="register-footer">
        <p class="mb-3">
          Already have an account?
          <a href="index.php?action=login">
            <i class="bi bi-box-arrow-in-right me-1"></i>Login here
          </a>
        </p>
        <p class="mb-0">
          <a href="index.php?action=forgot-password">
            <i class="bi bi-key-fill me-1"></i>Forgot Password?
          </a>

        </p>
      </div>
    </div>
  </div>

  <!-- Terms and Conditions Modal -->
  <div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Barangay System Terms and Conditions</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>By creating an account in the Barangay Management System, you agree to:</p>
          <ul>
            <li>Provide accurate, current, and complete information during registration</li>
            <li>Maintain and promptly update your information to keep it accurate</li>
            <li>Keep your password secure and confidential</li>
            <li>Accept responsibility for all activities that occur under your account</li>
            <li>Use the system only for legitimate barangay-related purposes</li>
            <li>Not engage in any unlawful activities through the system</li>
            <li>Allow Barangay Sibonga to use your information for official government purposes</li>
            <li>Abide by all barangay ordinances and national laws</li>
          </ul>
          <p class="mt-3"><small>The barangay reserves the right to suspend or terminate accounts that violate these terms.</small></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I Understand</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Toggle password visibility
      const togglePassword = document.querySelector('#togglePassword');
      const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
      const password = document.querySelector('#password');
      const confirmPassword = document.querySelector('#confirm_password');

      function setupPasswordToggle(button, field) {
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

      setupPasswordToggle(togglePassword, password);
      setupPasswordToggle(toggleConfirmPassword, confirmPassword);

      // Password strength and match validation
      function updatePasswordStrength(value) {
        const hasUpperCase = /[A-Z]/.test(value);
        const hasNumber = /[0-9]/.test(value);
        const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(value);
        const isLongEnough = value.length >= 8;
        const isVeryLong = value.length >= 12;

        let strength = 0;
        if (isLongEnough) strength += 1;
        if (hasUpperCase) strength += 1;
        if (hasNumber) strength += 1;
        if (hasSpecial) strength += 1;
        if (isVeryLong) strength += 1;

        const strengthLevels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
        const strengthClasses = ['strength-weak', 'strength-weak', 'strength-fair', 'strength-good', 'strength-strong', 'strength-strong'];

        const strengthDiv = document.getElementById('passwordStrength');
        if (strengthDiv) {
          strengthDiv.innerHTML = `<small>Password strength: <span class="${strengthClasses[strength]}">${strengthLevels[strength]}</span></small>`;
        }
      }

      function validatePassword() {
        const passwordValue = password.value;
        const confirmValue = confirmPassword.value;
        const matchDiv = document.getElementById('passwordMatch');

        // Update password strength
        updatePasswordStrength(passwordValue);

        // Check match
        if (confirmValue) {
          if (passwordValue === confirmValue) {
            matchDiv.innerHTML = '<span class="text-success">✓ Passwords match</span>';
          } else {
            matchDiv.innerHTML = '<span class="text-danger">✗ Passwords do not match</span>';
          }
        } else {
          matchDiv.innerHTML = '<span class="text-muted">Enter password confirmation</span>';
        }
      }

      if (password) password.addEventListener('input', validatePassword);
      if (confirmPassword) confirmPassword.addEventListener('input', validatePassword);

      // Initialize password strength
      if (password) updatePasswordStrength(password.value);

      // Form validation
      const form = document.getElementById('registerForm');
      if (form) {
        form.addEventListener('submit', function(e) {
          const terms = document.getElementById('terms');
          if (!terms.checked) {
            e.preventDefault();
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-warning alert-dismissible fade show mt-3';
            alertDiv.innerHTML = `
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            You must agree to the Terms and Conditions to continue.
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                        `;
            const formHeader = document.querySelector('.form-header');
            if (formHeader.nextElementSibling && formHeader.nextElementSibling.classList.contains('alert')) {
              formHeader.nextElementSibling.after(alertDiv);
            } else {
              formHeader.after(alertDiv);
            }
            terms.focus();
            return;
          }

          // Client-side validation
          const passwordValue = password.value;
          const confirmValue = confirmPassword.value;

          if (passwordValue !== confirmValue) {
            e.preventDefault();
            alert('Passwords do not match! Please check your password confirmation.');
            confirmPassword.focus();
            return;
          }

          // Additional password strength validation
          if (passwordValue.length < 6) {
            e.preventDefault();
            alert('Password must be at least 6 characters long.');
            password.focus();
            return;
          }

          // Button animation on submit
          const submitBtn = this.querySelector('.btn-register');
          if (submitBtn) {
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Creating Account...';
            submitBtn.disabled = true;
            submitBtn.classList.remove('pulse');
          }
        });
      }

      // Auto-focus on first field
      const fullNameField = document.querySelector('#full_name');
      if (fullNameField) {
        fullNameField.focus();
      }

      // Benefit items hover effect
      const benefitItems = document.querySelectorAll('.benefit-item');
      benefitItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
          const icon = this.querySelector('.benefit-icon');
          icon.style.animationPlayState = 'paused';
        });

        item.addEventListener('mouseleave', function() {
          const icon = this.querySelector('.benefit-icon');
          icon.style.animationPlayState = 'running';
        });
      });

      // Modal close button fix for dark header
      const modalCloseBtn = document.querySelector('.btn-close-white');
      if (modalCloseBtn) {
        modalCloseBtn.addEventListener('click', function() {
          const modal = document.querySelector('#termsModal');
          if (modal) {
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) bsModal.hide();
          }
        });
      }
    });
  </script>
</body>

</html>
