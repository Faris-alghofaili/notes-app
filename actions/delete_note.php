<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
redirectIfNotLoggedIn();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['note_id'])) {
    $noteId = $_POST['note_id'];
    
    // Verify the note belongs to the current user before deleting
    $stmt = $pdo->prepare("SELECT user_id FROM notes WHERE id = ?");
    $stmt->execute([$noteId]);
    $note = $stmt->fetch();
    
    if ($note && $note['user_id'] == $_SESSION['user_id']) {
        $stmt = $pdo->prepare("DELETE FROM notes WHERE id = ?");
        $stmt->execute([$noteId]);
    }
}

header('Location: ../notes.php');
exit();
?>