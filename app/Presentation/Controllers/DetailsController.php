<?php

declare(strict_types=1);

namespace App\Presentation\Controllers;

use App\Presentation\Controllers\BaseController;
use App\Application\Catalog\UseCases\GetCatalogItemUseCase;
use App\Exceptions\NotFoundException;

class DetailsController extends BaseController
{
    public function __construct(
        private GetCatalogItemUseCase $itemUseCase
    ) {}

    public function show(): void
    {
        // Protect route
        $this->requireLogin();

        // Get ID safely
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        // Invalid ID → redirect
        if (!$id) {
            $this->redirect(BASE_URL . '/Public/index.php?page=catalog');
            return;
        }

        // Get item from UseCase
        $item = $this->itemUseCase->execute($id);

        // Not found → redirect
        if (!$item) {
            $this->redirect(BASE_URL . '/Public/index.php?page=catalog');
            return;
        }

        // Render view
        $this->view('details', [
            'item' => $item,
            'user' => $this->user()
        ]);
    }
}