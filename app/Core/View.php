<?php

namespace App\Core;

class View
{
    public static function render(string $view, array $data = []): void
    {
        $path = BASE_PATH . "/resources/views/{$view}.php";

        if (!file_exists($path)) {
            throw new \Exception("View not found: {$view}");
        }

        extract($data);
        require $path;
    }

    // ADD THIS METHOD to handle things like header, footer, and pagination
    public static function partial(string $partial, array $data = []): void
    {
        $path = BASE_PATH . "/resources/views/{$partial}.php";

        if (file_exists($path)) {
            extract($data);
            require $path;
        }
    }
}