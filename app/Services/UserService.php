<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    private UserRepository $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    public function register(array $data): bool
    {
        // validation (simple example)
        if (empty($data['email']) || empty($data['password'])) {
            throw new \Exception("Email and password required");
        }

        return $this->repo->createUser(
            $data['name'],
            $data['email'],
            $data['password']
        );
    }

    public function login(string $email, string $password): ?array
    {
        $user = $this->repo->findByEmail($email);

        if (!$user) {
            return null;
        }

        if (!password_verify($password, $user['password'])) {
            return null;
        }

        unset($user['password']); // security

        return $user;
    }
}