<?php

declare(strict_types=1);

final class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $path): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $pattern = preg_replace('#\{[^}]+\}#', '([^/]+)', $route);
            if (preg_match('#^' . $pattern . '$#', $path, $matches)) {
                array_shift($matches);
                [$controller, $action] = $handler;
                (new $controller())->$action(...$matches);
                return;
            }
        }

        http_response_code(404);
        view('errors/404', ['pageTitle' => 'Página no encontrada']);
    }
}
