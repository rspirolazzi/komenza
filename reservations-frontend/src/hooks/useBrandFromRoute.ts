import { useEffect, useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { useRouter } from 'next/router';
import { AppDispatch, RootState } from '@/store/store';
import { fetchBrands, selectBrand } from '@/store/general/thunks';
import { Brand } from '@/types/brand';
import { brandNameToClass } from '@/utils/products';

/**
 * Resuelve la marca a partir de /[company]/reserva/[marca]. Permite entrar directo, por ejemplo desde un email.
 */
export const useBrandFromRoute = (): { brand: Brand | null; notFound: boolean } => {
  const dispatch = useDispatch<AppDispatch>();
  const { query } = useRouter();
  const { company, marca } = query as { company?: string; marca?: string };
  const selected = useSelector((state: RootState) => (state.general as any)?.selectedBrand as Brand | undefined);
  const [resolvedKey, setResolvedKey] = useState('');
  const [notFound, setNotFound] = useState(false);
  const key = `${company}/${marca}`;

  useEffect(() => {
    if (!company || !marca) return;
    let active = true;
    setNotFound(false);
    dispatch(fetchBrands(company))
      .unwrap()
      .then((brands: Brand[]) => {
        if (!active) return;
        const found = brands.find((item) => brandNameToClass(item.name) === brandNameToClass(marca));
        if (found) {
          dispatch(selectBrand(found.id) as any);
          setResolvedKey(`${company}/${marca}`);
        } else {
          setNotFound(true);
        }
      })
      .catch(() => {
        if (active) setNotFound(true);
      });
    return () => {
      active = false;
    };
  }, [company, marca, dispatch]);

  return { brand: resolvedKey === key && selected?.id ? selected : null, notFound };
};
