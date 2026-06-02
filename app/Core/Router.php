<?php

namespace App\Core;

use App\Exceptions\NotFoundException;

class Router
{
    private array $routes = [];

    private array $services = [];

    public function registerService(
        string $controller,
        mixed $service
    ): void {
        $this->services[$controller] = $service;
    }

    public function get(
        string $route,
        array $action
    ): void {
        $this->routes['GET'][$route] = $action;
    }

    public function post(
        string $route,
        array $action
    ): void {
        $this->routes['POST'][$route] = $action;
    }

    public function dispatch(
        string $route
    ): void {

        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes[$method] ?? [] as $registeredRoute => $action) {

            $pattern = preg_replace(
                '#\{[a-zA-Z_]+\}#',
                '([0-9]+)',
                $registeredRoute
            );

            if (
                preg_match(
                    '#^' . $pattern . '$#',
                    $route,
                    $matches
                )
            ) {

                [$controllerClass, $methodName] = $action;

                if (!isset($this->services[$controllerClass])) {
                    throw new \Exception(
                        "No service registered for {$controllerClass}"
                    );
                }

                $dependencies =
                    $this->services[$controllerClass];

                if (!is_array($dependencies)) {
                    $dependencies = [$dependencies];
                }

                $controller =
                    new $controllerClass(
                        ...$dependencies
                    );

                array_shift($matches);

                call_user_func_array(
                    [$controller, $methodName],
                    $matches
                );

                return;
            }
        }

        throw new NotFoundException(
            "Route not found: {$route}"
        );
    }
}