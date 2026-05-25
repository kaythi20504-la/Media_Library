<?php

namespace App\Repositories;

use PDO;
use PDOException;
use App\Interfaces\CatalogRepositoryInterface;


 /* Handles catalog database operations
 */
class CatalogRepository
    extends BaseRepository
    implements CatalogRepositoryInterface
{
    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    
     /* Get catalog items by category
     */
    public function getCategoryCatalog(
        $category,
        $limit = null,
        $offset = 0
    ) {
        try {
            $result = $this->db->prepare(
                "CALL sp_get_catalog(?, ?, ?)"
            );

            $result->bindParam(
                1,
                $category,
                PDO::PARAM_STR
            );

            $result->bindParam(
                2,
                $limit,
                $limit === null
                    ? PDO::PARAM_NULL
                    : PDO::PARAM_INT
            );

            $result->bindParam(
                3,
                $offset,
                PDO::PARAM_INT
            );

            $result->execute();

            $catalog = $result->fetchAll();

            $result->closeCursor();

            return $catalog;
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();
            if (stripos($errorMessage, 'sp_get_catalog') !== false || stripos($errorMessage, 'procedure') !== false) {
                return $this->getCategoryFromCatalogView($category, $limit, $offset);
            }

            throw $e;
        }
    }

    private function getCategoryFromCatalogView(
        $category,
        $limit = null,
        $offset = 0
    ) {
        $query = <<<'SQL'
SELECT
    media_id,
    title,
    category,
    img
FROM view_catalog
WHERE (
    :category IS NULL
    OR LOWER(category) = LOWER(:category)
)
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
            ':category',
            $category,
            $category === null
                ? PDO::PARAM_NULL
                : PDO::PARAM_STR
        );

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

    
     /* Search catalog
     */
    public function getSearchCatalog(
        $search,
        $category = null,
        $limit = null,
        $offset = 0
    ) {
        $search = ($search === '' ? null : $search);
        $category = ($category === '' ? null : $category);

        try {
            $result = $this->db->prepare(
                "CALL sp_search_catalog(?, ?, ?, ?)"
            );

            $result->bindValue(
                1,
                $search,
                $search === null
                    ? PDO::PARAM_NULL
                    : PDO::PARAM_STR
            );

            $result->bindValue(
                2,
                $category,
                $category === null
                    ? PDO::PARAM_NULL
                    : PDO::PARAM_STR
            );

            $result->bindValue(
                3,
                $limit,
                PDO::PARAM_INT
            );

            $result->bindValue(
                4,
                $offset,
                PDO::PARAM_INT
            );

            $result->execute();

            $catalog = $result->fetchAll();

            $result->nextRowset();
            $result->closeCursor();

            return $catalog;
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();
            if (stripos($errorMessage, 'sp_search_catalog') !== false || stripos($errorMessage, 'procedure') !== false) {
                return $this->getSearchCatalogFromCatalogView($search, $category, $limit, $offset);
            }

            throw $e;
        }
    }

    private function getSearchCatalogFromCatalogView(
        $search,
        $category,
        $limit = null,
        $offset = 0
    ) {
        $query = <<<'SQL'
SELECT DISTINCT
    vc.media_id,
    vc.title,
    vc.category,
    vc.img
FROM view_catalog vc
WHERE (
        :search IS NULL
        OR :search = ''
        OR vc.title LIKE CONCAT('%', :search, '%')
        OR EXISTS (
            SELECT 1
            FROM Media_People mp
            JOIN People p
                ON p.people_id = mp.people_id
            WHERE
                mp.media_id = vc.media_id
                AND p.fullname LIKE CONCAT('%', :search, '%')
        )
    )
    AND (
        :category IS NULL
        OR LOWER(vc.category) = LOWER(:category)
    )
ORDER BY
    REPLACE(
        REPLACE(
            REPLACE(vc.title, 'The ', ''),
        'An ', ''),
    'A ', '')
LIMIT :limit OFFSET :offset
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

    
     /* Get random catalog items
     */
    public function getRandomCatalog()
    {
        $result = $this->db->query(
            "SELECT * FROM view_random"
        );

        return $result->fetchAll();
    }

    /**
     * Get single item by ID
     */
    public function getById(
    int $id
): ?array
{
    try {
        $result = $this->db->prepare(
            "CALL sp_get_item_full_detail(?)"
        );

        $result->bindParam(
            1,
            $id,
            PDO::PARAM_INT
        );

        $result->execute();

        $item = $result->fetch(PDO::FETCH_ASSOC);

        if ($item === false) {
            $result->closeCursor();
            return null;
        }

        $result->nextRowset();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $item[strtolower($row['role'])][] =
                $row['fullname'];
        }

        $result->closeCursor();

        return $item;
    } catch (PDOException $e) {
        $errorMessage = $e->getMessage();
        if (stripos($errorMessage, 'sp_get_item_full_detail') !== false || stripos($errorMessage, 'procedure') !== false) {
            return $this->getItemDetailFromView($id);
        }

        throw $e;
    }
}

    private function getItemDetailFromView(int $id): ?array
    {
        $query = <<<'SQL'
SELECT
    media_id,
    title,
    category,
    img,
    format,
    year,
    genre,
    publisher,
    isbn,
    fullname,
    role
FROM view_item_detail
WHERE media_id = :id
SQL;

        $result = $this->db->prepare($query);
        $result->bindValue(':id', $id, PDO::PARAM_INT);
        $result->execute();

        $item = null;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            if ($item === null) {
                $item = [
                    'media_id' => $row['media_id'],
                    'title' => $row['title'],
                    'category' => $row['category'],
                    'img' => $row['img'],
                    'format' => $row['format'],
                    'year' => $row['year'],
                    'genre' => $row['genre'],
                    'publisher' => $row['publisher'],
                    'isbn' => $row['isbn'],
                ];
            }

            $item[strtolower($row['role'])][] = $row['fullname'];
        }

        $result->closeCursor();

        return $item;
}
}