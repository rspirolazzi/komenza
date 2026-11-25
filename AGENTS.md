# AGENTS.md

**Komenza** (antes "Reservations"): plataforma multi-compañía de reservas de mesas para restaurantes: API y panel (Laravel), web (Next.js) y app (Expo).

## Qué leer

| Para | Documento |
|---|---|
| Levantar todo con Docker | [docs/docker.md](docs/docker.md) |
| Entender el producto, conceptos y reglas | [README.md](README.md) |
| Trabajar en `reservations-backend` | [docs/analysis-backend.md](docs/analysis-backend.md) |
| Trabajar en `reservations-frontend` | [docs/analysis-frontend.md](docs/analysis-frontend.md) |
| Trabajar en `reservations-mobile` | [docs/analysis-mobile.md](docs/analysis-mobile.md) |
| Marca: logo y colores (aplicada en web y panel) | [docs/brand/brand-guide.md](docs/brand/brand-guide.md) |

Lee el README y el análisis del proyecto que vayas a tocar. Las capturas del README están en `docs/screenshots/`.

## Estructura

- Repositorio único (monorepo): git se ejecuta en la raíz. Remoto: `git@github.com:rspirolazzi/komenzar.git`, **público**.
- Solo locales, ignorados por git: `originals/` (código original; no modificar), `docs/private/`, fotos de `public/images/restaurants/` y `.env*`. No forzar su publicación.
- `reservations-mobile/reservations-frontend/`: copia vieja embebida, también ignorada. No usar.

## Comandos

| Proyecto | Arrancar | Verificar |
|---|---|---|
| todos | `docker compose up -d --wait`; ver [docs/docker.md](docs/docker.md) | `docker compose ps` |
| backend | Docker | `vendor/bin/phpunit` en el contenedor (sin fallos) |
| frontend | `npm ci` y `npm run dev` | `npm run build` (hoy falla), `npx tsc --noEmit --incremental false` (22 errores conocidos) |
| mobile | `npm install` y `npm start` | `npx tsc --noEmit` (2 errores conocidos), `npm run lint` |

No empeores esa línea base; actualiza los números si la mejoras.

## Reglas

1. **Preserva el trabajo sin commitear.** No uses `git reset --hard`, `checkout --`, `clean` ni `stash` sin autorización. Revisa `git status` antes de empezar.
2. Cumple las secciones Marca, Estilos y Claves y secretos.
3. Prueba con Docker Compose: Mailpit captura los emails. Sin Docker, usa `MAIL_DRIVER=log`.
4. El backend es compartido: un cambio en `/api` exige revisar web y mobile.
5. Todo dato nuevo debe respetar `company_id`; hoy el aislamiento es parcial.
6. Backend: Laravel 5.6 / PHP 7.2, patrón Controller → Repository + Criteria. No uses sintaxis posterior.
7. Frontend: código nuevo en TypeScript; usa `.tsx`/`.ts` de Redux Toolkit. Un `index.js` junto a un `index.tsx` gana en la resolución: cuidado.
8. Mobile: Expo + React Navigation; lógica en `domain/usecases`, HTTP en `services`.
9. Textos de usuario en español rioplatense; identificadores en inglés.
10. Si cambias comportamiento, versiones o arranque, actualiza el documento correspondiente.

### Marca (obligatorio)

- Todo lo visual o de comunicación de Komenza sigue la [guía de marca](docs/brand/brand-guide.md): isologo A, paleta Albahaca y azafrán, slogan y reglas de uso.
- Usar los SVG de `docs/brand/logos/`; no redibujar ni recolorear el logo. No introducir colores de marca nuevos ni usar las opciones descartadas.
- Respetar el contraste AA; nunca texto blanco sobre Azafrán.
- En páginas de un restaurante manda la marca del cliente; Komenza es firma secundaria.
- Aplicar esta línea en trabajo nuevo. No restilizar lo existente sin que el usuario lo pida. Si algo no está en la guía, consultar.

### Estilos (obligatorio)

