{{-- Autor: David Osorio --}}
@extends('layouts.app')

@section('titulo', __('mensajes.sedes_titulo'))

@section('contenido')
    <h1>{{ __('mensajes.sedes_titulo') }}</h1>
    <p>{{ __('mensajes.sedes_descripcion') }}</p>

    @if ($cercana)
        <p class="mensaje">
            <strong>{{ __('mensajes.sede_tu_mas_cercana') }}:</strong>
            {{ $cercana->nombre }} — {{ $cercana->direccion }}
        </p>
    @endif

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <style>
        #mapa-sedes { height: 420px; border: 1px solid var(--linea); border-radius: 4px; margin-bottom: 1.5rem; }
    </style>

    <div id="mapa-sedes"
         data-sedes="{{ json_encode($sedes->map(fn ($s) => [
            'nombre' => $s->nombre,
            'direccion' => $s->direccion,
            'ciudad' => $s->ciudad,
            'horario' => $s->horario,
            'telefono' => $s->telefono,
            'latitud' => $s->latitud,
            'longitud' => $s->longitud,
         ]), JSON_HEX_QUOT | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE) }}"
         role="img"
         aria-label="{{ __('mensajes.sedes_titulo') }}"></div>

    <div class="cortes">
        @foreach ($sedes as $sede)
            <article class="corte">
                <h3>{{ $sede->nombre }}</h3>
                <p><strong>{{ __('mensajes.sede_direccion') }}:</strong> {{ $sede->direccion }}, {{ $sede->ciudad }}</p>
                <p><strong>{{ __('mensajes.sede_horario') }}:</strong> {{ $sede->horario }}</p>
                @if ($sede->telefono)
                    <p><strong>{{ __('mensajes.sede_telefono') }}:</strong> {{ $sede->telefono }}</p>
                @endif
                @if (! $sede->tieneUbicacion())
                    <p class="vacio">{{ __('mensajes.sede_sin_ubicacion') }}</p>
                @endif
            </article>
        @endforeach
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        (function () {
            var contenedor = document.getElementById('mapa-sedes');
            if (!contenedor || !window.L) { return; }

            var sedes = JSON.parse(contenedor.dataset.sedes || '[]');
            var conUbicacion = sedes.filter(function (s) { return s.latitud !== null && s.longitud !== null; });

            var mapa = L.map('mapa-sedes').setView([6.2442, -75.5812], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: @json(__('mensajes.mapa_atribucion'))
            }).addTo(mapa);

            if (conUbicacion.length === 0) { return; }

            function construirPopup(sede) {
                var fragmento = document.createDocumentFragment();

                var titulo = document.createElement('strong');
                titulo.textContent = sede.nombre;
                fragmento.appendChild(titulo);
                fragmento.appendChild(document.createElement('br'));

                fragmento.appendChild(document.createTextNode(sede.direccion + ', ' + sede.ciudad));
                fragmento.appendChild(document.createElement('br'));

                fragmento.appendChild(document.createTextNode(sede.horario));

                if (sede.telefono) {
                    fragmento.appendChild(document.createElement('br'));
                    fragmento.appendChild(document.createTextNode(sede.telefono));
                }

                var contenedorPopup = document.createElement('div');
                contenedorPopup.appendChild(fragmento);
                return contenedorPopup;
            }

            var limites = [];
            conUbicacion.forEach(function (sede) {
                var marcador = L.marker([sede.latitud, sede.longitud]).addTo(mapa);
                marcador.bindPopup(construirPopup(sede));
                limites.push([sede.latitud, sede.longitud]);
            });

            if (limites.length > 1) {
                mapa.fitBounds(limites, { padding: [30, 30] });
            } else {
                mapa.setView(limites[0], 14);
            }
        })();
    </script>
@endsection
