<?php

use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::controller(ProductoController::class)->group(function () {
    Route::get('/productos', [ProductoController::class, 'index']);
});
