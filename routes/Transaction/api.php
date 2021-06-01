<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'transaction',
], function () {
    Route::post('/', ['uses' => 'TransactionController@createTransaction', 'as' => 'createTransaction']);
});
