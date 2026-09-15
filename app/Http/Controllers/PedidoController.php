<?php
// Autor: David Osorio

namespace App\Http\Controllers;

use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PedidoController extends Controller
{
    public function show(Pedido $pedido): View
    {
        $pedido->load('productos', 'usuario', 'pago');

        return view('pedidos.show', compact('pedido'));
    }

    /**
     * Funcionalidad interesante 4: generar la factura del pedido en PDF.
     */
    public function facturaPdf(Pedido $pedido): Response
    {
        $pedido->load('productos', 'usuario');

        $pdf = Pdf::loadView('pedidos.factura', compact('pedido'));

        return $pdf->download("factura-pedido-{$pedido->id}.pdf");
    }
}
