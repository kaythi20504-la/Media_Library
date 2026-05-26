<?php

namespace App\Repositories;

use App\Interfaces\CatalogRepositoryInterface;

class CatalogRepository extends BaseRepository implements CatalogRepositoryInterface
{
    protected string $table = 'view_catalog';
    protected string $primaryKey = 'media_id';

    // =========================
    // GET ALL
    // =========================
    public function getAll(?int $limit = null, int $offset = 0): array
    {
        $sql = "
            SELECT media_id, title, category, img
            FROM {$this->table}
            ORDER BY REPLACE(REPLACE(REPLACE(title, 'The ', ''), 'An ', ''), 'A ', '')
        ";

        $sql .= $this->buildLimitOffset($limit, $offset);

        return $this->fetchAll($sql);
    }

    // =========================
    // COUNT FILTERED
    // =========================
    public function count(array $filters = []): int
    {
        $sql = "
            SELECT COUNT(DISTINCT vc.media_id) AS total
            FROM view_catalog vc
            WHERE 1 = 1
        ";

        $params = [];

        if (!empty($filters['search'])) {

            $sql .= "
                AND (
                    vc.title LIKE :search
                    OR EXISTS (
                        SELECT 1
                        FROM Media_People mp
                        JOIN People p ON p.people_id = mp.people_id
                        WHERE mp.media_id = vc.media_id
                        AND p.fullname LIKE :search
                    )
                )
            ";

            $params['search'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['category'])) {

            $sql .= " AND LOWER(vc.category) = LOWER(:category)";
            $params['category'] = $filters['category'];
        }

        $row = $this->fetchOne($sql, $params);

        return (int) ($row['total'] ?? 0);
    }

    // =========================
    // SEARCH
    // =========================
    public function search(
        ?string $search = null,
        ?string $category = null,
        ?int $limit = null,
        int $offset = 0
    ): array {

        $sql = "
            SELECT DISTINCT vc.media_id, vc.title, vc.category, vc.img
            FROM view_catalog vc
            WHERE 1 = 1
        ";

        $params = [];

        if ($search) {

            $sql .= "
                AND (
                    vc.title LIKE :search
                    OR EXISTS (
                        SELECT 1
                        FROM Media_People mp
                        JOIN People p ON p.people_id = mp.people_id
                        WHERE mp.media_id = vc.media_id
                        AND p.fullname LIKE :search
                    )
                )
            ";

            $params['search'] = '%' . $search . '%';
        }

        if ($category) {
            $sql .= " AND LOWER(vc.category) = LOWER(:category)";
            $params['category'] = $category;
        }

        $sql .= "
            ORDER BY REPLACE(REPLACE(REPLACE(vc.title, 'The ', ''), 'An ', ''), 'A ', '')
        ";

        $sql .= $this->buildLimitOffset($limit, $offset);

        return $this->fetchAll($sql, $params);
    }

    // =========================
    // CATEGORY
    // =========================
    public function getByCategory(string $category, ?int $limit = null, int $offset = 0): array
    {
        return $this->search(null, $category, $limit, $offset);
    }

    // =========================
    // RANDOM
    // =========================
    public function getRandom(): array
    {
        return $this->fetchAll("SELECT * FROM view_random");
    }

    // =========================
    // DETAILS
    // =========================
    public function getById(int $id): ?array
    {
        $rows = $this->fetchAll("
            SELECT media_id, title, category, img, format, year,
                   genre, publisher, isbn, fullname, role
            FROM view_item_detail
            WHERE media_id = :id
        ", ['id' => $id]);

        if (!$rows) {
            return null;
        }

        $item = [
            'media_id' => $rows[0]['media_id'],
            'title' => $rows[0]['title'],
            'category' => $rows[0]['category'],
            'img' => $rows[0]['img'],
            'format' => $rows[0]['format'],
            'year' => $rows[0]['year'],
            'genre' => $rows[0]['genre'],
            'publisher' => $rows[0]['publisher'],
            'isbn' => $rows[0]['isbn'],
        ];

        foreach ($rows as $row) {
            if (!empty($row['role']) && !empty($row['fullname'])) {
                $item[strtolower($row['role'])][] = $row['fullname'];
            }
        }

        return $item;
    }
}