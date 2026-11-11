<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Producto extends Model
{
   protected $fillable = [
    'categoria_id',
    'nombre',
    'tipo_carne',
    'precio',
    'imagen',
    'disponible',
];

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'disponible' => 'boolean',
        ];
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function carritos(): BelongsToMany
    {
        return $this->belongsToMany(Carrito::class, 'carrito_producto')->withPivot('cantidad')->withTimestamps();
    }

    public function pedidos(): BelongsToMany
    {
        return $this->belongsToMany(Pedido::class, 'pedido_producto')
            ->withPivot('cantidad', 'precio_unitario')
            ->withTimestamps();
    }

    public function actualizarStock(bool $disponible): void
    {
        $this->update(['disponible' => $disponible]);
    }

    public function descuentoVigente(): float
    {
        $promocion = Promocion::where('tipo_carne', $this->tipo_carne)
            ->get()
            ->first(fn (Promocion $promo) => $promo->estaVigente());

        return $promocion ? (float) $promocion->descuento : 0.0;
    }

    public function precioHoy(): float
    {
        $descuento = $this->descuentoVigente();

        return round(((float) $this->precio) * (1 - $descuento / 100), 2);
    }
}
