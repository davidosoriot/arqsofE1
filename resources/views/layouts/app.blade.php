{{-- Autor: David Osorio --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carnicería Virtual — @yield('titulo')</title>
</head>
<body>
    <header>
        <a href="{{ route('catalogo.index') }}">Carnicería Virtual</a>
        <nav>
            <a href="{{ route('catalogo.index') }}">{{ __('mensajes.catalogo') }}</a>
            @auth
                <a href="{{ route('carrito.index') }}">{{ __('mensajes.carrito') }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">{{ __('mensajes.cerrar_sesion') }}</button>
                </form>
            @else
                <a href="{{ route('login') }}">{{ __('mensajes.iniciar_sesion') }}</a>
                <a href="{{ route('register') }}">{{ __('mensajes.crear_cuenta') }}</a>
            @endauth
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
