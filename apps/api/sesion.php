<?php

function arrancarSesion() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    session_name('planazo_session');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function usuarioActualId() {
    arrancarSesion();
    return $_SESSION['usuario_id'] ?? null;
}

function entrar($id) {
    arrancarSesion();
    $_SESSION['usuario_id'] = $id;
}

function salir() {
    arrancarSesion();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}
