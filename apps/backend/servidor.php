<?php

// el server.php de laravel escribe en php://stdout.
// en windows eso falla (errno 22) y el aviso sale arriba de la pagina.
$publicPath = getcwd();

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

if ($uri !== '/' && file_exists($publicPath.$uri)) {
    return false;
}

require_once $publicPath.'/index.php';
