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
        body {
            background: linear-gradient(135deg, #ce1126 0%, #003893 100%); /* Reversed Philippine colors */
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .signup-container {
            max-width: 450px;
            width: 100%;
            padding: 30px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #003893; /* Philippine blue */
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .header p {
            color: #ce1126; /* Philippine red */
            font-size: 1.1rem;
            margin-bottom: 0;
        }
        .header .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #003893 0%, #ce1126 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 2rem;
        }
        .btn-signup {
            background: linear-gradient(135deg, #003893 0%, #002a6e 100%);
            color: white;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        .btn-signup:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 56, 147, 0.3);
        }
        .form-label {
            color: #333;
            font-weight: 500;
            margin-bottom: 5px;
        }
        .form-control {
            padding: 12px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #003893;
            box-shadow: 0 0 0 0.25rem rgba(0, 56, 147, 0.25);
        }
        .password-strength {
            font-size: 0.85rem;
            margin-top: 5px;
        }
        .alert {
            border-radius: 8px;
            border: none;
        }
        .links {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }
        .links a {
            color: #003893;
            text-decoration: none;
            font-weight: 500;
        }
        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="header">
            <div class="logo">
                <i class="bi bi-person-plus-fill"></i>
            </div>
            <h1>Barangay Management System</h1>
            <p>Create Account for Barangay Sibonga</p>
        </div>

        <!-- Success or Error Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Registration Form -->
        <form action="index.php?action=register" method="POST" id="registerForm">
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="full_name" name="full_name"
                       value="<?php echo htmlspecialchars($old['full_name'] ?? ''); ?>"
                       autocomplete="name" required minlength="2">
                <div class="form-text">Enter your complete name as it will appear on official documents.</div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email"
                       value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>"
                       autocomplete="email" required>
                <div class="form-text">We'll never share your email with anyone else.</div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password"
                           autocomplete="new-password" required minlength="6">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <div class="password-strength text-muted">
                    <small>Must contain at least 6 characters, one uppercase letter, and one number.</small>
                </div>
            </div>

            <div class="mb-4">
                <label for="confirm_password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                           autocomplete="new-password" required minlength="6">
                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <div id="passwordMatch" class="form-text"></div>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                    <label class="form-check-label" for="terms">
                        I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-signup">
                <i class="bi bi-person-plus me-2"></i>Create Account
            </button>
        </form>

        <div class="links">
            <p class="mb-0">
                Already have an account?
                <a href="index.php?action=login">Login here</a>
            </p>
        </div>
    </div>

    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>By creating an account, you agree to:</p>
                    <ul>
                        <li>Provide accurate and truthful information</li>
                        <li>Use the system only for legitimate barangay-related purposes</li>
                        <li>Maintain the confidentiality of your account credentials</li>
                        <li>Abide by barangay rules and regulations</li>
                        <li>Allow the barangay to use your information for official purposes</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
            function validatePassword() {
                const passwordValue = password.value;
                const confirmValue = confirmPassword.value;
                const matchDiv = document.getElementById('passwordMatch');

                // Check match
                if (confirmValue) {
                    if (passwordValue === confirmValue) {
                        matchDiv.innerHTML = '<span class="text-success">✓ Passwords match</span>';
                    } else {
                        matchDiv.innerHTML = '<span class="text-danger">✗ Passwords do not match</span>';
                    }
                } else {
                    matchDiv.innerHTML = '';
                }

                // Check strength
                const hasUpperCase = /[A-Z]/.test(passwordValue);
                const hasNumber = /[0-9]/.test(passwordValue);
                const isLongEnough = passwordValue.length >= 6;

                if (passwordValue) {
                    const strength = (hasUpperCase ? 1 : 0) + (hasNumber ? 1 : 0) + (isLongEnough ? 1 : 0);
                    const strengthText = ['Weak', 'Fair', 'Good', 'Strong'][strength];
                    const strengthColor = ['danger', 'warning', 'info', 'success'][strength];

                    const strengthDiv = document.querySelector('.password-strength');
                    if (strengthDiv) {
                        strengthDiv.innerHTML = `<small>Password strength: <span class="text-${strengthColor}">${strengthText}</span></small>`;
                    }
                }
            }

            if (password) password.addEventListener('input', validatePassword);
            if (confirmPassword) confirmPassword.addEventListener('input', validatePassword);

            // Form validation
            const form = document.getElementById('registerForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const terms = document.getElementById('terms');
                    if (!terms.checked) {
                        e.preventDefault();
                        alert('You must agree to the Terms and Conditions.');
                        return;
                    }

                    // Client-side validation
                    const passwordValue = password.value;
                    const confirmValue = confirmPassword.value;

                    if (passwordValue !== confirmValue) {
                        e.preventDefault();
                        alert('Passwords do not match!');
                        return;
                    }

                    // Additional password strength validation
                    if (!/[A-Z]/.test(passwordValue) || !/[0-9]/.test(passwordValue)) {
                        e.preventDefault();
                        alert('Password must contain at least one uppercase letter and one number.');
                        return;
                    }
                });
            }

            // Auto-focus on first field
            const firstNameField = document.querySelector('#full_name');
            if (firstNameField) {
                firstNameField.focus();
            }
        });
    </script>
</body>
</html>
