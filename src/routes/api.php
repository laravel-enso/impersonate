<?php

Route::middleware(['web', 'auth', 'core'])
    ->namespace('LaravelEnso\Impersonate\app\Http\Controllers')
    ->prefix('api/core/impersonate')->as('core.impersonate.')
    ->group(function () {
        require 'app/impersonate.php';
    });
