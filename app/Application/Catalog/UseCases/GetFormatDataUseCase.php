<?php

declare(strict_types=1);

namespace App\Application\Catalog\UseCases;

use App\Domain\Catalog\Repositories\FormatRepositoryInterface;

class GetFormatDataUseCase
{
    public function __construct(
        private FormatRepositoryInterface $repo
    ) {}

    public function execute(?string $category = null): array
    {
        return [
            'categories' => $this->repo->getCategoryDropDown(),
            'formats'    => $this->repo->getFormatDropDown($category),
            'genres'     => $this->repo->getGenresDropDown($category),
        ];
    }
}