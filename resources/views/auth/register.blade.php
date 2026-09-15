{{-- Autor: David Osorio --}}
@extends('layouts.app')

@section('titulo', __('mensajes.crear_cuenta'))

@section('contenido')
    <h1>{{ __('mensajes.crear_cuenta') }}</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('register.store') }}">
        @csrf

        <label>{{ __('mensajes.nombre') }}</label>
        <input type="text" name="name" value="{{ old('name') }}">

        <label>{{ __('mensajes.correo') }}</label>
        <input type="email" name="email" value="{{ old('email') }}">

        <label>{{ __('mensajes.direccion') }}</label>
        <input type="text" name="direccion" value="{{ old('direccion') }}">

        <label>{{ __('mensajes.contrasena') }}</label>
        <input type="password" name="password">

        <label>{{ __('mensajes.confirmar_contrasena') }}</label>
        <input type="password" name="password_confirmation">

        <button type="submit">{{ __('mensajes.crear_cuenta') }}</button>
    </form>

    <a href="{{ route('login') }}">{{ __('mensajes.ya_tengo_cuenta') }}</a>
@endsection
