<?php
// Fetch all residents
try {
    $stmt = $pdo->query("SELECT id, full_name, address, birthdate, contact_number, status FROM residents ORDER BY full_name");
    $residents = $stmt->fetchAll();
} catch (PDOException $e) {
    $_SESSION['error'] = "Failed to load residents: " . $e->getMessage();
    $residents = [];
}

// Handle delete request (via GET for simplicity, or change to POST)
if (isset($_GET['delete_id']) && in_array($_SESSION['role'], ['admin', 'staff'])) {
    $delete_id = (int)$_GET['delete_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM residents WHERE id = ?");
        $stmt->execute([$delete_id]);
        $_SESSION['success'] = "Resident deleted successfully.";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Delete failed: " . $e->getMessage();
    }
    header("Location: index.php?action=residents");
    exit();
}
