<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/user/customer/dashboard', function () {
    return view('user.customer.dashboard.dashboard');
});

Route::get('/user/customer/find-psychologist', function () {
    return view('user.customer.find_psychologist.find-psycologist');
});

Route::get('/user/psychologist/dashboard', function () {
    return view('user.psychologist.dashboard.dashboard');
});