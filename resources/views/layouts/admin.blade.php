{{-- Autor: David Osorio --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ __('mensajes.panel_admin') }} — @yield('titulo')</title>
</head>
<body>
    <header>
        <strong>{{ __('mensajes.panel_admin') }}</strong>
        <nav>
            <a href="{{ route('admin.productos.index') }}">{{ __('mensajes.productos') }}</a>
            <a href="{{ route('admin.categorias.index') }}">{{ __('mensajes.categorias') }}</a>
            <a href="{{ route('admin.sedes.index') }}">{{ __('mensajes.sedes') }}</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">{{ __('mensajes.cerrar_sesion') }}</button>
            </form>
        </nav>
    </header>

    <main>
        @if (session('mensaje'))
            <p>{{ session('mensaje') }}</p>
        @endif

        @yield('contenido')
    </main>
</body>
</html>
