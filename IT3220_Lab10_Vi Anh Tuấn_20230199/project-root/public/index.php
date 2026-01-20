<?php
// public/index.php - Front Controller
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../app/bootstrap.php';

$router = new Router();
$router->dispatch();