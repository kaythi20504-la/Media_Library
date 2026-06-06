<?php

namespace App\Infrastructure\Persistence\Catalog;

use PDO;
use App\Domain\Catalog\Repositories\FormatRepositoryInterface;

class FormatRepository implements FormatRepositoryInterface
{
    public function __construct(
        private PDO $pdo
    ) {}

    // =========================
    // CATEGORY
    // =========================
    public function getCategoryDropDown(): array
    {
        $sql = "SELECT DISTINCT category FROM view_catalog ORDER BY category";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    }

    // =========================
    // FORMATS
    // =========================
    public function getFormatDropDown(?string $category = null): array
    {
        try {
            $sql = "CALL sp_get_formats_by_category(:category)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['category' => $category]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (\PDOException $e) {
            if ($this->isMissingProcedure($e, 'sp_get_formats_by_category')) {
                return $this->getFormatsFromView($category);
            }
            throw $e;
        }

        return $this->groupFormats($rows);
    }

    // =========================
    // GENRES
    // =========================
    public function getGenresDropDown(?string $category = null): array
    {
        try {
            $sql = "CALL sp_get_genres_by_category(:category)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['category' => $category]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (\PDOException $e) {
            if ($this->isMissingProcedure($e, 'sp_get_genres_by_category')) {
                return $this->getGenresFromView($category);
            }
            throw $e;
        }

        return $this->groupGenres($rows);
    }

    // =========================
    // FALLBACK: FORMATS VIEW
    // =========================
    private function getFormatsFromView(?string $category): array
    {
        $sql = "
            SELECT DISTINCT LOWER(category) AS category, format
            FROM view_catalog
            WHERE (:category IS NULL OR LOWER(category) = LOWER(:category))
            ORDER BY category, format
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['category' => $category]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $this->groupFormats($rows);
    }

    // =========================
    // FALLBACK: GENRES VIEW
    // =========================
    private function getGenresFromView(?string $category): array
    {
        $sql = "
            SELECT DISTINCT LOWER(category) AS category, genre
            FROM view_catalog
            WHERE (:category IS NULL OR LOWER(category) = LOWER(:category))
            ORDER BY category, genre
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['category' => $category]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $this->groupGenres($rows);
    }

    // =========================
    // GROUP FORMATS
    // =========================
    private function groupFormats(array $rows): array
    {
        $result = [];

        foreach ($rows as $row) {
            $result[$row['category']][] = $row['format'];
        }

        return $result;
    }

    // =========================
    // GROUP GENRES
    // =========================
    private function groupGenres(array $rows): array
    {
        $result = [];

        foreach ($rows as $row) {
            $result[$row['category']][] = $row['genre'];
        }

        return $result;
    }

    // =========================
    // ERROR CHECK
    // =========================
    private function isMissingProcedure(\PDOException $e, string $procedure): bool
    {
        return str_contains($e->getMessage(), $procedure)
            || str_contains($e->getMessage(), 'procedure');
    }
}