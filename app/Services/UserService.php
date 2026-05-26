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

    /*
    |--------------------------------------------------------------------------
    | GET ALL USERS
    |--------------------------------------------------------------------------
    */
    public function getUsers(): array
    {
        return $this->repo->getAll();
    }

    /*
    |--------------------------------------------------------------------------
    | GET SINGLE USER
    |--------------------------------------------------------------------------
    */
    public function getUser(int $id): ?array
    {
        return $this->repo->getById($id);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE USER
    |--------------------------------------------------------------------------
    */
    public function createUser(array $data): bool
    {
        // REMOVE confirm_password
        unset($data['confirm_password']);

        // HASH PASSWORD
        $data['password'] = password_hash(
            $data['password'],
            PASSWORD_BCRYPT
        );

        // SAVE USER
        return $this->repo->create($data);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN USER
    |--------------------------------------------------------------------------
    */
    public function login(string $email, string $password): ?array
    {
        // FIND USER BY EMAIL
        $user = $this->repo->findByEmail($email);

        // USER NOT FOUND
        if (!$user) {
            return null;
        }

        // VERIFY PASSWORD
        if (!password_verify($password, $user['password'])) {
            return null;
        }

        return $user;
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE USER
    |--------------------------------------------------------------------------
    */
    public function deleteUser(int $id): bool
    {
        return $this->repo->delete($id);
    }
}