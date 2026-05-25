<?php

namespace App\Controllers\Api;

use App\Services\CatalogService;

class ApiCatalogController
{
    private CatalogService $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function index(): void
    {
        header('Content-Type: application/json');

        $data = $this->catalogService->getCatalogPage($_GET);

        echo json_encode($data, JSON_PRETTY_PRINT);
        exit; // Stop execution so no HTML is appended
    }
}