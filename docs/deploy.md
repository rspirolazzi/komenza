# Komenza: deploy y release

Tres workflows de GitHub Actions en `.github/workflows/`:

| Workflow | Cuándo corre | Qué hace |
|---|---|---|
| `deploy-backend.yml` | Push a `main` que toque `reservations-backend/`, o manual | Tests (phpunit en la imagen), imagen `komenza-backend` en ghcr.io y actualización en el servidor |
| `deploy-web.yml` | Push a `main` que toque `reservations-frontend/`, o manual | Tipos y `next build`, imagen `komenza-frontend` en ghcr.io y actualización en el servidor |
| `release-android.yml` | Tag `mobile-v<versión>`, o manual | Lint y tipos, APK arm64 y AAB firmados; con tag, GitHub Release |

Una ejecución manual desde otra rama solo verifica: se publica solo desde `main`.

## Secretos y variables de GitHub

En *Settings → Secrets and variables → Actions* del repositorio.

| Nombre | Tipo | Usado por | Contenido |
|---|---|---|---|
| `DROPLET_IP` | Secreto | backend, web | IP o host del servidor |
| `DROPLET_USER` | Secreto | backend, web | Usuario SSH del servidor |
| `SSH_PRIVATE_KEY` | Secreto | backend, web | Clave privada SSH de deploy |
| `DEPLOY_PATH` | Variable | backend, web | Carpeta del servidor con `docker-compose.yml` y `.env` |
| `NEXT_PUBLIC_API_BASE` | Variable | web | URL pública de la API, sin `/api` (ej.: `https://api.dominio`) |
| `NEXT_PUBLIC_GOOGLE_MAP_KEY` | Variable (opcional) | web | Clave de Google Static Maps, restringida por dominio |
| `EXPO_PUBLIC_API_URL` | Variable | Android | URL pública de la API **con** `/api/` al final |
| `ANDROID_UPLOAD_KEYSTORE_BASE64` | Secreto | Android | Keystore de subida en base64 |
| `ANDROID_UPLOAD_STORE_PASSWORD` | Secreto | Android | Clave del keystore |
| `ANDROID_UPLOAD_KEY_ALIAS` | Secreto | Android | Alias de la clave |
| `ANDROID_UPLOAD_KEY_PASSWORD` | Secreto | Android | Clave del alias |

Las variables `NEXT_PUBLIC_*` y `EXPO_PUBLIC_*` quedan dentro de la web y de la app: nunca poner secretos ahí.

La conexión SSH no verifica la huella del servidor (no se usa `DROPLET_SSH_FINGERPRINT`).

Si falta alguno, el job falla en el paso *Check configuration* con el nombre faltante.

## Servidor

Requisitos: Docker con Compose y un proxy con HTTPS (Caddy, nginx) delante de los puertos locales.

1. Copiar `deploy/docker-compose.yml` y `deploy/.env.example` a `DEPLOY_PATH`; renombrar el segundo a `.env` y completarlo.
2. Las imágenes de ghcr.io son privadas: en el servidor, `docker login ghcr.io` con un token de GitHub de solo lectura de paquetes (`read:packages`).
3. Primer arranque: `docker compose up -d --wait`. Las migraciones corren solas.
4. Una sola vez, si hace falta el administrador inicial: `docker compose exec -u www-data komenza-backend php artisan db:seed --force`.

| Servicio | Puerto local | Imagen |
|---|---|---|
| `komenza-backend` | `BACKEND_PORT` (8100) | `ghcr.io/<dueño>/komenza-backend` |
| `komenza-frontend` | `FRONTEND_PORT` (3100) | `ghcr.io/<dueño>/komenza-frontend` |

Cada imagen se publica con los tags `latest` y `sha-<commit>`. Para volver atrás, fijar `BACKEND_TAG` o `FRONTEND_TAG` en `.env` y `docker compose up -d`.

La URL de la API queda fijada en la imagen de la web al compilar: si cambia `NEXT_PUBLIC_API_BASE`, volver a ejecutar *Deploy web*.

### Proxy HTTPS

- El proxy debe enviar `X-Forwarded-Proto`, `X-Forwarded-Host` y `X-Forwarded-Port`. El backend las acepta según `TRUSTED_PROXIES` (`*` por defecto en `deploy/`). Sin eso, Laravel arma los formularios del panel con `http://` y el navegador avisa que el envío no es seguro.
- Con web y panel en el mismo dominio, mandar al backend: `/api/`, `/admin`, `/login`, `/logout`, `/password/`, `/js/`, `/css/`, `/img/` (incluye los logos y fondos subidos), `/jquery-timepicker-master/` y `/vendor/`. El resto va a la web. En ese caso, `NEXT_PUBLIC_API_BASE` es el dominio, sin `/api`.

## Android

- Paquete: `ar.com.komenza.app` (en `reservations-mobile/app.config.js`). No se puede cambiar después de publicar en Google Play.
- `versionCode` = número de ejecución del workflow; `version` sale de `app.json`.
- Para publicar: subir `version` en `app.json`, commitear y crear el tag `mobile-v<versión>`.
- La firma la configura `reservations-mobile/plugins/withReleaseSigning.js` durante `expo prebuild`. Sin las variables `ANDROID_UPLOAD_*`, el build release usa la clave de debug.
- `android/` e `ios/` se generan en cada build y no se versionan.

Crear el keystore de subida (una vez; guardarlo fuera del repositorio):

```powershell
keytool -genkeypair -v -keystore upload.jks -alias upload -keyalg RSA -keysize 2048 -validity 10000
```

```powershell
[Convert]::ToBase64String([IO.File]::ReadAllBytes("upload.jks")) | Set-Clipboard
```

El segundo comando copia el valor para `ANDROID_UPLOAD_KEYSTORE_BASE64`.
