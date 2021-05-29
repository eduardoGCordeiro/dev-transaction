<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth',
    'namespace' => 'AuthRoute',
], function () {
    Route::post('/login', 'AuthController@login');
    Route::post('/logout', 'AuthController@logout');
    Route::get('/refresh', 'AuthController@refresh');
    Route::post('/refresh', 'AuthController@refresh');
});
