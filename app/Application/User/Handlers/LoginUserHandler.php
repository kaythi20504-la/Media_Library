<?php

namespace App\Application\User\Commands;

use App\Application\User\DTOs\UserDTO;
use App\Domain\User\Repositories\UserRepositoryInterface;

final class LoginUserHandler
{
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    public function handle(
        UserDTO $dto
    ): array
    {
        $user = $this->repository
            ->findByEmail($dto->email);

        if (!$user) {
            return [
                'success' => false,
                'errors' => [
                    'general' =>
                    'Invalid email or password'
                ]
            ];
        }

        if (
            !password_verify(
                $dto->password,
                $user->getPasswordHash()
            )
        ) {
            return [
                'success' => false,
                'errors' => [
                    'general' =>
                    'Invalid email or password'
                ]
            ];
        }

        return [
            'success' => true,
            'user' => [
                'id' => $user->getId(),
                'name' => $user->getName()
            ]
        ];
    }
}