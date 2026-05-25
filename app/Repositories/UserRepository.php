<?php

namespace App\Repositories;

use PDO;

class UserRepository extends BaseRepository
{
    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    public function createUser(string $name, string $email, string $password): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (name, email, password)
            VALUES (?, ?, ?)
        ");

        return $stmt->execute([
            $name,
            $email,
            password_hash($password, PASSWORD_BCRYPT)
        ]);
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM users WHERE email = ?
        ");

        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    /**
     * REQUIRED (fix for your error)
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM users WHERE id = ?
        ");

        $stmt->execute([$id]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }
}