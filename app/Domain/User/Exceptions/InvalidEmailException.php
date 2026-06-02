<?php

namespace App\Domain\User\Exceptions;

use DomainException;

final class InvalidEmailException extends DomainException
{
    public function __construct(string $email)
    {
        parent::__construct(
            sprintf(
                'Invalid email address "%s".',
                $email
            )
        );
    }
}