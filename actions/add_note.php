<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
redirectIfNotLoggedIn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    
    if (empty($title) || empty($content)) {
        $_SESSION['error'] = 'Title and content are required';
        header('Location: ../notes.php');
        exit();
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO notes (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $title, $content]);
        header('Location: ../notes.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Failed to add note';
        header('Location: ../notes.php');
        exit();
    }
}

header('Location: ../notes.php');
exit();
?>