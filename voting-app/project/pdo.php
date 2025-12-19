<?php
// PDO -> creates a PDO Object for the app database and returns it

$config = require __DIR__ . '/db.config.php';

// get the app connection settings
$appConfig = $config['app'];

$host = $appConfig['host'];
$dbname = $appConfig['dbname'];
$username = $appConfig['username'];
$password = $appConfig['password'];

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // throw exceptions when there are errors
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // fetch rows as associative arrays by default
} catch (PDOException $e) {
    //if something goes wrong, stop the script and show an error message
    die('Database connection failed: '. $e->getMessage());
}

return $pdo;