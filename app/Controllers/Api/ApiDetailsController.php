<?php

declare(strict_types=1);

namespace App\Controllers\Api;

use App\Services\CatalogService;
use Exception;

class ApiDetailsController
{
    private CatalogService $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function show(): void
    {
        header('Content-Type: application/json');

        try {
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

            if (!$id) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid or missing ID'
                ]);
                exit;
            }

            $item = $this->catalogService->getSingleItem($id);

            if (empty($item)) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Item not found'
                ]);
                exit;
            }

            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Item fetched successfully',
                'data' => $item
            ], JSON_PRETTY_PRINT);
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Server Error: ' . $e->getMessage()
            ]);
            exit;
        }
    }
}