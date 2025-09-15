<?php

namespace RCV\Core\Services\Communication;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

class RpcManager
{
    /** @var array<string,Closure> */
    protected array $handlers = [];

    public function register(string $name, Closure $handler): void
    {
        $this->handlers[$name] = $handler;
    }

    public function call(string $name, array $payload = []): mixed
    {
        if (!isset($this->handlers[$name])) {
            throw new \RuntimeException("RPC handler not found: {$name}");
        }
        return App::call($this->handlers[$name], $payload);
    }
}


