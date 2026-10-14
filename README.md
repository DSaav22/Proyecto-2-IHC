# Planazo

Ayudar a un grupo a organizar un plan.

El backend es un proyecto de Laravel en `apps/backend`. El frontend está en `apps/web`: las pantallas en `paginas`, el estilo en `css` y lo compartido en `js`. Laravel abre esas páginas y también atiende `/api`.

## Cómo levantarlo

En `apps/backend`, la primera vez:

```bash
php -d extension=pdo_sqlite -d extension=sqlite3 artisan migrate --seed
```

Para encender, en esta máquina SQLite no está activo en el `php.ini`, y `php artisan serve` no le pasa esa extensión al servidor. Desde `apps/backend/public`:

```bash
php -d extension=pdo_sqlite -d extension=sqlite3 -S localhost:8000 -t . ..\servidor.php
```

Abre http://localhost:8000

Si más adelante activas `extension=pdo_sqlite` y `extension=sqlite3` en el `php.ini`, el mismo comando sirve, sin los `-d`. `php artisan serve` en Windows muestra un aviso arriba de la página, por eso no lo usamos.

## Usuarios de prueba

| Nombre | Correo | Contraseña |
| --- | --- | --- |
| Usuario Uno | usuario1@planazo.com | `Planazo123!` |
| Usuario Dos | usuario2@planazo.com | `Planazo123!` |

Páginas: `/`, `/login`, `/register`, `/recover` y `/mis-planes`.
