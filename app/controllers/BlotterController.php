<?php
// app/controllers/BlotterController.php

// Handle POST for adding blotter
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    if (empty(trim($_POST['complainant'] ?? ''))) $errors[] = "Complainant is required.";
    if (empty(trim($_POST['respondent'] ?? ''))) $errors[] = "Respondent is required.";
    if (empty(trim($_POST['description'] ?? ''))) $errors[] = "Description is required.";

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO blotters (complainant, respondent, description, date) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$_POST['complainant'], $_POST['respondent'], $_POST['description']]);
            $_SESSION['success'] = "Blotter added successfully.";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
    }
    header("Location: index.php?action=blotters");
    exit();
}

// Fetch blotters
$blotters = $pdo->query("SELECT * FROM blotters ORDER BY date DESC")->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Blotter Records";
require '../app/views/blotters.php';
