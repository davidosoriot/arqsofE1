<?php
// Autor: David Osorio

namespace Tests\Unit;

use App\Services\GeocodificadorService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GeocodificadorServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config()->set('services.geocodificador.url', 'https://nominatim.test/search');
        config()->set('services.geocodificador.user_agent', 'CarniceriaVirtualTests/1.0');
        Cache::flush();
    }

    public function test_devuelve_coordenadas_cuando_nominatim_responde(): void
    {
        Http::fake([
            '*' => Http::response([
                ['lat' => '6.2088', 'lon' => '-75.5681', 'display_name' => 'El Poblado, Medellin'],
            ], 200),
        ]);

        $servicio = new GeocodificadorService();

        $resultado = $servicio->coordenadas('Cra 43A # 1-50', 'Medellín');

        $this->assertIsArray($resultado);
        $this->assertEqualsWithDelta(6.2088, $resultado['lat'], 0.0001);
        $this->assertEqualsWithDelta(-75.5681, $resultado['lng'], 0.0001);
    }

    public function test_retorna_null_cuando_no_hay_resultados(): void
    {
        Http::fake([
            '*' => Http::response([], 200),
        ]);

        $servicio = new GeocodificadorService();

        $this->assertNull($servicio->coordenadas('Direccion inexistente XYZ', 'Medellín'));
    }

    public function test_retorna_null_cuando_nominatim_falla(): void
    {
        Http::fake([
            '*' => Http::response('server error', 500),
        ]);

        $servicio = new GeocodificadorService();

        $this->assertNull($servicio->coordenadas('Cra 43A # 1-50', 'Medellín'));
    }
}
