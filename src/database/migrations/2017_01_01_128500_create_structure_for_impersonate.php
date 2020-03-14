<?php

use LaravelEnso\Migrator\App\Database\Migration;

class CreateStructureForImpersonate extends Migration
{
    protected $permissions = [
        ['name' => 'core.impersonate.start', 'description' => 'Start impersonating user', 'is_default' => false],
        ['name' => 'core.impersonate.stop', 'description' => 'Stop impersonating user', 'is_default' => true],
    ];
}
