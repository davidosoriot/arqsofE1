{{-- Autor: David Osorio --}}
@extends('layouts.admin')

@section('titulo', __('mensajes.categorias'))

@section('contenido')
    <h1>{{ __('mensajes.categorias') }}</h1>

    <a href="{{ route('admin.categorias.create') }}">{{ __('mensajes.crear_categoria') }}</a>

    <table>
        <thead>
            <tr><th>{{ __('mensajes.id') }}</th><th>{{ __('mensajes.nombre') }}</th></tr>
        </thead>
        <tbody>
            @forelse ($categorias as $categoria)
                <tr>
                    <td><a href="{{ route('admin.categorias.show', $categoria) }}">{{ $categoria->id }}</a></td>
                    <td>{{ $categoria->nombre }}</td>
                </tr>
            @empty
                <tr><td colspan="2">{{ __('mensajes.no_hay_registros') }}</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