- **Sin estilos inline** en código nuevo o modificado: ni `style="..."` en HTML/Blade ni `style={{...}}` en React. Van en hojas CSS/SCSS.
- Si hay framework, usarlo primero: componentes y utilidades de **Bootstrap / react-bootstrap** en frontend, y **AdminLTE / Bootstrap** en el panel. CSS propio solo para lo que no cubren.
- Personalizar Bootstrap con variables Sass o CSS (por ejemplo, en `src/styles/global.scss`), no pisando reglas con `!important`.
- Colores de marca como variables (`--brand-primary`, etc.); no repetir hex por el código. Clases con nombres semánticos, responsive y con foco visible.
- Excepciones:
  - **React Native** no usa CSS: `StyleSheet.create` con tokens en `constants/`, sin objetos literales en `style`.
  - **Emails y PDF**: se admite inline por compatibilidad de clientes de correo y DomPDF.
  - **Valores dinámicos por marca** (color, imagen de fondo): solo asignar variables CSS; la regla vive en la hoja.

### Claves y secretos (obligatorio)

- **Nunca** escribir claves, contraseñas, tokens ni secretos en código, seeds, tests, documentación, Dockerfiles, logs o ejemplos.
- **Nunca** commitearlos. Antes de cada commit, revisar `git diff --cached` y que los archivos de entorno estén ignorados (`git check-ignore .env`).
- Guardarlos en `.env` o en el gestor de secretos del entorno. Versionar solo `.env.example`, con nombres de variables y valores vacíos o ficticios.
- Backend: leer `env()` solo en `config/` y usar `config()` en el resto.
- `NEXT_PUBLIC_*` y `EXPO_PUBLIC_*` quedan **visibles para el usuario**: nunca poner secretos ahí. Las claves públicas de Google Maps se restringen por dominio o app.
- Si aparece un secreto, no copiar su valor: avisar al usuario e indicar archivo y línea. Si llegó a git, debe **rotarse**; borrarlo del último commit no alcanza.
- El repositorio nació sin historial anterior y sin secretos. Los repos viejos sí los tenían: esas credenciales y la clave de Google Maps deben rotarse o restringirse.
- Al ser público, tampoco publicar datos personales: usar emails `@*.example` y nombres ficticios en seeds, tests y capturas.

## Trampas de dominio

- `delivery_times` son horarios de reserva, no delivery. Días `0` (domingo) a `6`.
- `users` contiene clientes (`role` NULL) y administradores; `App\User` y `App\Admin` comparten tabla.
- Crear una reserva pasa por `ReservationFlat` y luego `Reservation`.
- `shortName` (iniciales de la marca) define la variable de remitente `MAIL_FROM_ADDRESS_<shortName>`.

## Cambio de nombre: Reservations → Komenza

Nombre definido el 2026-11-25: **Komenza**. Slogan aprobado: **"Todo comienza en la mesa."** En documentación y textos nuevos, llamar así al producto.
Marca aprobada: isologo A (K con plato) y paleta Albahaca `#0F4C3A` / Azafrán `#F2A93B`; ver la guía de marca.
**Aplicado el 2026-11-25:** textos, config, seeds, colores y logo en web y panel; textos y logos de las marcas en mobile.
**No renombrar directorios, repositorios, bases de datos ni identificadores hasta que el usuario lo pida.**

Relevamiento del 2026-11-25 (puede cambiar):

- Sin registro: `komenza.com.ar`, `komenza.io`. De terceros: `komenza.com`, `komenza.app`.
- Sin apps de iOS con ese nombre en la App Store de Argentina ni productos en la búsqueda web.
- Pendiente: INPI (clases 9, 42 y 43), Google Play, redes sociales y registro de dominios.

Inventario de lo que falta cambiar:

| Dónde | Qué |
|---|---|
| Raíz | Directorios `reservations-*` |
| Backend | Nombre de la base de datos |
| Mobile | `package.json`, `app.json` (name, slug, scheme), íconos, colores, README |

Distinguir tres cosas: **nombre del producto** (pasa a Komenza), **marcas de ejemplo** Komenza y Bar Ejemplo,
que son datos y **entidad de dominio** `Reservation`, tablas y rutas
`/api/reservation` (no son el nombre del producto; por defecto no se renombran).
