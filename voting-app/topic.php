<?php
require 'bootstrap.php'; 
// include 'header.php';

// require a topic id
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    die('Invalid topic ID');
}

$topicId = (int) $_GET['id'];

// checking login status
$loggedIn = isset($_SESSION['user_id']);

// fetching the topic
$stmt = $pdo->prepare("
    SELECT t.*, u.username
    FROM topics t
    JOIN users u ON t.user_id = u.id
    WHERE t.id = ? 
");
$stmt->execute([$topicId]);
$topic = $stmt->fetch();

if (!$topic) {
    die('Topic not found');
}

$voteObj = new Vote($pdo);
$commentObj = new Comment($pdo);

$message = '';
$error = '';

// handling voting actions and history
if ($loggedIn && isset($_POST['vote_type'])) {
    $voteType = $_POST['vote_type']; // voted up or down

    if ($voteObj->vote($_SESSION['user_id'], $topicId, $voteType)) {
        $message = "Your vote has been recorded.";
    }
    else {
        $error = "You already voted on this topic or the vote type is invalid.";
    }
}

// handling adding a comment
if ($loggedIn && isset($_POST['add_comment'])) {
    $commentTxt = trim($_POST['comment'] ?? '');

    if ($commentObj->addComment($_SESSION['user_id'], $topicId, $commentTxt)) {
        $message = "Comment added.";
    }
    else {
        $error = "Comment failed. Make sure it's not empty.";
    }
}

// getting comments
$comments = $commentObj->getComments($topicId);

// vote counts
$voteCount = $pdo->prepare("
    SELECT vote_type, COUNT(*) AS total
    FROM votes
    WHERE topic_id = ?
    GROUP BY vote_type
");
$voteCount->execute([$topicId]);

$voteC = ['up' => 0, 'down' => 0];
while ($row = $voteCount->fetch()) {
    $voteC[$row['vote_type']] = (int)$row['total'];
}
?>
<?php include 'header.php'; ?>

<div class="topic-container">
    <p><a href="index.php">&larr; Back to Topics</a></p>

    <div class="topic-header">
        <h2><?= htmlspecialchars($topic['title']); ?></h2>

        <p class="meta">
            Created by <?= htmlspecialchars($topic['username']); ?>
            &nbsp;•&nbsp;
            Topic ID: <?php echo (int)$topic['id']; ?>
        </p>

        <p><?php echo nl2br(htmlspecialchars($topic['description'])); ?></p>
    </div>

    <?php if ($message): ?>
        <div class="success"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="vote_box">
        <h2>Votes</h2>

        <p>
            👍 <?= $voteC['up']; ?>
            &nbsp;&nbsp;&nbsp;
            👎 <?= $voteC['down']; ?>
        </p>

        <?php if ($loggedIn): ?>
            <form method="post">
                <button type="submit" name="vote_type" value="up" class="vote-up">👍 Vote Up</button>
                <button type="submit" name="vote_type" value="down" class="vote-down">👎 Vote Down</button>
            </form>
        <?php else: ?>
            <p><a href="login.php">Log in</a> to vote.</p>
        <?php endif; ?>
    </div>

    <div class="comment-form">
        <h2>Add a Comment</h2>

        <?php if ($loggedIn): ?>
            <form method="post">
                <textarea name="comment" rows="3" required></textarea>
                <br><br>
                <button type="submit" name="add_comment">Post Comment</button>
            </form>
        <?php else: ?>
            <p><a href="login.php">Log in</a> to comment.</p>
        <?php endif; ?>
    </div>

    <div class="comments-list">
        <h2>Comments</h2>

        <?php if (empty($comments)): ?>
            <p>No comments yet. Be the first.</p>
        <?php else: ?>
            <?php foreach ($comments as $c): ?>
                <div class="comment">
                    <p><?php echo nl2br(htmlspecialchars($c['comment'])); ?></p>
                    <p>
                        <small>
                            User ID: <?php echo (int)$c['user_id']; ?>
                            &nbsp;•&nbsp;
                            <?= htmlspecialchars($c['commented_at']); ?>
                        </small>
                    </p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

<?php
include 'footer.php';
?>