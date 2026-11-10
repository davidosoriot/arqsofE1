<?php
// Autor: David Osorio

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Servicio de tercero: Nominatim (OpenStreetMap).
 *
 * Politica de uso respetada:
 *   - User-Agent identificable (configurable, ver config/services.php).
 *   - Maximo 1 peticion/segundo: evitamos spam cacheando 30 dias por direccion.
 *   - Tolerante a fallos: nunca lanza excepcion hacia la vista, retorna null.
 */
class GeocodificadorService
{
    private const CACHE_DIAS = 30;

    /**
     * @return array{lat: float, lng: float}|null
     */
    public function coordenadas(string $direccion, string $ciudad = 'Medellín'): ?array
    {
        $direccion = trim($direccion);
        $ciudad = trim($ciudad);

        if ($direccion === '') {
            return null;
        }

        $clave = 'geocoder.'.md5(strtolower("{$direccion}|{$ciudad}"));

        return Cache::remember($clave, now()->addDays(self::CACHE_DIAS), function () use ($direccion, $ciudad) {
            return $this->consultarNominatim($direccion, $ciudad);
        });
    }

    /**
     * @return array{lat: float, lng: float}|null
     */
    private function consultarNominatim(string $direccion, string $ciudad): ?array
    {
        try {
            $respuesta = Http::withHeaders([
                'User-Agent' => config('services.geocodificador.user_agent'),
                'Accept-Language' => 'es',
            ])->timeout(5)->get(config('services.geocodificador.url'), [
                'q' => "{$direccion}, {$ciudad}, Colombia",
                'format' => 'jsonv2',
                'limit' => 1,
                'countrycodes' => 'co',
            ]);

            if ($respuesta->failed()) {
                Log::warning('Nominatim respondio con error', ['status' => $respuesta->status()]);

                return null;
            }

            $resultados = $respuesta->json();

            if (! is_array($resultados) || empty($resultados[0])) {
                return null;
            }

            $primero = $resultados[0];

            if (! isset($primero['lat'], $primero['lon'])) {
                return null;
            }

            return [
                'lat' => (float) $primero['lat'],
                'lng' => (float) $primero['lon'],
            ];
        } catch (Throwable $e) {
            Log::error('Fallo la consulta a Nominatim: '.$e->getMessage());

            return null;
        }
    }
}
