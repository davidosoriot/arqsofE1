{{-- Autor: David Osorio --}}
@extends('layouts.app')

@section('titulo', __('mensajes.pedido')." #{$pedido->id}")

@section('contenido')
    <h1>{{ __('mensajes.pedido') }} #{{ $pedido->id }}</h1>
    <p><strong>{{ __('mensajes.estado') }}:</strong> {{ $pedido->estado }}</p>
    <p><strong>{{ __('mensajes.total') }}:</strong> ${{ $pedido->total }}</p>

    <a href="{{ route('pedidos.factura', $pedido) }}">{{ __('mensajes.descargar_factura') }}</a>
@endsection
