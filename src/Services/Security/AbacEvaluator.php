<?php

namespace RCV\Core\Services\Security;

use Closure;

class AbacEvaluator
{
    /** @var array<string,Closure> key => rule */
    protected array $rules = [];

    public function define(string $name, Closure $rule): void
    {
        $this->rules[$name] = $rule;
    }

    public function allows(string $name, array $context = []): bool
    {
        if (!isset($this->rules[$name])) {
            return false;
        }
        return (bool) call_user_func($this->rules[$name], $context);
    }
}


