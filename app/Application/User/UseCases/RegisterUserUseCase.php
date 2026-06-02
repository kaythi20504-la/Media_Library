<?php

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\RegisterUserDTO;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Entities\User;
use App\Domain\User\ValueObjects\Email;

final class RegisterUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    public function execute(RegisterUserDTO $dto): array
    {
        $password = trim($dto->password);
        $confirm  = trim($dto->confirmPassword);

        // 1. Validate password
        if ($password !== $confirm) {
            return [
                'success' => false,
                'errors' => ['Passwords do not match']
            ];
        }

        // 2. Basic validation
        if (empty($dto->email) || empty($password)) {
            return [
                'success' => false,
                'errors' => ['Email and password are required']
            ];
        }

        // 3. Email VO
        try {
            $email = new Email($dto->email);
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'errors' => [$e->getMessage()]
            ];
        }

        // 4. Check duplicate email (IMPORTANT DDD RULE)
        $existing = $this->repository->findByEmail($dto->email);

        if ($existing) {
            return [
                'success' => false,
                'errors' => ['Email already exists']
            ];
        }

        // 5. Create User Entity
        $user = new User(
            null,
            $dto->name,
            $email,
            password_hash($password, PASSWORD_BCRYPT)
        );

        // 6. Save
        $this->repository->save($user);

        return [
            'success' => true,
            'errors' => []
        ];
    }
}