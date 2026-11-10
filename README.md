# Carnicería Virtual

Tienda web en Laravel 12 para la venta de productos cárnicos. Ver la [wiki](../../wiki) del repositorio para el detalle completo del entregable (logo, modelo verbal, diagramas, guía de estilo, reglas de programación, funcionalidades interesantes).

## Requisitos

- PHP 8.2+
- Composer
- MySQL 8+

## Instalación

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configurar la base de datos y las URLs externas en `.env`:

```
DB_CONNECTION=mysql
DB_DATABASE=carniceria_virtual
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password

ALIADO_API_URL=https://api-del-equipo-1.ejemplo/productos
GEOCODER_URL=https://nominatim.openstreetmap.org/search
GEOCODER_USER_AGENT="CarniceriaVirtual/1.0 (contacto@carniceriavirtual.com)"
```

Correr las migraciones y cargar los datos ficticios:

```bash
php artisan migrate
php artisan db:seed
```

## Correr el proyecto

```bash
php artisan serve
```

**Ruta principal:** `http://localhost:8000/` (catálogo de productos, acceso público).

## Usuarios de prueba (creados por el seeder)

| Rol | Correo | Contraseña |
|---|---|---|
| Administrador | admin@carniceriavirtual.com | admin123 |
| Cliente | cliente@correo.com | cliente123 |

## Rutas principales

| Ruta | Descripción |
|---|---|
| `/` | Catálogo (búsqueda por `?q=`) |
| `/productos/{id}` | Ficha de producto |
| `/promociones` | Promociones vigentes hoy |
| `/mas-vendidos` | Top 3 productos más vendidos |
| `/productos-aliados` | Videojuegos consumidos del Equipo 1 |
| `/sedes` | Mapa y listado de sedes (Leaflet + OpenStreetMap) |
| `/carrito` | Carrito de compras (requiere sesión) |
| `/login`, `/registro` | Autenticación |
| `/idioma/{es|en}` | Cambio de idioma |
| `/admin/productos`, `/admin/categorias`, `/admin/sedes` | Paneles de administración (solo rol `administrador`) |

## Entregable 2 — Servicios y DI

### Servicio propio (consumido por el Equipo 3 — Tienda de Joyas)

**Endpoint:** `GET /api/productos-en-venta`
**Filtro opcional:** `?tipo_carne=res|cerdo|pollo`

Respuesta de ejemplo:

```json
{
  "tienda": "Carniceria Virtual",
  "generado": "2026-11-12T10:00:00-05:00",
  "total": 1,
  "productos": [
    {
      "id": 4,
      "nombre": "Punta de anca",
      "tipo_carne": "res",
      "categoria": "Cortes finos",
      "precio": 42000.0,
      "descuento_hoy": 15.0,
      "precio_hoy": 35700.0,
      "enlace": "http://IP-AWS/productos/4"
    }
  ]
}
```

Código: `app/Http/Controllers/Api/ProductoApiController.php`, `app/Http/Resources/ProductoResource.php`, `routes/api.php`.

### Servicio consumido del Equipo 1 (Tienda de Videojuegos)

La ruta `/productos-aliados` muestra el catálogo expuesto por el Equipo 1. Toda la lógica HTTP vive en `app/Services/AliadoService.php` (cachea 10 min, reintenta 2 veces, degrada a vacío si la API cae).

### Servicio de tercero — Nominatim (OpenStreetMap)

`app/Services/GeocodificadorService.php` consulta Nominatim para geocodificar las direcciones de las sedes (`coordenadas($direccion, $ciudad): ?array`). La página pública `/sedes` dibuja un mapa con Leaflet 1.9.x y tiles de OpenStreetMap; el admin `/admin/sedes` usa el servicio al crear/editar una sede si el admin no escribió las coordenadas a mano.

Política respetada: `User-Agent` identificable (configurable con `GEOCODER_USER_AGENT`), 1 request/segundo máximo y caché de 30 días por dirección.

### Inversión de dependencias — GeneradorFactura

Interfaz `App\Interfaces\GeneradorFactura` con dos implementaciones:

- `App\Util\FacturaPdf` — usa dompdf.
- `App\Util\FacturaExcel` — usa PhpSpreadsheet.

El binding vive en `App\Providers\FacturaServiceProvider`. El `PedidoController::factura` sólo conoce la interfaz: la implementación la elige el parámetro `?formato=pdf|excel`.

Ruta: `GET /pedidos/{pedido}/factura?formato=pdf|excel`.

## Internacionalización (es / en)

Archivos en `resources/lang/{es,en}/{mensajes,validation}.php`. El middleware `App\Http\Middleware\EstablecerIdioma` lee el idioma guardado en sesión en cada request; el selector ES/EN del layout dispara `GET /idioma/{locale}`.

## Pruebas

```bash
php artisan test --testsuite=Unit
```

- `PromocionTest` — valida `Promocion::estaVigente()` con `Carbon::setTestNow`.
- `AliadoServiceTest` — simula caídas y respuestas del Equipo 1 con `Http::fake()`.
- `GeocodificadorServiceTest` — simula Nominatim con `Http::fake()` (OK, vacío, 500).

## Docker + AWS

Levantar el stack localmente:

```bash
cp .env.example .env
# Genera la llave sin necesitar los contenedores arriba y la imprime en pantalla.
# Copia el valor (base64:...) y pegalo en APP_KEY del .env.
docker compose run --rm app php artisan key:generate --show
docker compose up -d --build
docker compose exec app php artisan migrate --seed --force
```

En una instancia EC2 (Ubuntu, `t3.micro` o `t3.small`):

1. Instalar Docker y `docker compose`.
2. `git clone` del repo, `cp .env.example .env`, ajustar `APP_URL`, `DB_*` y `ALIADO_API_URL`.
3. Generar `APP_KEY` con `docker compose run --rm app php artisan key:generate --show` y pegarla en `.env` **antes** de `docker compose up -d --build`.
4. Security Group: abrir puerto **80** (HTTP) y **22** (SSH) desde tu IP. Idealmente asociar una **Elastic IP** para que la dirección pública no cambie entre reinicios.
5. `docker compose up -d --build` y después `docker compose exec app php artisan migrate --seed --force` para la primera carga.

Variables críticas en producción (`.env`):

```
APP_ENV=production
APP_DEBUG=false
APP_URL=http://<IP-publica-o-dominio>
APP_TIMEZONE=America/Bogota
DB_CONNECTION=mysql
DB_HOST=mysql
DB_DATABASE=carniceria
DB_USERNAME=carniceria
DB_PASSWORD=<una password fuerte>
ALIADO_API_URL=<URL del Equipo 1>
```
