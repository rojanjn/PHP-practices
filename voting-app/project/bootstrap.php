<?php
// bootstrap.php
session_start();
// load DB config
$config = require __DIR__ . '/db.config.php';

// use the app database
$db = $config['app'];

$dsn = "mysql:host={$db['host']};dbname={$db['dbname']};charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $db['username'], $db['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("DB Connection Failed: " . $e->getMessage());
}

// load all classes (User, Topic, Vote, Comment, TimeFormatter)
require __DIR__ . '/classes.php';
