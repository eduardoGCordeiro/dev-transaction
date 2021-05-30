<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'transaction',
], function () {
    Route::post('/income', 'TransactionController@logout');
    Route::post('/outcome', 'TransactionController@logout');
});
