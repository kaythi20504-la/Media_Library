<?php

namespace App\Error;

class Renderer
{
    public function __construct(
        private string $viewPath
    ) {}

    public function render(Response $response): void
    {
        http_response_code($response->statusCode);

        if ($this->isApi()) {
            header('Content-Type: application/json');

            echo json_encode([
                'status' => $response->statusCode,
                'message' => $response->message ?? 'Error',
                'data' => $response->data ?? null
            ]);

            exit;
        }

        $file = $this->resolveView($response->view);

        require $file;
        exit;
    }

    private function resolveView(string $view): string
    {
        $file = $this->viewPath . '/errors/' . $view . '.php';

        if (file_exists($file)) {
            return $file;
        }

        return $this->viewPath . '/errors/500.php';
    }

    private function isApi(): bool
    {
        return str_contains($_SERVER['REQUEST_URI'] ?? '', '/api');
    }
}