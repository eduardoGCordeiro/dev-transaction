<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('/logout', 'AuthController@logout');
    Route::get('/refresh', 'AuthController@refresh');
});
