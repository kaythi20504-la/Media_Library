<?php

namespace App\Controllers;

use App\Services\CatalogService;

class DetailsController extends BaseController
{
    private CatalogService $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function show(): void
    {
        // Protect route
        $this->requireLogin();

        // Get ID safely
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        // Invalid ID → redirect
        if (!$id) {
            $this->redirect('index.php?page=catalog');
            return;
        }

        // Get item
        $item = $this->catalogService->getById($id);

        // Not found → redirect
        if (!$item) {
            $this->redirect('index.php?page=catalog');
            return;
        }

        // Render view properly
        $this->view('details', [
            'item' => $item,
            'user' => $this->user()
        ]);
    }
}