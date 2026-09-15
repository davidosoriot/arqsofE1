<?php
// Autor: David Osorio

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoriaController extends Controller
{
    public function index(): View
    {
        $categorias = Categoria::orderBy('nombre')->get();

        return view('admin.categorias.index', compact('categorias'));
    }

    public function create(): View
    {
        return view('admin.categorias.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:categorias,nombre'],
        ]);

        Categoria::create($datos);

        return redirect()
            ->route('admin.categorias.index')
            ->with('mensaje', __('mensajes.elemento_creado'));
    }

    public function show(Categoria $categoria): View
    {
        $categoria->loadCount('productos');

        return view('admin.categorias.show', compact('categoria'));
    }

    public function edit(Categoria $categoria): View
    {
        return view('admin.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:categorias,nombre,'.$categoria->id],
        ]);

        $categoria->update($datos);

        return redirect()
            ->route('admin.categorias.show', $categoria)
            ->with('mensaje', __('mensajes.elemento_actualizado'));
    }

    public function destroy(Categoria $categoria): RedirectResponse
    {
        $categoria->delete();

        return redirect()
            ->route('admin.categorias.index')
            ->with('mensaje', __('mensajes.elemento_eliminado'));
    }
}
