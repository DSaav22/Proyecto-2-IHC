<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($uri === null || $uri === '') {
    $uri = '/';
}

if (str_starts_with($uri, '/api')) {
    require __DIR__ . '/index.php';
    return true;
}

$paginas = [
    '/' => '/index.html',
    '/login' => '/login.html',
    '/register' => '/registro.html',
    '/recover' => '/recuperar.html',
    '/mis-planes' => '/planes.html',
];

if (isset($paginas[$uri])) {
    $uri = $paginas[$uri];
}

$carpeta = realpath(__DIR__ . '/../web');
$archivo = realpath($carpeta . $uri);

// que no se salga de apps/web
if (!$carpeta || !$archivo || !str_starts_with($archivo, $carpeta) || !is_file($archivo)) {
    http_response_code(404);
    echo 'No existe';
    return true;
}

$ext = pathinfo($archivo, PATHINFO_EXTENSION);
if ($ext === 'html') header('Content-Type: text/html; charset=utf-8');
if ($ext === 'css') header('Content-Type: text/css; charset=utf-8');
if ($ext === 'js') header('Content-Type: text/javascript; charset=utf-8');

readfile($archivo);
return true;
