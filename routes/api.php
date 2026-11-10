<?php
// Autor: Mateo Ariztizabal

use App\Http\Controllers\Api\ProductoApiController;
use Illuminate\Support\Facades\Route;

Route::get('/productos-en-venta', [ProductoApiController::class, 'index'])
    ->name('api.productos.index');
