{{-- Autor: David Osorio --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carnicería Virtual — @yield('titulo')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --rojo: #B3141C;
            --rojo-oscuro: #6E0D12;
            --vino: #2B0A0C;
            --hueso: #F7F1EE;
            --linea: #E6D8D4;
            --tinta: #2A1517;
            --gris: #7A6A6B;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Source Sans 3', sans-serif; background: var(--hueso); color: var(--tinta); line-height: 1.5; }
        a { color: var(--rojo); }
        h1, h2, h3 { font-family: 'Archivo Black', sans-serif; font-weight: 400; line-height: 1.15; margin: 0; }

        /* Cabecera */
        header { background: var(--rojo-oscuro); border-bottom: 4px solid var(--rojo); }
        .barra { max-width: 1100px; margin: 0 auto; padding: .9rem 1.25rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
        .marca { font-family: 'Archivo Black', sans-serif; font-size: 1.4rem; color: #fff; text-decoration: none; }
        nav { display: flex; align-items: center; gap: 1.25rem; flex-wrap: wrap; }
        nav a, nav button { color: #fff; text-decoration: none; font: inherit; font-weight: 600; background: none; border: 0; padding: .25rem 0; cursor: pointer; }
        nav a:hover, nav button:hover { text-decoration: underline; }
        nav form { margin: 0; }

        /* Contenido */
        main { max-width: 1100px; margin: 0 auto; padding: 1.5rem 1.25rem 3rem; }
        .mensaje { background: #fff; border-left: 5px solid var(--rojo); padding: .75rem 1rem; margin-bottom: 1.25rem; }

        /* Botones y formularios */
        .btn { display: inline-block; background: var(--rojo); color: #fff; border: 0; border-radius: 4px; padding: .6rem 1.1rem; font: inherit; font-weight: 700; text-decoration: none; cursor: pointer; }
        .btn:hover { background: var(--rojo-oscuro); }
        .btn-claro { background: #fff; color: var(--rojo-oscuro); }
        .btn-claro:hover { background: var(--hueso); }
        input[type=text], input[type=search] { font: inherit; padding: .6rem .8rem; border: 1px solid var(--linea); border-radius: 4px; min-width: 0; }
        :focus-visible { outline: 3px solid #FFB3B7; outline-offset: 2px; }

        /* Portada del catálogo */
        .portada { background: var(--vino); color: #fff; margin: -1.5rem -1.25rem 2rem; padding: 2.5rem 1.25rem; }
        .portada h1 { font-size: clamp(2rem, 5vw, 3.2rem); margin-bottom: 1rem; }
        .buscador { display: flex; gap: .5rem; flex-wrap: wrap; }
        .buscador input { flex: 1 1 240px; border-color: transparent; }
        .accesos { display: flex; gap: .75rem; flex-wrap: wrap; margin-top: 1rem; }

        /* Cortes */
        .seccion-carne { margin-bottom: 2.5rem; }
        .seccion-carne h2 { font-size: 1.5rem; color: var(--rojo-oscuro); border-bottom: 3px solid var(--rojo); padding-bottom: .4rem; margin-bottom: 1rem; }
        .cortes { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 1rem; }
        .corte { background: #fff; border: 1px solid var(--linea); border-top: 5px solid var(--rojo); border-radius: 4px; padding: 1rem; display: flex; flex-direction: column; gap: .4rem; }
        .corte h3 { font-size: 1.1rem; }
        .corte h3 a { color: var(--tinta); text-decoration: none; }
        .corte h3 a:hover { color: var(--rojo); }
        .precio { font-size: 1.4rem; font-weight: 700; color: var(--rojo); }
        .precio small { font-size: .9rem; color: var(--gris); font-weight: 400; }
        .corte .btn { margin-top: auto; text-align: center; }

        /* Promociones */
        .promos { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem; }
        .promo { background: var(--rojo); color: #fff; border-radius: 4px; padding: 1.25rem; }
        .promo .porcentaje { font-family: 'Archivo Black', sans-serif; font-size: 3rem; line-height: 1; }
        .promo p { margin: .5rem 0 0; font-weight: 600; }
        .vacio { background: #fff; border: 1px dashed var(--linea); padding: 1.5rem; text-align: center; color: var(--gris); }

        footer { background: var(--vino); color: #E8CFCB; text-align: center; padding: 1.25rem; font-size: .9rem; }
    </style>
</head>
<body>
    <header>
        <div class="barra">
            <a class="marca" href="{{ route('catalogo.index') }}">Carnicería Virtual</a>
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
        </div>
    </header>

    <main>
        @if (session('mensaje'))
            <p class="mensaje">{{ session('mensaje') }}</p>
        @endif

        @yield('contenido')
    </main>

    <footer>Carnicería Virtual</footer>
</body>
</html>