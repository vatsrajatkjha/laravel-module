<?php

namespace RCV\Core\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModuleEnabled
{
    use Dispatchable, SerializesModels;

    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
