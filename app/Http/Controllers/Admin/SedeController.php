<?php
// Autor: David Osorio

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SedeRequest;
use App\Models\Sede;
use App\Services\GeocodificadorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SedeController extends Controller
{
    public function __construct(private readonly GeocodificadorService $geocodificador) {}

    public function index(): View
    {
        $sedes = Sede::orderBy('nombre')->get();

        return view('admin.sedes.index', compact('sedes'));
    }

    public function create(): View
    {
        return view('admin.sedes.create');
    }

    public function store(SedeRequest $request): RedirectResponse
    {
        $datos = $request->validated();

        $resuelto = $this->resolverCoordenadas($datos);
        if ($resuelto === null) {
            return back()
                ->withInput()
                ->withErrors(['direccion' => __('mensajes.sede_no_geocodificada')]);
        }

        Sede::create($resuelto);

        return redirect()
            ->route('admin.sedes.index')
            ->with('mensaje', __('mensajes.elemento_creado'));
    }

    public function show(Sede $sede): View
    {
        return view('admin.sedes.show', compact('sede'));
    }

    public function edit(Sede $sede): View
    {
        return view('admin.sedes.edit', compact('sede'));
    }

    public function update(SedeRequest $request, Sede $sede): RedirectResponse
    {
        $datos = $request->validated();

        $resuelto = $this->resolverCoordenadas($datos);
        if ($resuelto === null) {
            return back()
                ->withInput()
                ->withErrors(['direccion' => __('mensajes.sede_no_geocodificada')]);
        }

        $sede->update($resuelto);

        return redirect()
            ->route('admin.sedes.show', $sede)
            ->with('mensaje', __('mensajes.elemento_actualizado'));
    }

    public function destroy(Sede $sede): RedirectResponse
    {
        $sede->delete();

        return redirect()
            ->route('admin.sedes.index')
            ->with('mensaje', __('mensajes.elemento_eliminado'));
    }

    /**
     * Si el admin dejo lat/lng en blanco, pide las coordenadas al servicio de tercero.
     * Si tampoco se consiguen, retorna null para que el controlador redirija con error.
     *
     * @param  array<string, mixed>  $datos
     * @return array<string, mixed>|null
     */
    private function resolverCoordenadas(array $datos): ?array
    {
        $tieneLatitud = isset($datos['latitud']) && $datos['latitud'] !== '';
        $tieneLongitud = isset($datos['longitud']) && $datos['longitud'] !== '';

        if ($tieneLatitud && $tieneLongitud) {
            return $datos;
        }

        $coordenadas = $this->geocodificador->coordenadas($datos['direccion'], $datos['ciudad']);
        if ($coordenadas === null) {
            return null;
        }

        $datos['latitud'] = $coordenadas['lat'];
        $datos['longitud'] = $coordenadas['lng'];

        return $datos;
    }
}
