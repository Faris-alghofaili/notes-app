<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes App</title>
    <link rel="stylesheet" href="<?php echo dirname($_SERVER['PHP_SELF']) === '/' ? '' : '../'; ?>style.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/svg+xml" href="<?php echo dirname($_SERVER['PHP_SELF']) === '/' ? '' : '../'; ?>logo.svg">
<link rel="icon" type="image/svg+xml" href="logo.svg">

</head>
<body>
    <header>
        <h1>Notes📝</h1>
        <nav>
    <?php if (isLoggedIn()): ?>
        <a href="notes.php">My Notes</a>
        <a href="auth/logout.php">Logout</a>
    <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    <?php endif; ?>
</nav>
    </header>
    <main>