<?php

use LaravelEnso\Migrator\Database\Migration;

return new class extends Migration
{
    protected array $permissions = [
        ['name' => 'core.impersonate.start', 'description' => 'Start impersonating user', 'is_default' => false],
        ['name' => 'core.impersonate.stop', 'description' => 'Stop impersonating user', 'is_default' => true],
    ];
};
