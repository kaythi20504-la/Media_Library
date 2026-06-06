<?php

namespace App\Application\User\Commands;

use App\Application\User\DTOs\RegisterUserDTO;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Entities\User;
use App\Domain\User\ValueObjects\Email;

final class RegisterUserHandler
{
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    public function handle(
        RegisterUserDTO $dto
    ): array
    {
        $password = trim($dto->password);
        $confirm  = trim($dto->confirmPassword);

        if ($password !== $confirm) {
            return [
                'success' => false,
                'errors' => ['Passwords do not match']
            ];
        }

        if (empty($dto->email) || empty($password)) {
            return [
                'success' => false,
                'errors' => ['Email and password are required']
            ];
        }

        try {
            $email = new Email($dto->email);
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'errors' => [$e->getMessage()]
            ];
        }

        $user = new User(
            null,
            $dto->name,
            $email,
            password_hash($password, PASSWORD_BCRYPT)
        );

        $this->repository->save($user);

        return [
            'success' => true,
            'errors' => []
        ];
    }
}