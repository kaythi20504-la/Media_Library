<?php

namespace App\Controllers;
use App\Services\CatalogService;

class CatalogController extends BaseController
{
    private CatalogService $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    public function home(): void
    {
        $data = $this->catalogService->getHomePageData();

        $this->view('home', $data);
    }

    public function index(): void
    {
        $data = $this->catalogService->getCatalogPage($_GET);

        $this->view('catalog', $data);
    }
}
