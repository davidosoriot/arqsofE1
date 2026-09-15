<?php
// Autor: David Osorio

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Promocion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin Carnicería',
            'email' => 'admin@carniceriavirtual.com',
            'rol' => 'administrador',
            'password' => Hash::make('admin123'),
        ]);

        User::create([
            'name' => 'Cliente de prueba',
            'email' => 'cliente@correo.com',
            'direccion' => 'Cra 45 # 10-20, Medellín',
            'rol' => 'cliente',
            'password' => Hash::make('cliente123'),
        ]);

        $categorias = collect(['Res', 'Cerdo', 'Pollo', 'Embutidos'])
            ->mapWithKeys(fn ($nombre) => [$nombre => Categoria::create(['nombre' => $nombre])->id]);

        $productos = [
            ['nombre' => 'Lomo fino', 'tipo_carne' => 'Res', 'categoria' => 'Res', 'precio' => 38500],
            ['nombre' => 'Costilla de res', 'tipo_carne' => 'Res', 'categoria' => 'Res', 'precio' => 22000],
            ['nombre' => 'Punta de anca', 'tipo_carne' => 'Res', 'categoria' => 'Res', 'precio' => 26000],
            ['nombre' => 'Chuleta de cerdo', 'tipo_carne' => 'Cerdo', 'categoria' => 'Cerdo', 'precio' => 15000],
            ['nombre' => 'Chorizo', 'tipo_carne' => 'Cerdo', 'categoria' => 'Embutidos', 'precio' => 9800],
            ['nombre' => 'Muslo de pollo', 'tipo_carne' => 'Pollo', 'categoria' => 'Pollo', 'precio' => 12000],
            ['nombre' => 'Pechuga de pollo', 'tipo_carne' => 'Pollo', 'categoria' => 'Pollo', 'precio' => 18500],
            ['nombre' => 'Morcilla', 'tipo_carne' => 'Cerdo', 'categoria' => 'Embutidos', 'precio' => 8500],
        ];

        foreach ($productos as $p) {
            Producto::create([
                'categoria_id' => $categorias[$p['categoria']],
                'nombre' => $p['nombre'],
                'tipo_carne' => $p['tipo_carne'],
                'precio' => $p['precio'],
                'disponible' => true,
            ]);
        }

        Promocion::create(['dia_semana' => 'martes', 'tipo_carne' => 'Res', 'descuento' => 10]);
        Promocion::create(['dia_semana' => 'viernes', 'tipo_carne' => 'Pollo', 'descuento' => 15]);
        Promocion::create(['dia_semana' => 'domingo', 'tipo_carne' => 'Cerdo', 'descuento' => 20]);
    }
}
