{{-- Autor: David Osorio --}}
@extends('layouts.admin')

@section('titulo', __('mensajes.sedes'))

@section('contenido')
    <h1>{{ __('mensajes.sedes') }}</h1>

    <a href="{{ route('admin.sedes.create') }}">{{ __('mensajes.crear_sede') }}</a>

    <table>
        <thead>
            <tr>
                <th>{{ __('mensajes.id') }}</th>
                <th>{{ __('mensajes.nombre') }}</th>
                <th>{{ __('mensajes.sede_direccion') }}</th>
                <th>{{ __('mensajes.sede_ciudad') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sedes as $sede)
                <tr>
                    <td><a href="{{ route('admin.sedes.show', $sede) }}">{{ $sede->id }}</a></td>
                    <td>{{ $sede->nombre }}</td>
                    <td>{{ $sede->direccion }}</td>
                    <td>{{ $sede->ciudad }}</td>
                </tr>
            @empty
                <tr><td colspan="4">{{ __('mensajes.no_hay_registros') }}</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
