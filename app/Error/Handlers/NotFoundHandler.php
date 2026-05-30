<?php

namespace App\Error\Response;

use Throwable;
use App\Error\Response;

class NotFoundHandler
{
    public function handle(Throwable $e): Response
    {
        return new Response(
            404,
            "Page Not Found",
            [],
            '404'
        );
    }
}