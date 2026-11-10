<?php
// Autor: David Osorio

namespace App\Http\Controllers;

use App\Services\AliadoService;
use Illuminate\View\View;

class AliadoController extends Controller
{
    public function __construct(private readonly AliadoService $aliados) {}

    public function index(): View
    {
        $productos = $this->aliados->obtenerProductos();

        return view('aliados.index', compact('productos'));
    }
}
