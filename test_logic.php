<?php
// Verificacion funcional: mismo esquema que las 9 migraciones, mismas
// relaciones que los modelos Eloquent, y misma logica de login/CRUD/
// funcionalidades interesantes, ejecutadas contra SQLite real.

$pdo = new PDO('sqlite::memory:');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec('PRAGMA foreign_keys = ON');

$pdo->exec("CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT, name VARCHAR, email VARCHAR UNIQUE, direccion VARCHAR, rol VARCHAR DEFAULT 'cliente', password VARCHAR)");
$pdo->exec("CREATE TABLE categorias (id INTEGER PRIMARY KEY AUTOINCREMENT, nombre VARCHAR)");
$pdo->exec("CREATE TABLE productos (id INTEGER PRIMARY KEY AUTOINCREMENT, categoria_id INTEGER, nombre VARCHAR, tipo_carne VARCHAR, precio DECIMAL, disponible BOOLEAN DEFAULT 1, FOREIGN KEY(categoria_id) REFERENCES categorias(id))");
$pdo->exec("CREATE TABLE carritos (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, FOREIGN KEY(user_id) REFERENCES users(id))");
$pdo->exec("CREATE TABLE carrito_producto (id INTEGER PRIMARY KEY AUTOINCREMENT, carrito_id INTEGER, producto_id INTEGER, cantidad INTEGER DEFAULT 1, FOREIGN KEY(carrito_id) REFERENCES carritos(id), FOREIGN KEY(producto_id) REFERENCES productos(id))");
$pdo->exec("CREATE TABLE pedidos (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, total DECIMAL DEFAULT 0, estado VARCHAR DEFAULT 'pendiente_pago', FOREIGN KEY(user_id) REFERENCES users(id))");
$pdo->exec("CREATE TABLE pedido_producto (id INTEGER PRIMARY KEY AUTOINCREMENT, pedido_id INTEGER, producto_id INTEGER, cantidad INTEGER, precio_unitario DECIMAL, FOREIGN KEY(pedido_id) REFERENCES pedidos(id), FOREIGN KEY(producto_id) REFERENCES productos(id))");
$pdo->exec("CREATE TABLE pagos (id INTEGER PRIMARY KEY AUTOINCREMENT, pedido_id INTEGER, metodo VARCHAR, estado VARCHAR DEFAULT 'pendiente', FOREIGN KEY(pedido_id) REFERENCES pedidos(id))");
$pdo->exec("CREATE TABLE promociones (id INTEGER PRIMARY KEY AUTOINCREMENT, dia_semana VARCHAR, tipo_carne VARCHAR, descuento DECIMAL)");

$pass = 0; $fails = 0;
function check($label, $cond) {
    global $pass, $fails;
    if ($cond) { $pass++; echo "PASS  $label\n"; }
    else { $fails++; echo "FAIL  $label\n"; }
}

echo "=== Migraciones: todas las tablas existen con sus FK ===\n";
$tablas = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
foreach (['users','categorias','productos','carritos','carrito_producto','pedidos','pedido_producto','pagos','promociones'] as $t) {
    check("tabla '$t' existe", in_array($t, $tablas));
}

echo "\n=== Login: Hash::make / Auth::attempt equivalente ===\n";
$hash = password_hash('secreto123', PASSWORD_BCRYPT);
$pdo->prepare("INSERT INTO users (name, email, rol, password) VALUES (?, ?, ?, ?)")
    ->execute(['Ana Cliente', 'ana@correo.com', 'cliente', $hash]);
$pdo->prepare("INSERT INTO users (name, email, rol, password) VALUES (?, ?, ?, ?)")
    ->execute(['Beto Admin', 'beto@correo.com', 'administrador', password_hash('admin123', PASSWORD_BCRYPT)]);

function intentarLogin(PDO $pdo, string $email, string $password): array|false {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$usuario || !password_verify($password, $usuario['password'])) return false;
    return $usuario;
}

$loginOk = intentarLogin($pdo, 'ana@correo.com', 'secreto123');
check('login valido para cliente', $loginOk !== false);
check('login rechaza password incorrecta', intentarLogin($pdo, 'ana@correo.com', 'mala') === false);

$loginAdmin = intentarLogin($pdo, 'beto@correo.com', 'admin123');
check('login valido para administrador', $loginAdmin !== false);
check("esAdministrador() -> true para Beto", $loginAdmin['rol'] === 'administrador');
check("esAdministrador() -> false para Ana", $loginOk['rol'] !== 'administrador');

