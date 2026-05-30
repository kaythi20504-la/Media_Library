<?php

namespace App\Controllers;

use App\Exceptions\ValidationException;
use App\Exceptions\NotFoundException;

abstract class BaseController
{
    protected function view(string $path, array $data = []): void
    {
        extract($data);
        require BASE_PATH . '/resources/views/' . $path . '.php';
    }

    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    protected function json(array $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }

    protected function get(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    protected function post(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    /* =========================
       AUTH GUARDS (CLEAN VERSION)
    ========================= */

    protected function requireLogin(): void
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . '/Public/index.php?page=login');
        exit;
    }
}
    protected function guestOnly(): void
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect(BASE_URL . '/Public/index.php?page=home');
        }
    }

    protected function user(): ?array
    {
        return isset($_SESSION['user_id'])
            ? [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['username'] ?? 'User'
            ]
            : null;
    }

    protected function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /* =========================
       ERROR HELPERS (OPTIONAL)
    ========================= */

    // protected function throwValidation(array $errors): void
    // {
    //     throw new ValidationException("Validation failed", $errors);
    // }
}