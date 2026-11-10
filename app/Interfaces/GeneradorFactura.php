<?php
// Autor: David Osorio

namespace App\Interfaces;

use App\Models\Pedido;
use Symfony\Component\HttpFoundation\Response;

/**
 * Contrato para generar una factura de pedido en distintos formatos.
 * Permite al PedidoController no depender de dompdf ni PhpSpreadsheet.
 */
interface GeneradorFactura
{
    public function generar(Pedido $pedido): Response;
}
