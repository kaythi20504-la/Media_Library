<?php

namespace App\Repositories;

use PDO;

class UserRepository extends BaseRepository
{
    protected string $table = 'users';
    protected string $primaryKey = 'id';

    public function __construct(PDO $db)
    {
        parent::__construct($db);
    }

    // custom method (not CRUD)
    public function findByEmail(string $email): ?array
    {
        return $this->fetchOne(
            "SELECT * FROM users WHERE email = :email",
            ['email' => $email]
        );
    }
}