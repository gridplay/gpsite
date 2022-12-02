<?php
use Illuminate\Support\Facades\Route;
Route::get('auth/callback', 'AuthController@callback');
Route::resources([
    'auth' => 'AuthController',
    'admin' => 'AdminController',
]);
Route::get('{id?}', 'HomeController@showPage');
Route::resource('/', 'HomeController');
