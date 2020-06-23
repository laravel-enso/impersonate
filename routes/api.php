<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth:web', 'core'])
    ->namespace('LaravelEnso\Impersonate\Http\Controllers')
    ->prefix('api/core/impersonate')->as('core.impersonate.')
    ->group(function () {
        Route::get('stop', 'Stop')->name('stop');
        Route::get('{user}', 'Start')->name('start');
    });
