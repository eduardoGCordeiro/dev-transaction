<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'user',
], function () {
    Route::post('/register', 'UserController@register');
});