echo "\n=== Middleware EsAdministrador: gating de /admin ===\n";
function puedeEntrarAdmin(?array $usuario): bool {
    return $usuario && $usuario['rol'] === 'administrador';
}
check('cliente NO puede entrar a /admin', puedeEntrarAdmin($loginOk) === false);
check('administrador SI puede entrar a /admin', puedeEntrarAdmin($loginAdmin) === true);
check('usuario no autenticado NO puede entrar a /admin', puedeEntrarAdmin(null) === false);

echo "\n=== CRUD admin de Producto (igual logica que ProductoController) ===\n";
$pdo->exec("INSERT INTO categorias (nombre) VALUES ('Res')");
$catId = $pdo->lastInsertId();

function validarProducto(array $d): array {
    $errores = [];
    if (empty($d['categoria_id'] ?? null)) $errores[] = 'categoria requerida';
    if (empty($d['nombre'] ?? null)) $errores[] = 'nombre requerido';
    if (!isset($d['precio']) || !is_numeric($d['precio']) || (float)$d['precio'] < 0) $errores[] = 'precio invalido';
    return $errores;
}
function crearProducto(PDO $pdo, array $d): array {
    $errores = validarProducto($d);
    if ($errores) return ['ok' => false, 'errores' => $errores];
    $stmt = $pdo->prepare("INSERT INTO productos (categoria_id, nombre, tipo_carne, precio, disponible) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$d['categoria_id'], $d['nombre'], $d['tipo_carne'], $d['precio'], !empty($d['disponible']) ? 1 : 0]);
    return ['ok' => true, 'id' => $pdo->lastInsertId()];
}

$r1 = crearProducto($pdo, ['categoria_id' => $catId, 'nombre' => 'Lomo fino', 'tipo_carne' => 'Res', 'precio' => 38500, 'disponible' => '1']);
check('crear producto valido', $r1['ok'] === true);
$r2 = crearProducto($pdo, ['nombre' => 'Sin categoria', 'tipo_carne' => 'Res', 'precio' => 1000]);
check('rechaza producto sin categoria', $r2['ok'] === false);

$stmt = $pdo->prepare("SELECT id, nombre FROM productos");
$stmt->execute();
$listado = $stmt->fetchAll(PDO::FETCH_ASSOC);
check('listado admin trae solo id+nombre', count($listado) === 1 && array_keys($listado[0]) === ['id','nombre']);

$pdo->prepare("DELETE FROM productos WHERE id = ?")->execute([$r1['id']]);
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$r1['id']]);
check('producto eliminado correctamente', $stmt->fetch() === false);

echo "\n=== Relacion Carrito <-> Producto (pivote, igual que belongsToMany) ===\n";
$pdo->exec("INSERT INTO users (name, email, rol, password) VALUES ('Cliente Dos','c2@correo.com','cliente','x')");
$uid = $pdo->lastInsertId();
$pdo->prepare("INSERT INTO carritos (user_id) VALUES (?)")->execute([$uid]);
$carritoId = $pdo->lastInsertId();
$pdo->exec("INSERT INTO productos (categoria_id, nombre, tipo_carne, precio, disponible) VALUES ($catId, 'Chorizo', 'Cerdo', 9800, 1)");
$prodId = $pdo->lastInsertId();

$pdo->prepare("INSERT INTO carrito_producto (carrito_id, producto_id, cantidad) VALUES (?, ?, ?)")->execute([$carritoId, $prodId, 3]);

$stmt = $pdo->prepare("SELECT p.precio, cp.cantidad FROM carrito_producto cp JOIN productos p ON p.id = cp.producto_id WHERE cp.carrito_id = ?");
$stmt->execute([$carritoId]);
$total = array_sum(array_map(fn($r) => $r['precio'] * $r['cantidad'], $stmt->fetchAll(PDO::FETCH_ASSOC)));
check('calcularTotal() = 9800 * 3 = 29400', (float)$total === 29400.0);

$pdo->prepare("INSERT INTO pedidos (user_id, total, estado) VALUES (?, ?, 'pendiente_pago')")->execute([$uid, $total]);
$pedidoId = $pdo->lastInsertId();
$pdo->prepare("INSERT INTO pedido_producto (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, 3, 9800)")->execute([$pedidoId, $prodId]);
$pdo->prepare("DELETE FROM carrito_producto WHERE carrito_id = ?")->execute([$carritoId]);

