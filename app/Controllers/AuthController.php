<?php

namespace App\Controllers;

use App\Services\UserService;
use App\DTO\UserDTO;

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
        $dto = new UserDTO(
            name: '',
            email: $this->post('email'),
            password: $this->post('password')
        );

        $result = $this->userService->login($dto);

        // =========================
        // ERROR
        // =========================
        if ($result['status'] !== 'success') {

            $this->view('auth/login', [
                'errors' => $result['data'],
                'old' => [
                    'email' => $dto->email
                ]
            ]);

            return;
        }

        // =========================
        // SUCCESS
        // =========================
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
        $dto = new UserDTO(
            name: $this->post('name'),
            email: $this->post('email'),
            password: $this->post('password')
        );

        // optional confirm password check in controller
        if ($this->post('password') !== $this->post('confirm_password')) {
            $this->view('auth/register', [
                'errors' => ['Passwords do not match'],
                'old' => [
                    'name' => $dto->name,
                    'email' => $dto->email
                ]
            ]);
            return;
        }

        $result = $this->userService->createUser($dto);

        // =========================
        // ERROR
        // =========================
        if ($result['status'] !== 'success') {

            $this->view('auth/register', [
                'errors' => $result['data'],
                'old' => [
                    'name' => $dto->name,
                    'email' => $dto->email
                ]
            ]);

            return;
        }

        // =========================
        // SUCCESS
        // =========================
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

        $this->redirect('index.php?page=login');
    }
}