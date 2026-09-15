<?php
// Script auxiliar (no forma parte del proyecto Laravel): genera el archivo
// .sql de datos ficticios usando la MISMA logica que DatabaseSeeder.php,
// para poder subirlo al repo como pide el enunciado.

$pdo = new PDO('sqlite::memory:');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec("CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, email TEXT, direccion TEXT, rol TEXT, password TEXT, created_at TEXT, updated_at TEXT)");
$pdo->exec("CREATE TABLE categorias (id INTEGER PRIMARY KEY AUTOINCREMENT, nombre TEXT, created_at TEXT, updated_at TEXT)");
$pdo->exec("CREATE TABLE productos (id INTEGER PRIMARY KEY AUTOINCREMENT, categoria_id INTEGER, nombre TEXT, tipo_carne TEXT, precio REAL, disponible INTEGER, created_at TEXT, updated_at TEXT)");
$pdo->exec("CREATE TABLE promociones (id INTEGER PRIMARY KEY AUTOINCREMENT, dia_semana TEXT, tipo_carne TEXT, descuento REAL, created_at TEXT, updated_at TEXT)");

$ahora = date('Y-m-d H:i:s');

$pdo->prepare("INSERT INTO users (name,email,direccion,rol,password,created_at,updated_at) VALUES (?,?,?,?,?,?,?)")
    ->execute(['Admin Carnicería', 'admin@carniceriavirtual.com', null, 'administrador', password_hash('admin123', PASSWORD_BCRYPT), $ahora, $ahora]);
$pdo->prepare("INSERT INTO users (name,email,direccion,rol,password,created_at,updated_at) VALUES (?,?,?,?,?,?,?)")
    ->execute(['Cliente de prueba', 'cliente@correo.com', 'Cra 45 # 10-20, Medellín', 'cliente', password_hash('cliente123', PASSWORD_BCRYPT), $ahora, $ahora]);

$nombresCategorias = ['Res', 'Cerdo', 'Pollo', 'Embutidos'];
$catIds = [];
foreach ($nombresCategorias as $nombre) {
    $pdo->prepare("INSERT INTO categorias (nombre, created_at, updated_at) VALUES (?, ?, ?)")->execute([$nombre, $ahora, $ahora]);
    $catIds[$nombre] = $pdo->lastInsertId();
}

$productos = [
    ['Lomo fino', 'Res', 'Res', 38500],
    ['Costilla de res', 'Res', 'Res', 22000],
    ['Punta de anca', 'Res', 'Res', 26000],
    ['Chuleta de cerdo', 'Cerdo', 'Cerdo', 15000],
    ['Chorizo', 'Cerdo', 'Embutidos', 9800],
    ['Muslo de pollo', 'Pollo', 'Pollo', 12000],
    ['Pechuga de pollo', 'Pollo', 'Pollo', 18500],
    ['Morcilla', 'Cerdo', 'Embutidos', 8500],
];
foreach ($productos as [$nombre, $tipoCarne, $catNombre, $precio]) {
    $pdo->prepare("INSERT INTO productos (categoria_id,nombre,tipo_carne,precio,disponible,created_at,updated_at) VALUES (?,?,?,?,1,?,?)")
        ->execute([$catIds[$catNombre], $nombre, $tipoCarne, $precio, $ahora, $ahora]);
}

$promos = [['martes', 'Res', 10], ['viernes', 'Pollo', 15], ['domingo', 'Cerdo', 20]];
foreach ($promos as [$dia, $tipo, $desc]) {
    $pdo->prepare("INSERT INTO promociones (dia_semana,tipo_carne,descuento,created_at,updated_at) VALUES (?,?,?,?,?)")
        ->execute([$dia, $tipo, $desc, $ahora, $ahora]);
}

// --- Exportar como INSERTs compatibles con MySQL ---
function exportarTabla(PDO $pdo, string $tabla): string {
    $sql = "-- Datos ficticios para la tabla `$tabla`\n";
    $stmt = $pdo->query("SELECT * FROM $tabla");
    $columnas = null;
    while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($columnas === null) $columnas = array_keys($fila);
        $valores = array_map(function ($v) use ($pdo) {
            if ($v === null) return 'NULL';
            if (is_numeric($v) && !preg_match('/^0[0-9]/', (string)$v)) return $v;
            return $pdo->quote($v);
        }, array_values($fila));
        $sql .= "INSERT INTO `$tabla` (`" . implode('`, `', $columnas) . "`) VALUES (" . implode(', ', $valores) . ");\n";
    }
    return $sql . "\n";
}

$salida = "-- Datos ficticios - Carnicería Virtual\n";
$salida .= "-- Generado a partir de database/seeders/DatabaseSeeder.php\n";
$salida .= "-- Contraseñas de prueba: admin123 (admin@carniceriavirtual.com), cliente123 (cliente@correo.com)\n\n";
foreach (['users', 'categorias', 'productos', 'promociones'] as $tabla) {
    $salida .= exportarTabla($pdo, $tabla);
}

file_put_contents('database/sql/datos_ficticios.sql', $salida);
echo "Generado database/sql/datos_ficticios.sql (" . strlen($salida) . " bytes)\n";
echo "Filas: users=" . $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
echo ", categorias=" . $pdo->query("SELECT COUNT(*) FROM categorias")->fetchColumn();
echo ", productos=" . $pdo->query("SELECT COUNT(*) FROM productos")->fetchColumn();
echo ", promociones=" . $pdo->query("SELECT COUNT(*) FROM promociones")->fetchColumn() . "\n";
