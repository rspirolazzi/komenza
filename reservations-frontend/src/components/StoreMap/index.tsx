import React from 'react';
import { getDirectionsUrl, getStaticMapUrl } from '@/utils/maps';

interface StoreMapProps {
  store: { name: string; address?: string; lat?: string | null; lon?: string | null };
}

const StoreMap: React.FC<StoreMapProps> = ({ store }) => {
  const directionsUrl = getDirectionsUrl(store);
  const mapUrl = getStaticMapUrl(store);

  if (!directionsUrl) return null;

  return (
    <div className="store-map">
      {mapUrl && <img src={mapUrl} alt={`Mapa de ${store.name}`} />}
      <a className="btn btn-primary" href={directionsUrl} target="_blank" rel="noopener noreferrer">
        <i className="fa fa-map-marker-alt" aria-hidden="true"></i> Cómo llegar
      </a>
    </div>
  );
};

export default StoreMap;
