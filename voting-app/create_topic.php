<?php
// session_start();

// require 'classes.php';
require 'bootstrap.php';

// must be logged in BEFORE output starts
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    $topic = new Topic($pdo);

    if ($topic->createTopic($_SESSION['user_id'], $title, $description)) {
        $success = "Topic created successfully!";
    }
    else {
        $error = "Failed to create topic. Make sure you filled all required fields.";
    }
}
?>

<div class="container">
    <h2>Create a Topic</h2>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Title</label>
        <input type="text" name="title" required>
        
        <label>Description</label>
        <textarea name="description"></textarea>

        <button class="btn" type="submit">Publish Topic</button>
    </form>
</div>

<?php include 'footer.php'; ?>