{{-- Autor: David Osorio --}}
@extends('layouts.app')

@section('titulo', __('mensajes.iniciar_sesion'))

@section('contenido')
    <h1>{{ __('mensajes.iniciar_sesion') }}</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('login.store') }}">
        @csrf

        <label>{{ __('mensajes.correo') }}</label>
        <input type="email" name="email" value="{{ old('email') }}">

        <label>{{ __('mensajes.contrasena') }}</label>
        <input type="password" name="password">

        <label>
            <input type="checkbox" name="recordar" value="1">
            {{ __('mensajes.recordarme') }}
        </label>

        <button type="submit">{{ __('mensajes.ingresar') }}</button>
    </form>

    <a href="{{ route('register') }}">{{ __('mensajes.no_tengo_cuenta') }}</a>
@endsection
