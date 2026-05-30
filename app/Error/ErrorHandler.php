<?php

namespace App\Error;

use App\Error\Response;
use Throwable;

class ErrorHandler
{
    public static function register(): void
    {
        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
    }

    public static function handleError($severity, $message, $file, $line): void
    {
        throw new \ErrorException($message, 0, $severity, $file, $line);
    }

   public static function handleException(\Throwable $e): void
{
    $renderer = new Renderer(
        BASE_PATH . '/resources/views'
    );

    $dispatcher = new Dispatcher(
        new Resolver(),
        $renderer
    );

    // ❌ REMOVE THIS LINE
    // $response = Response::fromException($e);

    // ✅ PASS EXCEPTION DIRECTLY
    $dispatcher->handle($e);
}
}