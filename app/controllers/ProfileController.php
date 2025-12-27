<?php
// app/controllers/ProfileController.php
require_once 'BaseController.php';

class ProfileController extends BaseController
{
  public function index()
  {
    $sub = $_GET['sub'] ?? 'view';

    switch ($sub) {
      case 'edit':
        $this->editForm();
        break;
      case 'update':
        $this->updateProfile();
        break;
      case 'change_password':
        $this->changePassword();
        break;
      case 'update_password':
        $this->updatePassword();
        break;
      default:
        $this->viewProfile();
        break;
    }
  }

  private function viewProfile()
  {
    if (!isset($_SESSION['user_id'])) {
      $this->redirect('login');
    }

    $userId = $_SESSION['user_id'];

    try {
      // Get user information
      $stmt = $this->pdo->prepare("
                SELECT u.*, r.*
                FROM tbl_users u
                LEFT JOIN tbl_residents r ON u.id = r.user_id
                WHERE u.id = ?
            ");
      $stmt->execute([$userId]);
      $profile = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$profile) {
        $_SESSION['error'] = "Profile not found.";
        $this->redirect('dashboard');
      }
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to load profile: " . $e->getMessage();
      $this->redirect('dashboard');
    }

    $this->render('profile/my_profile', [
      'profile' => $profile,
      'title' => 'My Profile'
    ]);
  }

  private function editForm()
  {
    if (!isset($_SESSION['user_id'])) {
      $this->redirect('login');
    }

    $userId = $_SESSION['user_id'];

    try {
      // Get user information
      $stmt = $this->pdo->prepare("
                SELECT u.*, r.*
                FROM tbl_users u
                LEFT JOIN tbl_residents r ON u.id = r.user_id
                WHERE u.id = ?
            ");
      $stmt->execute([$userId]);
      $profile = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$profile) {
        $_SESSION['error'] = "Profile not found.";
        $this->redirect('dashboard');
      }
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to load profile: " . $e->getMessage();
      $this->redirect('dashboard');
    }

    $this->render('profile/edit_profile', [
      'profile' => $profile,
      'title' => 'Edit Profile'
    ]);
  }

  private function updateProfile()
  {
    if (!isset($_SESSION['user_id'])) {
      $this->redirect('login');
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirect('profile', ['sub' => 'edit']);
    }

    $userId = $_SESSION['user_id'];
    $errors = [];

    // Validate inputs
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $birthdate = $_POST['birthdate'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $contact_number = trim($_POST['contact_number'] ?? '');
    $occupation = trim($_POST['occupation'] ?? '');
    $civil_status = $_POST['civil_status'] ?? '';

    if (empty($full_name)) $errors[] = "Full name is required.";
    if (empty($email)) $errors[] = "Email is required.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";

    if (empty($errors)) {
      try {
        // Start transaction
        $this->pdo->beginTransaction();

        // Update user information
        $stmt = $this->pdo->prepare("
                    UPDATE tbl_users
                    SET full_name = ?, email = ?
                    WHERE id = ?
                ");
        $stmt->execute([$full_name, $email, $userId]);

        // Check if resident record exists
        $stmt = $this->pdo->prepare("SELECT id FROM tbl_residents WHERE user_id = ?");
        $stmt->execute([$userId]);
        $resident = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resident) {
          // Update existing resident record
          $stmt = $this->pdo->prepare("
                        UPDATE tbl_residents
                        SET full_name = ?, address = ?, birthdate = ?, gender = ?,
                            contact_number = ?, occupation = ?, civil_status = ?
                        WHERE user_id = ?
                    ");
          $stmt->execute([
            $full_name,
            $address,
            $birthdate,
            $gender,
            $contact_number,
            $occupation,
            $civil_status,
            $userId
          ]);
        } else {
          // Create new resident record
          $stmt = $this->pdo->prepare("
                        INSERT INTO tbl_residents
                        (user_id, full_name, address, birthdate, gender, contact_number, occupation, civil_status)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ");
          $stmt->execute([
            $userId,
            $full_name,
            $address,
            $birthdate,
            $gender,
            $contact_number,
            $occupation,
            $civil_status
          ]);
        }

        // Commit transaction
        $this->pdo->commit();

        // Update session
        $_SESSION['full_name'] = $full_name;
        $_SESSION['email'] = $email;

        // Log the action
        $this->logAction($userId, "Updated profile information");

        $_SESSION['success'] = "Profile updated successfully!";
        $this->redirect('profile');
      } catch (PDOException $e) {
        $this->pdo->rollBack();
        $_SESSION['error'] = "Failed to update profile: " . $e->getMessage();
        $this->redirect('profile', ['sub' => 'edit']);
      }
    } else {
      $_SESSION['error'] = implode("<br>", $errors);
      $_SESSION['old'] = $_POST;
      $this->redirect('profile', ['sub' => 'edit']);
    }
  }

  private function changePassword()
  {
    if (!isset($_SESSION['user_id'])) {
      $this->redirect('login');
    }

    $this->render('profile/change_password', [
      'title' => 'Change Password'
    ]);
  }

  private function updatePassword()
  {
    if (!isset($_SESSION['user_id'])) {
      $this->redirect('login');
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirect('profile', ['sub' => 'change_password']);
    }

    $userId = $_SESSION['user_id'];
    $errors = [];

    // Validate inputs
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($current_password)) $errors[] = "Current password is required.";
    if (empty($new_password)) $errors[] = "New password is required.";
    if (empty($confirm_password)) $errors[] = "Confirm password is required.";

    if ($new_password !== $confirm_password) {
      $errors[] = "New passwords do not match.";
    }

    if (strlen($new_password) < 6) {
      $errors[] = "New password must be at least 6 characters.";
    }

    if (empty($errors)) {
      try {
        // Get current password hash
        $stmt = $this->pdo->prepare("SELECT password FROM tbl_users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($current_password, $user['password'])) {
          $errors[] = "Current password is incorrect.";
        }

        if (empty($errors)) {
          // Update password
          $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
          $stmt = $this->pdo->prepare("UPDATE tbl_users SET password = ? WHERE id = ?");
          $stmt->execute([$hashed_password, $userId]);

          // Log the action
          $this->logAction($userId, "Changed password");

          $_SESSION['success'] = "Password changed successfully!";
          $this->redirect('profile');
        }
      } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to change password: " . $e->getMessage();
        $this->redirect('profile', ['sub' => 'change_password']);
      }
    }

    if (!empty($errors)) {
      $_SESSION['error'] = implode("<br>", $errors);
      $this->redirect('profile', ['sub' => 'change_password']);
    }
  }

  private function logAction($userId, $action)
  {
    try {
      $stmt = $this->pdo->prepare("INSERT INTO tbl_logs (user_id, action) VALUES (?, ?)");
      $stmt->execute([$userId, $action]);
    } catch (PDOException $e) {
      error_log("Failed to log action: " . $e->getMessage());
    }
  }
}
