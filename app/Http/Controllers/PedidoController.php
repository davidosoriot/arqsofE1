<?php
// Autor: David Osorio

namespace App\Http\Controllers;

use App\Interfaces\GeneradorFactura;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class PedidoController extends Controller
{
    public function show(Pedido $pedido): View
    {
        $pedido->load('productos', 'usuario', 'pago');

        return view('pedidos.show', compact('pedido'));
    }

    /**
     * Funcionalidad interesante 4: generar la factura del pedido en PDF o Excel.
     * Usa inversion de dependencias: el controlador solo conoce la interfaz GeneradorFactura.
     */
    public function factura(Request $request, Pedido $pedido): Response
    {
        $formato = $request->query('formato', 'pdf');

        $generador = app()->makeWith(GeneradorFactura::class, ['formato' => $formato]);

        return $generador->generar($pedido);
    }
}
