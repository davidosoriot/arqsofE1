{{-- Autor: David Osorio --}}
@extends('layouts.admin')

@section('titulo', __('mensajes.productos'))

@section('contenido')
    <h1>{{ __('mensajes.productos') }}</h1>

    <a href="{{ route('admin.productos.create') }}">{{ __('mensajes.crear_producto') }}</a>

    <table>
        <thead>
            <tr><th>{{ __('mensajes.id') }}</th><th>{{ __('mensajes.nombre') }}</th></tr>
        </thead>
        <tbody>
            @forelse ($productos as $producto)
                <tr>
                    <td><a href="{{ route('admin.productos.show', $producto) }}">{{ $producto->id }}</a></td>
                    <td>{{ $producto->nombre }}</td>
                </tr>
            @empty
                <tr><td colspan="2">{{ __('mensajes.no_hay_registros') }}</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
