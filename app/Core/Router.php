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

        if (!isset($this->routes[$method][$route])) {
            http_response_code(404);
            echo "Route not found";
            exit;
        }

        [$controllerClass, $methodName] = $this->routes[$method][$route];

        if (!isset($this->services[$controllerClass])) {
            throw new \Exception("No service registered for $controllerClass");
        }

        $service = $this->services[$controllerClass];

        $controller = new $controllerClass($service);

        $controller->$methodName();
    }
}