<?php

use App\Livewire\Productos\Index as ProductosIndex;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('/productos', ProductosIndex::class)->name('productos.index');
