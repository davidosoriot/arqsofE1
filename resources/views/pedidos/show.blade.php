{{-- Autor: David Osorio --}}
@extends('layouts.app')

@section('titulo', __('mensajes.pedido')." #{$pedido->id}")

@section('contenido')
    <h1>{{ __('mensajes.pedido') }} #{{ $pedido->id }}</h1>
    <p><strong>{{ __('mensajes.estado') }}:</strong> {{ $pedido->estado }}</p>
    <p><strong>{{ __('mensajes.total') }}:</strong> ${{ $pedido->total }}</p>

    <p>
        <a class="btn" href="{{ route('pedidos.factura', ['pedido' => $pedido, 'formato' => 'pdf']) }}">
            {{ __('mensajes.descargar_factura_pdf') }}
        </a>
        <a class="btn btn-claro" href="{{ route('pedidos.factura', ['pedido' => $pedido, 'formato' => 'excel']) }}">
            {{ __('mensajes.descargar_factura_excel') }}
        </a>
    </p>
@endsection
