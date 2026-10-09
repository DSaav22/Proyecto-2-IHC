<?php

use App\Http\Controllers\CuentaController;
use App\Http\Controllers\PlanController;
use Illuminate\Support\Facades\Route;

// el html esta en apps/web, un nivel arriba de este proyecto
function archivoWeb(string $nombre)
{
    $ruta = dirname(base_path()).DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.$nombre;
    abort_unless(is_file($ruta), 404);

    $tipos = [
        'html' => 'text/html; charset=utf-8',
        'css' => 'text/css; charset=utf-8',
        'js' => 'text/javascript; charset=utf-8',
    ];
    $ext = pathinfo($ruta, PATHINFO_EXTENSION);

    return response()->file($ruta, [
        'Content-Type' => $tipos[$ext] ?? 'text/plain; charset=utf-8',
    ]);
}

Route::get('/', fn () => archivoWeb('index.html'));
Route::get('/login', fn () => archivoWeb('login.html'));
Route::get('/register', fn () => archivoWeb('registro.html'));
Route::get('/recover', fn () => archivoWeb('recuperar.html'));
Route::get('/mis-planes', fn () => archivoWeb('planes.html'));
Route::get('/estilos.css', fn () => archivoWeb('estilos.css'));
Route::get('/comun.js', fn () => archivoWeb('comun.js'));

Route::post('/api/auth/register', [CuentaController::class, 'registrar']);
Route::post('/api/auth/login', [CuentaController::class, 'entrar']);
Route::post('/api/auth/logout', [CuentaController::class, 'salir']);
Route::get('/api/auth/me', [CuentaController::class, 'yo']);
Route::post('/api/auth/recover', [CuentaController::class, 'recuperar']);
Route::post('/api/auth/recover/confirm', [CuentaController::class, 'confirmar']);

Route::get('/api/planes', [PlanController::class, 'lista']);
Route::post('/api/planes', [PlanController::class, 'crear']);
Route::post('/api/planes/confirmar', [PlanController::class, 'confirmar']);
