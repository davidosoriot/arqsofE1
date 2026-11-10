<?php
// Autor: David Osorio

namespace Tests\Unit;

use App\Services\AliadoService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AliadoServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config()->set('services.equipo_aliado.url', 'https://ejemplo-aliado.test/api/videojuegos');
        Cache::flush();
    }

    public function test_retorna_coleccion_vacia_cuando_la_api_falla(): void
    {
        Http::fake([
            '*' => Http::response('server error', 500),
        ]);

        $servicio = new AliadoService();

        $resultado = $servicio->obtenerProductos();

        $this->assertTrue($resultado->isEmpty());
    }

    public function test_normaliza_la_respuesta_del_aliado(): void
    {
        Http::fake([
            '*' => Http::response([
                'productos' => [
                    ['id' => 1, 'nombre' => 'Zelda', 'categoria' => 'Aventura', 'precio' => 150000, 'enlace' => 'https://aliado.test/1'],
                    ['id' => 2, 'name' => 'Mario', 'category' => 'Plataformas', 'price' => 120000],
                ],
            ], 200),
        ]);

        $servicio = new AliadoService();

        $resultado = $servicio->obtenerProductos();

        $this->assertCount(2, $resultado);
        $this->assertSame('Zelda', $resultado->first()['nombre']);
        $this->assertSame('Mario', $resultado->last()['nombre']);
        $this->assertSame(120000.0, $resultado->last()['precio']);
    }
}
