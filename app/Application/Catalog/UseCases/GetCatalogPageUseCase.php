<?php

namespace App\Application\Catalog\UseCases;

use App\Domain\Catalog\Repositories\CatalogRepositoryInterface;

class GetCatalogPageUseCase
{
    public function __construct(
        private CatalogRepositoryInterface $repo
    ) {}

    public function execute(array $params): array
    {
        // -----------------------
        // CATEGORY FILTER
        // -----------------------
        $section = $this->getCategory($params);

        // -----------------------
        // SEARCH FILTER
        // -----------------------
        $search = $this->getSearchTerm($params);

        // -----------------------
        // CURRENT PAGE
        // -----------------------
        $currentPage = $this->getCurrentPage($params);

        // -----------------------
        // TOTAL ITEMS
        // -----------------------
        $totalItems = $this->repo->count([
            'category' => $section,
            'search'   => $search
        ]);

        // -----------------------
        // PAGINATION
        // -----------------------
        $limit = 8;
        $totalPages = max(1, (int)ceil($totalItems / $limit));

        if ($currentPage > $totalPages) {
            $currentPage = $totalPages;
        }

        $offset = ($currentPage - 1) * $limit;

        // -----------------------
        // LOAD DATA
        // -----------------------
        $catalog = $this->loadCatalogData(
            $section,
            $search,
            $limit,
            $offset
        );

        // -----------------------
        // RETURN DATA
        // -----------------------
        return [
            'catalog' => $catalog,

            'pagination' => [
                'currentPage' => $currentPage,
                'totalPages'  => $totalPages
            ],

            'filters' => [
                'category' => $section,
                'search'   => $search
            ],

            'pageTitle' => $section
                ? ucfirst($section)
                : 'Full Catalog'
        ];
    }

    // =========================
    // LOAD DATA
    // =========================
    private function loadCatalogData(
        ?string $section,
        ?string $search,
        int $limit,
        int $offset
    ): array {
        if ($search !== null) {
            return $this->repo->search(
                $search,
                $section,
                $limit,
                $offset
            );
        }

        if ($section !== null) {
            return $this->repo->getByCategory(
                $section,
                $limit,
                $offset
            );
        }

        return $this->repo->getAll(
            $limit,
            $offset
        );
    }

    // =========================
    // CATEGORY
    // =========================
    private function getCategory(array $params): ?string
    {
        $category = $params['cat'] ?? null;

        $allowed = ['books', 'movies', 'music'];

        return in_array($category, $allowed, true)
            ? $category
            : null;
    }

    // =========================
    // SEARCH
    // =========================
    private function getSearchTerm(array $params): ?string
    {
        $search = trim($params['s'] ?? '');

        return $search !== '' ? $search : null;
    }

    // =========================
    // PAGE
    // =========================
    private function getCurrentPage(array $params): int
    {
        $page = (int)($params['pg'] ?? 1);

        return $page < 1 ? 1 : $page;
    }
}