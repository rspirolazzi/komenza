import { useEffect, useState } from 'react';
import { useDispatch } from 'react-redux';
import { useRouter } from 'next/router';
import { AppDispatch } from '@/store/store';
import { fetchReservation } from '@/store/reservation/thunks';
import { Reservation } from '@/types/reservation';
import { useBrandFromRoute } from './useBrandFromRoute';

export const useReservationFromRoute = () => {
  const dispatch = useDispatch<AppDispatch>();
  const { query } = useRouter();
  const { company, token } = query as { company?: string; token?: string };
  const { brand, notFound } = useBrandFromRoute();
  const [reservation, setReservation] = useState<Reservation | null>(null);
  const [failed, setFailed] = useState(false);
  const brandId = brand?.id;

  useEffect(() => {
    if (!brandId || !token) return;
    let active = true;
    setReservation(null);
    setFailed(false);
    dispatch(fetchReservation({ brandId, token }))
      .unwrap()
      .then((data: Reservation) => {
        if (active) setReservation(data);
      })
      .catch(() => {
        if (active) setFailed(true);
      });
    return () => {
      active = false;
    };
  }, [brandId, token, dispatch]);

  return { company, token, brand, reservation, notFound: notFound || failed };
};
