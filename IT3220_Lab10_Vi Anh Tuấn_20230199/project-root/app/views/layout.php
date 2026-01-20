<?php
// app/views/layout.php
declare(strict_types=1);
if (!function_exists('e')) {
    function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
}
?><!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Mini Sales App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php">Mini Sales</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="index.php?c=products&a=index">Products</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?c=customers&a=index">Customers</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?c=orders&a=index">Orders</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container">
    <?php if (!empty($flash_success)): ?>
        <div class="alert alert-success"><?= e($flash_success) ?></div>
    <?php endif; ?>
    <?php if (!empty($flash_error)): ?>
        <div class="alert alert-danger"><?= e($flash_error) ?></div>
    <?php endif; ?>

    <?php include $viewFile; ?>

</div>
</body>
</html>