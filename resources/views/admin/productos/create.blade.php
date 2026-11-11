
@extends('layouts.admin')

@section('titulo', __('mensajes.crear_producto'))

@section('contenido')
    <h1>{{ __('mensajes.crear_producto') }}</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST"
          action="{{ route('admin.productos.store') }}"
          enctype="multipart/form-data">
        @csrf

        <label>{{ __('mensajes.categoria') }}</label>
        <select name="categoria_id" required>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}"
                    @selected(old('categoria_id') == $categoria->id)>
                    {{ $categoria->nombre }}
                </option>
            @endforeach
        </select>

        <label>{{ __('mensajes.nombre') }}</label>
        <input type="text" name="nombre" value="{{ old('nombre') }}" required>

        <label>{{ __('mensajes.tipo_carne') }}</label>
        <input type="text" name="tipo_carne" value="{{ old('tipo_carne') }}" required>

        <label>{{ __('mensajes.precio') }} (por kilogramo)</label>
        <input type="number" step="0.01" min="0" name="precio"
               value="{{ old('precio') }}" required>

        <label for="imagen">Fotografía del producto</label>
        <input type="file" id="imagen" name="imagen"
               accept=".jpg,.jpeg,.png,.webp">

        <small>Formatos permitidos: JPG, JPEG, PNG y WEBP. Máximo 2 MB.</small>

        <label>
            <input type="checkbox" name="disponible" value="1"
                   @checked(old('disponible', '1') == '1')>
            {{ __('mensajes.disponible') }}
        </label>

        <button type="submit">{{ __('mensajes.guardar') }}</button>
    </form>
@endsection
