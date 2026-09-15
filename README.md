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

Configurar la base de datos en `.env`:

```
DB_CONNECTION=mysql
DB_DATABASE=carniceria_virtual
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password
```

Correr las migraciones y cargar los datos ficticios:

```bash
php artisan migrate
php artisan db:seed
```

(Alternativamente, `database/sql/datos_ficticios.sql` tiene el mismo grupo de datos ya exportado como INSERTs, por si prefieren cargarlo directo con `mysql -u usuario -p carniceria_virtual < database/sql/datos_ficticios.sql` después de migrar.)

Para la funcionalidad de factura en PDF, instalar además:

```bash
composer require barryvdh/laravel-dompdf
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
| `/carrito` | Carrito de compras (requiere sesión) |
| `/login`, `/registro` | Autenticación |
| `/admin/productos`, `/admin/categorias` | Paneles de administración (solo rol `administrador`) |

## Middleware personalizado

`EsAdministrador` (alias `es.administrador`) protege toda la sección `/admin/*`. Registrarlo en `bootstrap/app.php` — ver `bootstrap/app.snippet.php` en este mismo repo para el bloque exacto a agregar.
