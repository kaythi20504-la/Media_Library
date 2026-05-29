<?php

namespace App\DTO;

class UserDTO
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly string $email = '',
        public readonly string $password = '',
        public readonly ?string $confirmPassword = null
    ) {}
}