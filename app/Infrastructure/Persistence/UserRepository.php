<?php

namespace App\Infrastructure\Persistence;

use PDO;
use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\Email;

final class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private PDO $pdo
    ) {}

    public function save(User $user): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO users (name, email, password)
            VALUES (:name, :email, :password)
        ");

        $stmt->execute([
            'name' => $user->getName(),
            'email' => $user->getEmail()->value(),
            'password' => $user->getPasswordHash()
        ]);
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM users WHERE email = :email LIMIT 1
        ");

        $stmt->execute([
            'email' => $email
        ]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return new User(
            (int)$data['id'],
            $data['name'],
            new Email($data['email']),
            $data['password']
        );
    }
}