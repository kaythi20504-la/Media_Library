<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private array $services = [];

    public function registerService(string $controller, object $service): void
    {
        $this->services[$controller] = $service;
    }

    public function get(string $route, array $action): void
    {
        $this->routes['GET'][$route] = $action;
    }

    public function post(string $route, array $action): void
    {
        $this->routes['POST'][$route] = $action;
    }

 public function dispatch(string $route): void
{
    $method = $_SERVER['REQUEST_METHOD'];

    foreach ($this->routes[$method] ?? [] as $registeredRoute => $action) {

        $pattern = preg_replace('#\{[a-zA-Z_]+\}#', '([0-9]+)', $registeredRoute);

        if (preg_match('#^' . $pattern . '$#', $route, $matches)) {

            [$controllerClass, $methodName] = $action;

            if (!isset($this->services[$controllerClass])) {
                throw new \Exception("No service registered for $controllerClass");
            }

            $service = $this->services[$controllerClass];

            $controller = new $controllerClass($service);

            array_shift($matches); // remove full match

            $controller->$methodName(...$matches);

            return;
        }
    }

    http_response_code(404);
    echo "Route not found";
    exit;
}
}