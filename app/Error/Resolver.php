<?php

namespace App\Error;

use App\Error\Handlers\GenericHandler;
use Throwable;

class Resolver
{
    private string $namespace = "App\\Error\\Handlers\\";

    public function resolve(Throwable $e): object
    {
        $handlerClass = $this->buildHandlerClass($e);

        if (class_exists($handlerClass)) {
            return new $handlerClass();
        }

        return new GenericHandler();
    }

    private function buildHandlerClass(Throwable $e): string
    {
        $short = (new \ReflectionClass($e))->getShortName();

        // ValidationException → ValidationHandler
        return $this->namespace .
            str_replace('Exception', 'Handler', $short);
    }
}