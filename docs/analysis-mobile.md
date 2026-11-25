# Komenza: análisis de reservations-mobile

> **Actualización Docker (2026-11-25):** corre en modo web con Docker (instalación limpia y pantalla inicial verificadas; `/` responde 404 pero la app carga, ver M4). Ver [docker.md](docker.md).

> Relevado el 2026-11-25. **[V]** = verificado ejecutando.

App móvil del cliente final, portada desde la web. Es el proyecto menos avanzado en multi-compañía.

## 1. Stack

| Componente | Declarado | Instalado **[V]** |
|---|---|---|
| Expo SDK | ~53.0.9 | 53.0.9 |
| React Native (New Architecture) | 0.79.2 | 0.79.2 |
| React | 19.0.0 | 19.0.0 |
| TypeScript (`strict: true`) | ~5.8.3 | 5.8.3 |
| expo-router (solo punto de entrada) | ~5.0.6 | 5.0.7 |
| React Navigation native-stack (navegación real) | ^7.3.14 | — |
| Redux Toolkit / react-redux | ^2.8.2 / ^9.2.0 | 2.8.2 / 9.2.0 |
| redux-persist + AsyncStorage | ^6.0.0 / ^2.1.2 | 6.0.0 / — |
| react-hook-form | ^7.57.0 | 7.57.0 |
| axios | ^1.9.0 | 1.9.0 |
| ESLint (expo) | ^9.25.0 | — |

Plataformas: Android, iOS (requiere Mac) y web. Gestor: npm (`package-lock.json`).
Sin base de datos propia: API del [backend](analysis-backend.md) y AsyncStorage. Sin tests.

## 2. Estructura

```
app/_layout.tsx     entrada: Redux + tema + AppNavigator
components/         AppNavigator, ReservationForm, tarjetas, UI
screens/            Brands, Home, Congrats, EditReservation, CancelReservation, ReservationsList
domain/usecases/    crear, editar, cancelar
services/           clientes HTTP
store/              slices general, reservation, user
```

Navegación: `Brands → Home (formulario en 2 pasos) → Congrats`; `Brands → ReservationsList → Edit / Cancel`.
"Mis reservas" lista únicamente lo guardado en el dispositivo.

## 3. Instalación y arranque

`.env` (para el emulador de Android, `10.0.2.2` es la máquina anfitriona):

```
EXPO_PUBLIC_API_URL=http://10.0.2.2:8000/api/
```

En iOS o web usar `http://localhost:8000/api/`; en un dispositivo físico, la IP de la máquina en la red.

```powershell
npm install
```

```powershell
npm start
```

También `npm run android`, `npm run ios`, `npm run web` y `npm run lint`.

### Resultado de la prueba **[V]**

- `expo export --platform web`: OK, con `/+not-found` como única ruta estática (no existe `app/index.tsx`).
- `tsc --noEmit`: 2 errores (M1 y M3).
- No se probó una instalación limpia ni la ejecución en emulador, dispositivo o navegador.

## 4. Hallazgos

| # | Hallazgo |
|---|---|
| M1 | **Cancelar está roto [V por tsc]**: el caso de uso pasa un string y el servicio exige un objeto con `token`. Además la lista envía `reservation` y la pantalla lee `reservationId` |
| M2 | Marcas fijas en el código (ids 1 y 2, Komenza y Bar Ejemplo): no consulta `/brands` ni contempla compañía |
| M3 | `App.tsx` importa `navigation/AppNavigator`, inexistente **[V por tsc]**; no se usa, la entrada es `expo-router/entry` |
| M4 | Sin `app/index.tsx`: depende de que `_layout` renderice su propio navegador. Confirmar en dispositivo |
| M5 | Envía `notes`; el backend espera `comment`, así que se pierde |
| M6 | Personas fijo de 1 a 10: ignora `max_persons` |
| M7 | "Continuar" no valida el paso 1; los errores de ese paso quedan ocultos en el paso 2 |
| M8 | Un fallo al crear se guarda igualmente en "Mis reservas" con `status: 'error'` |
| M9 | `sendError` responde HTTP 404 con `message`, pero los casos de uso muestran `error.message` de axios: el usuario no ve el motivo del rechazo |
| M10 | Congrats siempre dice "éxito", también con `maximum_capacity` |
| M11 | Fechas vía `toISOString()` y `new Date('YYYY-MM-DD')` (UTC): en Argentina puede correrse el día |
| M12 | Cumpleaños es texto libre; el token se muestra en las tarjetas |
| M13 | "Eliminar" solo borra del dispositivo: la reserva sigue vigente |
| M16 | No hay configuración de build de tiendas: faltan `eas.json`, `bundleIdentifier` y `package` |
