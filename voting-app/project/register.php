<?php
// require 'classes.php';
require 'bootstrap.php'; 
include 'header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User($pdo);
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // username, email, password validation
    if ($user->registerUser($username, $email, $password)) {
        $success = 'Registration successful. You can now log in.';
    }
    else {
        $error = 'Registration failed. Check your input or try a different username/email.';
    }
}
?>

<div class="container">
    <h2>Register</h2>
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password (min 9 characters)</label>
        <input type="password" name="password" required>

        <button class="btn" type="submit">Create Account</button>
    </form>
</div>

<?php include 'footer.php'; ?>