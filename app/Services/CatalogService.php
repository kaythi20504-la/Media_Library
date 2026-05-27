<?php

namespace App\Services;

use App\Core\Database;
use App\Repositories\CatalogRepository;
use App\Interfaces\CatalogRepositoryInterface;

class CatalogService extends BaseService
{
    private CatalogRepositoryInterface $repo;

    public function __construct(
        ?CatalogRepositoryInterface $repo = null
    ) {

        if ($repo === null) {

            $repo = new CatalogRepository(
                Database::getConnection()
            );
        }

        $this->repo = $repo;
    }

    /**
     * =========================
     * HOME PAGE
     * =========================
     */
    public function getHomePageData(): array
    {
        return [
            'random' => $this->repo->getRandom(),
            'pageTitle' => 'Personal Media Library',
            'section' => 'catalog'
        ];
    }

    /**
     * =========================
     * CATALOG PAGE
     * =========================
     */
    public function getCatalogPage(
        array $queryParams
    ): array {

        // Category filter
        $section = $this->getCategory(
            $queryParams
        );

        // Search filter
        $search = $this->getSearchTerm(
            $queryParams
        );

        // Current page
        $currentPage = $this->getCurrentPage(
            $queryParams
        );

        /**
         * =========================
         * TOTAL ITEMS
         * =========================
         */
        $totalItems = $this->repo->count([
            'category' => $section,
            'search'   => $search
        ]);

        /**
         * =========================
         * PAGINATION DATA
         * =========================
         */
        $pagination = $this->buildPagination(
            $totalItems,
            $currentPage
        );

        /**
         * =========================
         * LOAD CATALOG DATA
         * =========================
         */
        $catalog = $this->loadCatalogData(
            $section,
            $search,
            $pagination['limit'],
            $pagination['offset']
        );

        /**
         * =========================
         * RETURN VIEW DATA
         * =========================
         */
        return [

            // Catalog items
            'catalog' => $catalog,

            // Pagination array
            'pagination' => [
                'currentPage' => $pagination['currentPage'],
                'totalPages'  => $pagination['totalPages']
            ],

            // Filters array
            'filters' => [
                'category' => $section,
                'search'   => $search
            ],

            // Page title
            'pageTitle' => $section
                ? ucfirst($section)
                : 'Full Catalog',

            // Query string
            'queryString' => $this->buildQueryString(
                $section,
                $search
            )
        ];
    }

    /**
     * =========================
     * LOAD DATA
     * =========================
     */
    private function loadCatalogData(
        ?string $section,
        ?string $search,
        int $limit,
        int $offset
    ): array {

        // Search + optional category
        if ($search !== null) {

            return $this->repo->search(
                $search,
                $section,
                $limit,
                $offset
            );
        }

        // Category only
        if ($section !== null) {

            return $this->repo->getByCategory(
                $section,
                $limit,
                $offset
            );
        }

        // All catalog
        return $this->repo->getAll(
            $limit,
            $offset
        );
    }

    /**
     * =========================
     * CATEGORY
     * =========================
     */
    private function getCategory(
        array $params
    ): ?string {

        $category = $params['cat'] ?? null;

        $allowed = [
            'books',
            'movies',
            'music'
        ];

        return in_array(
            $category,
            $allowed,
            true
        )
            ? $category
            : null;
    }

    /**
     * =========================
     * SEARCH TERM
     * =========================
     */
    private function getSearchTerm(
        array $params
    ): ?string {

        $search = trim(
            $params['s'] ?? ''
        );

        return $search !== ''
            ? $search
            : null;
    }

    /**
     * =========================
     * DETAILS PAGE
     * =========================
     */
    public function getById(
        int $id
    ): ?array {

        return $this->repo->getById($id);
    }
}