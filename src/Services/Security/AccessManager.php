<?php

namespace RCV\Core\Services\Security;

use Illuminate\Support\Facades\Gate;

class AccessManager
{
    public function allows(string $ability, mixed $arguments = null): bool
    {
        return Gate::allows($ability, $arguments);
    }

    public function denies(string $ability, mixed $arguments = null): bool
    {
        return Gate::denies($ability, $arguments);
    }
}


