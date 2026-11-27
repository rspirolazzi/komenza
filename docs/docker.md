# Komenza con Docker

Entorno de **desarrollo** completo. Verificado el 2026-11-25 con Docker 24 y Compose 2.22 en Windows.

## Primer arranque

Desde la raíz del proyecto, con Docker Desktop iniciado:

```powershell
powershell -ExecutionPolicy Bypass -File scripts/init-env.ps1
```

```powershell
docker compose up -d --build --wait
```

```powershell
docker compose exec -u www-data backend php artisan db:seed --force
```

El seed se ejecuta **una sola vez**: repetirlo duplica marcas y sucursales. Las migraciones corren solas.
Los fondos de ejemplo sí se pueden recargar: `php artisan db:seed --class=BrandBackgroundSeeder --force`.

| Servicio | URL | Detalle |
|---|---|---|
| Web | http://localhost:3100/Default | Next.js en modo desarrollo |
| API | http://localhost:8100/api/brands | Laravel con Apache |
| Panel | http://localhost:8100/login | Compañía `Default`; usuario `admin@komenza.example`; clave: `SEED_ADMIN_PASSWORD` de `.env` |
| Emails | http://localhost:8125 | Mailpit: captura todo, nada sale a destinatarios reales |
| MySQL | 127.0.0.1:3316 | 5.7.44 |
| Mobile (opcional) | http://localhost:8181 | Expo en modo web |

Los puertos se cambian en `.env` y solo escuchan en `127.0.0.1`.

Mobile web:

```powershell
docker compose --profile mobile up -d --build
```

Para Android o iOS, ejecutar Expo en la máquina (ver [análisis mobile](analysis-mobile.md)).

## Uso diario

| Acción | Comando |
|---|---|
| Levantar | `docker compose up -d` |
| Detener | `docker compose stop` |
| Logs | `docker compose logs -f backend` |
| Artisan | `docker compose exec -u www-data backend php artisan <comando>` |
| Tests | `docker compose exec backend vendor/bin/phpunit` |
| Borrar contenedores **y datos** | `docker compose down -v` |

Se montan en solo lectura `app`, `config`, `database`, `resources` y `routes` (backend), y `pages`, `src` y
`public` (frontend): los cambios se ven sin reconstruir. Al cambiar dependencias, `public` o `tests` del
backend, u otros archivos, hay que reconstruir. Mobile no monta código: reconstruir en cada cambio.
Las imágenes subidas desde el panel, los logs y la base de datos persisten en volúmenes.

## Secretos

- Viven solo en `.env` de la raíz, ignorado por git. `init-env.ps1` los genera y no pisa un `.env` existente.
- Compose los pasa como variables de entorno; si falta uno, no arranca.
- Los `.dockerignore` excluyen los `.env`: **no quedan en las imágenes** (verificado).
- Los `.env` de cada proyecto son solo para ejecutar sin Docker.
- Cambiar `DB_PASSWORD` en `.env` no cambia la clave de una base ya creada.

## Decisiones

| Decisión | Motivo |
|---|---|
| PHP 7.2 | Con 7.4, las versiones de `composer.lock` fallan al enviar emails y generar PDF |
| Composer 1.10 | Respeta el lock; Laravel 5.6.39 no lee el formato de Composer 2 |
| Snapshot de Debian | Los paquetes de la imagen base ya no están en los espejos |
| Frontend en desarrollo | Recarga en caliente; la imagen de producción es `Dockerfile.prod` ([deploy.md](deploy.md)) |
| Puertos 3100 y 8100 | No chocar con otros proyectos |
| Xdebug opcional | `INSTALL_XDEBUG=true` y reconstruir; opción no probada |

**No apto para producción**: PHP 7.2, Laravel 5.6 y MySQL 5.7 están fuera de soporte y sin parches de seguridad.
