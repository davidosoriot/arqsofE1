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

        $usuario = auth()->user();
        $cercana = ($usuario && ! empty($usuario->direccion))
            ? $this->geocodificador->sedeMasCercana($usuario->direccion)
            : null;

        return view('sedes.index', compact('sedes', 'cercana'));
    }
}
