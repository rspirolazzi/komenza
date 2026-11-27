<div align="center">

<picture>
  <source media="(prefers-color-scheme: dark)" srcset="docs/brand/logos/a-isologo-negativo.svg">
  <img src="docs/brand/logos/a-isologo-color.svg" alt="Komenza" width="380">
</picture>

### Todo comienza en la mesa.

Plataforma de reservas de mesas para grupos gastronómicos con varias marcas y sucursales.

![Laravel 5.6](https://img.shields.io/badge/Laravel-5.6-0F4C3A?logo=laravel&logoColor=white)
![PHP 7.2](https://img.shields.io/badge/PHP-7.2-0F4C3A?logo=php&logoColor=white)
![Next.js 15](https://img.shields.io/badge/Next.js-15-0F4C3A?logo=nextdotjs&logoColor=white)
![React 18](https://img.shields.io/badge/React-18-0F4C3A?logo=react&logoColor=white)
![Expo 53](https://img.shields.io/badge/Expo-53-0F4C3A?logo=expo&logoColor=white)
![MySQL 5.7](https://img.shields.io/badge/MySQL-5.7-0F4C3A?logo=mysql&logoColor=white)
![Docker Compose](https://img.shields.io/badge/Docker-Compose-F2A93B?logo=docker&logoColor=17211D)

[Recorrido](#recorrido) · [Funcionalidades](#funcionalidades) · [Arquitectura](#arquitectura) · [Inicio rápido](#inicio-rápido) · [API](#api) · [Documentación](#documentación)

</div>

---

**Komenza** deja que cada cliente reserve su mesa en minutos y sin crear una cuenta. El restaurante administra marcas, sucursales, horarios y reservas desde un panel, y cada marca conserva su propia identidad.

<p align="center"><img src="docs/screenshots/web-step2.png" alt="Formulario de reserva con la foto del restaurante de fondo" width="860"></p>

## Recorrido

### Web del cliente

| 1. Elegir el restaurante | 2. Completar los datos |
|:---:|:---:|
| <img src="docs/screenshots/web-brands.png" alt="Elección de restaurante" width="420"> | <img src="docs/screenshots/web-step1.png" alt="Datos del cliente" width="420"> |
| **3. Elegir sucursal, fecha y horario** | **4. Confirmación y cómo llegar** |
| <img src="docs/screenshots/web-step2.png" alt="Sucursal, fecha, horario y personas" width="420"> | <img src="docs/screenshots/web-thanks.png" alt="Reserva confirmada" width="420"> |
| **5. Modificar** | **6. Cancelar** |
| <img src="docs/screenshots/web-edit.png" alt="Modificar la reserva" width="420"> | <img src="docs/screenshots/web-cancel.png" alt="Cancelar la reserva" width="420"> |

En estas capturas, **Bar Ejemplo** usa su propio color y sus fotos: cada marca configura los suyos desde el panel.

<p align="center">
  <img src="docs/screenshots/web-mobile-brands.png" alt="Web en celular: restaurantes" width="230">
  &nbsp;&nbsp;
  <img src="docs/screenshots/web-mobile-form.png" alt="Web en celular: formulario" width="230">
  &nbsp;&nbsp;
  <img src="docs/screenshots/app-brands.png" alt="App: elección de marca" width="230">
</p>
<p align="center"><sub>Web en el celular (izquierda y centro) y app (derecha)</sub></p>

### Emails

| Reserva confirmada | Reserva cancelada |
|:---:|:---:|
| <img src="docs/screenshots/email-confirmed.png" alt="Email de confirmación" width="380"> | <img src="docs/screenshots/email-canceled.png" alt="Email de cancelación" width="380"> |

### Panel del restaurante

| Ingreso por compañía | Ocupación del día |
|:---:|:---:|
| <img src="docs/screenshots/panel-login.png" alt="Ingreso al panel" width="420"> | <img src="docs/screenshots/panel-dashboard.png" alt="Dashboard" width="420"> |
| **Agenda** | **Listado de reservas** |
| <img src="docs/screenshots/panel-calendar.png" alt="Agenda del día" width="420"> | <img src="docs/screenshots/panel-list.png" alt="Listado de reservas" width="420"> |
| **Sucursales** | **Personalización de la marca** |
| <img src="docs/screenshots/panel-stores.png" alt="Sucursales" width="420"> | <img src="docs/screenshots/panel-brand.png" alt="Logo, fondos y colores de la marca" width="420"> |
| **Reportes** | **Auditoría** |
| <img src="docs/screenshots/panel-reports.png" alt="Reportes" width="420"> | <img src="docs/screenshots/panel-audit.png" alt="Registro de actividad" width="420"> |

## Funcionalidades

### Para el cliente

- Reserva en dos pasos, sin cuenta ni contraseña.
- Sucursal, fecha (hasta un año), horario cada 30 minutos y cantidad de personas.
- Repetición de la última reserva.
- Confirmación por email, con enlaces para modificar o cancelar desde cualquier dispositivo.
- Aviso por email cuando la reserva se cancela, desde la web o desde el panel.
- Botón **Cómo llegar** con la ruta en Google Maps, sin clave de API.

### Para el restaurante

- Agenda por día, filtros, alta manual, cambio de estado y notas internas.
- Reservas en PDF.
- Marcas, sucursales, horarios y capacidad.
- Clientes y usuarios administradores.
- Dashboard de ocupación y reportes: visitas, clientes frecuentes, reservas por turno y listado de clientes.
- Registro de cambios.

### Para el grupo gastronómico

- Varias compañías, cada una con sus marcas y sucursales.
- Logo, color y fondos por marca, aplicados en la web y los emails.
- Roles de administrador, marca y sucursal.

### Reglas de negocio

| Regla | Detalle |
|---|---|
| Una reserva por cliente, sucursal y día | Configurable en `config/komenza.php` |
| Tope por reserva | Si se supera, la web invita a contactar a la sucursal |
| Tope diario por sucursal | Excedido, la reserva queda pendiente de confirmación |
| Modificar o cancelar | Solo reservas abiertas o pendientes por capacidad |
| Tolerancia | 15 minutos para no perder la mesa |

## Arquitectura

```mermaid
flowchart LR
    C([Cliente]) --> W[Web<br/>Next.js]
    C --> A[App<br/>Expo]
    P([Personal]) --> PA[Panel<br/>Laravel + AdminLTE]
    W -->|REST| API[API<br/>Laravel]
    A -->|REST| API
    PA --- API
    API --> DB[(MySQL)]
    API --> M[SMTP<br/>Mailpit en desarrollo]
```

| Directorio | Qué es | Tecnología |
|---|---|---|
| [`reservations-backend`](reservations-backend) | API y panel | Laravel 5.6, PHP 7.2, MySQL 5.7, AdminLTE |
| [`reservations-frontend`](reservations-frontend) | Web del cliente | Next.js 15, React 18, TypeScript, Redux Toolkit, Bootstrap 5 |
| [`reservations-mobile`](reservations-mobile) | App del cliente | Expo 53, React Native 0.79, TypeScript |
| [`docs`](docs) | Documentación y marca | Markdown, SVG |

```mermaid
erDiagram
    COMPANY ||--o{ BRAND : tiene
    BRAND ||--o{ STORE : tiene
    BRAND ||--o{ BRAND_BACKGROUND : muestra
    STORE ||--o{ RESERVATION : recibe
    USER ||--o{ RESERVATION : hace
```

## Inicio rápido

Requisitos: Docker con Compose y PowerShell.

```powershell
git clone git@github.com:rspirolazzi/komenza.git
```

```powershell
cd komenza
```

Genera `.env` con secretos aleatorios:

```powershell
powershell -ExecutionPolicy Bypass -File scripts/init-env.ps1
```

```powershell
docker compose up -d --build --wait
```

Carga los datos de ejemplo, una sola vez:

```powershell
docker compose exec -u www-data backend php artisan db:seed --force
```

| Servicio | URL |
|---|---|
| Web | http://localhost:3100/Default |
| Panel | http://localhost:8100/login |
| API | http://localhost:8100/api/brands |
| Emails de prueba | http://localhost:8125 |

Para entrar al panel: compañía `Default`, usuario `admin@komenza.example` y la clave `SEED_ADMIN_PASSWORD` de tu `.env`.

Más opciones, incluida la app, en la [guía de Docker](docs/docker.md).

## API

Base `/api`. Las respuestas tienen la forma `{ success, data, message }`.

| Método | Ruta | Función |
|---|---|---|
| `GET` | `/brands?company=Default` | Marcas con sucursales y fondos |
| `GET` | `/stores` | Sucursales |
| `POST` | `/reservation` | Crear una reserva |
| `GET` | `/reservation?brandId=&token=` | Consultar una reserva |
| `POST` | `/reservation/{token}` | Modificar |
| `POST` | `/reservation/cancel/{token}` | Cancelar |

## Seguridad y configuración

- Los secretos viven solo en `.env`, que git ignora. El repositorio incluye únicamente [`.env.example`](.env.example).
- Compose no arranca si falta un secreto, y las imágenes no contienen archivos `.env`.
- Los puertos escuchan solo en `127.0.0.1`.
- En desarrollo, Mailpit captura todos los emails.

## Pruebas

```powershell
docker compose exec backend vendor/bin/phpunit
```

La cobertura automática es mínima: 2 tests de base.

## Estado del proyecto

En desarrollo; **todavía no está listo para producción**.

| Componente | Estado |
|---|---|
| API y panel | Funcionan en Docker |
| Web | Compila a producción; deploy con GitHub Actions ([guía](docs/deploy.md)) |
| App | Corre en modo web; la carga dinámica de marcas está pendiente |

Próximos pasos: actualizar PHP, Laravel y MySQL (sin soporte), corregir el cálculo de capacidad, llevar la marca a la app y ampliar los tests.

## Documentación

| Documento | Contenido |
|---|---|
| [Guía de Docker](docs/docker.md) | Instalación y uso diario |
| [Deploy y release](docs/deploy.md) | GitHub Actions, servidor y Android |
| [Análisis del backend](docs/analysis-backend.md) | Stack, datos, API y hallazgos |
| [Análisis del frontend](docs/analysis-frontend.md) | Stack, rutas y hallazgos |
| [Análisis de mobile](docs/analysis-mobile.md) | Stack, navegación y hallazgos |
| [Guía de marca](docs/brand/brand-guide.md) | Logo, colores y reglas de uso |
| [AGENTS.md](AGENTS.md) | Reglas para contribuir con agentes |

---

<div align="center">
<img src="docs/brand/logos/a-icono-app.svg" alt="" width="36">
<br>
<sub>Desarrollado por <a href="https://github.com/rspirolazzi">@rspirolazzi</a>. Los nombres, teléfonos y emails de las capturas son ficticios.</sub>
</div>
