<?php

use LaravelEnso\Migrator\app\Database\Migration;

class CreateStructureForImpersonate extends Migration
{
    protected $permissions = [
        ['name' => 'core.impersonate.start', 'description' => 'Start impersonating user', 'type' => 0, 'is_default' => false],
        ['name' => 'core.impersonate.stop', 'description' => 'Stop impersonating user', 'type' => 0, 'is_default' => true],
    ];
}
