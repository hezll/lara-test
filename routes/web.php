<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/debug', function (Request $request) {
    return response()->json(['status' => 'ok']);
});