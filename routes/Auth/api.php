<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth',
    'namespace' => 'AuthRoute',
], function () {
    Route::post('/register', 'AuthController@register');
    Route::post('/login', 'AuthController@login');
    Route::post('/logout', 'AuthController@logout');
});
