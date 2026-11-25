import React, { useState } from 'react';
import { useDispatch } from 'react-redux';
import { useRouter } from 'next/router';
import moment from 'moment';
import BrandDynamicStyles from '@/components/BrandDynamicStyles';
import BrandRandomImage from '@/components/BrandRandomImage';
import Button from '@/components/Button';
import Footer from '@/components/Footer';
import ReservationStatus from '@/components/ReservationStatus';
import { useAlert } from '@/context/AlertContext';
import { useReservationFromRoute } from '@/hooks/useReservationFromRoute';
import { cancelReservation } from '@/store/reservation/thunks';
import { AppDispatch } from '@/store/store';
import { onErrorToMessage } from '@/utils/reservation';

const CancelarReservation: React.FC = () => {
  const dispatch = useDispatch<AppDispatch>();
  const router = useRouter();
  const alertModal = useAlert();
  const [isSending, setIsSending] = useState(false);
  const { company, token, brand, reservation, notFound } = useReservationFromRoute();

  const handleCancel = () => {
    setIsSending(true);
    dispatch(cancelReservation({ token }))
      .unwrap()
      .then(() => alertModal('', 'Tu reserva fue cancelada.', () => router.push(`/${company}`)))
      .catch((error: any) => {
        setIsSending(false);
        alertModal('', onErrorToMessage(error));
      });
  };

  return (
    <BrandRandomImage brand={brand}>
      {brand && <BrandDynamicStyles />}
      <h1>{brand ? `${brand.name} - ` : ''}Cancelar la reserva</h1>
      <div className="bg-agile">
        <div className="book-reservation">
          {reservation ? (
            <>
              <p className="reservation-description">
                {reservation.user?.name}, vas a cancelar tu reserva en {reservation.store?.name} del{' '}
                {moment(reservation.reservation_date).format('DD [de] MMMM')} a las {reservation.reservation_time} hs
                para {reservation.people} personas.
              </p>
              <Button type="button" variant="danger" disabled={isSending} onClick={handleCancel}>
                Cancelar la reserva
              </Button>
            </>
          ) : (
            <ReservationStatus notFound={notFound} company={company} />
          )}
        </div>
      </div>
      <Footer />
    </BrandRandomImage>
  );
};

export default CancelarReservation;
