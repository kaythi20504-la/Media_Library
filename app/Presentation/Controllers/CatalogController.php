<?php

declare(strict_types=1);

namespace App\Presentation\Controllers;

use App\Presentation\Controllers\BaseController;
use App\Application\Catalog\UseCases\GetHomePageUseCase;
use App\Application\Catalog\UseCases\GetCatalogPageUseCase;
use App\Application\Catalog\UseCases\GetCatalogItemUseCase;
use App\Exceptions\NotFoundException;

class CatalogController extends BaseController
{
    public function __construct(
        private GetHomePageUseCase $homeUseCase,
        private GetCatalogPageUseCase $catalogUseCase,
        private GetCatalogItemUseCase $itemUseCase
    ) {}

    /*
     * Home Page
     */
    public function home(): void
    {
        $this->requireLogin();

        $data = $this->homeUseCase->execute();

        $data['user'] = $this->user();

        $this->view('home', $data);
    }

    /*
     * Catalog Page
     */
    public function index(): void
    {
        $this->requireLogin();

        $data = $this->catalogUseCase->execute($_GET);

        $data['user'] = $this->user();

        $this->view('catalog', $data);
    }

    /**
     * Details Page
     */
    public function show(int $id): void
    {
        $item = $this->itemUseCase->execute($id);

        if ($item === null) {
            throw new NotFoundException("Item not found (ID: $id)");
        }

        $this->view('details', [
            'item' => $item,
            'user' => $this->user()
        ]);
    }
}