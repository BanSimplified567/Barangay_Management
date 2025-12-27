<?php
// app/controllers/SettingsController.php
require_once 'BaseController.php';

class SettingsController extends BaseController
{
  public function index()
  {
    // Check if user is admin
    if (($_SESSION['role'] ?? '') !== 'admin') {
      $_SESSION['error'] = "Access denied. Administrator privileges required.";
      $this->redirect('dashboard');
    }

    $sub = $_GET['sub'] ?? 'general';

    switch ($sub) {
      case 'save':
        $this->saveSettings();
        break;
      case 'system':
        $this->systemSettings();
        break;
      case 'email':
        $this->emailSettings();
        break;
      case 'security':
        $this->securitySettings();
        break;
      case 'backup':
        $this->backupSettings();
        break;
      case 'database':
        $this->databaseSettings();
        break;
      case 'perform_backup':
        $this->performBackup();
        break;
      case 'test_email':
        $this->testEmail();
        break;
      default:
        $this->generalSettings();
        break;
    }
  }

  private function generalSettings()
  {
    // Get all settings
    $settings = $this->getAllSettings();

    $this->render('settings/settings', [
      'settings' => $settings,
      'title' => 'General Settings'
    ]);
  }

  private function systemSettings()
  {
    $settings = $this->getAllSettings();

    $this->render('settings/system', [
      'settings' => $settings,
      'title' => 'System Settings'
    ]);
  }

  private function emailSettings()
  {
    $settings = $this->getAllSettings();

    $this->render('settings/email', [
      'settings' => $settings,
      'title' => 'Email Settings'
    ]);
  }

  private function securitySettings()
  {
    $settings = $this->getAllSettings();

    $this->render('settings/security', [
      'settings' => $settings,
      'title' => 'Security Settings'
    ]);
  }

  private function backupSettings()
  {
    $settings = $this->getAllSettings();

    $this->render('settings/backup', [
      'settings' => $settings,
      'title' => 'Backup Settings'
    ]);
  }

  private function databaseSettings()
  {
    try {
      // Get database information
      $stmt = $this->pdo->query("
                SELECT
                    TABLE_NAME,
                    TABLE_ROWS,
                    DATA_LENGTH,
                    INDEX_LENGTH,
                    CREATE_TIME,
                    UPDATE_TIME
                FROM information_schema.TABLES
                WHERE TABLE_SCHEMA = DATABASE()
                ORDER BY TABLE_NAME
            ");
      $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Get database size
      $stmt = $this->pdo->query("
                SELECT
                    SUM(DATA_LENGTH + INDEX_LENGTH) as total_size,
                    COUNT(*) as table_count
                FROM information_schema.TABLES
                WHERE TABLE_SCHEMA = DATABASE()
            ");
      $dbInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to get database information: " . $e->getMessage();
      $tables = [];
      $dbInfo = ['total_size' => 0, 'table_count' => 0];
    }

    $this->render('settings/database', [
      'tables' => $tables,
      'dbInfo' => $dbInfo,
      'title' => 'Database Information'
    ]);
  }

  private function saveSettings()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $this->redirect('settings');
    }

    // Check if user is admin
    if (($_SESSION['role'] ?? '') !== 'admin') {
      $_SESSION['error'] = "Only administrators can change settings.";
      $this->redirect('settings');
    }

    try {
      // Ensure settings table exists
      $this->createSettingsTable();

      // Process each setting
      $settingsToSave = $_POST['settings'] ?? [];
      $errors = [];

      foreach ($settingsToSave as $key => $value) {
        $value = trim($value);

        // Validate specific settings
        switch ($key) {
          case 'system_name':
            if (empty($value)) {
              $errors[] = "System name cannot be empty.";
            }
            break;
          case 'default_items_per_page':
            if (!is_numeric($value) || $value < 5 || $value > 100) {
              $errors[] = "Items per page must be between 5 and 100.";
            }
            break;
          case 'session_timeout':
            if (!is_numeric($value) || $value < 5 || $value > 480) {
              $errors[] = "Session timeout must be between 5 and 480 minutes.";
            }
            break;
        }
      }

      if (!empty($errors)) {
        $_SESSION['error'] = implode("<br>", $errors);
      } else {
        foreach ($settingsToSave as $key => $value) {
          // Check if setting exists
          $stmt = $this->pdo->prepare("SELECT id FROM tbl_settings WHERE setting_key = ?");
          $stmt->execute([$key]);

          if ($stmt->fetch()) {
            // Update existing setting
            $stmt = $this->pdo->prepare("UPDATE tbl_settings SET setting_value = ?, updated_at = NOW() WHERE setting_key = ?");
            $stmt->execute([$value, $key]);
          } else {
            // Insert new setting
            $stmt = $this->pdo->prepare("INSERT INTO tbl_settings (setting_key, setting_value) VALUES (?, ?)");
            $stmt->execute([$key, $value]);
          }
        }

        // Log the action
        $this->logAction($_SESSION['user_id'] ?? 0, "Updated system settings");

        $_SESSION['success'] = "Settings saved successfully!";
      }
    } catch (PDOException $e) {
      $_SESSION['error'] = "Failed to save settings: " . $e->getMessage();
    }

    // Redirect back to appropriate section
    $section = $_POST['section'] ?? 'general';
    $this->redirect('settings', ['sub' => $section]);
  }

