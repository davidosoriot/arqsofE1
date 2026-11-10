<?php
// Autor: David Osorio

namespace Database\Seeders;

use App\Models\Sede;
use Illuminate\Database\Seeder;

/**
 * Seed de 3 sedes en Medellin con coordenadas fijas. No llama a Nominatim
 * para que el seed sea offline y determinista.
 */
class SedeSeeder extends Seeder
{
    public function run(): void
    {
        $sedes = [
            [
                'nombre' => 'Sede El Poblado',
                'direccion' => 'Cra 43A # 1-50',
                'ciudad' => 'Medellín',
                'horario' => 'Lun–Sab 7:00–20:00',
                'telefono' => '+57 604 444 0001',
                'latitud' => 6.2088,
                'longitud' => -75.5681,
            ],
            [
                'nombre' => 'Sede Laureles',
                'direccion' => 'Circular 4 # 70-23',
                'ciudad' => 'Medellín',
                'horario' => 'Lun–Sab 7:30–20:00',
                'telefono' => '+57 604 444 0002',
                'latitud' => 6.2440,
                'longitud' => -75.5932,
            ],
            [
                'nombre' => 'Sede Belén',
                'direccion' => 'Cra 76 # 32-15',
                'ciudad' => 'Medellín',
                'horario' => 'Lun–Dom 8:00–19:00',
                'telefono' => '+57 604 444 0003',
                'latitud' => 6.2328,
                'longitud' => -75.6060,
            ],
        ];

        foreach ($sedes as $sede) {
            Sede::create($sede);
        }
    }
}
