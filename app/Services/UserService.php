<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Validation\Validator;
use App\Request\RegisterUserRequest;
use App\Request\LoginRequest;
use App\DTO\UserDTO;
use App\Mapper\UserMapper;
use App\Models\User;

class UserService
{
    public function __construct(
        private UserRepository $repo,
        private Validator $validator
    ) {}

    /* =========================
        REGISTER
    ========================= */
    public function register(UserDTO $dto): array
    {
        $data = [
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password,
            'confirm_password' => $dto->confirmPassword
        ];

        $errors = $this->validator->validate(
                    $data,
                    RegisterUserRequest::rules()
                );
        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        if ($this->repo->findByEmail($dto->email)) {
            return [
                'success' => false,
                'errors' => [
                    'email' => 'Email already exists'
                ]
            ];
        }

        // ⭐ MAPPER USED HERE
        // $user = UserMapper::toModel($dto);
        $user = new User();
        $user->setName($dto->name);
        $user->setEmail($dto->email);

        // password hashing stays in service
        $user->setPasswordHash(
            password_hash($dto->password, PASSWORD_DEFAULT)
            
        );

        $saved = $this->repo->insertUser($user);

        return [
            'success' => $saved
        ];
    }

    /* =========================
        LOGIN
    ========================= */
    public function login(UserDTO $dto): array
    {
        $data = [
            'email' => $dto->email,
            'password' => $dto->password
        ];

        $errors = $this->validator->validate($data, LoginRequest::rules());

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        $user = $this->repo->findByEmail($dto->email);

        if (!$user) {
            return [
                'success' => false,
                'errors' => [
                    'email' => 'User not found'
                ]
            ];
        }

        if (!password_verify($dto->password, $user->getPasswordHash())) {
            return [
                'success' => false,
                'errors' => [
                    'password' => 'Invalid password'
                ]
            ];
        }

        return [
            'success' => true,
            'user' => $user->toArray()
        ];
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
    }
}