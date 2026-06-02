<?php

namespace App\Controllers;

use App\Application\User\DTOs\UserDTO;
use App\Application\User\DTOs\RegisterUserDTO;

use App\Application\User\UseCases\RegisterUserUseCase;
use App\Application\User\UseCases\LoginUserUseCase;

class AuthController extends BaseController
{
    public function __construct(
        private RegisterUserUseCase $registerUserUseCase,
        private LoginUserUseCase $loginUserUseCase
    ) {}

    /* =========================
       SHOW LOGIN PAGE
    ========================= */
    public function showLogin(): void
    {
        $this->view('auth/login', [
            'errors' => $_SESSION['errors'] ?? [],
            'old' => $_SESSION['old'] ?? []
        ]);

        unset($_SESSION['errors'], $_SESSION['old']);
    }

    /* =========================
       SHOW REGISTER PAGE
    ========================= */
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
            name: '',
            email: $_POST['email'] ?? '',
            password: $_POST['password'] ?? '',
            confirmPassword: ''
        );

        $result = $this->loginUserUseCase->execute($dto);

        if (!$result['success']) {

            $_SESSION['errors'] = $result['errors'];

            $_SESSION['old'] = [
                'email' => $_POST['email'] ?? ''
            ];

            $this->redirect(BASE_URL . '/Public/index.php?page=login');
            return;
        }

        $_SESSION['user_id'] = $result['user']['id'] ?? null;
        $_SESSION['username'] = $result['user']['name'] ?? null;

        $this->redirect(BASE_URL . '/Public/index.php?page=home');
    }
    
    /* =========================
       REGISTER
    ========================= */
    public function registerSubmit(): void
    {
        $dto = new RegisterUserDTO(
            name: $_POST['name'] ?? '',
            email: $_POST['email'] ?? '',
            password: $_POST['password'] ?? '',
            confirmPassword: $_POST['confirm_password'] ?? ''
        );

        $result = $this->registerUserUseCase->execute($dto);

        if (!$result['success']) {

            $_SESSION['errors'] = $result['errors'];

            $_SESSION['old'] = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? ''
            ];

            $this->redirect(BASE_URL . '/Public/index.php?page=register');
            return;
        }

        $_SESSION['success'] = 'Registration successful! Please sign in.';

        $this->redirect(BASE_URL . '/Public/index.php?page=login');
    }

    /* =========================
       LOGOUT
    ========================= */
    public function logout(): void
    {
        $_SESSION = [];

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        $this->redirect(BASE_URL . '/Public/index.php?page=login');
    }
}