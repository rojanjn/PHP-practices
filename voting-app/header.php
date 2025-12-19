<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Rojan Jafarnezhad">

    <title>Voting App</title>

    <!-- stylesheet -->
    <style>
        /* global styles */
        body {
            margin: 0;
            font-family: 'Segoe UI', Roboto, sans-serif;
            background: #f7f9fc;
            color: #333;
        }
        a {
            text-decoration: none;
            color: inherit;
        }

        /* header and navbar */
        header {
            background: #2a2d43;
            color: #fff;
            padding: 14px 0;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .main-title{
            font-size: 3.5rem;
            text-align: center;
            text-shadow: 2px 2px 5px #686772ff;
        }
        .nav-container {
            max-width: 950px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .nav-container strong {
            color: #fff;
            font-size: 1.4rem;
            font-weight: 600;
        }
        nav a {
            color: #e8e9ef;
            transition: 0.2s ease;
            margin-left: 18px;
            font-size: 0.97rem;
        }
        nav a:hover {
            color: #ffffff;
            opacity: 0.85;
        }

        /* layout container */
        .container {
            max-width: 950px;
            margin: 30px auto;
            background: #fff;
            padding: 28px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.07);
        }

        /* forms */
        input[type=text],
        input[type=password],
        input[type=email],
        textarea,
        select {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px 0;
            border-radius: 10px;
            border: 1px solid #d4d9e2;
            font-size: 0.95rem;
            background: #fdfdfd;
            transition: all 0.15s ease;
        }
        input:focus,
        textarea:focus {
            border-color: #7286d3;
            outline: none;
            box-shadow: 0 0 4px rgba(114, 134, 211, 0.4);
        }
        textarea{
            min-height: 100px;
        }

        /* buttons */
        .btn {
            padding: 10px 20px;
            background: #7286d3;
            color: #fff;
            border: none;
            font-size: 0.95rem;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.2s ease;
        }
        .btn:hover {
            background: #5e6cc5;
        }
        .btn-secondary {
            background: #a6b1e1;
        }
        .btn-danger {
            background: #e66767;
        }
        .btn-danger:hover {
            background: #d25656;
        }
        .btn-secondary:hover {
            background: #909bd4;
        }
        .btn:disabled {
            opacity: 0.5;
            cursor: default;
        }

        /* messages */
        .error {
            background: #ffd0d0;
            padding: 12px;
            border-left: 5px solid #d64545;
            border-radius: 8px;
            margin-bottom: 16px;
            color: #8b1f1f;
            font-size: 0.9rem;
        }
        .success {
            background: #d6f5d6;
            padding: 12px;
            border-left: 5px solid #47a447;
            border-radius: 8px;
            margin-bottom: 16px;
            color: #2e6b2e;
            font-size: 0.9rem;
        }       

        /* topic cards */
        .topic-container {
            max-width: 900px;
            margin: 40px auto;
        }
        .topic-header {
            background: #ffffff;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.07);
            margin-bottom: 25px;
        }
        .topic-header h2 {
            margin-top: 0;
            font-size: 1.8rem;
            color: #2a2d43;
        }
        .topic-meta {
            font-size: 0.85rem;
            color: #666;
            margin-top: 5px;
        }
        .topic-card {
            border-radius: 12px;
            box-shadow: 0px 3px 15px #c5c7d6ff;
            padding: 25px;
            margin: 25px 0;
            margin-bottom: 40px;
        }
        .topic-card>p {
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .topic-card>h2 {
            font-size: 2rem;
            color: #2e2c61ff;
        }
        .topic-actions {
            margin-top: 25px;
        }
        .topic-actions>a {
            background: #2a2d43;
            color: #fff;
            padding: 10px;
            border-radius: 12px;
            box-shadow: 0px 3px 15px #e1e2eeff;
        }
        .topic-actions>a:hover {
            background: #7f8effaf;
            color: #000000ff;
        }
        .section-card {
            background: white;
            padding: 22px 28px;
            margin-top: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.07);
        }
        .section-card h2,
        .section-card h3 {
            margin-top: 0;
            color: #2a2d43;
        }

        /* votes */
        .vote-counts {
            font-size: 1.2rem;
            margin-bottom: 15px;
        }
        .vote-counts span {
            margin-right: 25px;
        }
        .vote-buttons button {
            margin-right: 10px;
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.95rem;
            color: white;
        }
        .vote-up {
            background: #32c759;
        }
        .vote-down {
            background: #ff8c42;
        }
        .comment-form textarea {
            width: 100%;
            min-height: 120px;
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #d4d9e2;
        }
        .comment-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }
        .comment-item:last-child {
            border-bottom: none;
        }
        .comment-item small {
            color: #777;
            font-size: 0.8rem;
        }

        /* comments */
        .comment {
            padding: 12px 0;
            border-bottom: 1px solid #eef1f6;
        }
        .comment:last-child {
            border-bottom: none;
        }

        /* footer */
        footer {
            text-align: center;
            font-size: 0.8rem;
            color: #777;
            margin: 20px 0 40px;
        }
    </style>
</head>
<body>
    <header>
        <div class="nav-container">
            <h2><strong>Voting App</strong></h2>
            <!-- navbar links -->
            <nav>
                <a href="index.php">Topics</a>
                <a href="create_topic.php">Create Topic</a>
                <a href="professor.php">For Prof</a>
                <?php
                if (!empty($_SESSION['user_id'])): ?>
                    <span style="color:#ddd; margin-left:15px;">Hello, <?= htmlspecialchars($_SESSION['username']) ?></span>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>