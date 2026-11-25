<?php

use App\Livewire\Ordenes\Index as OrdenesIndex;
use App\Livewire\Ordenes\Show as OrdenShow;
use App\Livewire\Productos\Index as ProductosIndex;
use App\Livewire\Productos\Show as ProductoShow;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::get('/productos', ProductosIndex::class)->name('productos.index');
Route::get('/productos/{producto}', ProductoShow::class)->name('productos.show');
Route::get('/ordenes', OrdenesIndex::class)->name('ordenes.index');
Route::get('/ordenes/crear', OrdenesIndex::class)->name('ordenes.create');
Route::get('/ordenes/{orden}', OrdenShow::class)->name('ordenes.show');
