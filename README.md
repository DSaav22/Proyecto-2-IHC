# Planazo

Ayudar a un grupo a organizar un plan.

La página es HTML y la API es PHP con SQLite. Las dos cosas salen del mismo servidor.

## Qué necesitas

PHP 8, con `pdo_sqlite` y `sqlite3`. En este Windows no vienen activas en el `php.ini`, así que el comando las carga con `-d`.

## Cómo levantarlo

Desde la raíz:

```bash
php -d extension=pdo_sqlite -d extension=sqlite3 -S localhost:8080 -t apps/api apps/api/router.php
```

Abre http://localhost:8080

La base se crea sola en `apps/api/data/planazo.sqlite` la primera vez. Ahí también entran los usuarios de prueba.

## Usuarios de prueba

| Nombre | Correo | Contraseña |
| --- | --- | --- |
| Usuario Uno | usuario1@planazo.com | `Planazo123!` |
| Usuario Dos | usuario2@planazo.com | `Planazo123!` |

## Páginas

`/` , `/login` , `/register` , `/recover` y `/mis-planes` (esta pide sesión).

Si borras `apps/api/data/planazo.sqlite` y vuelves a abrir la página, se vuelven a crear los dos usuarios.
