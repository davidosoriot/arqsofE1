<?php
// Autor: Maria Jose Holguin

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class IdiomaController extends Controller
{
    private const IDIOMAS_VALIDOS = ['es', 'en'];

    public function cambiar(Request $request, string $locale): RedirectResponse
    {
        if (in_array($locale, self::IDIOMAS_VALIDOS, true)) {
            $request->session()->put('locale', $locale);
        }

        return back();
    }
}
