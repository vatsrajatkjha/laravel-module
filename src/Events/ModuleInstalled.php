<?php

namespace RCV\Core\Events;

class ModuleInstalled
{
    public $moduleName;

    public function __construct($moduleName)
    {
        $this->moduleName = $moduleName;
    }
} 