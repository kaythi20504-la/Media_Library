<?php

namespace App\Repositories;

use PDO;
use PDOException;
use App\Interfaces\BaseInterface;

abstract class BaseRepository implements BaseInterface
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function count(
        array $filters = []
    ): int {

        $search = $filters['search'] ?? null;
        $category = $filters['category'] ?? null;

        try {
            $result = $this->db->prepare(
                "CALL sp_search_catalog_count(
                    :search,
                    :category
                )"
            );

            $result->bindValue(
                ':search',
                $search,
                $search === null
                    ? PDO::PARAM_NULL
                    : PDO::PARAM_STR
            );

            $result->bindValue(
                ':category',
                $category,
                $category === null
                    ? PDO::PARAM_NULL
                    : PDO::PARAM_STR
            );

            $result->execute();

            $count = $result->fetchColumn();

            $result->nextRowset();
            $result->closeCursor();

            return (int) $count;
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();
            if (strpos($errorMessage, 'sp_search_catalog_count') !== false || strpos($errorMessage, 'PROCEDURE') !== false) {
                return $this->countFromCatalogView($search, $category);
            }

            throw $e;
        }
    }

    private function countFromCatalogView(
        ?string $search,
        ?string $category
    ): int {
        $query = <<<SQL
SELECT COUNT(DISTINCT vc.media_id) AS total
FROM view_catalog vc
WHERE (
        :search IS NULL
        OR :search = ''
        OR vc.title LIKE CONCAT('%', :search, '%')
        OR EXISTS (
            SELECT 1
            FROM Media_People mp
            JOIN People p ON p.people_id = mp.people_id
            WHERE mp.media_id = vc.media_id
              AND p.fullname LIKE CONCAT('%', :search, '%')
        )
    )
    AND (
        :category IS NULL
        OR LOWER(vc.category) = LOWER(:category)
    );
SQL;

        $result = $this->db->prepare($query);

        $result->bindValue(
            ':search',
            $search,
            $search === null
                ? PDO::PARAM_NULL
                : PDO::PARAM_STR
        );

        $result->bindValue(
            ':category',
            $category,
            $category === null
                ? PDO::PARAM_NULL
                : PDO::PARAM_STR
        );

        $result->execute();
        $count = $result->fetchColumn();
        $result->closeCursor();

        return (int) $count;
    }

    public function getAll(
        ?int $limit = null,
        int $offset = 0
    ): array {
        try {
            $result = $this->db->prepare(
                "CALL sp_get_full_catalog(?, ?)"
            );

            $result->bindParam(
                1,
                $limit,
                $limit === null
                    ? PDO::PARAM_NULL
                    : PDO::PARAM_INT
            );

            $result->bindParam(
                2,
                $offset,
                PDO::PARAM_INT
            );

            $result->execute();

            $catalog = $result->fetchAll();

            $result->closeCursor();

            return $catalog;
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();
            if (stripos($errorMessage, 'sp_get_full_catalog') !== false || stripos($errorMessage, 'procedure') !== false) {
                return $this->getAllFromCatalogView($limit, $offset);
            }

            throw $e;
        }
    }

    private function getAllFromCatalogView(
        ?int $limit,
        int $offset
    ): array {
        $query = <<<'SQL'
SELECT
    media_id,
    title,
    category,
    img
FROM view_catalog
ORDER BY
    REPLACE(
        REPLACE(
            REPLACE(title, 'The ', ''),
        'An ', ''),
    'A ', '')
LIMIT :limit OFFSET :offset
SQL;

        $result = $this->db->prepare($query);

        $result->bindValue(
            ':limit',
            $limit ?? PHP_INT_MAX,
            PDO::PARAM_INT
        );

        $result->bindValue(
            ':offset',
            $offset,
            PDO::PARAM_INT
        );

        $result->execute();
        $catalog = $result->fetchAll();
        $result->closeCursor();

        return $catalog;
    }

    abstract public function getById(
        int $id
    ): ?array;
}