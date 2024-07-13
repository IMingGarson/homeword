<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('api')->group(function () {
    Route::resource('orders', OrderController::class);
});