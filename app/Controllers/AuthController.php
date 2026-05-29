<?php

namespace App\Controllers;

use App\Services\UserService;
use App\DTO\UserDTO;

class AuthController extends BaseController
{
    public function __construct(
        private UserService $userService
    ) {}

    public function showLogin(): void
    {
        $this->view('auth/login', [
            'errors' => $_SESSION['errors'] ?? [],
            'success' => $_SESSION['success'] ?? ''
        ]);

        unset($_SESSION['errors'], $_SESSION['success']);
    }

    public function showRegister(): void
    {
        $this->view('auth/register', [
            'errors' => $_SESSION['errors'] ?? []
        ]);

        unset($_SESSION['errors']);
    }

    /* LOGIN */
    public function loginSubmit(): void
    {
        $dto = new UserDTO(
            email: $_POST['email'] ?? '',
            password: $_POST['password'] ?? ''
        );

        $result = $this->userService->login($dto);

        if (!$result['success']) {
            $this->view('auth/login', [
                'errors' => $result['errors'],
                'old' => $_POST
            ]);
            return;
        }

        $_SESSION['user_id'] = $result['user']['id'];
        $_SESSION['username'] = $result['user']['name'];

        header('Location: index.php?page=home');
        exit;
    }

    /* REGISTER */
    public function registerSubmit(): void
    {
        $dto = new UserDTO(
            name: $_POST['username'] ?? '',
            email: $_POST['email'] ?? '',
            password: $_POST['password'] ?? '',
            confirmPassword: $_POST['confirm_password'] ?? ''
        );

        $result = $this->userService->register($dto);

        if (!$result['success']) {
            $this->view('auth/register', [
                'errors' => $result['errors'],
                'old' => $_POST
            ]);
            return;
        }

        $_SESSION['success'] = "Registration successful";

        header('Location: index.php?page=login');
        exit;
    }

    public function logout(): void
    {
        $this->userService->logout();

        header('Location: index.php?page=login');
        exit;
    }
}