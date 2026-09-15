<?php
// Autor: David Osorio

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    protected $table = 'promociones';

    protected $fillable = ['dia_semana', 'tipo_carne', 'descuento'];

    protected function casts(): array
    {
        return ['descuento' => 'decimal:2'];
    }

    public function estaVigente(): bool
    {
        $diasSemana = ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
        $hoy = $diasSemana[now()->dayOfWeek];

        return strtolower($this->dia_semana) === $hoy;
    }
}
