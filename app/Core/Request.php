<?php

namespace App\Core;

class Request
{
    public function input(string $key, $default = null)
    {
        return $_REQUEST[$key] ?? $default;
    }

    public function get(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    public function post(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    public function all(): array
    {
        return $_REQUEST;
    }
}