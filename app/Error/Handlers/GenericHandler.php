<?php

namespace App\Error\Handlers;

use Throwable;
use App\Error\Response;

namespace App\Error\Handlers;

use App\Error\Response;
use Throwable;

class GenericHandler
{
    public function handle(Throwable $e): Response
    {
        return Response::fromException($e, '500');
    }
}