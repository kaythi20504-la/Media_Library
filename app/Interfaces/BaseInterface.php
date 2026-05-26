<?php

namespace App\Interfaces;

interface BaseInterface
{
    public function create(array $data): bool;

    public function getAll(?int $limit = null, int $offset = 0): array;

    public function getById(int $id): ?array;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function count(array $filters = []): int;
}