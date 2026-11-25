import React, { useEffect, useMemo, useRef } from 'react';
import { Brand } from '@/types/brand';

interface BrandCardProps {
  brand: Brand;
  onSelect: (brand: Brand) => void;
}

const toUrl = (path: string) => new URL(path, process.env.NEXT_PUBLIC_API_BASE).toString();

const BrandCard: React.FC<BrandCardProps> = ({ brand, onSelect }) => {
  const ref = useRef<HTMLButtonElement>(null);
  const hasStores = Array.isArray(brand.stores) && brand.stores.length > 0;

  const backgroundUrl = useMemo(() => {
    const { backgrounds } = brand;
    if (!backgrounds || backgrounds.length === 0) return undefined;
    return toUrl(backgrounds[Math.floor(Math.random() * backgrounds.length)].image);
  }, [brand]);

  useEffect(() => {
    if (backgroundUrl) ref.current?.style.setProperty('--brand-image', `url("${backgroundUrl}")`);
  }, [backgroundUrl]);

  return (
    <button
      ref={ref}
      type="button"
      disabled={!hasStores}
      className={`brand-card ${backgroundUrl ? 'brand-card--image' : ''}`}
      onClick={() => onSelect(brand)}
    >
      {brand.logo ? (
        <img className="brand-card__logo" src={toUrl(brand.logo)} alt={brand.name} />
      ) : (
        <span className="brand-card__name">{brand.name}</span>
      )}
    </button>
  );
};

export default BrandCard;
