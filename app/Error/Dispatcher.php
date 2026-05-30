<?php

namespace App\Error;

namespace App\Error;

use Throwable;

class Dispatcher
{
    public function __construct(
        private Resolver $resolver,
        private Renderer $renderer
    ) {}

    public function handle(Throwable $e): void
    {
        $handler = $this->resolver->resolve($e);

        // 🔥 THIS LINE IS CRITICAL
        $response = $handler->handle($e);

        $this->renderer->render($response);
    }
}