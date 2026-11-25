interface StoreLocation {
  name?: string;
  address?: string;
  lat?: string | null;
  lon?: string | null;
}

/** Link de Google Maps para llegar a la sucursal; no usa clave. Vacío si no hay ubicación. */
export const getDirectionsUrl = ({ lat, lon, address }: StoreLocation): string => {
  const destination = lat && lon ? `${lat},${lon}` : address;
  return destination ? `https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(destination)}` : '';
};

/** Imagen de Google Static Maps. Vacío si no hay clave o coordenadas. */
export const getStaticMapUrl = ({ lat, lon }: StoreLocation): string => {
  const key = process.env.NEXT_PUBLIC_GOOGLE_MAP_KEY;
  if (!key || !lat || !lon) return '';
  const point = encodeURIComponent(`${lat},${lon}`);
  return `https://maps.googleapis.com/maps/api/staticmap?center=${point}&zoom=16&size=400x300&markers=${point}&key=${encodeURIComponent(key)}`;
};
