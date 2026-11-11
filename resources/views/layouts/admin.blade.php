
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Los del Corte - @yield('titulo')</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f7f3f1;
            color: #352321;
        }

        .admin-header {
            background: #701c2b;
            color: white;
            padding: 20px 35px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            box-shadow: 0 3px 12px rgba(0,0,0,.15);
        }

        .marca {
            font-size: 25px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .marca small {
            display: block;
            font-size: 11px;
            font-weight: normal;
            color: #f0c8c8;
            letter-spacing: 2px;
            margin-top: 5px;
        }

        .admin-nav {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .admin-nav a,
        .admin-nav button {
            color: white;
            text-decoration: none;
            border: 1px solid rgba(255,255,255,.35);
            border-radius: 7px;
            padding: 10px 16px;
            background: transparent;
            font-size: 14px;
            cursor: pointer;
        }

        .admin-nav a:hover,
        .admin-nav button:hover {
            background: white;
            color: #701c2b;
        }

        .admin-nav form {
            margin: 0;
        }

        .admin-main {
            width: min(1200px, 94%);
            margin: 35px auto;
            min-height: 65vh;
        }

        .admin-contenido {
            background: white;
            border-radius: 14px;
            padding: 30px;
            box-shadow: 0 4px 18px rgba(65, 25, 25, .07);
        }

        .admin-main h1 {
            color: #701c2b;
            margin-top: 0;
            margin-bottom: 25px;
        }

        .mensaje {
            background: #e8f5e9;
            color: #27632d;
            border-left: 4px solid #398442;
            border-radius: 7px;
            padding: 14px 18px;
            margin-bottom: 20px;
        }

        .admin-footer {
            background: #3b171d;
            color: #f4dddd;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
            font-size: 13px;
        }

        @media (max-width: 650px) {
            .admin-header {
                padding: 20px;
                align-items: flex-start;
            }

            .admin-nav {
                width: 100%;
            }

            .admin-nav a,
            .admin-nav button {
                padding: 9px 11px;
            }

            .admin-contenido {
                padding: 18px;
            }
        }
    </style>
</head>

<body>
    <header class="admin-header">
        <div class="marca">
            LOS DEL CORTE
            <small>PANEL DE ADMINISTRACIÓN</small>
        </div>

        <nav class="admin-nav">
            <a href="{{ route('admin.productos.index') }}">
                {{ __('mensajes.productos') }}
            </a>

            <a href="{{ route('admin.categorias.index') }}">
                {{ __('mensajes.categorias') }}
            </a>

            <a href="{{ route('admin.sedes.index') }}">
                {{ __('mensajes.sedes') }}
            </a>

            <a href="{{ route('catalogo.index') }}">
                Ver tienda
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Cerrar sesión</button>
            </form>
        </nav>
    </header>

    <main class="admin-main">
        @if (session('mensaje'))
            <div class="mensaje">
                {{ session('mensaje') }}
            </div>
        @endif

        <div class="admin-contenido">
            @yield('contenido')
        </div>
    </main>

    <footer class="admin-footer">
        LOS DEL CORTE · Administración de la carnicería
    </footer>
</body>
</html>
