<?php

use LaravelEnso\StructureManager\app\Classes\StructureMigration;

class CreateStructureForImpersonate extends StructureMigration
{
    protected $permissionGroup = [
        'name' => 'core.impersonate', 'description' => 'Impersonate permissions group',
    ];

    protected $permissions = [
        ['name' => 'core.impersonate.start', 'description' => 'Start impersonating user', 'type' => 0, 'default' => false],
        ['name' => 'core.impersonate.stop', 'description' => 'Stop impersonating user', 'type' => 0, 'default' => true],
    ];
}