  private function performBackup()
  {
    if (($_SESSION['role'] ?? '') !== 'admin') {
      $_SESSION['error'] = "Only administrators can perform backups.";
      $this->redirect('settings', ['sub' => 'backup']);
    }

    try {
      // Get all tables
      $stmt = $this->pdo->query("SHOW TABLES");
      $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

      $backupContent = "-- Barangay Management System Backup\n";
      $backupContent .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
      $backupContent .= "-- Database: " . $this->pdo->query("SELECT DATABASE()")->fetchColumn() . "\n\n";

      foreach ($tables as $table) {
        // Add table structure
        $stmt = $this->pdo->query("SHOW CREATE TABLE `$table`");
        $createTable = $stmt->fetch(PDO::FETCH_ASSOC);
        $backupContent .= $createTable['Create Table'] . ";\n\n";

        // Add table data
        $stmt = $this->pdo->query("SELECT * FROM `$table`");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($rows)) {
          $columns = array_keys($rows[0]);
          $backupContent .= "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES \n";

          $rowValues = [];
          foreach ($rows as $row) {
            $values = array_map(function ($value) {
              if ($value === null) return 'NULL';
              return "'" . addslashes($value) . "'";
            }, array_values($row));
            $rowValues[] = "  (" . implode(', ', $values) . ")";
          }

          $backupContent .= implode(",\n", $rowValues) . ";\n\n";
        }
      }

      // Save backup file
      $backupDir = BASE_PATH . '/backups/';
      if (!is_dir($backupDir)) {
        mkdir($backupDir, 0755, true);
      }

      $filename = 'backup_' . date('Y-m-d_His') . '.sql';
      $filepath = $backupDir . $filename;

      if (file_put_contents($filepath, $backupContent)) {
        // Log the action
        $this->logAction($_SESSION['user_id'] ?? 0, "Created database backup: $filename");

        $_SESSION['success'] = "Backup created successfully: " . $filename;
      } else {
        $_SESSION['error'] = "Failed to save backup file.";
      }
    } catch (PDOException $e) {
      $_SESSION['error'] = "Backup failed: " . $e->getMessage();
    }

    $this->redirect('settings', ['sub' => 'backup']);
  }

  private function testEmail()
  {
    if (($_SESSION['role'] ?? '') !== 'admin') {
      $_SESSION['error'] = "Only administrators can test email settings.";
      $this->redirect('settings', ['sub' => 'email']);
    }

    $to = $_SESSION['email'] ?? '';

    if (empty($to)) {
      $_SESSION['error'] = "No email address found in your profile.";
      $this->redirect('settings', ['sub' => 'email']);
    }

    // This is a simplified email test
    $subject = "Barangay System - Email Test";
    $message = "This is a test email from your Barangay Management System.\n\n";
    $message .= "Sent at: " . date('Y-m-d H:i:s') . "\n";
    $message .= "To: " . $to . "\n";
    $message .= "System: " . ($this->getSetting('system_name') ?? 'Barangay Management System');

    $headers = "From: barangay-system@localhost\r\n";
    $headers .= "Reply-To: no-reply@localhost\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    if (@mail($to, $subject, $message, $headers)) {
      // Log the action
      $this->logAction($_SESSION['user_id'] ?? 0, "Sent test email to $to");

      $_SESSION['success'] = "Test email sent to " . $to;
    } else {
      $_SESSION['error'] = "Failed to send test email. Check your server's mail configuration.";
    }

    $this->redirect('settings', ['sub' => 'email']);
  }

  private function getAllSettings()
  {
    try {
      // Create settings table if it doesn't exist
      $this->createSettingsTable();

      $stmt = $this->pdo->query("SELECT setting_key, setting_value FROM tbl_settings");
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $settings = [];
      foreach ($rows as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
      }

      // Set default values for missing settings
      $defaults = $this->getDefaultSettings();
      foreach ($defaults as $key => $value) {
        if (!isset($settings[$key])) {
          $settings[$key] = $value;
        }
      }

      return $settings;
    } catch (PDOException $e) {
      error_log("Failed to get settings: " . $e->getMessage());
      return $this->getDefaultSettings();
    }
  }

  private function getSetting($key)
  {
    $settings = $this->getAllSettings();
    return $settings[$key] ?? null;
  }

  private function getDefaultSettings()
  {
    return [
      'system_name' => 'Barangay Management System',
      'system_version' => '1.0.0',
      'system_email' => 'admin@barangay.local',
      'default_items_per_page' => '25',
      'session_timeout' => '30',
      'maintenance_mode' => '0',
      'email_notifications' => '1',
      'email_smtp_host' => 'localhost',
      'email_smtp_port' => '25',
      'email_smtp_username' => '',
      'email_smtp_password' => '',
      'backup_auto' => '0',
      'backup_interval' => '7',
      'backup_keep' => '5',
      'login_attempts' => '5',
      'password_expiry' => '90',
      'registration_allowed' => '1',
      'theme' => 'default',
      'date_format' => 'Y-m-d',
      'time_format' => 'H:i:s',
      'currency_symbol' => '₱',
      'enable_audit_log' => '1',
      'enable_activity_log' => '1'
    ];
  }

  private function createSettingsTable()
  {
    try {
      $sql = "
                CREATE TABLE IF NOT EXISTS tbl_settings (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    setting_key VARCHAR(100) UNIQUE NOT NULL,
                    setting_value TEXT,
                    description TEXT,
                    setting_group VARCHAR(50) DEFAULT 'general',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )
            ";
      $this->pdo->exec($sql);

      // Insert default settings if table is empty
      $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM tbl_settings");
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($result['count'] == 0) {
        $defaults = $this->getDefaultSettings();
        foreach ($defaults as $key => $value) {
          $stmt = $this->pdo->prepare("INSERT INTO tbl_settings (setting_key, setting_value) VALUES (?, ?)");
          $stmt->execute([$key, $value]);
        }
      }
    } catch (PDOException $e) {
      error_log("Failed to create settings table: " . $e->getMessage());
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
