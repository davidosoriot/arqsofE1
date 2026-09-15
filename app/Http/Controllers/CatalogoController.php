<?php
// Autor: David Osorio

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Promocion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CatalogoController extends Controller
{
    /**
     * Funcionalidad interesante 1: busqueda de productos por nombre o tipo de carne.
     */
    public function index(Request $request): View
    {
        $productos = Producto::with('categoria')->where('disponible', true);

        if ($request->filled('q')) {
            $texto = $request->string('q');
            $productos->where(function ($sub) use ($texto) {
                $sub->where('nombre', 'like', "%{$texto}%")
                    ->orWhere('tipo_carne', 'like', "%{$texto}%");
            });
        }

        $productos = $productos->orderBy('nombre')->get();

        return view('catalogo.index', compact('productos'));
    }

    /**
     * Ficha de producto: vista de detalle para el usuario final.
     */
    public function show(Producto $producto): View
    {
        $producto->load('categoria');

        return view('catalogo.show', compact('producto'));
    }

    /**
     * Funcionalidad interesante 2: promociones vigentes hoy (usa Promocion::estaVigente()).
     */
    public function promocionesVigentes(): View
    {
        $promociones = Promocion::all()->filter->estaVigente()->values();

        return view('catalogo.promociones', compact('promociones'));
    }

    /**
     * Funcionalidad interesante 3: top 3 productos mas vendidos (agregando pedido_producto).
     */
    public function masVendidos(): View
    {
        $ventas = DB::table('pedido_producto')
            ->select('producto_id')
            ->selectRaw('SUM(cantidad) as total_vendido')
            ->groupBy('producto_id')
            ->orderByDesc('total_vendido')
            ->take(3)
            ->get();

        $productos = Producto::with('categoria')
            ->whereIn('id', $ventas->pluck('producto_id'))
            ->get()
            ->keyBy('id');

        $productos = $ventas->map(function ($venta) use ($productos) {
            $producto = $productos->get($venta->producto_id);
            $producto->total_vendido = $venta->total_vendido;

            return $producto;
        });

        return view('catalogo.mas-vendidos', compact('productos'));
    }
}
