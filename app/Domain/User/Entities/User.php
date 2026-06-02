<?php

namespace App\Domain\User\Entities;

use App\Domain\User\ValueObjects\Email;

final class User
{
    private ?int $id;

    private string $name;

    private Email $email;

    private string $passwordHash;

    public function __construct(
        ?int $id,
        string $name,
        Email $email,
        string $passwordHash
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
}