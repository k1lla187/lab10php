<?php
// app/core/View.php - optional helpers used by layout/views
declare(strict_types=1);

function e($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}