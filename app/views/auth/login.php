<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Barangay Management System | Login</title>
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

    .login-grid-container {
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
    .login-welcome {
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

    .login-welcome::before {
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

    .welcome-logo-container img {
      width: 80%;
      height: auto;
      border-radius: 50%;
      object-fit: cover;
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
      font-size: 1.6rem;
      opacity: 0.95;
      margin-bottom: 30px;
      font-weight: 400;
    }

    .welcome-features {
      margin-top: 50px;
      text-align: left;
      width: 100%;
      max-width: 350px;
    }

    .feature-item {
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

    .feature-item:hover {
      transform: translateX(5px);
      background: rgba(255, 255, 255, 0.15);
    }

    .feature-icon {
      font-size: 1.8rem;
      color: var(--ph-yellow);
      margin-right: 20px;
      width: 50px;
      text-align: center;
    }

    .feature-text {
      flex: 1;
    }

    .feature-title {
      font-weight: 600;
      margin-bottom: 5px;
      font-size: 1.1rem;
    }

    .feature-desc {
      opacity: 0.9;
      font-size: 0.9rem;
      line-height: 1.4;
    }

    /* Right Side - Login Form */
    .login-form-container {
      padding: 60px 50px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      background-color: white;
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

    .alert-danger {
      background-color: #ffe6e9;
      color: #b00020;
      border-left: 4px solid var(--ph-red);
    }

    .alert-success {
      background-color: #e8f7ef;
      color: #006442;
      border-left: 4px solid #28a745;
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

    .btn-login {
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

    .btn-login:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(0, 56, 147, 0.3);
    }

    .btn-login:active {
      transform: translateY(-1px);
    }

    .login-footer {
      text-align: center;
      margin-top: 30px;
      padding-top: 25px;
      border-top: 1px solid #eaeff5;
    }

    .login-footer a {
      color: var(--ph-blue);
      text-decoration: none;
      font-weight: 500;
      transition: all 0.2s ease;
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }

    .login-footer a:hover {
      color: var(--ph-red);
      text-decoration: underline;
    }

    .login-footer p {
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

    .input-icon {
      color: var(--ph-blue);
      width: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #f8fafd;
      border-right: 1px solid #e0e6f0;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
      .login-grid-container {
        grid-template-columns: 1fr;
        max-width: 600px;
        min-height: auto;
      }

      .login-welcome {
        padding: 40px 30px;
      }

      .welcome-title {
        font-size: 1.8rem;
      }

      .login-form-container {
        padding: 40px 35px;
      }
    }

    @media (max-width: 576px) {
      .login-grid-container {
        border-radius: 15px;
      }

      .login-welcome {
        padding: 30px 20px;
      }

      .welcome-logo-container {
        width: 120px;
        height: 120px;
        margin-bottom: 30px;
      }

      .welcome-title {
        font-size: 1.6rem;
      }

      .login-form-container {
        padding: 30px 25px;
      }

      .form-title {
        font-size: 1.6rem;
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

    /* Feature icons animation */
    @keyframes float {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-5px);
      }
    }

    .feature-icon {
      animation: float 3s ease-in-out infinite;
    }

    .feature-item:nth-child(2) .feature-icon {
      animation-delay: 0.5s;
    }

    .feature-item:nth-child(3) .feature-icon {
      animation-delay: 1s;
    }
  </style>
</head>

<body>
  <div class="login-grid-container">
    <!-- Left Column: Welcome/Info Section -->
    <div class="login-welcome">
      <div class="welcome-logo-container">
        <img src="../assets/Sibonga.jpg" alt="Sibonga Barangay Seal">
      </div>
      <div class="welcome-text">
        <h1 class="welcome-title">Barangay Management System</h1>
        <p class="barangay-name-large">Barangay Sibonga</p>
      </div>

      <div class="welcome-features">
        <div class="feature-item">
          <div class="feature-icon">
            <i class="bi bi-shield-check"></i>
          </div>
          <div class="feature-text">
            <div class="feature-title">Secure & Reliable</div>
            <div class="feature-desc">Government-grade security for all your barangay data</div>
          </div>
        </div>

        <div class="feature-item">
          <div class="feature-icon">
            <i class="bi bi-speedometer2"></i>
          </div>
          <div class="feature-text">
            <div class="feature-title">Efficient Management</div>
            <div class="feature-desc">Streamline barangay operations and citizen services</div>
          </div>
        </div>

        <div class="feature-item">
          <div class="feature-icon">
            <i class="bi bi-people-fill"></i>
          </div>
          <div class="feature-text">
            <div class="feature-title">Community Focused</div>
            <div class="feature-desc">Designed to serve the residents of Barangay Sibonga</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column: Login Form -->
    <div class="login-form-container">
      <div class="form-header">
        <h2 class="form-title">Welcome Back</h2>
        <p class="form-subtitle">Sign in to access your dashboard</p>
      </div>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          <div><?php echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']); ?></div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
          <i class="bi bi-check-circle-fill me-2"></i>
          <div><?php echo htmlspecialchars($_SESSION['success']);
                unset($_SESSION['success']); ?></div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <form action="index.php?action=login" method="POST" id="loginForm">
        <div class="mb-4">
          <label for="email" class="form-label">Email Address</label>
          <div class="input-group">
            <span class="input-icon">
              <i class="bi bi-envelope-fill"></i>
            </span>
            <input type="email" class="form-control" id="email" name="email"
              autocomplete="email" required
              placeholder="barangay.staff@example.com">
          </div>
        </div>

        <div class="mb-4">
          <label for="password" class="form-label">Password</label>
          <div class="input-group">
            <span class="input-icon">
              <i class="bi bi-lock-fill"></i>
            </span>
            <input type="password" class="form-control" id="password" name="password"
              autocomplete="current-password" required
              placeholder="Enter your password">
            <button class="btn input-group-text" type="button" id="togglePassword">
              <i class="bi bi-eye"></i>
            </button>
          </div>
        </div>

        <div class="form-check mb-4">
          <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
          <label class="form-check-label" for="rememberMe">
            Remember me on this device
          </label>
        </div>

        <button type="submit" class="btn btn-login pulse">
          <i class="bi bi-box-arrow-in-right me-2"></i>Login to Dashboard
        </button>
      </form>

      <div class="login-footer">
        <p class="mb-3">
          New to the system?
          <a href="index.php?action=register">
            <i class="bi bi-person-plus-fill me-1"></i>Create Account
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

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Toggle password visibility
      const togglePassword = document.querySelector('#togglePassword');
      const password = document.querySelector('#password');

      if (togglePassword && password) {
        togglePassword.addEventListener('click', function() {
          const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
          password.setAttribute('type', type);

          const icon = this.querySelector('i');
          if (icon) {
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
          }

          // Focus back to password field
          password.focus();
        });
      }

      // Auto-focus on email field
      const emailField = document.querySelector('#email');
      if (emailField) {
        emailField.focus();
      }

      // Form submission animation
      const loginForm = document.getElementById('loginForm');
      if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
          const submitBtn = this.querySelector('.btn-login');
          if (submitBtn) {
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Logging in...';
            submitBtn.disabled = true;
            submitBtn.classList.remove('pulse');
          }
        });
      }

      // Add placeholder animation
      const inputs = document.querySelectorAll('.form-control');
      inputs.forEach(input => {
        input.addEventListener('focus', function() {
          this.parentElement.classList.add('focus');
        });

        input.addEventListener('blur', function() {
          if (!this.value) {
            this.parentElement.classList.remove('focus');
          }
        });
      });

      // Add some interactive effects
      const labels = document.querySelectorAll('.form-label');
      labels.forEach(label => {
        label.addEventListener('click', function() {
          const inputId = this.getAttribute('for');
          if (inputId) {
            const input = document.getElementById(inputId);
            if (input) input.focus();
          }
        });
      });

      // Feature items hover effect
      const featureItems = document.querySelectorAll('.feature-item');
      featureItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
          const icon = this.querySelector('.feature-icon');
          icon.style.animationPlayState = 'paused';
        });

        item.addEventListener('mouseleave', function() {
          const icon = this.querySelector('.feature-icon');
          icon.style.animationPlayState = 'running';
        });
      });
    });
  </script>
</body>

</html>
