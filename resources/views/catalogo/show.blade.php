{{-- Autor: David Osorio --}}
@extends('layouts.app')

@section('titulo', $producto->nombre)

@section('contenido')
    <h1>{{ $producto->nombre }}</h1>

    <p><strong>{{ __('mensajes.categoria') }}:</strong> {{ $producto->categoria->nombre }}</p>
    <p><strong>{{ __('mensajes.tipo_carne') }}:</strong> {{ $producto->tipo_carne }}</p>
    <p><strong>{{ __('mensajes.precio') }}:</strong> ${{ $producto->precio }}</p>
    <p><strong>{{ __('mensajes.disponible') }}:</strong> {{ $producto->disponible ? __('mensajes.si') : __('mensajes.no') }}</p>

    @auth
        <form method="POST" action="{{ route('carrito.agregar', $producto) }}">
            @csrf
            <label>{{ __('mensajes.cantidad') }}</label>
            <input type="number" name="cantidad" value="1" min="1">
            <button type="submit">{{ __('mensajes.agregar_al_carrito') }}</button>
        </form>
    @else
        <a href="{{ route('login') }}">{{ __('mensajes.iniciar_sesion') }}</a>
    @endauth

    <a href="{{ route('catalogo.index') }}">{{ __('mensajes.volver_al_listado') }}</a>
@endsection
