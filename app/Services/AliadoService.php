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
 *
 * Nota: el aliado corre en un plan gratuito que se duerme por inactividad,
 * por eso usamos timeout largo (10s), reintento y cache local de respaldo.
 */
class AliadoService
{
    private const CACHE_KEY = 'aliado.productos';
    private const CACHE_MINUTOS = 10;
    private const TIMEOUT_SEGUNDOS = 10;

    public function obtenerProductos(): Collection
    {
        $url = config('services.equipo_aliado.url');

        if (empty($url)) {
            return collect();
        }

        return Cache::remember(self::CACHE_KEY, now()->addMinutes(self::CACHE_MINUTOS), function () use ($url) {
            try {
                $respuesta = Http::timeout(self::TIMEOUT_SEGUNDOS)->retry(2, 500)->get($url);

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
     * Mapea la respuesta del aliado (envoltorio "data") al formato que usa la vista.
     * Si cambian un campo, se toca solo este metodo.
     */
    private function normalizar(mixed $datos): Collection
    {
        $items = $datos['data'] ?? $datos['productos'] ?? $datos ?? [];

        if (! is_array($items)) {
            return collect();
        }

        return collect($items)->map(fn (array $item) => [
            'id' => $item['id'] ?? null,
            'titulo' => $item['titulo'] ?? $item['nombre'] ?? __('mensajes.sin_nombre'),
            'plataforma' => $item['plataforma'] ?? null,
            'categoria' => $item['categoria'] ?? null,
            'precio' => (float) ($item['precio'] ?? 0),
            'stock' => isset($item['stock']) ? (int) $item['stock'] : null,
            'imagen' => $item['imagen'] ?? null,
            'enlace' => $item['enlace'] ?? null,
        ]);
    }
}
