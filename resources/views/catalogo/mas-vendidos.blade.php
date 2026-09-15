{{-- Autor: David Osorio --}}
@extends('layouts.app')

@section('titulo', __('mensajes.mas_vendidos'))

@section('contenido')
    <h1>{{ __('mensajes.mas_vendidos') }}</h1>

    <ol>
        @foreach ($productos as $producto)
            <li>{{ $producto->nombre }} — {{ $producto->total_vendido }} {{ __('mensajes.unidades_vendidas') }}</li>
        @endforeach
    </ol>
@endsection
