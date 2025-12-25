<?php
// app/controllers/CrimeController.php

// Handle POST for adding crime
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    if (empty(trim($_POST['type'] ?? ''))) $errors[] = "Type is required.";
    if (empty(trim($_POST['description'] ?? ''))) $errors[] = "Description is required.";

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO crimes (type, description, date) VALUES (?, ?, NOW())");
            $stmt->execute([$_POST['type'], $_POST['description']]);
            $_SESSION['success'] = "Crime record added successfully.";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
    }
    header("Location: index.php?action=crimes");
    exit();
}

// Fetch crimes
$crimes = $pdo->query("SELECT * FROM crimes ORDER BY date DESC")->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Crime Records";
require '../app/views/crimes.php';
