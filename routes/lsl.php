<?php
use Illuminate\Support\Facades\Route;
Route::middleware(['api'])->group(function () {
    Route::put('{id?}/{p?}', 'LslController@update');
    Route::resources([
        '/' => 'LslController'
    ]);
});