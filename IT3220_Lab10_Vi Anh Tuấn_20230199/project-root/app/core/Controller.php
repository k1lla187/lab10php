<?php
// app/core/Controller.php
declare(strict_types=1);

class Controller
{
    protected function render(string $view, array $data = [])
    {
        extract($data, EXTR_SKIP);
        $viewFile = APP_PATH . '/views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            throw new RuntimeException("View not found: {$viewFile}");
        }
        include APP_PATH . '/views/layout.php';
    }

    protected function redirect(string $url)
    {
        header('Location: ' . $url);
        exit;
    }

    // Flash messages
    protected function setFlash(string $key, string $message)
    {
        $_SESSION['flash'][$key] = $message;
    }

    protected function getFlash(string $key)
    {
        if (!empty($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
        return null;
    }
}