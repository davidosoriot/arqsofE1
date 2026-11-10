<?php
// Autor: David Osorio

namespace App\Providers;

use App\Interfaces\GeneradorFactura;
use App\Util\FacturaExcel;
use App\Util\FacturaPdf;
use Illuminate\Support\ServiceProvider;

/**
 * Inversion de dependencias: el PedidoController no conoce dompdf ni PhpSpreadsheet,
 * solo pide un GeneradorFactura. El binding decide la implementacion por el parametro "formato".
 */
class FacturaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(GeneradorFactura::class, function ($app, array $parametros = []) {
            $formato = $parametros['formato'] ?? 'pdf';

            return match ($formato) {
                'excel' => new FacturaExcel(),
                default => new FacturaPdf(),
            };
        });
    }
}
