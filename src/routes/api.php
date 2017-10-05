<?php

Route::middleware(['web', 'auth', 'core'])
    ->prefix('api/core/impersonate')->as('core.impersonate.')
    ->namespace('LaravelEnso\Impersonate\app\Http\Controllers')
    ->group(function () {
        Route::get('stop', 'ImpersonateController@stop')
            ->name('stop');
        Route::get('/{user}', 'ImpersonateController@start')
            ->name('start');
    });
