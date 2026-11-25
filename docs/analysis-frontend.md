# Komenza: análisis de reservations-frontend

> **Actualización Docker (2026-11-25):** Dockerfile de desarrollo nuevo (Node 22) y `.gitignore` corregido (W11). Ver [docker.md](docker.md). `nginx.conf` quedó sin uso.

> Relevado el 2026-11-25.
> **[V]** = verificado ejecutando sobre una copia.

Web pública donde el cliente final reserva. Es una migración incompleta de Create React App a Next.js.

## 1. Stack

| Componente | Declarado | Instalado **[V]** |
|---|---|---|
| Node.js | sin `engines`; el Dockerfile usa 22 | Probado con 24 |
| Next.js (Pages Router) | ^15.3.3 | 15.3.3 |
| React / React DOM | ^18.3.0 | 18.3.1 |
| TypeScript (`strict: false`) | ^5.8.3 | 5.8.3 |
| @types/react | ^19.1.6 | 19.1.6 (no coincide con React 18) |
| Redux Toolkit | ^2.8.2 | 2.8.2 |
| react-redux | ^9.1.0 | 9.2.0 |
| redux-persist | ^6.0.0 | 6.0.0 |
| Bootstrap / react-bootstrap | ^5.3.6 / ^2.10.10 | 5.3.6 / 2.10.10 |
| Sass | ^1.89.1 | 1.89.1 |
| react-hook-form | ^7.57.0 | 7.57.0 |
| react-datepicker | ^8.4.0 | 8.4.0 |
| axios | ^1.9.0 | 1.9.0 |
| moment | ^2.21.0 | 2.30.1 |
| date-fns (idioma del calendario) | ^4.1.0 | 4.1.0 |

Gestor: npm (`package-lock.json`). Sin base de datos: consume la API del [backend](analysis-backend.md) y
persiste el estado de Redux en `localStorage`. "Cómo llegar" usa un link de Google Maps sin clave; la imagen de Google Static Maps solo aparece si hay clave. También usa Google Fonts y Font Awesome por CDN.
No hay tests ejecutables ni linter.

## 2. Estructura

```
pages/          rutas Next.js
src/components/ UI (FormReservation, Brands, Layout, ...)
src/containers/ pantallas
src/context/    AlertContext (modales)
src/services/   clientes HTTP
src/store/      slices y thunks: general, reservation, user
src/types, src/utils, src/styles
public/         estáticos
```

| Ruta | Pantalla | `next dev` **[V]** |
|---|---|---|
| `/` | Presentación | 200 |
| `/[company]` | Elección de marca | 200 |
| `/[company]/reserva/[marca]` | Reserva en dos pasos | 200 |
| `/[company]/gracias/[token]` | Confirmación | 200 |
| `/[company]/reserva/[marca]/modificar/[token]` | Modificar | Funciona |
| `/[company]/reserva/[marca]/cancelar/[token]` | Cancelar | Funciona |

El flujo completo se probó en navegador: crear, confirmar, modificar y cancelar.

## 3. Instalación y arranque

`.env` en `reservations-frontend`:

```
NEXT_PUBLIC_API_BASE=http://localhost:8000
NEXT_PUBLIC_API_URL=$NEXT_PUBLIC_API_BASE/api/
NEXT_PUBLIC_GOOGLE_MAP_KEY=<clave>
```

```powershell
npm ci
```

```powershell
npm run dev
```

Abrir http://localhost:3000/Default (compañía del seeder) con el backend levantado.

### Resultado de la prueba **[V]**

- `npm ci`: OK.
- `npm run dev`: OK.
- **`npm run build` falla**: error de tipos en `pages/[company]/gracias/[token].tsx:8`. `tsc --noEmit` informa 22 errores.

## 4. Hallazgos

| # | Hallazgo |
|---|---|
| W1 | **No compila a producción [V]** |
| W6 | `user/thunks.ts` llama métodos inexistentes; `utils/alert.tsx` importa `react-confirm-alert`, no instalado |
| W8 | Los horarios se calculan solo en el cliente e incluyen la hora de cierre; no manejan rangos que crucen medianoche |
| W9 | Se perdió la regla "No se permite reservar el 31/12" del historial de git: no está en el DatePicker actual |
| W10 | Restos de CRA: `src/App.js`, `src/index.js`, `registerServiceWorker.js`, `history.js`, `Congrats/_index.js`, README de CRA, `public/index.html` |
| W12 | Datos del cliente (nombre, email, celular, cumpleaños) persisten en `localStorage` |