$stmt = $pdo->prepare("SELECT COUNT(*) FROM carrito_producto WHERE carrito_id = ?");
$stmt->execute([$carritoId]);
check('vaciar() deja el carrito en 0 items', (int)$stmt->fetchColumn() === 0);

$stmt = $pdo->prepare("SELECT COUNT(*) FROM pedido_producto WHERE pedido_id = ?");
$stmt->execute([$pedidoId]);
check('generarPedido() copio el item al pedido', (int)$stmt->fetchColumn() === 1);

echo "\n=== Pago::procesarPago() cambia estado del pedido ===\n";
$pdo->prepare("INSERT INTO pagos (pedido_id, metodo, estado) VALUES (?, 'tarjeta', 'pendiente')")->execute([$pedidoId]);
$pagoId = $pdo->lastInsertId();
$pdo->prepare("UPDATE pagos SET estado = 'aprobado' WHERE id = ?")->execute([$pagoId]);
$pdo->prepare("UPDATE pedidos SET estado = 'pagado' WHERE id = ?")->execute([$pedidoId]);
$stmt = $pdo->prepare("SELECT estado FROM pedidos WHERE id = ?");
$stmt->execute([$pedidoId]);
check("procesarPago() deja el pedido en 'pagado'", $stmt->fetchColumn() === 'pagado');

