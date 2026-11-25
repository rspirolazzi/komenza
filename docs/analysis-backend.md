# Komenza: análisis de reservations-backend

> La guía vigente para levantar el proyecto es [docker.md](docker.md).

> Relevado el 2026-11-25.
> **[V]** = verificado ejecutando en contenedores descartables. Sin marca = leído en el código.

API REST pública y panel de administración (server-rendered) en una única aplicación Laravel.

## 1. Stack

| Componente | Versión / detalle |
|---|---|
| Lenguaje | PHP: `>=7.1.3` en `composer.json`; imagen Docker `php:7.2-apache`, fuera de soporte |
| Framework | Laravel 5.6.39, fuera de soporte |
| Base de datos | MySQL 5.7 (`mysql:5.7.44`) **[V]** |
| Gestor de paquetes | Composer 1.10, respeta `composer.lock` |
| Vistas | Blade + AdminLTE (`infyomlabs/adminlte-templates`) |
| Assets | Laravel Mix 2 / webpack, Bootstrap 4, jQuery 3, Vue 2 (sin lockfile) |
| Tests | PHPUnit 7 |
| Debug | Xdebug 3.1.6 |

### Paquetes principales

| Paquete | Versión | Uso |
|---|---|---|
| infyomlabs/laravel-generator | 5.6.x-dev | Scaffolding de CRUD |
| prettus/l5-repository | 2.6.32 | Repository + Criteria |
| owen-it/laravel-auditing | 6.1.1 | Auditoría de modelos |
| tymon/jwt-auth | dev-develop | JWT; configurado pero sin rutas que lo usen |
| barryvdh/laravel-dompdf | 0.8.3 | PDF |
| jimmyjs/laravel-report-generator | 1.0.25 | Reportes |
| dirape/token | 2.1 | Token único de cada reserva |
| jenssegers/date, laraveles/spanish | 3.4.0 / 1.1.1 | Fechas y textos en español |
| swiftmailer/swiftmailer | 6.1.3 | Email |
| maatwebsite/excel | 2.1.30 | Excel (dependencia transitiva) |

Cache y sesión: archivos. Cola: `sync`. Redis y Pusher figuran en `.env` pero no son necesarios.

## 2. Estructura

```
app/
  Http/Controllers/API/     API pública
  Http/Controllers/Admin/   panel
  Http/Middleware/          AuthAdmin, CheckRoleStore, CORS, authJWT
  Models/Admin/             Company, Brand, BrandBackground, Store, Reservation, ReservationFlat
  Admin.php, User.php       ambos sobre la tabla users
  Repositories/             API; Repositories/Admin = panel (filtra por company)
  Criteria/                 filtros de consulta
  Mail/                     ReservationOk, ReservationExceeds, ReservationCanceled (la envía Observers/ReservationObserver), MailTest
config/komenza.php        reglas de reservas múltiples
database/migrations, seeds
resources/views/            panel, emails, PDF
routes/api.php, web.php
```

## 3. Modelo de datos

```
companies 1─N brands 1─N stores 1─N reservations N─1 users (cliente)
              └─N brand_backgrounds        └─ admin_id → users
```

| Tabla | Campos relevantes |
|---|---|
| companies | name (único). Es el tenant |
| brands | name, logo, styles (JSON: `primary`, `secondary`), company_id |
| brand_backgrounds | brand_id, image |
| stores | brand_id, address, telephone, lat/lon, text_schedule, delivery_times (JSON), max_persons (9), max_persons_day (40), company_id |
| users | Clientes (`role` NULL) y administradores; role ∈ role_admin, role_brand, role_store; company_id |
| reservations | user, store, brand, company, people, fecha, hora, comment, admin_notes, token, status, admin_id |
| reservation_flats | Copia cruda de la solicitud recibida |
| audits | Auditoría |

Estados: `open`, `canceled`, `attended`, `not_attended`, `s/e`, `maximum_capacity`.

## 4. API pública

Base `/api`, sin autenticación, 60 requests/min, CORS `*`. Respuesta: `{success, data, message}`.

| Método | Ruta | Función |
|---|---|---|
| GET | `/brands?company=<nombre>` | Marcas con sucursales y fondos |
| GET | `/stores` | Todas las sucursales (no filtra por company) |
| POST | `/reservation` | Crear reserva |
| GET | `/reservation?brandId=&token=` | Consultar (solo `open` o `maximum_capacity`) |
| POST | `/reservation/{token}` | Modificar |
| POST | `/reservation/cancel/{token}` | Cancelar |

Campos obligatorios al crear: `name, last_name, email, birthday, mobile, brand_id, store_id, people, reservation_date, reservation_time, reservation`.

## 5. Panel (`/admin`)

Login en `/login` con **compañía + email + contraseña** (guard `admin`, sesión).

| Rol | Menú |
|---|---|
| role_admin | Marcas, sucursales, clientes, administradores, reservas, reportes, auditoría |
| role_brand | Sucursales, clientes, administradores, reservas, reportes |
| role_store | Reservas |

`check.role.store` solo redirige a `role_store`; las demás diferencias son visuales.
Los usuarios del seeder se definen en `database/seeds/UsersTableSeeder.php`.

## 6. Instalación y arranque

Con Docker: [docker.md](docker.md).

### Tests **[V]**

`vendor/bin/phpunit` ejecuta 2 tests de ejemplo: ambos pasan.
Los tests de marcas, sucursales y reservas en la raíz de `tests/` no se ejecutan.

## 7. Hallazgos

### Funcionales

| # | Hallazgo |
|---|---|
| F1 | **Capacidad contada dos veces [V]**: la suma ya incluye la reserva recién creada y se le vuelve a sumar. Una reserva de 30 personas con máximo 40 y local vacío queda `maximum_capacity` |
| F2 | Al modificar, `maximum_capacity` nunca vuelve a `open` aunque haya lugar **[V]** (se envía el email de confirmación) |
| F3 | `max_persons_day` no está en `fillable` (lo cargado en el panel se ignora) y `Store::toArray()` devuelve siempre 50 |
| F4 | Modificar con token inexistente devuelve 500 **[V]**: se hace `fill()` antes de validar null |
| F5 | La API no valida horario de atención, fecha pasada ni `max_persons` **[V]** (aceptó las 03:00 y 30 personas) |
| F6 | Al crear, la respuesta trae `status: null` para una reserva normal **[V]** |
| F7 | `comment` no está en `fillable` de ReservationFlat: se pierde al crear |
| F8 | `users` no tiene `store_id` ni `brand_id`, pero `ReservationAuthCriteria` filtra por ellos: los roles de marca y sucursal no verían reservas |
| F9 | El ABM de administradores no asigna rol ni compañía (`fillable`) y no hashea la contraseña |
| F11 | `ReservationExceeds` solo se envía al modificar, no al crear |
| F12 | Cliente identificado por email **o** celular **[V]**: compartir celular asocia la reserva a otra persona |
| F14 | El control de capacidad no es transaccional: posible sobreventa con pedidos simultáneos |
