<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\DTO\UserDTO;
use App\Core\ApiResponse;

class UserService
{
    private UserRepository $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER USER
    |--------------------------------------------------------------------------
    */
    public function createUser(UserDTO $dto): array
    {
        $errors = [];

        // =========================
        // VALIDATION
        // =========================
        if (empty($dto->name)) {
            $errors[] = 'Name is required';
        }

        if (empty($dto->email)) {
            $errors[] = 'Email is required';
        }

        if (empty($dto->password)) {
            $errors[] = 'Password is required';
        }

        // check email exists
        if ($this->repo->findByEmail($dto->email)) {
            $errors[] = 'Email already exists';
        }

        if (!empty($errors)) {
            return ApiResponse::error(
                'Validation failed',
                $errors
            );
        }

        // =========================
        // SAVE TO DATABASE
        // =========================
        $created = $this->repo->create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => password_hash(
                $dto->password,
                PASSWORD_BCRYPT
            )
        ]);

        if (!$created) {
            return ApiResponse::error(
                'User registration failed'
            );
        }

        return ApiResponse::success(
            null,
            'User registered successfully'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN USER
    |--------------------------------------------------------------------------
    */
    public function login(UserDTO $dto): array
{
    // =========================
    // VALIDATION
    // =========================
    if (empty($dto->email)) {
        return ApiResponse::error(
            'Validation failed',
            ['Email is required']
        );
    }

    if (empty($dto->password)) {
        return ApiResponse::error(
            'Validation failed',
            ['Password is required']
        );
    }

    // =========================
    // FIND USER (MODEL OBJECT)
    // =========================
    $user = $this->repo->findByEmail($dto->email);

    if (
        !$user ||
        !password_verify(
            $dto->password,
            $user->getPasswordHash()
        )
    ) {
        return ApiResponse::error(
            'Invalid email or password'
        );
    }

    // =========================
    // SUCCESS RESPONSE
    // =========================
    return ApiResponse::success(
        [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail()
        ],
        'Login successful'
    );
}

    /*
    |--------------------------------------------------------------------------
    | GET ALL USERS
    |--------------------------------------------------------------------------
    */
    public function getAllUsers(): array
    {
        $rows = $this->repo->getAll();

        $users = array_map(function ($row) {
            return [
                'id' => $row['id'],
                'name' => $row['name'],
                'email' => $row['email']
            ];
        }, $rows);

        return ApiResponse::success(
            $users,
            'Users fetched successfully'
        );
    }
}