<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'core'])
    ->namespace('LaravelEnso\Impersonate\App\Http\Controllers')
    ->prefix('api/core/impersonate')->as('core.impersonate.')
    ->group(function () {
        Route::get('stop', 'Stop')->name('stop');
        Route::get('{user}', 'Start')->name('start');
    });
