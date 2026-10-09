# Planazo

Ayudar a un grupo a organizar un plan.

El backend es un proyecto de Laravel en `apps/backend`. Las páginas siguen en `apps/web`. Laravel las abre y también atiende `/api`.

## Cómo levantarlo

En `apps/backend`, la primera vez:

```bash
php -d extension=pdo_sqlite -d extension=sqlite3 artisan migrate --seed
```

Para encender, en esta máquina SQLite no está activo en el `php.ini`, y `php artisan serve` no le pasa esa extensión al servidor. Desde `apps/backend/public`:

```bash
php -d extension=pdo_sqlite -d extension=sqlite3 -S localhost:8000 -t . ..\vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php
```

Abre http://localhost:8000

Si más adelante activas `extension=pdo_sqlite` y `extension=sqlite3` en el `php.ini`, alcanza con `php artisan serve` dentro de `apps/backend`.

## Usuarios de prueba

| Nombre | Correo | Contraseña |
| --- | --- | --- |
| Usuario Uno | usuario1@planazo.com | `Planazo123!` |
| Usuario Dos | usuario2@planazo.com | `Planazo123!` |

Páginas: `/`, `/login`, `/register`, `/recover` y `/mis-planes`.
