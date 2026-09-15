{{-- Autor: David Osorio --}}
@extends('layouts.app')

@section('titulo', __('mensajes.promociones_vigentes'))

@section('contenido')
    <h1>{{ __('mensajes.promociones_vigentes') }}</h1>

    <ul>
        @forelse ($promociones as $promocion)
            <li>{{ $promocion->tipo_carne }}: {{ $promocion->descuento }}% {{ __('mensajes.descuento') }}</li>
        @empty
            <li>{{ __('mensajes.sin_promociones_hoy') }}</li>
        @endforelse
    </ul>
@endsection
