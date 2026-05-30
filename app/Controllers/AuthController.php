<?php

namespace App\Controllers;

use App\Services\UserService;
use App\DTO\UserDTO;
use App\Exceptions\ValidationException;

class AuthController extends BaseController
{
    public function __construct(
        private UserService $userService
    ) {}

    public function showLogin(): void
    {
        $this->view('auth/login', [
            'errors' => $_SESSION['errors'] ?? [],
            'success' => $_SESSION['success'] ?? '',
            'old' => $_SESSION['old'] ?? []
        ]);

        unset($_SESSION['errors'], $_SESSION['success'], $_SESSION['old']);
    }

    public function showRegister(): void
    {
        $this->view('auth/register', [
            'errors' => $_SESSION['errors'] ?? [],
            'old' => $_SESSION['old'] ?? []
        ]);

        unset($_SESSION['errors'], $_SESSION['old']);
    }

    /* =========================
       LOGIN
    ========================= */
    public function loginSubmit(): void
    {
        $dto = new UserDTO(
            email: $_POST['email'] ?? '',
            password: $_POST['password'] ?? ''
        );

        $user = $this->userService->login($dto);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['name'];

        $this->redirect(BASE_URL . '/Public/index.php?page=home');
    }

    /* =========================
       REGISTER
    ========================= */
    public function registerSubmit(): void
    {
        
        $dto = new UserDTO(
            name: $_POST['name'] ?? '',
            email: $_POST['email'] ?? '',
            password: $_POST['password'] ?? '',
            confirmPassword: $_POST['confirm_password'] ?? ''
        );

        $this->userService->register($dto);

        $_SESSION['success'] = "Registration successful! Please sign in.";

        $this->redirect(BASE_URL . '/Public/index.php?page=login');
    }

    public function logout(): void
    {
        $this->userService->logout();
        $this->redirect(BASE_URL . '/Public/index.php?page=login');
    }

    
}