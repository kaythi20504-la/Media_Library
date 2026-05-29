<?php

namespace App\Mapper;

use App\DTO\UserDTO;
use App\Models\User;

class UserMapper
{
    public static function toModel(UserDTO $dto): User
    {
        $user = new User();
        $user->setName($dto->name ?? '');
        $user->setEmail($dto->email);

        return $user;
    }
}