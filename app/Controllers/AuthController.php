<?php

namespace App\Controllers;

use App\Services\UserService;

class AuthController extends BaseController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW LOGIN PAGE
    |--------------------------------------------------------------------------
    */
    public function showLogin(): void
    {
        $this->view('auth/login');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW REGISTER PAGE
    |--------------------------------------------------------------------------
    */
    public function showRegister(): void
    {
        $this->view('auth/register');
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER USER
    |--------------------------------------------------------------------------
    */
    public function register(): void
    {
        $data = $_POST;

        if ($data['password'] !== $data['confirm_password']) {
            $this->view('auth/register', [
                'error' => 'Passwords do not match'
            ]);
            return;
        }

        $this->userService->register($data);

        header("Location: index.php?page=login");
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN USER
    |--------------------------------------------------------------------------
    */
    public function login(): void
    {
        $user = $this->userService->login(
            $_POST['email'],
            $_POST['password']
        );

        if (!$user) {
            $this->view('auth/login', [
                'error' => 'Invalid email or password'
            ]);
            return;
        }

        session_start();
        $_SESSION['user'] = $user;

        header("Location: index.php?page=home");
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW LOGIN PAGE (alias safety optional)
    |--------------------------------------------------------------------------
    */
    public function showRegisterPage(): void
    {
        $this->showRegister();
    }

    public function logout(): void
{
    session_start();

    $_SESSION = [];

    session_destroy();

    header("Location: index.php?page=login");
    exit;
}
}