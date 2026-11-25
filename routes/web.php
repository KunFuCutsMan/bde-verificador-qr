<?php

use App\Livewire\Productos\Index as ProductosIndex;
use App\Livewire\Ordenes\Index as OrdenesIndex;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('/productos', ProductosIndex::class)->name('productos.index');
Route::get('/ordenes', OrdenesIndex::class)->name('ordenes.index');
Route::get('/ordenes/crear', OrdenesIndex::class)->name('ordenes.create');
