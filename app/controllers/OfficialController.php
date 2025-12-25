<?php
// Fetch all officials for display
try {
    $stmt = $pdo->query("SELECT * FROM officials ORDER BY term_start DESC");
    $officials = $stmt->fetchAll();
} catch (PDOException $e) {
    $_SESSION['error'] = "Failed to load officials: " . $e->getMessage();
    $officials = [];
}
