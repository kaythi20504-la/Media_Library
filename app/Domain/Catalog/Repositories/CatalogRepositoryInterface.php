<?php

namespace App\Domain\Catalog\Repositories;

interface CatalogRepositoryInterface
{
    public function search(
        ?string $search = null,
        ?string $category = null,
        ?int $limit = null,
        int $offset = 0
    ): array;

    public function getByCategory(
        string $category,
        ?int $limit = null,
        int $offset = 0
    ): array;

    public function getRandom(): array;

    public function getAll(?int $limit = null, int $offset = 0): array;

    public function getById(int $id): ?array;

    public function count(array $filters = []): int;
}