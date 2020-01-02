<?php

use LaravelEnso\Migrator\App\Database\Migration;
use LaravelEnso\Permissions\App\Enums\Types;

class CreateStructureForImpersonate extends Migration
{
    protected $permissions = [
        ['name' => 'core.impersonate.start', 'description' => 'Start impersonating user', 'type' => Types::Read, 'is_default' => false],
        ['name' => 'core.impersonate.stop', 'description' => 'Stop impersonating user', 'type' => Types::Read, 'is_default' => true],
    ];
}
