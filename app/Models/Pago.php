<?php
// Autor: David Osorio

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $fillable = ['pedido_id', 'metodo', 'estado'];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function procesarPago(): void
    {
        $this->update(['estado' => 'aprobado']);
        $this->pedido->cambiarEstado('pagado');
    }
}
