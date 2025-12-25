<?php
session_start();

require_once '../config/dbcon.php';                 // Database connection
require_once '../app/middleware/auth.php';         // Authentication middleware

// Determine the action from URL (e.g., index.php?action=officials)
$action = $_GET['action'] ?? 'dashboard';


switch ($action) {

 case 'login':
        guest_only();
        require_once '../app/controllers/Auth/LoginController.php';

        break;

    case 'register':
        guest_only();
        require_once '../app/controllers/Auth/RegisterController.php';
        break;

    case 'logout':
        require_once '../app/controllers/Auth/LogoutController.php';
        break;

    case 'dashboard':
        authorize(['admin', 'staff', 'resident']);
        require_once '../app/controllers/DashboardController.php';
        require_once '../app/views/dashboard.php';
        break;

    case 'residents':
        authorize(['admin', 'staff']);
        require_once '../app/controllers/ResidentsController.php';
        require_once '../app/views/residents.php';
        break;

    case 'certifications':
        authorize(['admin', 'staff']);
        require_once '../app/controllers/CertificationController.php';
        require_once '../app/views/certifications.php';
        break;

    case 'blotters':
        authorize(['admin', 'staff']);
        require_once '../app/controllers/BlotterController.php';
        require_once '../app/views/blotters.php';
        break;

    case 'crimes':
        authorize(['admin', 'staff']);
        require_once '../app/controllers/CrimeController.php';
        require_once '../app/views/crimes.php';
        break;

    case 'events':
        authorize(['admin', 'staff']);
        require_once '../app/controllers/EventController.php';
        require_once '../app/views/events.php';
        break;

    case 'officials':
        authorize(['admin', 'staff']);
        require_once '../app/controllers/OfficialController.php';

        // Special POST handling for adding an official
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];

            if (empty(trim($_POST['name'] ?? ''))) {
                $errors[] = "Name is required.";
            }
            if (empty(trim($_POST['position'] ?? ''))) {
                $errors[] = "Position is required.";
            }

            if (empty($errors)) {
                try {
                    // Insert logic here (example)
                    // $stmt = $pdo->prepare("INSERT INTO officials (...) VALUES (...)");
                    // $stmt->execute([...]);

                    $_SESSION['success'] = "Official added successfully.";
                } catch (PDOException $e) {
                    $_SESSION['error'] = "Database error: " . $e->getMessage();
                }
            } else {
                $_SESSION['error'] = implode("<br>", $errors);
            }

            header("Location: index.php?action=officials");
            exit();
        }

        require_once '../app/views/officials.php';
        break;

    case 'announcements':
        authorize(['admin', 'staff']);
        require_once '../app/controllers/AnnouncementController.php';
        require_once '../app/views/announcements.php';
        break;

    case 'logs':
        authorize(['admin', 'staff']);
        require_once '../app/controllers/LogController.php';
        require_once '../app/views/logs.php';
        break;

    case 'profile':
        authorize(['resident']);
        require_once '../app/controllers/ProfileController.php';
        require_once '../app/views/profile.php';
        break;

    case 'my_profile':
        authorize(['resident', 'admin', 'staff']);
        require_once '../app/controllers/ProfileController.php';
        require_once '../app/views/my_profile.php';
        break;

    case 'request-certification':
        authorize(['resident']);
        require_once '../app/controllers/CertificationController.php';
        require_once '../app/views/request_certification.php';
        break;

    case 'projects':
        authorize(['admin', 'staff']);
        // No controller needed (view-only)
        require_once '../app/views/projects.php';
        break;

    case 'settings':
        authorize(['admin']);
        // No controller needed (view-only)
        require_once '../app/views/settings.php';
        break;

    default:
        // Invalid action
        http_response_code(404);
        require_once '../app/views/Auth/error.php'; // Or your custom 404 page
        break;
}
