<?php

namespace App\Error\Response;

use Throwable;
use App\Error\Response;

class ValidationHandler
{
    public function handle(Throwable $e): Response
    {
        return new Response(
            422,
            $e->getMessage(),
            method_exists($e, 'getContext') ? $e->getContext() : [],
            'validation'
        );
    }
}