
@extends('layouts.app')

@section('titulo', 'Iniciar sesión')

@section('contenido')
<style>
    .login-contenedor {
        max-width: 950px;
        margin: 35px auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        background: #fff;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 12px 35px rgba(43, 10, 12, .15);
    }

    .login-presentacion {
        background: linear-gradient(145deg, #8d1019, #2b0a0c);
        color: white;
        padding: 55px 35px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: center;
    }

    .login-presentacion .icono {
        font-size: 75px;
        margin-bottom: 20px;
    }

    .login-presentacion h2 {
        font-size: 30px;
        margin-bottom: 15px;
    }

    .login-presentacion p {
        color: #f5dede;
        font-size: 16px;
    }

    .login-formulario {
        padding: 45px 35px;
    }

    .login-formulario h1 {
        color: #6e0d12;
        font-size: 29px;
        margin-bottom: 10px;
    }

    .login-descripcion {
        color: #806e6e;
        margin-bottom: 28px;
    }

    .login-campo {
        margin-bottom: 20px;
    }

    .login-campo label {
        display: block;
        font-weight: 700;
        margin-bottom: 7px;
        color: #351416;
    }

    .login-campo input {
        width: 100%;
        padding: 13px;
        border: 1px solid #e1cece;
        border-radius: 8px;
        font-size: 15px;
    }

    .login-campo input:focus {
        border-color: #b3141c;
        outline: 2px solid #f4c5c8;
    }

    .login-recordar {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 15px 0 23px;
        color: #5e4b4c;
    }

    .login-recordar input {
        width: 17px;
        height: 17px;
        accent-color: #b3141c;
    }

    .login-boton {
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 8px;
        background: #b3141c;
        color: white;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: .2s;
    }

    .login-boton:hover {
        background: #6e0d12;
    }

    .login-registro {
        text-align: center;
        margin-top: 23px;
        color: #6c5a5a;
    }

    .login-registro a {
        color: #b3141c;
        font-weight: 700;
        text-decoration: none;
    }

    .login-registro a:hover {
        text-decoration: underline;
    }

    .login-errores {
        background: #fff0f0;
        color: #9b151d;
        border-left: 4px solid #b3141c;
        padding: 12px 15px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    @media (max-width: 700px) {
        .login-contenedor {
            grid-template-columns: 1fr;
            margin: 15px auto;
        }

        .login-presentacion {
            padding: 30px 20px;
        }

        .login-presentacion .icono {
            font-size: 48px;
        }

        .login-formulario {
            padding: 30px 22px;
        }
    }
</style>

<div class="login-contenedor">

    <div class="login-presentacion">
        <div class="icono">🥩</div>
        <h2>LOS DEL CORTE</h2>
        <p>
            Calidad, frescura y los mejores cortes para llevar
            a tu mesa.
        </p>
        <p><strong>¡Bienvenido a nuestra carnicería!</strong></p>
    </div>

    <div class="login-formulario">
        <h1>Iniciar sesión</h1>

        <p class="login-descripcion">
            Ingresa tus datos para continuar.
        </p>

        @if ($errors->any())
            <div class="login-errores">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div class="login-campo">
                <label for="email">Correo electrónico</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="tu@correo.com"
                    autocomplete="username"
                    required
                    autofocus
                >
            </div>

            <div class="login-campo">
                <label for="password">Contraseña</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Ingresa tu contraseña"
                    autocomplete="current-password"
                    required
                >
            </div>

            <label class="login-recordar">
                <input type="checkbox" name="recordar" value="1">
                Recordarme
            </label>

            <button type="submit" class="login-boton">
                INGRESAR
            </button>
        </form>

        <div class="login-registro">
            ¿Aún no tienes cuenta?
            <a href="{{ route('register') }}">Crear una cuenta</a>
        </div>
    </div>

</div>
@endsection
