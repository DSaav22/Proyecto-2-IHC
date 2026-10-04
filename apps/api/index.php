<?php

require __DIR__ . '/db.php';
require __DIR__ . '/sesion.php';

header('Content-Type: application/json; charset=utf-8');

$metodo = $_SERVER['REQUEST_METHOD'];
$ruta = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$db = obtenerDb();

$raw = file_get_contents('php://input');
$datos = json_decode($raw, true);
if (!is_array($datos)) {
    $datos = [];
}

function responder($codigo, $payload) {
    http_response_code($codigo);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function correoOk($correo) {
    return filter_var($correo, FILTER_VALIDATE_EMAIL);
}

// lo que ve el front, sin el hash
function armarUsuario($fila) {
    return [
        'id' => $fila['id'],
        'correo' => $fila['email'],
        'nombre' => $fila['nombre'],
    ];
}

if ($metodo === 'POST' && $ruta === '/api/auth/register') {
    $nombre = trim($datos['nombre'] ?? '');
    $correo = strtolower(trim($datos['correo'] ?? ''));
    $clave = $datos['clave'] ?? '';

    $errores = [];
    if ($nombre === '') $errores['nombre'] = 'Ingresa tu nombre.';
    if ($correo === '' || !correoOk($correo)) $errores['correo'] = 'Ingresa un correo válido.';
    if (strlen($clave) < 8) $errores['clave'] = 'La contraseña debe tener al menos 8 caracteres.';
    if ($errores) {
        responder(400, ['message' => 'Datos inválidos', 'errors' => $errores]);
    }

    $busca = $db->prepare('SELECT id FROM usuarios WHERE email = ?');
    $busca->execute([$correo]);
    if ($busca->fetch()) {
        responder(409, ['message' => 'Ya existe una cuenta con ese correo']);
    }

    $id = bin2hex(random_bytes(16));
    $ahora = date('c');
    $insert = $db->prepare('INSERT INTO usuarios (id, email, password_hash, nombre, creado_en, actualizado_en) VALUES (?, ?, ?, ?, ?, ?)');
    $insert->execute([$id, $correo, password_hash($clave, PASSWORD_DEFAULT), $nombre, $ahora, $ahora]);

    entrar($id);
    responder(201, ['user' => ['id' => $id, 'correo' => $correo, 'nombre' => $nombre]]);
}

if ($metodo === 'POST' && $ruta === '/api/auth/login') {
    $correo = strtolower(trim($datos['correo'] ?? ''));
    $clave = $datos['clave'] ?? '';

    $stmt = $db->prepare('SELECT * FROM usuarios WHERE email = ?');
    $stmt->execute([$correo]);
    $fila = $stmt->fetch();

    // mismo mensaje si el correo no está o la clave no calza
    if (!$fila || !password_verify($clave, $fila['password_hash'])) {
        responder(401, ['message' => 'Correo o contraseña incorrectos']);
    }

    entrar($fila['id']);
    responder(200, ['user' => armarUsuario($fila)]);
}

if ($metodo === 'POST' && $ruta === '/api/auth/logout') {
    salir();
    responder(200, ['message' => 'Sesión cerrada']);
}

if ($metodo === 'GET' && $ruta === '/api/auth/me') {
    $id = usuarioActualId();
    if (!$id) {
        responder(401, ['message' => 'No has iniciado sesión']);
    }

    $stmt = $db->prepare('SELECT * FROM usuarios WHERE id = ?');
    $stmt->execute([$id]);
    $fila = $stmt->fetch();
    if (!$fila) {
        salir();
        responder(401, ['message' => 'No has iniciado sesión']);
    }

    responder(200, ['user' => armarUsuario($fila)]);
}

if ($metodo === 'POST' && $ruta === '/api/auth/recover') {
    $correo = strtolower(trim($datos['correo'] ?? ''));
    if ($correo === '' || !correoOk($correo)) {
        responder(400, ['message' => 'Datos inválidos', 'errors' => ['correo' => 'Ingresa un correo válido.']]);
    }

    $mensaje = 'Si el correo existe, se generó un token de recuperación.';
    $stmt = $db->prepare('SELECT * FROM usuarios WHERE email = ?');
    $stmt->execute([$correo]);
    $fila = $stmt->fetch();

    // no decimos si el correo existe
    if (!$fila) {
        responder(200, ['message' => $mensaje]);
    }

    $token = bin2hex(random_bytes(24));
    $expira = date('c', time() + 15 * 60);
    $upd = $db->prepare('UPDATE usuarios SET reset_token_hash = ?, reset_token_expira = ?, actualizado_en = ? WHERE id = ?');
    $upd->execute([hash('sha256', $token), $expira, date('c'), $fila['id']]);

    responder(200, ['message' => $mensaje, 'token' => $token]);
}

if ($metodo === 'POST' && $ruta === '/api/auth/recover/confirm') {
    $correo = strtolower(trim($datos['correo'] ?? ''));
    $token = trim($datos['token'] ?? '');
    $clave = $datos['clave'] ?? '';

    $errores = [];
    if ($correo === '' || !correoOk($correo)) $errores['correo'] = 'Ingresa un correo válido.';
    if ($token === '') $errores['token'] = 'Ingresa el token.';
    if (strlen($clave) < 8) $errores['clave'] = 'La contraseña debe tener al menos 8 caracteres.';
    if ($errores) {
        responder(400, ['message' => 'Datos inválidos', 'errors' => $errores]);
    }

    $stmt = $db->prepare('SELECT * FROM usuarios WHERE email = ?');
    $stmt->execute([$correo]);
    $fila = $stmt->fetch();
    $expira = $fila ? $fila['reset_token_expira'] : '';
    $sirve = $fila
        && $fila['reset_token_hash']
        && hash_equals($fila['reset_token_hash'], hash('sha256', $token))
        && $expira
        && strtotime($expira) >= time();

    if (!$sirve) {
        responder(400, ['message' => 'Token inválido o expirado']);
    }

    $upd = $db->prepare('UPDATE usuarios SET password_hash = ?, reset_token_hash = NULL, reset_token_expira = NULL, actualizado_en = ? WHERE id = ?');
    $upd->execute([password_hash($clave, PASSWORD_DEFAULT), date('c'), $fila['id']]);
    responder(200, ['message' => 'Contraseña actualizada']);
}

function usuarioLogueado() {
    $id = usuarioActualId();
    if (!$id) {
        responder(401, ['message' => 'No has iniciado sesión']);
    }
    return $id;
}

if ($metodo === 'GET' && $ruta === '/api/planes') {
    $uid = usuarioLogueado();
    $stmt = $db->prepare('SELECT id, nombre, fecha_limite, estado FROM planes WHERE usuario_id = ?');
    $stmt->execute([$uid]);
    responder(200, ['planes' => $stmt->fetchAll()]);
}

if ($metodo === 'POST' && $ruta === '/api/planes') {
    $uid = usuarioLogueado();
    $nombre = trim($datos['nombre'] ?? '');
    $fecha = trim($datos['fecha'] ?? '');
    if ($nombre === '' || $fecha === '') {
        responder(400, ['message' => 'Falta el nombre o la fecha.']);
    }

    $id = bin2hex(random_bytes(8));
    $ins = $db->prepare('INSERT INTO planes (id, usuario_id, nombre, fecha_limite, estado) VALUES (?, ?, ?, ?, ?)');
    $ins->execute([$id, $uid, $nombre, $fecha, 'borrador']);
    responder(201, ['id' => $id]);
}

if ($metodo === 'POST' && $ruta === '/api/planes/confirmar') {
    $uid = usuarioLogueado();
    $id = $datos['id'] ?? '';

    $stmt = $db->prepare('SELECT estado FROM planes WHERE id = ? AND usuario_id = ?');
    $stmt->execute([$id, $uid]);
    $plan = $stmt->fetch();
    if (!$plan) {
        responder(404, ['message' => 'No encontré ese plan.']);
    }
    if ($plan['estado'] !== 'borrador') {
        responder(400, ['message' => 'Ese plan ya no se puede confirmar.']);
    }

    $upd = $db->prepare('UPDATE planes SET estado = ? WHERE id = ?');
    $upd->execute(['confirmado', $id]);
    responder(200, ['ok' => true]);
}

responder(404, ['message' => 'No existe']);
