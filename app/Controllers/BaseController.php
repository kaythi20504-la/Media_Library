<?php

namespace App\Controllers;

/**
 * Base Controller
 * Shared helper methods for all controllers
 */
abstract class BaseController
{
    /**
     * Render a view file
     */
    protected function view(string $path, array $data = []): void
    {
        extract($data);

        require BASE_PATH . '/resources/views/' . $path . '.php';
    }

    /**
     * Redirect helper
     */
    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    /**
     * JSON response helper (for APIs)
     */
    protected function json(array $data, int $code = 200): void
    {
        http_response_code($code);

        header('Content-Type: application/json');

        echo json_encode($data, JSON_PRETTY_PRINT);

        exit;
    }

    /**
     * Get GET parameter safely
     */
    protected function get(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Get POST parameter safely
     */
    protected function post(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

 protected function requireLogin(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = 'Please login first';
        $this->redirect('index.php?page=login');
    }
}

protected function guestOnly(): void
{
    if (isset($_SESSION['user'])) {
        $this->redirect('index.php?page=catalog');
    }
}

    /**
     * Get current logged in user
     */
    protected function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Check authentication status
     */
    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }
}