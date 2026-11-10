<?php
// Autor: Maria Jose Holguin

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class EstablecerIdioma
{
    private const IDIOMAS_VALIDOS = ['es', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale', config('app.locale', 'es'));

        if (in_array($locale, self::IDIOMAS_VALIDOS, true)) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
