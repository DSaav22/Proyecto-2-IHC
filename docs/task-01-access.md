# Tarea 1: acceso

El backend está en `apps/backend` (Laravel). Las pantallas están en `apps/web/paginas`. La base es SQLite, el archivo `apps/backend/database/database.sqlite`.

La sesión se guarda en la tabla `sessions`. El navegador no guarda un token. La contraseña queda hasheada. En las respuestas solo se devuelve el id, el correo y el nombre.

Recuperar la contraseña no manda correo. Si el correo existe, la respuesta incluye un token para pegarlo en la página. En `password_reset_tokens` se guarda el SHA-256 de ese token, no el token. Sirve 15 minutos.

## Tablas

`users`: id, name, email, password, y las fechas de Laravel.

`password_reset_tokens`: email, token (el hash) y created_at.

## API

| Método | Ruta | Qué hace |
| --- | --- | --- |
| POST | `/api/auth/register` | Crea la cuenta y entra. 201, 400 o 409. |
| POST | `/api/auth/login` | Entra. 200 o 401. |
| POST | `/api/auth/logout` | Cierra la sesión. |
| GET | `/api/auth/me` | Quién está logueado. 200 o 401. |
| POST | `/api/auth/recover` | Genera el token. |
| POST | `/api/auth/recover/confirm` | Cambia la contraseña con correo, token y clave nueva. |

## Páginas

| Ruta | Archivo |
| --- | --- |
| `/` | `apps/web/paginas/index.html` |
| `/login` | `apps/web/paginas/login.html` |
| `/register` | `apps/web/paginas/registro.html` |
| `/recover` | `apps/web/paginas/recuperar.html` |
| `/mis-planes` | `apps/web/paginas/planes.html` |

El controlador es `CuentaController`. Las rutas están en `apps/backend/routes/web.php`.

Usuarios de prueba: `usuario1@planazo.com` y `usuario2@planazo.com`, contraseña `Planazo123!`.
