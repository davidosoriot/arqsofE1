<?php
// Autor: David Osorio

namespace App\Util;

use App\Interfaces\GeneradorFactura;
use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

class FacturaPdf implements GeneradorFactura
{
    public function generar(Pedido $pedido): Response
    {
        $pedido->load('productos', 'usuario');

        return Pdf::loadView('pedidos.factura', compact('pedido'))
            ->download("factura-pedido-{$pedido->id}.pdf");
    }
}
