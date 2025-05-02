<?php
require_once '../config/database.php';
require_once '../includes/auth.php';

if (isLoggedIn()) {
    header('Location: ../notes.php');
    exit();
}

$error = '';
$success = '';
$username_value = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $username_value = htmlspecialchars($username); // For sticking the form
    
    // Validate username
    if (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters';
    } 
    // Validate password
    elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    }
    // Check password match
    elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    }
    // Check password complexity (optional)
    elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $error = 'Password must contain at least one uppercase letter and one number';
    }
    else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hashedPassword]);
            $success = 'Registration successful. Please login.';
            // Clear the username value after successful registration
            $username_value = '';
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = 'Username already taken';
            } else {
                $error = 'Registration failed: ' . $e->getMessage();
            }
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="auth-container">
    <div class="auth-form">
        <h2>Register</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $username_value; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <small class="hint">At least 6 characters with one uppercase letter and one number</small>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit">Register</button>
        </form>
        <p class="auth-switch">Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>