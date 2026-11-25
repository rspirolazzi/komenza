import React from 'react';
import { useRouter } from 'next/router';
import BrandDynamicStyles from '@/components/BrandDynamicStyles';
import BrandRandomImage from '@/components/BrandRandomImage';
import Footer from '@/components/Footer';
import FormStoreReservation from '@/components/FormReservation/FormStoreReservation';
import ReservationStatus from '@/components/ReservationStatus';
import { useReservationFromRoute } from '@/hooks/useReservationFromRoute';

const EditReservation: React.FC = () => {
  const router = useRouter();
  const { company, token, brand, reservation, notFound } = useReservationFromRoute();

  return (
    <BrandRandomImage brand={brand}>
      {brand && <BrandDynamicStyles />}
      <h1>{brand ? `${brand.name} - ` : ''}Modificar la reserva</h1>
      <div className="bg-agile">
        <div className="book-reservation">
          {reservation ? (
            <FormStoreReservation
              isEditable
              reservation={reservation}
              handleRedirect={() => router.push(`/${company}/gracias/${token}`)}
              title="estás a punto de modificar tu reserva"
            />
          ) : (
            <ReservationStatus notFound={notFound} company={company} />
          )}
        </div>
      </div>
      <Footer />
    </BrandRandomImage>
  );
};

export default EditReservation;
