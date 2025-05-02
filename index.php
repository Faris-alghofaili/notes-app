<?php
require_once 'config/database.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header('Location: notes.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: notes.php');
            exit();
        } else {
            $error = 'Invalid username or password';
        }
    } elseif (isset($_POST['register'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        
        if (strlen($username) < 3) {
            $error = 'Username must be at least 3 characters';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            try {
                $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                $stmt->execute([$username, $hashedPassword]);
                $success = 'Registration successful. Please login.';
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $error = 'Username already taken';
                } else {
                    $error = 'Registration failed';
                }
            }
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="auth-container">
    <div class="auth-form">
        <h2>Login</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="hidden" name="login" value="1">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
    
    <div class="auth-form">
        <h2>Register</h2>
        <form method="post">
            <input type="hidden" name="register" value="1">
            <div class="form-group">
                <label for="reg_username">Username:</label>
                <input type="text" id="reg_username" name="username" required>
            </div>
            <div class="form-group">
                <label for="reg_password">Password:</label>
                <input type="password" id="reg_password" name="password" required>
            </div>
            <button type="submit">Register</button>
        </form>
    </div>
</div>

<?php
// Redirect to login page by default
header('Location: auth/login.php');
exit();
?>