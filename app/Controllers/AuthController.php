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

    /* LOGIN PAGE */
    public function showLogin(): void
    {
        $this->view('auth/login');
    }

    /* REGISTER PAGE */
    public function showRegister(): void
    {
        $this->view('auth/register');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN SUBMIT
    |--------------------------------------------------------------------------
    */
    public function loginSubmit(): void
    {
        $data = [
            'email'    => $this->post('email'),
            'password' => $this->post('password')
        ];

        $result = $this->userService->login($data);

        if (!$result['success']) {
            $this->view('auth/login', [
                'errors' => $result['errors'] ?? [$result['message'] ?? 'Login failed'],   // ensure errors display
                'old'    => $data
            ]);
            return;
        }

        $_SESSION['user'] = $result['data'];

        $this->redirect('index.php?page=home');
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER SUBMIT
    |--------------------------------------------------------------------------
    */
    public function registerSubmit(): void
    {
        $data = [
            'name'              => $this->post('name'),   // ✅ FIXED
            'email'             => $this->post('email'),
            'password'          => $this->post('password'),
            'confirm_password'  => $this->post('confirm_password')
        ];

        $result = $this->userService->createUser($data);

        if (!$result['success']) {
            $this->view('auth/register', [
                'errors' => $result['errors'],   // ✅ FIXED
                'old'    => $data
            ]);
            return;
        }

        $this->redirect('index.php?page=login');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    public function logout(): void
    {
        session_unset();
        session_destroy();

        header("Location: index.php?page=login");
        exit;
    }
}