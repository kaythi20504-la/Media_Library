<?php

namespace App\Application\Catalog\UseCases;

use App\Domain\Catalog\Repositories\CatalogRepositoryInterface;

class GetCatalogItemUseCase
{
    public function __construct(
        private CatalogRepositoryInterface $repo
    ) {}

    public function execute(int $id): ?array
    {
        return $this->repo->getById($id);
    }
}