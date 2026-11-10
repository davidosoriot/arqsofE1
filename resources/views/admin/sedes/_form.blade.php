{{-- Autor: David Osorio --}}
@csrf

@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<label>{{ __('mensajes.nombre') }}</label>
<input type="text" name="nombre" value="{{ old('nombre', $sede->nombre ?? '') }}">

<label>{{ __('mensajes.sede_direccion') }}</label>
<input type="text" name="direccion" value="{{ old('direccion', $sede->direccion ?? '') }}">

<label>{{ __('mensajes.sede_ciudad') }}</label>
<input type="text" name="ciudad" value="{{ old('ciudad', $sede->ciudad ?? 'Medellín') }}">

<label>{{ __('mensajes.sede_horario') }}</label>
<input type="text" name="horario" value="{{ old('horario', $sede->horario ?? '') }}">

<label>{{ __('mensajes.sede_telefono') }}</label>
<input type="text" name="telefono" value="{{ old('telefono', $sede->telefono ?? '') }}">

<fieldset>
    <legend>{{ __('mensajes.sede_coordenadas_opcional') }}</legend>
    <small>{{ __('mensajes.sede_coordenadas_ayuda') }}</small>

    <label>{{ __('mensajes.sede_latitud') }}</label>
    <input type="number" step="0.0000001" name="latitud" value="{{ old('latitud', $sede->latitud ?? '') }}">

    <label>{{ __('mensajes.sede_longitud') }}</label>
    <input type="number" step="0.0000001" name="longitud" value="{{ old('longitud', $sede->longitud ?? '') }}">
</fieldset>

<button type="submit">{{ __('mensajes.guardar') }}</button>
