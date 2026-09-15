<?php
// Autor: David Osorio

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Carrito extends Model
{
    protected $fillable = ['user_id'];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'carrito_producto')->withPivot('cantidad')->withTimestamps();
    }

    public function agregarItem(Producto $producto, int $cantidad = 1): void
    {
        $this->productos()->syncWithoutDetaching([
            $producto->id => ['cantidad' => $cantidad],
        ]);
    }

    public function calcularTotal(): float
    {
        return $this->productos->sum(fn (Producto $producto) => $producto->precio * $producto->pivot->cantidad);
    }

    public function vaciar(): void
    {
        $this->productos()->detach();
    }

    /**
     * "Carrito genera Pedido": convierte el contenido actual del carrito
     * en un pedido nuevo y vacia el carrito. No es una relacion persistida
     * (Pedido no guarda carrito_id), es una operacion puntual del dominio.
     */
    public function generarPedido(): Pedido
    {
        $pedido = Pedido::create([
            'user_id' => $this->user_id,
            'total' => $this->calcularTotal(),
            'estado' => 'pendiente_pago',
        ]);

        foreach ($this->productos as $producto) {
            $pedido->productos()->attach($producto->id, [
                'cantidad' => $producto->pivot->cantidad,
                'precio_unitario' => $producto->precio,
            ]);
        }

        $this->vaciar();

        return $pedido;
    }
}
