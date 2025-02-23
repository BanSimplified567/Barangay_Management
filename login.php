<?php
session_start();
include 'dbcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  try {
    // Fetch user info first - Fixed the SQL query by removing the incorrect password column reference
    $stmt = $pdo->prepare("SELECT * FROM tbluser WHERE email = ? AND password = ?");
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify if user exists
    if ($user) {
      // Store username in session
      $_SESSION['email'] = $user['email'];

      header("Location: ./index.php");
      exit();
    } else {
      header("Location: login.php?error=Invalid email or password");
      exit();
    }
  } catch (PDOException $e) {
    header("Location: login.php?error=Database error");
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/login.css">
    <title>Barangay Management System | Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  </head>

  <body>

    <div class="login-container">
      <h2 class="text-center mb-4">Barangay Management System Login</h2>
      <form action="login.php" method="POST">
        <div class="form-group">
          <label for="email" class="form-label">Email:</label>
          <input type="text" class="form-control" id="email" name="email" autocomplete="email" required>
        </div>
        <div class="form-group">
          <label for="password" class="form-label">Password:</label>
          <div class="input-group">
            <input type="password" class="form-control" id="password" name="password" autocomplete="current-password"
              required>
            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
              <i class="bi bi-eye"></i>
            </button>
          </div>
        </div>
        <button type="submit" class="btn btn-login">Login</button>
      </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      const togglePassword = document.querySelector('#togglePassword');
      const password = document.querySelector('#password');

      togglePassword.addEventListener('click', function () {
        // Toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        // Toggle the icon
        this.querySelector('i').classList.toggle('bi-eye');
        this.querySelector('i').classList.toggle('bi-eye-slash');
      });
    </script>
  </body>

</html>
