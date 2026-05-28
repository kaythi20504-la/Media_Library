<?php

namespace App\Mappers;

use App\Models\User;
use App\DTO\UserDTO;

class UserMapper
{
    // DTO → MODEL (for DB insert/update)
    public static function toModel(UserDTO $dto): User
    {
        $user = new User();

        $user->setName($dto->name);
        $user->setEmail($dto->email);

        if ($dto->password) {
            $user->setPasswordHash(
                password_hash($dto->password, PASSWORD_BCRYPT)
            );
        }

        return $user;
    }

    // MODEL → DTO (for output)
    public static function toDTO(User $user): UserDTO
    {
        return new UserDTO(
            $user->getName(),
            $user->getEmail()
        );
    }
}