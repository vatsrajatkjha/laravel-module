<?php

namespace RCV\Core\Events;

class ModuleEnabled
{
    public $moduleName;

    public function __construct($moduleName)
    {
        $this->moduleName = $moduleName;
    }
} 