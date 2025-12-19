<?php
// require 'classes.php';
require 'bootstrap.php';
include 'header.php';

$topicObj = new Topic($pdo);
$topics = $topicObj->getTopics();
?>

<div class="container">
    <h2 class="main-title">All Topics</h2>
    
    <?php if (empty($topics)): ?>
    <p>No topics yet. Go create one.</p>
    <?php else: ?>

        <?php foreach ($topics as $topic): ?>
            <div class="topic-card">
                <h2><?php echo htmlspecialchars($topic->title); ?></h2>
                <div class="topic-meta">
                    Topic ID: <?php echo (int)$topic->id; ?><br>
                    Posted: <?= TimeFormatter::formatTimestamp(strtotime($topic->created_at)) ?>
                </div>
                <p>
                    <?php echo nl2br(htmlspecialchars($topic->description)); ?>
                </p>
                <div class="topic-actions">
                    <a href="topic.php?id=<?php echo (int)$topic->id; ?>">View / Vote / Comment</a>
                </div>
            </div>
        <?php endforeach; ?>

    <?php endif; ?>

<?php include 'footer.php'; ?>