<?php
// app/controllers/residents/update_resident.php
session_start();
require_once '../../config/dbcon.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Unauthorized access.';
    header("Location: ../../public/index.php?action=login");
    exit();
}

// Check user role
$role = $_SESSION['role'] ?? '';
if (!in_array($role, ['admin', 'staff'])) {
    $_SESSION['error'] = 'Permission denied.';
    header("Location: ../../public/index.php?action=dashboard");
    exit();
}

// Check if it's a POST request
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    $_SESSION['error'] = 'Invalid request method.';
    header("Location: ../../public/index.php?action=residents");
    exit();
}

// Validate required fields
$required_fields = ['id', 'full_name', 'address', 'birthdate', 'gender', 'civil_status'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $_SESSION['error'] = "Required field '$field' is missing.";
        header("Location: ../../public/index.php?action=residents");
        exit();
    }
}

$id = (int)$_POST['id'];

// Validate and sanitize inputs
$full_name = trim(htmlspecialchars($_POST['full_name'] ?? ''));
$address = trim(htmlspecialchars($_POST['address'] ?? ''));
$birthdate = $_POST['birthdate'] ?? '';
$gender = $_POST['gender'] ?? '';
$contact_number = trim(htmlspecialchars($_POST['contact_number'] ?? ''));
$occupation = trim(htmlspecialchars($_POST['occupation'] ?? ''));
$civil_status = $_POST['civil_status'] ?? '';

// Validate gender
$valid_genders = ['male', 'female', 'other'];
if (!in_array($gender, $valid_genders)) {
    $_SESSION['error'] = 'Invalid gender selected.';
    header("Location: ../../public/index.php?action=residents&sub=edit&id=$id");
    exit();
}

// Validate civil status
$valid_civil_statuses = ['single', 'married', 'widowed', 'divorced'];
if (!in_array($civil_status, $valid_civil_statuses)) {
    $_SESSION['error'] = 'Invalid civil status selected.';
    header("Location: ../../public/index.php?action=residents&sub=edit&id=$id");
    exit();
}

try {
    // Check if resident exists
    $checkStmt = $pdo->prepare("SELECT id FROM tbl_residents WHERE id = ?");
    $checkStmt->execute([$id]);

    if (!$checkStmt->fetch()) {
        $_SESSION['error'] = 'Resident not found.';
        header("Location: ../../public/index.php?action=residents");
        exit();
    }

    // Update resident
    $stmt = $pdo->prepare("UPDATE tbl_residents SET
        full_name = ?,
        address = ?,
        birthdate = ?,
        gender = ?,
        contact_number = ?,
        occupation = ?,
        civil_status = ?,
        updated_at = CURRENT_TIMESTAMP
        WHERE id = ?");

    $stmt->execute([
        $full_name,
        $address,
        $birthdate,
        $gender,
        $contact_number,
        $occupation,
        $civil_status,
        $id
    ]);

    // Log the action
    $logStmt = $pdo->prepare("INSERT INTO tbl_logs (user_id, action) VALUES (?, ?)");
    $logStmt->execute([$_SESSION['user_id'], "Updated resident: $full_name (ID: $id)"]);

    $_SESSION['success'] = "Resident updated successfully.";

} catch (PDOException $e) {
    error_log("Update resident error: " . $e->getMessage());
    $_SESSION['error'] = "Failed to update resident: " . $e->getMessage();
}

// Redirect back to residents page
header("Location: ../../public/index.php?action=residents");
exit();
?>
