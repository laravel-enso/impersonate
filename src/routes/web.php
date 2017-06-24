<?php

Route::group([
    'namespace'  => 'LaravelEnso\Impersonate\app\Http\Controllers',
    'middleware' => ['web', 'auth', 'core'],
], function () {
    Route::group(['prefix' => 'core/impersonate', 'as' => 'core.impersonate.'], function () {
        Route::get('/{user}/start', 'ImpersonateController@start')->name('start');
        Route::get('stop', 'ImpersonateController@stop')->name('stop');
    });
});
