<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CarritoController extends Controller
{
    public function index(Request $request): View
    {
        $carrito = $request->user()->carrito()->firstOrCreate([]);
        $carrito->load('productos');

        $detalle = $carrito->calcularDetalle();

        return view('carrito.index', [
            'carrito' => $carrito,
            'detalle' => $detalle,
            'total' => $carrito->calcularTotal(),
            'ahorroTotal' => collect($detalle)->sum('ahorro'),
        ]);
    }

    public function agregar(Request $request, Producto $producto): RedirectResponse
    {
        $datos = $request->validate([
            'cantidad' => [
                'sometimes',
                'required',
                'numeric',
                'gt:0',
                'max:1000',
                'regex:/^\d+(\.\d{1,3})?$/',
            ],
        ]);

        $carrito = $request->user()->carrito()->firstOrCreate([]);

        $carrito->agregarItem(
            $producto,
            (float) ($datos['cantidad'] ?? 1)
        );

        return redirect()
            ->route('carrito.index')
            ->with('mensaje', __('mensajes.producto_agregado'));
    }

    public function quitar(Request $request, Producto $producto): RedirectResponse
    {
        $carrito = $request->user()->carrito;

        if ($carrito) {
            $carrito->productos()->detach($producto->id);
        }

        return redirect()
            ->route('carrito.index')
            ->with('mensaje', __('mensajes.producto_quitado'));
    }

    public function checkout(Request $request): RedirectResponse
    {
        $carrito = $request->user()->carrito;

        if (! $carrito || $carrito->productos()->count() === 0) {
            return redirect()
                ->route('carrito.index')
                ->with('mensaje', __('mensajes.carrito_vacio'));
        }

        $pedido = $carrito->generarPedido();

        return redirect()
            ->route('pedidos.show', $pedido)
            ->with('mensaje', __('mensajes.pedido_creado'));
    }
}
