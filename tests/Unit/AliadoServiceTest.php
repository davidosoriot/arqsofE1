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
        // Mismo formato que usa el Equipo 1: envoltorio "data" con campos
        // titulo, plataforma, categoria, precio (USD), stock, imagen, enlace.
        Http::fake([
            '*' => Http::response([
                'data' => [
                    [
                        'id' => 9,
                        'titulo' => "Baldur's Gate 3",
                        'plataforma' => 'PC',
                        'categoria' => 'Estrategia',
                        'precio' => 59.99,
                        'stock' => 171,
                        'imagen' => 'https://cdn.test/bg3.jpg',
                        'enlace' => 'https://aliado.test/videojuegos/9',
                    ],
                    [
                        'id' => 8,
                        'titulo' => 'Celeste',
                        'plataforma' => 'PC',
                        'categoria' => 'Acción',
                        'precio' => 19.99,
                        'stock' => 0,
                        'imagen' => null,
                        'enlace' => 'https://aliado.test/videojuegos/8',
                    ],
                ],
            ], 200),
        ]);

        $servicio = new AliadoService();

        $resultado = $servicio->obtenerProductos();

        $this->assertCount(2, $resultado);

        $primero = $resultado->first();
        $this->assertSame("Baldur's Gate 3", $primero['titulo']);
        $this->assertSame('PC', $primero['plataforma']);
        $this->assertSame(59.99, $primero['precio']);
        $this->assertSame(171, $primero['stock']);
        $this->assertSame('https://cdn.test/bg3.jpg', $primero['imagen']);

        $this->assertSame(0, $resultado->last()['stock']);
    }
}
