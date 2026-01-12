<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OAuth2Controller;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/oauth2/consent', [OAuth2Controller::class, 'consent']);
Route::get('/oauth2/callback', [OAuth2Controller::class, 'callback']);
