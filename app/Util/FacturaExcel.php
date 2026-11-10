<?php
// Autor: David Osorio

namespace App\Util;

use App\Interfaces\GeneradorFactura;
use App\Models\Pedido;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FacturaExcel implements GeneradorFactura
{
    public function generar(Pedido $pedido): Response
    {
        $pedido->load('productos', 'usuario');

        $libro = new Spreadsheet();
        $hoja = $libro->getActiveSheet();
        $hoja->setTitle('Factura');

        $hoja->setCellValue('A1', 'Carniceria Virtual');
        $hoja->setCellValue('A2', __('mensajes.factura_de_pedido').' #'.$pedido->id);
        $hoja->setCellValue('A4', __('mensajes.cliente').':');
        $hoja->setCellValue('B4', $pedido->usuario->name ?? '');
        $hoja->setCellValue('A5', __('mensajes.fecha').':');
        $hoja->setCellValue('B5', $pedido->created_at->format('d/m/Y'));

        $hoja->setCellValue('A7', __('mensajes.producto'));
        $hoja->setCellValue('B7', __('mensajes.cantidad'));
        $hoja->setCellValue('C7', __('mensajes.precio_unitario'));
        $hoja->setCellValue('D7', __('mensajes.subtotal'));

        $fila = 8;
        foreach ($pedido->productos as $producto) {
            $cantidad = (int) $producto->pivot->cantidad;
            $precio = (float) $producto->pivot->precio_unitario;

            $hoja->setCellValue("A{$fila}", $producto->nombre);
            $hoja->setCellValue("B{$fila}", $cantidad);
            $hoja->setCellValue("C{$fila}", $precio);
            $hoja->setCellValue("D{$fila}", $cantidad * $precio);
            $fila++;
        }

        $hoja->setCellValue("A{$fila}", __('mensajes.total'));
        $hoja->setCellValue("D{$fila}", (float) $pedido->total);

        foreach (['A', 'B', 'C', 'D'] as $col) {
            $hoja->getColumnDimension($col)->setAutoSize(true);
        }

        $nombre = "factura-pedido-{$pedido->id}.xlsx";

        return new StreamedResponse(function () use ($libro) {
            (new Xlsx($libro))->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$nombre}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
