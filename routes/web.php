<?php
// Autor: David Osorio

use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\PedidoController;
use Illuminate\Support\Facades\Route;

// --- Autenticación ---
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
Route::get('/registro', [RegisterController::class, 'create'])->name('register');
Route::post('/registro', [RegisterController::class, 'store'])->name('register.store');

// --- Sección usuario final (publica / "/*") ---
Route::get('/', [CatalogoController::class, 'index'])->name('catalogo.index');
Route::get('/promociones', [CatalogoController::class, 'promocionesVigentes'])->name('catalogo.promociones');
Route::get('/mas-vendidos', [CatalogoController::class, 'masVendidos'])->name('catalogo.mas-vendidos');
Route::get('/productos/{producto}', [CatalogoController::class, 'show'])->name('catalogo.show');

Route::middleware('auth')->group(function () {
    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
    Route::post('/carrito/checkout', [CarritoController::class, 'checkout'])->name('carrito.checkout');
    Route::post('/carrito/{producto}', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::delete('/carrito/{producto}', [CarritoController::class, 'quitar'])->name('carrito.quitar');

    Route::get('/pedidos/{pedido}', [PedidoController::class, 'show'])->name('pedidos.show');
    Route::get('/pedidos/{pedido}/factura', [PedidoController::class, 'facturaPdf'])->name('pedidos.factura');
});

// --- Sección administrador ("/admin/*") ---
Route::middleware(['auth', 'es.administrador'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('productos', ProductoController::class);
    Route::resource('categorias', CategoriaController::class);
});
