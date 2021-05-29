<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

Route::group(['prefix' => 'api'], function () {
    Route::group([], function () {
        require __DIR__ . '/Auth/api-not-authenticated.php';
        require __DIR__ . '/User/api-not-authenticated.php';
    });

    Route::group(['middleware' => 'auth'], function () {
        require __DIR__ . '/Auth/api-authenticated.php';
        require __DIR__ . '/User/api-authenticated.php';
        require __DIR__ . '/PersonUser/api.php';
        require __DIR__ . '/CorporateUser/api.php';
    });
});
