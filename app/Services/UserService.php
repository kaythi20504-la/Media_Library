<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;
use App\Validation\Validator;

class UserService
{
    private UserRepository $repo;
    private Validator $validator;

    public function __construct(
        UserRepository $repo,
        Validator $validator
    ) {
        $this->repo = $repo;
        $this->validator = $validator;
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
    | CREATE USER (REGISTER)
    |--------------------------------------------------------------------------
    */
    public function createUser(array $data): array
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */
        $errors = $this->validator->validateRegister($data);

        if (!$this->validator->isValid($errors)) {
            return [
                'success' => false,
                'errors'  => $errors,
                'old'     => $data
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | BUSINESS RULE: EMAIL EXISTS
        |--------------------------------------------------------------------------
        */
        if ($this->repo->findByEmail($data['email'])) {
            return [
                'success' => false,
                'errors' => [
                    'email' => 'Email already exists'
                ],
                'old' => $data
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | CLEAN DATA
        |--------------------------------------------------------------------------
        */
        unset($data['confirm_password']);

        /*
        |--------------------------------------------------------------------------
        | HASH PASSWORD
        |--------------------------------------------------------------------------
        */
        $data['password'] = password_hash(
            $data['password'],
            PASSWORD_BCRYPT
        );

        /*
        |--------------------------------------------------------------------------
        | SAVE USER
        |--------------------------------------------------------------------------
        */
        $created = $this->repo->create($data);

        if (!$created) {
            return [
                'success' => false,
                'errors' => [
                    'general' => 'Something went wrong while creating account'
                ]
            ];
        }

        return [
            'success' => true
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN USER
    |--------------------------------------------------------------------------
    */
    public function loginUser(array $data): array
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */
        $errors = $this->validator->validateLogin($data);

        if (!$this->validator->isValid($errors)) {
            return [
                'success' => false,
                'errors'  => $errors,
                'old'     => $data
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | FIND USER
        |--------------------------------------------------------------------------
        */
        $user = $this->repo->findByEmail($data['email']);

        if (!$user) {
            return [
                'success' => false,
                'errors' => [
                    'general' => 'Invalid email or password'
                ]
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | PASSWORD CHECK
        |--------------------------------------------------------------------------
        */
        if (!password_verify($data['password'], $user['password'])) {
            return [
                'success' => false,
                'errors' => [
                    'general' => 'Invalid email or password'
                ]
            ];
        }

        return [
            'success' => true,
            'user' => $user
        ];
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