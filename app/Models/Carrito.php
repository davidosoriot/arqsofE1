<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Carrito extends Model
{
    protected $fillable = ['user_id'];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'carrito_producto')
            ->withPivot('cantidad')
            ->withTimestamps();
    }

    /**
     * Agrega un producto al carrito con cantidad decimal en kilos.
     */
    public function agregarItem(Producto $producto, float $cantidad = 1.0): void
    {
        $this->productos()->syncWithoutDetaching([
            $producto->id => ['cantidad' => $cantidad],
        ]);
    }

    /**
     * Calcula precios, descuentos y subtotales de cada producto.
     */
    public function calcularDetalle(): array
    {
        $this->loadMissing('productos');

        $diasSemana = [
            'domingo',
            'lunes',
            'martes',
            'miercoles',
            'jueves',
            'viernes',
            'sabado',
        ];

        $hoy = $diasSemana[Carbon::now('America/Bogota')->dayOfWeek];

        $promociones = Promocion::query()
            ->whereRaw('LOWER(dia_semana) = ?', [$hoy])
            ->get();

        $detalle = [];

        foreach ($this->productos as $producto) {
            $tipoProducto = Str::ascii(
                Str::lower(trim($producto->tipo_carne))
            );

            $promocion = $promociones->first(function ($item) use ($tipoProducto) {
                $tipoPromocion = Str::ascii(
                    Str::lower(trim($item->tipo_carne))
                );

                return $tipoPromocion === $tipoProducto;
            });

            // El precio del producto corresponde al valor por kilogramo.
            $precioOriginal = (float) $producto->precio;

            $descuento = $promocion
                ? max(0, min(100, (float) $promocion->descuento))
                : 0;

            $precioFinal = round(
                $precioOriginal * (1 - ($descuento / 100)),
                2
            );

            // Se conserva la cantidad decimal en kilogramos.
            $cantidad = (float) $producto->pivot->cantidad;

            $subtotalOriginal = round($precioOriginal * $cantidad, 2);
            $subtotal = round($precioFinal * $cantidad, 2);

            $detalle[] = [
                'producto' => $producto,
                'cantidad' => $cantidad,
                'precio_original' => $precioOriginal,
                'descuento_porcentaje' => $descuento,
                'precio_unitario' => $precioFinal,
                'subtotal_original' => $subtotalOriginal,
                'subtotal' => $subtotal,
                'ahorro' => round($subtotalOriginal - $subtotal, 2),
            ];
        }

        return $detalle;
    }

    /**
     * Calcula el total del carrito con descuentos.
     */
    public function calcularTotal(): float
    {
        return round(
            collect($this->calcularDetalle())->sum('subtotal'),
            2
        );
    }

    /**
     * Vacía los productos del carrito.
     */
    public function vaciar(): void
    {
        $this->productos()->detach();
    }

    /**
     * Convierte el carrito en un pedido y conserva los precios cobrados.
     */
    public function generarPedido(): Pedido
    {
        return DB::transaction(function () {
            $detalle = $this->calcularDetalle();

            $total = round(
                collect($detalle)->sum('subtotal'),
                2
            );

            $pedido = Pedido::create([
                'user_id' => $this->user_id,
                'total' => $total,
                'estado' => 'pendiente_pago',
            ]);

            foreach ($detalle as $item) {
                $pedido->productos()->attach(
                    $item['producto']->id,
                    [
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $item['precio_unitario'],
                        'precio_original' => $item['precio_original'],
                        'descuento_porcentaje' => $item['descuento_porcentaje'],
                    ]
                );
            }

            $this->vaciar();

            return $pedido;
        });
    }
}
