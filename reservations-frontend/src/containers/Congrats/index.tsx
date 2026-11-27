import BrandRandomImage from '@/components/BrandRandomImage';
import { RootState } from '@/store/store';
import moment from 'moment';
import 'moment/locale/es';
import Link from 'next/link';
import React from 'react';
import { useSelector } from 'react-redux';
import Footer from '../../components/Footer';
import StoreMap from '../../components/StoreMap';
import { brandNameToClass } from '../../utils/products';
import { useRouter } from 'next/router';

interface CongratsProps {
  token?: string;
}

const Congrats: React.FC<CongratsProps> = ({ token }) => {
  // La API devuelve marca y sucursal como objetos
  const reservation = useSelector((state: RootState) => state.reservation?.lastReservation) as any;
  const store = reservation?.store;
  const brand = reservation?.brand;
  const router = useRouter();
  const { company } = router.query as { company?: string };




  if (!reservation || !store || !brand || !token || reservation.token !== token) {
    //router.replace('/');
    return null;
  }

  const { name } = reservation.user;
  const { status } = reservation;

  if (status === 'maximum_capacity') {
    return (
      <BrandRandomImage>
        <h1>Reserva pendiente de confirmación</h1>
        <div className="bg-agile">
          <div className="book-reservation">
            <div className="congrats-details">
              <p className="reservation-description">Hola {name}, estos son los datos de tu reserva:</p>
              <div className="separator" />
            </div>
            <div className="congrats-data-container">
              <div>
                <div className="congrats-data">
                  <i className="fa fa-calendar" aria-hidden="true"></i>
                  <p>Día {moment(reservation.reservation_date).format('DD [de] MMMM')}</p>
                </div>
                <div className="congrats-data">
                  <i className="fa fa-clock" aria-hidden="true"></i>
                  <p>Horario {reservation.reservation_time}</p>
                </div>
                <div className="congrats-data">
                  <i className="fa fa-map-marker" aria-hidden="true"></i>
                  <p>Dirección {store.address}</p>
                </div>
                <div className="congrats-data">
                  <i className="fa fa-phone" aria-hidden="true"></i>
                  <p>Teléfono {store.telephone}</p>
                </div>
                <div className="congrats-data">
                  <i className="fa fa-users" aria-hidden="true"></i>
                  <p>Para {reservation.people} personas</p>
                </div>
              </div>
              <StoreMap store={store} />
            </div>
            <div><Link href="/" legacyBehavior><a><i className="fa fa-chevron-left"></i> Volver</a></Link></div>
            <p className="congrats-hour">En breve nos estaremos comunicando con vos para confirmar la reserva</p>
          </div>
        </div>
        <Footer />
      </BrandRandomImage>
    );
  }

  return (
    <BrandRandomImage>
      <h1>¡Gracias! La reserva se realizó con éxito</h1>
      <div className="bg-agile">
        <div className="book-reservation">
          <div className="congrats-details">
            <p className="reservation-description">Hola {name}, estos son los datos de tu reserva:</p>
            <div className="separator" />
          </div>
          <div className="congrats-data-container">
            <div>
              <div className="congrats-data">
                <i className="fa fa-calendar" aria-hidden="true"></i>
                <p>Día {moment(reservation.reservation_date).format('DD [de] MMMM')}</p>
              </div>
              <div className="congrats-data">
                <i className="fa fa-clock" aria-hidden="true"></i>
                <p>Horario {reservation.reservation_time}</p>
              </div>
              <div className="congrats-data">
                <i className="fa fa-map-marker" aria-hidden="true"></i>
                <p>Dirección {store.address}</p>
              </div>
              <div className="congrats-data">
                <i className="fa fa-phone" aria-hidden="true"></i>
                <p>Teléfono {store.telephone}</p>
              </div>
              <div className="congrats-data">
                <i className="fa fa-users" aria-hidden="true"></i>
                <p>Para {reservation.people} personas</p>
              </div>
            </div>
            <StoreMap store={store} />
          </div>
          <div><Link href={`/${company}`} ><i className="fa fa-chevron-left"></i> Volver</Link></div>
          <div className="button-actions">
            <Link href={`/${company}/reserva/${brandNameToClass(brand.name)}/modificar/${reservation.token}`} legacyBehavior><a><i className="fa fa-edit"></i> Modificar la reserva</a></Link>
            <Link href={`/${company}/reserva/${brandNameToClass(brand.name)}/cancelar/${reservation.token}`} legacyBehavior><a><i className="fa fa-times"></i> Cancelar la reserva</a></Link>
          </div>
          <p className="congrats-hour">Recordá que tenés hasta 15 minutos de tolerancia para no perder la mesa</p>
        </div>
      </div>
      <Footer />
    </BrandRandomImage>
  );
};

export default Congrats;