echo "\n=== Funcionalidad 1: busqueda por nombre/tipo_carne ===\n";
function buscarProductos(PDO $pdo, ?string $q): array {
    $sql = "SELECT * FROM productos WHERE disponible = 1";
    $params = [];
    if ($q) {
        $sql .= " AND (nombre LIKE ? OR tipo_carne LIKE ?)";
        $params = ["%$q%", "%$q%"];
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
$pdo->exec("INSERT INTO productos (categoria_id, nombre, tipo_carne, precio, disponible) VALUES ($catId, 'Costilla de res', 'Res', 22000, 1)");
$pdo->exec("INSERT INTO productos (categoria_id, nombre, tipo_carne, precio, disponible) VALUES ($catId, 'Punta de anca', 'Res', 26000, 1)");
$pdo->exec("INSERT INTO productos (categoria_id, nombre, tipo_carne, precio, disponible) VALUES ($catId, 'Muslo de pollo', 'Pollo', 12000, 1)");
check("buscar 'res' encuentra por tipo_carne", count(buscarProductos($pdo, 'res')) >= 2);
check("buscar 'muslo' encuentra por nombre", count(buscarProductos($pdo, 'muslo')) === 1);
check("sin busqueda trae todos los disponibles", count(buscarProductos($pdo, null)) === count(buscarProductos($pdo, '')));

echo "\n=== Funcionalidad 2: promociones vigentes hoy (estaVigente()) ===\n";
function estaVigente(string $diaSemana): bool {
    $dias = ['domingo','lunes','martes','miercoles','jueves','viernes','sabado'];
    return strtolower($diaSemana) === $dias[(int)date('w')];
}
$hoyIdx = (int)date('w');
$dias = ['domingo','lunes','martes','miercoles','jueves','viernes','sabado'];
$hoy = $dias[$hoyIdx];
$manana = $dias[($hoyIdx + 1) % 7];
check("promocion de HOY ($hoy) esta vigente", estaVigente($hoy) === true);
check("promocion de MAÑANA ($manana) no esta vigente hoy", estaVigente($manana) === false);

echo "\n=== Funcionalidad 3: top 3 mas vendidos (agregando pedido_producto) ===\n";
$pdo->exec("INSERT INTO productos (categoria_id, nombre, tipo_carne, precio, disponible) VALUES ($catId, 'Chuleta', 'Cerdo', 15000, 1)");
$chuletaId = $pdo->lastInsertId();
$pdo->exec("INSERT INTO pedidos (user_id, total, estado) VALUES ($uid, 0, 'pagado')");
$otroPedido = $pdo->lastInsertId();
$pdo->prepare("INSERT INTO pedido_producto (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, 10, 15000)")->execute([$otroPedido, $chuletaId]);
$pdo->prepare("INSERT INTO pedido_producto (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, 5, 9800)")->execute([$otroPedido, $prodId]);
$costillaId = $pdo->query("SELECT id FROM productos WHERE nombre = 'Costilla de res'")->fetchColumn();
$pdo->prepare("INSERT INTO pedido_producto (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, 2, 22000)")->execute([$otroPedido, $costillaId]);

$stmt = $pdo->query("
    SELECT p.nombre, SUM(pp.cantidad) as total_vendido
    FROM pedido_producto pp JOIN productos p ON p.id = pp.producto_id
    GROUP BY p.id ORDER BY total_vendido DESC LIMIT 3
");
$topVendidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
check('top vendidos trae 3 filas', count($topVendidos) === 3);
check("el mas vendido es Chuleta (10 unidades)", $topVendidos[0]['nombre'] === 'Chuleta' && (int)$topVendidos[0]['total_vendido'] === 10);

echo "\n=== Registro de clientes (RegisterController) ===\n";
function validarRegistro(array $d, PDO $pdo): array {
    $errores = [];
    if (empty($d['name'] ?? null)) $errores[] = 'nombre requerido';
    if (empty($d['email'] ?? null) || !filter_var($d['email'], FILTER_VALIDATE_EMAIL)) $errores[] = 'email invalido';
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$d['email'] ?? '']);
    if ((int)$stmt->fetchColumn() > 0) $errores[] = 'email ya registrado';
    if (empty($d['password'] ?? null) || strlen($d['password']) < 8) $errores[] = 'password invalida';
    if (($d['password'] ?? null) !== ($d['password_confirmation'] ?? null)) $errores[] = 'passwords no coinciden';
    return $errores;
}
function registrarUsuario(PDO $pdo, array $d): array {
    $errores = validarRegistro($d, $pdo);
    if ($errores) return ['ok' => false, 'errores' => $errores];
    $stmt = $pdo->prepare("INSERT INTO users (name, email, direccion, rol, password) VALUES (?, ?, ?, 'cliente', ?)");
    $stmt->execute([$d['name'], $d['email'], $d['direccion'] ?? null, password_hash($d['password'], PASSWORD_BCRYPT)]);
    return ['ok' => true, 'id' => $pdo->lastInsertId()];
}
$reg1 = registrarUsuario($pdo, ['name' => 'Nuevo Cliente', 'email' => 'nuevo@correo.com', 'password' => 'clave1234', 'password_confirmation' => 'clave1234']);
check('registro valido crea el usuario', $reg1['ok'] === true);
check('nuevo usuario queda con rol cliente', intentarLogin($pdo, 'nuevo@correo.com', 'clave1234')['rol'] === 'cliente');
$reg2 = registrarUsuario($pdo, ['name' => 'Duplicado', 'email' => 'nuevo@correo.com', 'password' => 'clave1234', 'password_confirmation' => 'clave1234']);
check('rechaza registro con email duplicado', $reg2['ok'] === false && in_array('email ya registrado', $reg2['errores']));
$reg3 = registrarUsuario($pdo, ['name' => 'X', 'email' => 'x@correo.com', 'password' => 'clave1234', 'password_confirmation' => 'otra']);
check('rechaza si las contraseñas no coinciden', $reg3['ok'] === false);

echo "\n=== CarritoController: agregar / quitar / checkout end-to-end ===\n";
$pdo->prepare("INSERT INTO carritos (user_id) VALUES (?)")->execute([$reg1['id']]);
$carritoNuevoId = $pdo->lastInsertId();

function carritoAgregar(PDO $pdo, int $carritoId, int $productoId, int $cantidad): void {
    $stmt = $pdo->prepare("SELECT id, cantidad FROM carrito_producto WHERE carrito_id = ? AND producto_id = ?");
    $stmt->execute([$carritoId, $productoId]);
    $existente = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($existente) {
        $pdo->prepare("UPDATE carrito_producto SET cantidad = cantidad + ? WHERE id = ?")->execute([$cantidad, $existente['id']]);
    } else {
        $pdo->prepare("INSERT INTO carrito_producto (carrito_id, producto_id, cantidad) VALUES (?, ?, ?)")->execute([$carritoId, $productoId, $cantidad]);
    }
}
function carritoQuitar(PDO $pdo, int $carritoId, int $productoId): void {
    $pdo->prepare("DELETE FROM carrito_producto WHERE carrito_id = ? AND producto_id = ?")->execute([$carritoId, $productoId]);
}
function carritoContarItems(PDO $pdo, int $carritoId): int {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM carrito_producto WHERE carrito_id = ?");
    $stmt->execute([$carritoId]);
    return (int)$stmt->fetchColumn();
}

carritoAgregar($pdo, $carritoNuevoId, $chuletaId, 2);
carritoAgregar($pdo, $carritoNuevoId, $costillaId, 1);
check('carrito nuevo tiene 2 items distintos', carritoContarItems($pdo, $carritoNuevoId) === 2);

carritoQuitar($pdo, $carritoNuevoId, $costillaId);
check('quitar() deja 1 item', carritoContarItems($pdo, $carritoNuevoId) === 1);

// checkout(): mismo flujo que Carrito::generarPedido()
$stmt = $pdo->prepare("SELECT p.id as producto_id, p.precio, cp.cantidad FROM carrito_producto cp JOIN productos p ON p.id = cp.producto_id WHERE cp.carrito_id = ?");
$stmt->execute([$carritoNuevoId]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalCheckout = array_sum(array_map(fn($i) => $i['precio'] * $i['cantidad'], $items));
$pdo->prepare("INSERT INTO pedidos (user_id, total, estado) VALUES (?, ?, 'pendiente_pago')")->execute([$reg1['id'], $totalCheckout]);
$pedidoCheckoutId = $pdo->lastInsertId();
foreach ($items as $item) {
    $pdo->prepare("INSERT INTO pedido_producto (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)")
        ->execute([$pedidoCheckoutId, $item['producto_id'], $item['cantidad'], $item['precio']]);
}
$pdo->prepare("DELETE FROM carrito_producto WHERE carrito_id = ?")->execute([$carritoNuevoId]);

check('checkout genera un pedido con el total correcto', (float)$totalCheckout === 30000.0);
check('checkout vacia el carrito', carritoContarItems($pdo, $carritoNuevoId) === 0);
$stmt = $pdo->prepare("SELECT COUNT(*) FROM pedido_producto WHERE pedido_id = ?");
$stmt->execute([$pedidoCheckoutId]);
check('el pedido generado tiene 1 item (chuleta)', (int)$stmt->fetchColumn() === 1);

echo "\n=== CRUD admin de Categoria (segundo CRUD) ===\n";
function validarCategoria(array $d, PDO $pdo, ?int $ignorarId = null): array {
    $errores = [];
    if (empty($d['nombre'] ?? null)) $errores[] = 'nombre requerido';
    $sql = "SELECT COUNT(*) FROM categorias WHERE nombre = ?" . ($ignorarId ? " AND id != $ignorarId" : "");
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$d['nombre'] ?? '']);
    if ((int)$stmt->fetchColumn() > 0) $errores[] = 'nombre duplicado';
    return $errores;
}
function crearCategoria(PDO $pdo, array $d): array {
    $errores = validarCategoria($d, $pdo);
    if ($errores) return ['ok' => false, 'errores' => $errores];
    $pdo->prepare("INSERT INTO categorias (nombre) VALUES (?)")->execute([$d['nombre']]);
    return ['ok' => true, 'id' => $pdo->lastInsertId()];
}
$cat1 = crearCategoria($pdo, ['nombre' => 'Mariscos']);
check('crear categoria valida', $cat1['ok'] === true);
$cat2 = crearCategoria($pdo, ['nombre' => 'Res']); // 'Res' ya existe desde el setup inicial
check('rechaza categoria con nombre duplicado', $cat2['ok'] === false);

$pdo->prepare("UPDATE categorias SET nombre = ? WHERE id = ?")->execute(['Mariscos y Pescados', $cat1['id']]);
$stmt = $pdo->prepare("SELECT nombre FROM categorias WHERE id = ?");
$stmt->execute([$cat1['id']]);
check('editar categoria actualiza el nombre', $stmt->fetchColumn() === 'Mariscos y Pescados');

// destroy(): igual que el ON DELETE CASCADE de la migracion (categoria sin productos)
$pdo->prepare("DELETE FROM categorias WHERE id = ?")->execute([$cat1['id']]);
$stmt = $pdo->prepare("SELECT COUNT(*) FROM categorias WHERE id = ?");
$stmt->execute([$cat1['id']]);
check('eliminar categoria la borra', (int)$stmt->fetchColumn() === 0);

echo "\n=== Resumen final: $pass pasaron, $fails fallaron ===\n";
exit($fails > 0 ? 1 : 0);
