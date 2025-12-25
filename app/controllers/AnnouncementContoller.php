<?php
try {
    $stmt = $pdo->query("SELECT * FROM announcements ORDER BY created_at DESC");
    $announcements = $stmt->fetchAll();
} catch (PDOException $e) {
    $_SESSION['error'] = "Failed to load announcements.";
    $announcements = [];
}


// Handle POST for adding announcement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    if (empty(trim($_POST['title'] ?? ''))) $errors[] = "Title is required.";
    if (empty(trim($_POST['content'] ?? ''))) $errors[] = "Content is required.";

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO announcements (title, content, date) VALUES (?, ?, NOW())");
            $stmt->execute([$_POST['title'], $_POST['content']]);
            $_SESSION['success'] = "Announcement added successfully.";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
    }
    header("Location: index.php?action=announcements");
    exit();
}

// Fetch announcements for view
$announcements = $pdo->query("SELECT * FROM announcements ORDER BY date DESC")->fetchAll(PDO::FETCH_ASSOC);
