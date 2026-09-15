{{-- Autor: David Osorio --}}
@extends('layouts.app')

@section('titulo', __('mensajes.catalogo'))

@section('contenido')
    <h1>{{ __('mensajes.catalogo') }}</h1>

    <form method="GET" action="{{ route('catalogo.index') }}">
        <input type="text" name="q" placeholder="{{ __('mensajes.buscar_producto') }}" value="{{ request('q') }}">
        <button type="submit">{{ __('mensajes.buscar') }}</button>
    </form>

    <p>
        <a href="{{ route('catalogo.promociones') }}">{{ __('mensajes.promociones_vigentes') }}</a> ·
        <a href="{{ route('catalogo.mas-vendidos') }}">{{ __('mensajes.mas_vendidos') }}</a>
    </p>

    <ul>
        @forelse ($productos as $producto)
            <li>
                <a href="{{ route('catalogo.show', $producto) }}">{{ $producto->nombre }}</a>
                — {{ $producto->tipo_carne }} — ${{ $producto->precio }}
            </li>
        @empty
            <li>{{ __('mensajes.no_hay_registros') }}</li>
        @endforelse
    </ul>
@endsection
