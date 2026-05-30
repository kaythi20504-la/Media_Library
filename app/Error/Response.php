<?php

namespace App\Error;

use Throwable;
use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\ValidationException;

class Response
{
    public function __construct(
        public int $statusCode = 500,
        public string $message = '',
        public array $data = [],
        public string $view = '500',
        public ?string $file = null,
        public ?int $line = null,
        public ?string $trace = null
    ) {}

    public static function fromException(Throwable $e): self
    {
        $statusCode = 500;
        $view = '500';

        if ($e instanceof NotFoundException) {
            $statusCode = 404;
            $view = '404';
        }

        if ($e instanceof UnauthorizedException) {
            $statusCode = 401;
            $view = '401';
        }

        if ($e instanceof ValidationException) {
            $statusCode = 422;
            $view = '422';
        }

        return new self(
            statusCode: $statusCode,
            message: $e->getMessage(),
            data: [],
            view: $view,
            file: $e->getFile(),
            line: $e->getLine(),
            trace: $e->getTraceAsString()
        );
    }
}