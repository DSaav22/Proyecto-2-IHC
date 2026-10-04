<?php

function obtenerDb(): PDO
{
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $carpetaData = __DIR__ . '/data';
    if (!is_dir($carpetaData)) {
        mkdir($carpetaData, 0777, true);
    }

    $rutaDb = $carpetaData . '/planazo.sqlite';
    $pdo = new PDO('sqlite:' . $rutaDb);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS usuarios (
            id TEXT PRIMARY KEY,
            email TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            nombre TEXT NOT NULL,
            reset_token_hash TEXT,
            reset_token_expira TEXT,
            creado_en TEXT NOT NULL,
            actualizado_en TEXT NOT NULL
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS planes (
            id TEXT PRIMARY KEY,
            usuario_id TEXT NOT NULL,
            nombre TEXT NOT NULL,
            fecha_limite TEXT NOT NULL,
            estado TEXT NOT NULL
        )
    ");

    // si está vacía metemos los de prueba
    $cuenta = (int) $pdo->query('SELECT COUNT(*) FROM usuarios')->fetchColumn();
    if ($cuenta === 0) {
        $ahora = date('c');
        $claveHash = password_hash('Planazo123!', PASSWORD_DEFAULT);

        $stmt = $pdo->prepare('
            INSERT INTO usuarios (id, email, password_hash, nombre, reset_token_hash, reset_token_expira, creado_en, actualizado_en)
            VALUES (?, ?, ?, ?, NULL, NULL, ?, ?)
        ');

        $stmt->execute(['u1', 'usuario1@planazo.com', $claveHash, 'Usuario Uno', $ahora, $ahora]);
        $stmt->execute(['u2', 'usuario2@planazo.com', $claveHash, 'Usuario Dos', $ahora, $ahora]);
    }

    return $pdo;
}
