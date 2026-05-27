<?php
namespace App\Model;

class User
{
    private string $username;
    private string $email;
    private string $password;

    public function __construct(string $username, string $email, string $password)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    public function getUsername(): string { return $this->username; }
    public function getEmail(): string { return $this->email; }

    // NEVER expose password directly in real systems
}