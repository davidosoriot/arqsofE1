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
        $datos = $this->geocodificador->completarCoordenadas($request->validated());
        if ($datos === null) {
            return back()
                ->withInput()
                ->withErrors(['direccion' => __('mensajes.sede_no_geocodificada')]);
        }

        Sede::create($datos);

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
        $datos = $this->geocodificador->completarCoordenadas($request->validated());
        if ($datos === null) {
            return back()
                ->withInput()
                ->withErrors(['direccion' => __('mensajes.sede_no_geocodificada')]);
        }

        $sede->update($datos);

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
}
