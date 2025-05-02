<?php
require_once 'config/database.php';
require_once 'includes/auth.php';
redirectIfNotLoggedIn();

$error = '';
$notes = [];

// Fetch notes for the current user
$stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$notes = $stmt->fetchAll();
?>

<?php include 'includes/header.php'; ?>

<div class="notes-container">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
    
    <div class="add-note">
        <h3>Add New Note</h3>
        <form action="actions/add_note.php" method="post">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea id="content" name="content" rows="4" required></textarea>
            </div>
            <button type="submit">Add Note</button>
        </form>
    </div>
    
    <div class="notes-list">
        <h3>Your Notes</h3>
        <?php if (empty($notes)): ?>
            <p>No notes yet. Add your first note above!</p>
        <?php else: ?>
            <?php foreach ($notes as $note): ?>
                <div class="note">
                    <h4><?php echo htmlspecialchars($note['title']); ?></h4>
                    <p><?php echo nl2br(htmlspecialchars($note['content'])); ?></p>
                    <small><?php echo date('M j, Y g:i a', strtotime($note['created_at'])); ?></small>
                    <form action="actions/delete_note.php" method="post" class="delete-form">
                        <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">
                        <button type="submit" class="delete-btn">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>