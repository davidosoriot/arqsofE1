-- Datos ficticios - Carnicería Virtual
-- Generado a partir de database/seeders/DatabaseSeeder.php
-- Contraseñas de prueba: admin123 (admin@carniceriavirtual.com), cliente123 (cliente@correo.com)

-- Datos ficticios para la tabla `users`
INSERT INTO `users` (`id`, `name`, `email`, `direccion`, `rol`, `password`, `created_at`, `updated_at`) VALUES (1, 'Admin Carnicería', 'admin@carniceriavirtual.com', NULL, 'administrador', '$2y$10$Rpr2mWxoNeOOtMFs9kwdduH0GqmCsfnTEDBl8RgYneMvZFlIQErHC', '2026-09-15 19:02:11', '2026-09-15 19:02:11');
INSERT INTO `users` (`id`, `name`, `email`, `direccion`, `rol`, `password`, `created_at`, `updated_at`) VALUES (2, 'Cliente de prueba', 'cliente@correo.com', 'Cra 45 # 10-20, Medellín', 'cliente', '$2y$10$VKvsAkIUzigwU33yS/.nqeapOV8MvyU5emc6gmAxpYa.cc82q..kW', '2026-09-15 19:02:11', '2026-09-15 19:02:11');

-- Datos ficticios para la tabla `categorias`
INSERT INTO `categorias` (`id`, `nombre`, `created_at`, `updated_at`) VALUES (1, 'Res', '2026-09-15 19:02:11', '2026-09-15 19:02:11');
INSERT INTO `categorias` (`id`, `nombre`, `created_at`, `updated_at`) VALUES (2, 'Cerdo', '2026-09-15 19:02:11', '2026-09-15 19:02:11');
INSERT INTO `categorias` (`id`, `nombre`, `created_at`, `updated_at`) VALUES (3, 'Pollo', '2026-09-15 19:02:11', '2026-09-15 19:02:11');
INSERT INTO `categorias` (`id`, `nombre`, `created_at`, `updated_at`) VALUES (4, 'Embutidos', '2026-09-15 19:02:11', '2026-09-15 19:02:11');

-- Datos ficticios para la tabla `productos`
INSERT INTO `productos` (`id`, `categoria_id`, `nombre`, `tipo_carne`, `precio`, `disponible`, `created_at`, `updated_at`) VALUES (1, 1, 'Lomo fino', 'Res', 38500, 1, '2026-09-15 19:02:11', '2026-09-15 19:02:11');
INSERT INTO `productos` (`id`, `categoria_id`, `nombre`, `tipo_carne`, `precio`, `disponible`, `created_at`, `updated_at`) VALUES (2, 1, 'Costilla de res', 'Res', 22000, 1, '2026-09-15 19:02:11', '2026-09-15 19:02:11');
INSERT INTO `productos` (`id`, `categoria_id`, `nombre`, `tipo_carne`, `precio`, `disponible`, `created_at`, `updated_at`) VALUES (3, 1, 'Punta de anca', 'Res', 26000, 1, '2026-09-15 19:02:11', '2026-09-15 19:02:11');
INSERT INTO `productos` (`id`, `categoria_id`, `nombre`, `tipo_carne`, `precio`, `disponible`, `created_at`, `updated_at`) VALUES (4, 2, 'Chuleta de cerdo', 'Cerdo', 15000, 1, '2026-09-15 19:02:11', '2026-09-15 19:02:11');
INSERT INTO `productos` (`id`, `categoria_id`, `nombre`, `tipo_carne`, `precio`, `disponible`, `created_at`, `updated_at`) VALUES (5, 4, 'Chorizo', 'Cerdo', 9800, 1, '2026-09-15 19:02:11', '2026-09-15 19:02:11');
INSERT INTO `productos` (`id`, `categoria_id`, `nombre`, `tipo_carne`, `precio`, `disponible`, `created_at`, `updated_at`) VALUES (6, 3, 'Muslo de pollo', 'Pollo', 12000, 1, '2026-09-15 19:02:11', '2026-09-15 19:02:11');
INSERT INTO `productos` (`id`, `categoria_id`, `nombre`, `tipo_carne`, `precio`, `disponible`, `created_at`, `updated_at`) VALUES (7, 3, 'Pechuga de pollo', 'Pollo', 18500, 1, '2026-09-15 19:02:11', '2026-09-15 19:02:11');
INSERT INTO `productos` (`id`, `categoria_id`, `nombre`, `tipo_carne`, `precio`, `disponible`, `created_at`, `updated_at`) VALUES (8, 4, 'Morcilla', 'Cerdo', 8500, 1, '2026-09-15 19:02:11', '2026-09-15 19:02:11');

-- Datos ficticios para la tabla `promociones`
INSERT INTO `promociones` (`id`, `dia_semana`, `tipo_carne`, `descuento`, `created_at`, `updated_at`) VALUES (1, 'martes', 'Res', 10, '2026-09-15 19:02:11', '2026-09-15 19:02:11');
INSERT INTO `promociones` (`id`, `dia_semana`, `tipo_carne`, `descuento`, `created_at`, `updated_at`) VALUES (2, 'viernes', 'Pollo', 15, '2026-09-15 19:02:11', '2026-09-15 19:02:11');
INSERT INTO `promociones` (`id`, `dia_semana`, `tipo_carne`, `descuento`, `created_at`, `updated_at`) VALUES (3, 'domingo', 'Cerdo', 20, '2026-09-15 19:02:11', '2026-09-15 19:02:11');

