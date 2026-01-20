<?php
// app/core/Router.php
declare(strict_types=1);

class Router
{
    protected $controller = 'products';
    protected $action = 'index';

    public function dispatch()
    {
        $c = $_GET['c'] ?? $this->controller;
        $a = $_GET['a'] ?? $this->action;

        $controllerName = ucfirst($c) . 'Controller';
        $actionName = $a . 'Action';

        if (!class_exists($controllerName)) {
            http_response_code(404);
            echo "Controller not found: {$controllerName}";
            return;
        }

        $controller = new $controllerName();
        if (!method_exists($controller, $actionName)) {
            http_response_code(404);
            echo "Action not found: {$actionName}";
            return;
        }

        $controller->{$actionName}();
    }
}