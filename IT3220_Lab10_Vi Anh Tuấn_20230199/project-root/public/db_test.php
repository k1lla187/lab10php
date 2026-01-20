<?php
// public/db_test.php
declare(strict_types=1);
ini_set('display_errors', '1');
error_reporting(E_ALL);

$DB_HOST = '127.0.0.1';
$DB_NAME = 'lab10_sales';
$DB_USER = 'app_user';
$DB_PASS = 'secret';
$DB_CHARSET = 'utf8mb4';
$dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset={$DB_CHARSET}";

try {
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    echo "PDO connect OK. Server version: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
} catch (PDOException $e) {
    echo "PDO ERROR: " . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    error_log("DB TEST ERROR: " . $e->getMessage());
}