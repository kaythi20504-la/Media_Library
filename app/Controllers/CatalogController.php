<?php

namespace App\Controllers;

use App\Services\CatalogService;

class CatalogController extends BaseController
{
    private CatalogService $catalogService;

    public function __construct(
        CatalogService $catalogService
    ) {

        $this->catalogService =
            $catalogService;
    }

    /**
     * Home Page
     */
    public function home(): void
    {
            $this->requireLogin();

        $data = $this->catalogService
            ->getHomePageData();

        // Current logged in user
        $data['user'] = $this->user();

        $this->view(
            'home',
            $data
        );
    }

    /**
     * Catalog Page
     */
    public function index(): void
{
    $this->requireLogin();

    $data = $this->catalogService->getCatalogPage($_GET);

    $data['user'] = $this->user();

    $this->view('catalog', $data);
}

    /**
     * Details Page
     */
    public function show(
        int $id
    ): void {

        $item = $this->catalogService
            ->getById($id);

        if ($item === null) {

            http_response_code(404);

            echo 'Item not found';

            return;
        }

        $this->view(
            'details',
            [
                'item' => $item,
                'user' => $this->user()
            ]
        );
    }
}