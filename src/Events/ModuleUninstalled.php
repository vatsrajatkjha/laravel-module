<?php

namespace RCV\Core\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModuleUninstalled
{
    use Dispatchable, SerializesModels;

    public string $name;

    /**
     * Create a new event instance.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
