<?php

namespace App\Application\User\DTOs;

final class RegisterUserDTO
{
    public function __construct(
        public string $name = '',
        public string $email = '',
        public string $password = '',
        public string $confirmPassword = ''
    ) {}
}