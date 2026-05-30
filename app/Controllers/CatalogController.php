<?php

namespace App\Controllers;

use App\Services\CatalogService;
use App\Exceptions\NotFoundException;

class CatalogController extends BaseController
{
    public function __construct(
        private CatalogService $catalogService
    ) {}

    /*
     * Home Page
     */
    public function home(): void
    {
         $this->requireLogin();

        $data = $this->catalogService->getHomePageData();

        $data['user'] = $this->user();

        $this->view('home', $data);
    }

    /*
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
    public function show(int $id): void
    {
        $item = $this->catalogService->getById($id);

        if ($item === null) {
            throw new NotFoundException("Item not found (ID: $id)");
        }

        $this->view('details', [
            'item' => $item,
            'user' => $this->user()
        ]);
    }
}