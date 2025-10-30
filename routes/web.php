<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/user/dashboard', function () {
    return view('welcome');
});
