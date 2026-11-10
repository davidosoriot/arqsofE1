<?php
// Autor: David Osorio

namespace App\Http\Controllers;

use App\Models\Sede;
use App\Services\GeocodificadorService;
use Illuminate\View\View;

class SedeController extends Controller
{
    public function __construct(private readonly GeocodificadorService $geocodificador) {}

    public function index(): View
    {
        $sedes = Sede::orderBy('nombre')->get();
        $cercana = null;

        $usuario = auth()->user();
        if ($usuario && ! empty($usuario->direccion)) {
            $coordenadas = $this->geocodificador->coordenadas($usuario->direccion);
            if ($coordenadas !== null) {
                $cercana = Sede::masCercanaA($coordenadas['lat'], $coordenadas['lng']);
            }
        }

        return view('sedes.index', compact('sedes', 'cercana'));
    }
}
