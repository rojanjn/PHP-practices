<?php
// require 'classes.php';
require 'bootstrap.php'; 

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = new User($pdo);

    // successful login
    if ($user->authenticateUser($username, $password)) {
        // getting user's info from the database
        $stmt = $pdo->prepare("SELECT id, username FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // setting the session variables
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];

        // redirecting wherever you want after login
        header("Location: index.php");
        exit;
    }
    else {
        $error = 'Invalid username or password.';
    }
}

include 'header.php';
?>

<div class="container">
    <h2>Login</h2>
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button class="btn" type="submit">Login</button>
    </form>
</div>

<?php include 'footer.php'; ?>