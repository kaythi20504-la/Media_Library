<?php

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\UserDTO;
use App\Domain\User\Repositories\UserRepositoryInterface;

final class LoginUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    public function execute(UserDTO $dto): array
    {
        $user = $this->repository->findByEmail(trim($dto->email));

        if (!$user) {
            return [
                'success' => false,
                'errors' => ['Invalid email or password']
            ];
        }

        if (!password_verify($dto->password, $user->getPasswordHash())) {
            return [
                'success' => false,
                'errors' => ['Invalid email or password']
            ];
        }

        return [
            'success' => true,
            'user' => [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail()->value()
            ]
        ];
    }
}