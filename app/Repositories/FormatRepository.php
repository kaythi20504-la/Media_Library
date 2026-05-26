<?php

namespace App\Repositories;

use PDO;
use App\Interfaces\FormatRepositoryInterface;

class FormatRepository extends BaseRepository implements FormatRepositoryInterface
{
    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    public function getFormatDropDown(?string $category = null): array
    {
        try {
            $sql = "CALL sp_get_formats_by_category(:category)";

            $rows = $this->fetchAll($sql, [
                'category' => $category
            ]);

        } catch (\PDOException $e) {

            if ($this->isMissingProcedure($e, 'sp_get_formats_by_category')) {
                return $this->getFormatsFromView($category);
            }

            throw $e;
        }

        return $this->groupFormats($rows);
    }

    private function getFormatsFromView(?string $category): array
    {
        $sql = "
            SELECT DISTINCT LOWER(category) AS category, format
            FROM view_catalog
            WHERE (:category IS NULL OR LOWER(category) = LOWER(:category))
            ORDER BY category, format
        ";

        $rows = $this->fetchAll($sql, [
            'category' => $category
        ]);

        return $this->groupFormats($rows);
    }

    public function getCategoryDropDown(): array
    {
        $sql = "SELECT DISTINCT category FROM view_catalog ORDER BY category";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getGenresDropDown(?string $category = null): array
    {
        try {
            $sql = "CALL sp_get_genres_by_category(:category)";

            $rows = $this->fetchAll($sql, [
                'category' => $category
            ]);

        } catch (\PDOException $e) {

            if ($this->isMissingProcedure($e, 'sp_get_genres_by_category')) {
                return $this->getGenresFromView($category);
            }

            throw $e;
        }

        return $this->groupGenres($rows);
    }

    private function getGenresFromView(?string $category): array
    {
        $sql = "
            SELECT DISTINCT LOWER(category) AS category, genre
            FROM view_catalog
            WHERE (:category IS NULL OR LOWER(category) = LOWER(:category))
            ORDER BY category, genre
        ";

        $rows = $this->fetchAll($sql, [
            'category' => $category
        ]);

        return $this->groupGenres($rows);
    }

    private function groupFormats(array $rows): array
    {
        $format = [];

        foreach ($rows as $row) {
            $format[$row['category']][] = $row['format'];
        }

        return $format;
    }

    private function groupGenres(array $rows): array
    {
        $genre = [];

        foreach ($rows as $row) {
            $genre[$row['category']][] = $row['genre'];
        }

        return $genre;
    }

    private function isMissingProcedure(\PDOException $e, string $procedure): bool
    {
        $msg = $e->getMessage();

        return str_contains($msg, $procedure) || str_contains($msg, 'procedure');
    }
}