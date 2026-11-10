{{-- Autor: David Osorio --}}
@extends('layouts.admin')

@section('titulo', $sede->nombre)

@section('contenido')
    <h1>{{ $sede->nombre }}</h1>

    <p><strong>{{ __('mensajes.sede_direccion') }}:</strong> {{ $sede->direccion }}</p>
    <p><strong>{{ __('mensajes.sede_ciudad') }}:</strong> {{ $sede->ciudad }}</p>
    <p><strong>{{ __('mensajes.sede_horario') }}:</strong> {{ $sede->horario }}</p>
    @if ($sede->telefono)
        <p><strong>{{ __('mensajes.sede_telefono') }}:</strong> {{ $sede->telefono }}</p>
    @endif

    @if ($sede->tieneUbicacion())
        <p>
            <strong>{{ __('mensajes.sede_latitud') }}:</strong> {{ $sede->latitud }} ·
            <strong>{{ __('mensajes.sede_longitud') }}:</strong> {{ $sede->longitud }}
        </p>
    @else
        <p>{{ __('mensajes.sede_sin_ubicacion') }}</p>
    @endif

    <p>
        <a href="{{ route('admin.sedes.edit', $sede) }}">{{ __('mensajes.editar') }}</a>

        <form method="POST" action="{{ route('admin.sedes.destroy', $sede) }}"
              onsubmit="return confirm('{{ __('mensajes.confirmar_eliminar') }}');" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit">{{ __('mensajes.eliminar') }}</button>
        </form>
    </p>

    <p><a href="{{ route('admin.sedes.index') }}">{{ __('mensajes.volver_al_listado') }}</a></p>
@endsection
