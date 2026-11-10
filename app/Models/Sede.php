<?php
// Autor: David Osorio

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    protected $table = 'sedes';

    protected $fillable = [
        'nombre',
        'direccion',
        'ciudad',
        'horario',
        'telefono',
        'latitud',
        'longitud',
    ];

    protected function casts(): array
    {
        return [
            'latitud' => 'float',
            'longitud' => 'float',
        ];
    }

    public function tieneUbicacion(): bool
    {
        return $this->latitud !== null && $this->longitud !== null;
    }

    /**
     * Distancia en km hasta un punto dado, formula de Haversine.
     * Devuelve PHP_FLOAT_MAX si la sede no tiene coordenadas, para que no gane
     * una comparacion de "mas cercana".
     */
    public function distanciaKm(float $lat, float $lng): float
    {
        if (! $this->tieneUbicacion()) {
            return PHP_FLOAT_MAX;
        }

        $radioTierra = 6371.0;
        $dLat = deg2rad($lat - $this->latitud);
        $dLng = deg2rad($lng - $this->longitud);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($this->latitud)) * cos(deg2rad($lat))
            * sin($dLng / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($radioTierra * $c, 3);
    }

    public static function masCercanaA(float $lat, float $lng): ?Sede
    {
        $sedes = static::whereNotNull('latitud')->whereNotNull('longitud')->get();

        if ($sedes->isEmpty()) {
            return null;
        }

        return $sedes->sortBy(fn (Sede $sede) => $sede->distanciaKm($lat, $lng))->first();
    }
}
