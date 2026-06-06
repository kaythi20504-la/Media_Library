<?php

namespace App\Application\Catalog\UseCases;

use App\Domain\Catalog\Repositories\CatalogRepositoryInterface;

class GetHomePageUseCase
{
    public function __construct(
        private CatalogRepositoryInterface $repo
    ) {}

    public function execute(): array
    {
        return [
            'random' => $this->repo->getRandom(),
            'pageTitle' => 'Personal Media Library',
            'section' => 'catalog'
        ];
    }
}