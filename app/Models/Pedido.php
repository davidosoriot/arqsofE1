<?php
// Autor: David Osorio

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pedido extends Model
{
    protected $fillable = ['user_id', 'total', 'estado'];

    protected function casts(): array
    {
        return ['total' => 'decimal:2'];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'pedido_producto')
            ->withPivot('cantidad', 'precio_unitario')
            ->withTimestamps();
    }

    public function pago(): HasOne
    {
        return $this->hasOne(Pago::class);
    }

    public function cambiarEstado(string $estado): void
    {
        $this->update(['estado' => $estado]);
    }
}
