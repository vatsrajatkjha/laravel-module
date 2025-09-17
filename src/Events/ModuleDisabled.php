<?php

namespace RCV\Core\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModuleDisabled
{
    use Dispatchable, SerializesModels;

    public string $name;

    public bool $removed;

    public function __construct(string $name, bool $removed = false)
    {
        $this->name = $name;
        $this->removed = $removed;
    }
}
