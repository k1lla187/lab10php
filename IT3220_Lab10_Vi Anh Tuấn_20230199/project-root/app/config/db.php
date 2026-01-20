<?php
// app/config/db.php - robust connection (edit credentials)
declare(strict_types=1);

$DB_HOST = '127.0.0.1';
$DB_NAME = 'lab10_sales';
$DB_USER = 'app_user';   // <-- chỉnh lại
$DB_PASS = 'secret';     // <-- chỉnh lại
$DB_CHARSET = 'utf8mb4';

$dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset={$DB_CHARSET}";
try {
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    // Log with context
    $msg = sprintf(
        "[%s] PDO connection failed. dsn=%s user=%s host=%s error=%s",
        date('Y-m-d H:i:s'),
        $dsn,
        $DB_USER,
        $DB_HOST,
        $e->getMessage()
    );
    error_log($msg);
    // Optionally also log to a file:
    // error_log($msg . PHP_EOL, 3, __DIR__ . '/../../logs/app_error.log');

    http_response_code(500);
    echo "Database connection error. Check logs.";
    exit;
}

// helper function
function getPDO(): PDO {
    global $pdo;
    return $pdo;
}