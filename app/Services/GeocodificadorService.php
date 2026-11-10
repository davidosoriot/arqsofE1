<?php
// Autor: David Osorio

namespace App\Services;

use App\Models\Sede;
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
     * Geocodifica una direccion libre y devuelve la sede mas cercana,
     * o null si no se pudo ubicar. Mantiene fuera del modelo Sede la
     * dependencia al servicio externo.
     */
    public function sedeMasCercana(string $direccion, string $ciudad = 'Medellín'): ?Sede
    {
        $coordenadas = $this->coordenadas($direccion, $ciudad);
        if ($coordenadas === null) {
            return null;
        }

        return Sede::masCercanaA($coordenadas['lat'], $coordenadas['lng']);
    }

    /**
     * Toma el payload de un formulario de Sede: si el admin ya escribio
     * latitud y longitud se respetan; si vienen vacias, se intenta geocodificar.
     * Retorna null si no hay forma de resolver las coordenadas.
     *
     * @param  array<string, mixed>  $datos
     * @return array<string, mixed>|null
     */
    public function completarCoordenadas(array $datos): ?array
    {
        $tieneLatitud = isset($datos['latitud']) && $datos['latitud'] !== '';
        $tieneLongitud = isset($datos['longitud']) && $datos['longitud'] !== '';

        if ($tieneLatitud && $tieneLongitud) {
            return $datos;
        }

        $coordenadas = $this->coordenadas($datos['direccion'] ?? '', $datos['ciudad'] ?? 'Medellín');
        if ($coordenadas === null) {
            return null;
        }

        $datos['latitud'] = $coordenadas['lat'];
        $datos['longitud'] = $coordenadas['lng'];

        return $datos;
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
