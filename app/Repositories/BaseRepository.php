<?php

namespace App\Repositories;

use PDO;
use App\Interfaces\BaseInterface;

abstract class BaseRepository implements BaseInterface
{
    protected PDO $db;
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // =========================
    // CREATE
    // =========================
    public function create(array $data): bool
    {
        $columns = array_keys($data);

        $sql = "INSERT INTO {$this->table} (" .
            implode(',', $columns) .
            ") VALUES (:" . implode(',:', $columns) . ")";

        return $this->execute($sql, $data);
    }

    // =========================
    // READ ALL
    // =========================
    public function getAll(?int $limit = null, int $offset = 0): array
    {
        $sql = "SELECT * FROM {$this->table}";

        $sql .= $this->buildLimitOffset($limit, $offset);

        return $this->fetchAll($sql);
    }

    // =========================
    // READ ONE
    // =========================
    public function getById(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE {$this->primaryKey} = :id
                LIMIT 1";

        return $this->fetchOne($sql, ['id' => $id]);
    }

    // =========================
    // UPDATE
    // =========================
    public function update(int $id, array $data): bool
    {
        $fields = [];

        foreach ($data as $key => $value) {
            $fields[] = "{$key} = :{$key}";
        }

        $sql = "UPDATE {$this->table}
                SET " . implode(',', $fields) . "
                WHERE {$this->primaryKey} = :id";

        $data['id'] = $id;

        return $this->execute($sql, $data);
    }

    // =========================
    // DELETE
    // =========================
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table}
                WHERE {$this->primaryKey} = :id";

        return $this->execute($sql, ['id' => $id]);
    }

    // =========================
    // COUNT (SAFE FIXED)
    // =========================
    public function count(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) AS total FROM {$this->table}";

        $result = $this->fetchOne($sql);

        return (int) ($result['total'] ?? 0);
    }

    // =========================
    // EXECUTE
    // =========================
    protected function execute(string $sql, array $params = []): bool
    {
        $stmt = $this->db->prepare($sql);
        $this->bindValues($stmt, $params);
        return $stmt->execute();
    }

    // =========================
    // FETCH ALL
    // =========================
    protected function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);
        $this->bindValues($stmt, $params);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // FETCH ONE
    // =========================
    protected function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->db->prepare($sql);
        $this->bindValues($stmt, $params);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // =========================
    // BIND VALUES
    // =========================
    protected function bindValues($stmt, array $params): void
    {
        foreach ($params as $key => $value) {

            $param = is_int($key) ? $key + 1 : ':' . $key;

            $type = match (true) {
                is_int($value) => PDO::PARAM_INT,
                is_bool($value) => PDO::PARAM_BOOL,
                $value === null => PDO::PARAM_NULL,
                default => PDO::PARAM_STR
            };

            $stmt->bindValue($param, $value, $type);
        }
    }

    // =========================
    // PAGINATION FIXED
    // =========================
    protected function buildLimitOffset(?int $limit, int $offset): string
    {
        if ($limit === null) {
            return '';
        }

        return " LIMIT {$limit} OFFSET {$offset} ";
    }
}