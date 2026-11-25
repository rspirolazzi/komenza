import React, { ReactNode, useEffect, useMemo, useRef } from 'react';
import { useSelector } from 'react-redux';
import { Brand } from '@/types/brand';

interface BrandRandomImageProps {
  children?: ReactNode;
  className?: string;
  /** Si se omite, usa la marca seleccionada; null mientras se resuelve. */
  brand?: Brand | null;
}

const selectSelectedBrand = (state: any): Brand | undefined => state.general.selectedBrand;

const BrandRandomImage: React.FC<BrandRandomImageProps> = ({ children, className = '', brand: routeBrand }) => {
  const selectedBrand = useSelector(selectSelectedBrand);
  const brand = routeBrand === undefined ? selectedBrand : routeBrand;
  const ref = useRef<HTMLDivElement>(null);

  const backgroundImage = useMemo(() => {
    if (brand && Array.isArray(brand.backgrounds) && brand.backgrounds.length > 0) {
      const randomIdx = Math.floor(Math.random() * brand.backgrounds.length);
      return new URL(brand.backgrounds[randomIdx].image, process.env.NEXT_PUBLIC_API_BASE).toString();
    }
    return undefined;
  }, [brand]);

  useEffect(() => {
    if (backgroundImage) {
      ref.current?.style.setProperty('--brand-image', `url("${backgroundImage}")`);
    } else {
      ref.current?.style.removeProperty('--brand-image');
    }
  }, [backgroundImage]);

  return (
    <div ref={ref} className={`brand-page ${backgroundImage ? 'brand-page--image' : ''} ${className}`}>
      {children}
    </div>
  );
};

export default BrandRandomImage;
