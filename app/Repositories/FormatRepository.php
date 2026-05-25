<?php

namespace App\Repositories;

use PDO;
use PDOException;
use App\Interfaces\FormatRepositoryInterface;

class FormatRepository implements FormatRepositoryInterface
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Get formats based on selected category
    function get_format_drop_down($category = null)
    {
        try {
            $result = $this->db->prepare("CALL sp_get_formats_by_category(:category)");

            $result->bindValue(
                ':category',
                $category,
                $category === null ? PDO::PARAM_NULL : PDO::PARAM_STR
            );

            $result->execute();
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();
            if (stripos($errorMessage, 'sp_get_formats_by_category') !== false || stripos($errorMessage, 'procedure') !== false) {
                return $this->getFormatsFromCatalogView($category);
            }

            throw $e;
        }

        $format = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $format[$row['category']][] = $row['format'];
        }

        $result->closeCursor();

        return $format;
    }

    private function getFormatsFromCatalogView($category = null)
    {
        $query = <<<'SQL'
SELECT DISTINCT
    LOWER(category) AS category,
    format
FROM view_catalog
WHERE
    :category IS NULL
    OR LOWER(category) = LOWER(:category)
ORDER BY category, format
SQL;

        $result = $this->db->prepare($query);
        $result->bindValue(
            ':category',
            $category,
            $category === null ? PDO::PARAM_NULL : PDO::PARAM_STR
        );
        $result->execute();

        $format = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $format[$row['category']][] = $row['format'];
        }

        $result->closeCursor();
        return $format;
    }

    // Get all unique categories
    function get_category_drop_down()
    {
        $sql = " SELECT DISTINCT category FROM view_catalog ORDER BY category";

        $result = $this->db->prepare($sql);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_COLUMN);
    }

    // Get genres based on selected category
    function get_genres_drop_down($category = null)
    {
        try {
            $result = $this->db->prepare("CALL sp_get_genres_by_category(:category)");

            $result->bindValue(
                ':category',
                $category,
                $category === null ? PDO::PARAM_NULL : PDO::PARAM_STR
            );

            $result->execute();
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();
            if (stripos($errorMessage, 'sp_get_genres_by_category') !== false || stripos($errorMessage, 'procedure') !== false) {
                return $this->getGenresFromCatalogView($category);
            }

            throw $e;
        }

        $genre = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $genre[$row['category']][] = $row['genre'];
        }

        $result->closeCursor();

        return $genre;
    }

    private function getGenresFromCatalogView($category = null)
    {
        $query = <<<'SQL'
SELECT DISTINCT
    LOWER(category) AS category,
    genre
FROM view_catalog
WHERE
    :category IS NULL
    OR LOWER(category) = LOWER(:category)
ORDER BY category, genre
SQL;

        $result = $this->db->prepare($query);
        $result->bindValue(
            ':category',
            $category,
            $category === null ? PDO::PARAM_NULL : PDO::PARAM_STR
        );
        $result->execute();

        $genre = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $genre[$row['category']][] = $row['genre'];
        }

        $result->closeCursor();
        return $genre;
    }
}
