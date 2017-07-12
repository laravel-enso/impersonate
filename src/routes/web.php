<?php

Route::middleware(['web', 'auth', 'core'])
    ->namespace('LaravelEnso\Impersonate\app\Http\Controllers')
    ->group(function () {
        Route::prefix('core/impersonate')->as('core.impersonate.')
            ->group(function () {
            	Route::get('stop', 'ImpersonateController@stop')
                	->name('stop');
                Route::get('/{user}', 'ImpersonateController@start')
                	->name('start');
            });
    });
