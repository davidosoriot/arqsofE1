<?php
// Autor: Mateo Ariztizabal

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductoResource;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductoApiController extends Controller
{
    /**
     * GET /api/productos-en-venta
     * Servicio consumido por el Equipo 3 (Tienda de Joyas).
     * Filtro opcional: ?tipo_carne=res|cerdo|pollo
     */
    public function index(Request $request): JsonResponse
    {
        $query = Producto::with('categoria')->where('disponible', true);

        if ($request->filled('tipo_carne')) {
            $query->where('tipo_carne', $request->string('tipo_carne'));
        }

        $productos = $query->orderBy('nombre')->get();

        return response()->json([
            'tienda' => 'Carniceria Virtual',
            'generado' => now()->toIso8601String(),
            'total' => $productos->count(),
            'productos' => ProductoResource::collection($productos),
        ]);
    }
}
