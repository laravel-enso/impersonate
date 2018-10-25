<?php

use LaravelEnso\StructureManager\app\Classes\StructureMigration;

class CreateStructureForImpersonate extends StructureMigration
{
    protected $permissions = [
        ['name' => 'core.impersonate.start', 'description' => 'Start impersonating user', 'type' => 0, 'is_default' => false],
        ['name' => 'core.impersonate.stop', 'description' => 'Stop impersonating user', 'type' => 0, 'is_default' => true],
    ];
}
