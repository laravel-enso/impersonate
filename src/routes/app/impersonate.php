<?php

Route::get('stop', 'Stop')->name('stop');
Route::get('{user}', 'Start')->name('start');
