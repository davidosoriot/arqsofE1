{{-- Autor: David Osorio --}}
@extends('layouts.app')

@section('titulo', __('mensajes.promociones_vigentes'))

@section('contenido')
    <h1 style="margin-bottom:1.25rem">{{ __('mensajes.promociones_vigentes') }}</h1>

    @if ($promociones->isEmpty())
        <p class="vacio">{{ __('mensajes.sin_promociones_hoy') }}</p>
    @else
        <div class="promos">
            @foreach ($promociones as $promocion)
                <article class="promo">
                    <div class="porcentaje">{{ $promocion->descuento }}%</div>
                    <p>{{ $promocion->tipo_carne }} · {{ __('mensajes.descuento') }}</p>
                </article>
            @endforeach
        </div>
    @endif
@endsection