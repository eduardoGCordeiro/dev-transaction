<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'application',
], function () {
    Route::post('/register', 'ApplicationController@register');
});
