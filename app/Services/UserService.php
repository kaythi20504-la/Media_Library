<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Validation\Validator;
use App\Request\RegisterUserRequest;
use App\Request\LoginRequest;
use App\Models\User;

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
    public function getUser(int $id): ?User
    {
        return $this->repo->findById($id);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE USER
    |--------------------------------------------------------------------------
    */
public function createUser(array $data): array
{
    // 1. VALIDATION
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

    // 2. SAFE INPUT
    $name = $data['name'] ?? null;
    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;
    $confirmPassword = $data['confirm_password'] ?? null;

    if (!$name || !$email || !$password || !$confirmPassword) {
        return [
            'success' => false,
            'message' => 'Missing required fields'
        ];
    }

    // 3. BUSINESS RULES
    if (strlen($name) < 3) {
        return ['success' => false, 'message' => 'Name too short'];
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Invalid email'];
    }

    if (strlen($password) < 6) {
        return ['success' => false, 'message' => 'Password too short'];
    }

    if ($password !== $confirmPassword) {
        return ['success' => false, 'message' => 'Password confirmation does not match'];
    }

    // 4. DUPLICATE EMAIL CHECK (FIX IMPORTANT BUG)
    if ($this->repo->findByEmail($email)) {
        return ['success' => false, 'message' => 'Email already exists'];
    }

    // 5. BUILD MODEL
    $user = new User();
    $user->setName($name);
    $user->setEmail($email);
    $user->setPasswordHash(password_hash($password, PASSWORD_BCRYPT));

    // 6. SAVE
    $this->repo->insertUser($user); // or use create()

    return ['success' => true];
}

    /*
    |--------------------------------------------------------------------------
    | LOGIN USER
    |--------------------------------------------------------------------------
    */
    public function login(array $data): array
    {
        $errors = $this->validator->validate(
            $data,
            LoginRequest::rules()
        );

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        if (empty($data['email']) || empty($data['password'])) {
            return [
                'success' => false,
                'message' => 'Email and password required'
            ];
        }

        $user = $this->repo->findByEmail($data['email']);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found'
            ];
        }

        // PASSWORD CHECK IN SERVICE (NOT MODEL LOGIC)
        if (!password_verify($data['password'], $user->getPasswordHash())) {
            return [
                'success' => false,
                'message' => 'Invalid credentials'
            ];
        }
        return [
            'success' => true,
            'data' => $user->toArray()
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE USER
    |--------------------------------------------------------------------------
    */
    public function deleteUser(int $id): array
    {
        $result = $this->repo->delete($id);

        return [
            'success' => $result,
            'message' => $result ? 'Deleted successfully' : 'Delete failed'
        ];
    }
}