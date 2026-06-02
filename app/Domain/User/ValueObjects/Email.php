<?php

namespace App\Domain\User\ValueObjects;

use App\Domain\User\Exceptions\InvalidEmailException;

final class Email
{
    private string $value;

    public function __construct(string $email)
    {
        $email = trim($email);

        if (!$this->isValid($email)) {
            throw new InvalidEmailException($email);
        }

        $this->value = strtolower($email);
    }

    private function isValid(string $email): bool
    {
        return filter_var(
            $email,
            FILTER_VALIDATE_EMAIL
        ) !== false;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Email $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}