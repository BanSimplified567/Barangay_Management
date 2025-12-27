<?php
// public/index.php
session_start();

// Define BASE_PATH constant
define('BASE_PATH', dirname(__DIR__));

require_once '../config/dbcon.php';                 // Database connection
require_once '../app/middleware/auth.php';         // Authentication middleware

// Determine the action from URL (e.g., index.php?action=officials)
$action = $_GET['action'] ?? 'dashboard';

switch ($action) {
  case 'login':
    guest_only();
    require_once '../app/controllers/Auth/LoginController.php';
    $controller = new LoginController($pdo);
    $controller->index();
    break;

  case 'register':
    guest_only();
    require_once '../app/controllers/Auth/RegisterController.php';
    $controller = new RegisterController($pdo);
    $controller->index();
    break;

  case 'logout':
    require_once '../app/controllers/Auth/LogoutController.php';
    $controller = new LogoutController($pdo);
    $controller->index();
    break;

  case 'dashboard':
    authorize(['admin', 'staff', 'resident']);
    require_once '../app/controllers/DashboardController.php';
    $controller = new DashboardController($pdo);
    $controller->index();
    break;

  case 'residents':
    authorize(['admin', 'staff']);
    require_once '../app/controllers/ResidentsController.php';
    $controller = new ResidentsController($pdo);

    // Handle sub-actions - CORRECTED METHOD NAMES
    $sub = $_GET['sub'] ?? 'list';
    switch ($sub) {
      case 'add':
        $controller->addForm();  // Changed from showAddForm()
        break;
      case 'edit':
        $controller->editForm($_GET['id'] ?? 0);  // Changed from showEditForm()
        break;
      case 'delete':
        $controller->delete($_GET['id'] ?? 0);
        break;
      case 'create':
        $controller->create();  // No need to pass $_POST
        break;
      case 'update':
        $controller->update();  // ID will be taken from POST data
        break;
      default:
        $controller->list();  // Changed from index()
        break;
    }
    break;

  case 'certifications':
    authorize(['admin', 'staff', 'resident']);
    require_once '../app/controllers/CertificationController.php';
    $controller = new CertificationController($pdo);
    $controller->index();
    break;

  case 'request-certification':
    authorize(['resident']);
    require_once '../app/controllers/CertificationController.php';
    $controller = new CertificationController($pdo);
    $controller->requestForm();
    break;

  case 'blotters':
    authorize(['admin', 'staff']);
    require_once '../app/controllers/BlotterController.php';
    $controller = new BlotterController($pdo);

    // Handle sub-actions
    $sub = $_GET['sub'] ?? 'list';
    switch ($sub) {
      case 'add':
        $controller->index();
        break;
      case 'edit':
        $controller->index();
        break;
      case 'delete':
        $controller->index();
        break;
      case 'create':
        $controller->index();
        break;
      case 'update':
        $controller->index();
        break;
      case 'settle':
        $controller->index();
        break;
      default:
        $controller->index();
        break;
    }
    break;
  case 'crimes':
    authorize(['admin', 'staff']);
    require_once '../app/controllers/CrimeController.php';
    $controller = new CrimeController($pdo);

    // Call the index method which will handle sub-actions
    $controller->index();
    break;

  case 'events':
    authorize(['admin', 'staff']);
    require_once '../app/controllers/EventController.php';
    $controller = new EventController($pdo);

    // Handle sub-actions
    $sub = $_GET['sub'] ?? 'list';
    switch ($sub) {
      case 'add':
        $controller->index();
        break;
      case 'edit':
        $controller->index();
        break;
      case 'delete':
        $controller->index();
        break;
      case 'create':
        $controller->index();
        break;
      case 'update':
        $controller->index();
        break;
      case 'cancel':
        $controller->index();
        break;
      case 'complete':
        $controller->index();
        break;
      default:
        $controller->index();
        break;
    }
    break;
  case 'officials':
    authorize(['admin', 'staff']);
    require_once '../app/controllers/OfficialController.php';
    $controller = new OfficialController($pdo);

    // Handle sub-actions
    $sub = $_GET['sub'] ?? 'list';
    switch ($sub) {
      case 'add':
        $controller->index();
        break;
      case 'edit':
        $controller->index();
        break;
      case 'delete':
        $controller->index();
        break;
      case 'create':
        $controller->index();
        break;
      case 'update':
        $controller->index();
        break;
      default:
        $controller->index();
        break;
    }
    break;

    case 'announcements':
      authorize(['admin', 'staff']);
      require_once '../app/controllers/AnnouncementController.php';
      $controller = new AnnouncementController($pdo);

      // Handle sub-actions
      $sub = $_GET['sub'] ?? 'list';
      switch ($sub) {
        case 'add':
          $controller->index();
          break;
        case 'edit':
          $controller->index();
          break;
        case 'delete':
          $controller->index();
          break;
        case 'create':
          $controller->index();
          break;
        case 'update':
          $controller->index();
          break;
        case 'view':
          $controller->index();
          break;
        default:
          $controller->index();
          break;
      }
      break;
  case 'logs':
    authorize(['admin', 'staff']);
    require_once '../app/controllers/LogController.php';
    $controller = new LogController($pdo);

    // Handle sub-actions
    $sub = $_GET['sub'] ?? 'list';
    switch ($sub) {
      case 'clear':
        $controller->index();
        break;
      case 'export':
        $controller->index();
        break;
      case 'filter':
        $controller->index();
        break;
      default:
        $controller->index();
        break;
    }
    break;

  case 'profile':
  case 'my_profile': // Support both for compatibility
    authorize(['resident', 'admin', 'staff']);
    require_once '../app/controllers/ProfileController.php';
    $controller = new ProfileController($pdo);

    // Handle sub-actions
    $sub = $_GET['sub'] ?? 'view';
    switch ($sub) {
      case 'edit':
        $controller->index();
        break;
      case 'update':
        $controller->index();
        break;
      case 'change_password':
        $controller->index();
        break;
      case 'update_password':
        $controller->index();
        break;
      default:
        $controller->index();
        break;
    }
    break;

  case 'projects':
    authorize(['admin', 'staff']);
    require_once '../app/controllers/ProjectController.php';
    $controller = new ProjectController($pdo);

    // Handle sub-actions
    $sub = $_GET['sub'] ?? 'list';
    switch ($sub) {
      case 'add':
        $controller->index();
        break;
      case 'edit':
        $controller->index();
        break;
      case 'delete':
        $controller->index();
        break;
      case 'create':
        $controller->index();
        break;
      case 'update':
        $controller->index();
        break;
      case 'view':
        $controller->index();
        break;
      case 'complete':
        $controller->index();
        break;
      case 'cancel':
        $controller->index();
        break;
      default:
        $controller->index();
        break;
    }
    break;

  case 'settings':
    authorize(['admin']);
    require_once '../app/controllers/SettingsController.php';
    $controller = new SettingsController($pdo);

    // Handle sub-actions
    $sub = $_GET['sub'] ?? 'general';
    switch ($sub) {
      case 'save':
        $controller->index();
        break;
      case 'system':
        $controller->index();
        break;
      case 'email':
        $controller->index();
        break;
      case 'security':
        $controller->index();
        break;
      case 'backup':
        $controller->index();
        break;
      case 'database':
        $controller->index();
        break;
      case 'perform_backup':
        $controller->index();
        break;
      case 'test_email':
        $controller->index();
        break;
      default:
        $controller->index();
        break;
    }
    break;


  default:
    // Invalid action - show 404 page
    http_response_code(404);
    require_once '../app/views/Auth/error.php';
    break;
}
