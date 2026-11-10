<?php
// Autor: David Osorio

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Consume la API del Equipo 1 (Tienda de Videojuegos).
 * Se aisla toda la logica HTTP aqui: el controlador solo llama a obtenerProductos().
 */
class AliadoService
{
    private const CACHE_KEY = 'aliado.productos';
    private const CACHE_MINUTOS = 10;

    public function obtenerProductos(): Collection
    {
        $url = config('services.equipo_aliado.url');

        if (empty($url)) {
            return collect();
        }

        return Cache::remember(self::CACHE_KEY, now()->addMinutes(self::CACHE_MINUTOS), function () use ($url) {
            try {
                $respuesta = Http::timeout(5)->retry(2, 200)->get($url);

                if ($respuesta->failed()) {
                    Log::warning('Aliado respondio con error', ['status' => $respuesta->status()]);

                    return collect();
                }

                return $this->normalizar($respuesta->json());
            } catch (Throwable $e) {
                Log::error('Fallo la consulta al aliado: '.$e->getMessage());

                return collect();
            }
        });
    }

    /**
     * Mapea la respuesta del aliado al formato que usa nuestra vista.
     * Si cambian un campo, se toca solo este metodo.
     */
    private function normalizar(mixed $datos): Collection
    {
        $items = $datos['productos'] ?? $datos['data'] ?? $datos ?? [];

        if (! is_array($items)) {
            return collect();
        }

        return collect($items)->map(fn (array $item) => [
            'id' => $item['id'] ?? null,
            'nombre' => $item['nombre'] ?? $item['name'] ?? 'Sin nombre',
            'categoria' => $item['categoria'] ?? $item['category'] ?? null,
            'precio' => (float) ($item['precio'] ?? $item['price'] ?? 0),
            'enlace' => $item['enlace'] ?? $item['url'] ?? null,
        ]);
    }
}
