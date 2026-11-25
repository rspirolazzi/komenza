import React, { useEffect } from 'react';
import Footer from '../../components/Footer';
import moment from 'moment';
import 'moment/locale/es';
import { useSelector } from 'react-redux';
import { useRouter } from 'next/router';
import { brandNameToClass } from '../../utils/products';
import Link from 'next/link';
import { RootState } from '@/store/store';

const GOOGLE_MAP_KEY = process.env.NEXT_PUBLIC_GOOGLE_MAP_KEY || '';

const Congrats: React.FC = () => {
  const router = useRouter();
  const reservation = useSelector((state: RootState) => state.reservation?.lastReservation?.data);
  const store = reservation?.store;
  const brand = reservation?.brand;

  useEffect(() => {
    if (reservation && brand) {
      const brandNameClass = brandNameToClass(brand.name);
      if (brandNameClass) {
        document.body.classList.add(brandNameClass);
        return () => document.body.classList.remove(brandNameClass);
      }
    }
  }, [reservation, brand]);

  if (!reservation || !store || !brand) {
    router.replace('/');
    return null;
  }

  const { name } = reservation.user;
  const width = 400, height = 300, zoom = 16;
  const { lat, lon } = store;
  const { status } = reservation;

  const mapUrl = `https://maps.googleapis.com/maps/api/staticmap?center=${lat},${lon}&zoom=${zoom}&scale=false&size=${width}x${height}&maptype=roadmap&format=png&visual_refresh=true&markers=size:mid%7Ccolor:0xff0000%7Clabel:${store.name.substr(0, 1).toString().toUpperCase()}%7C${lat},${lon}&key=${GOOGLE_MAP_KEY}`;

  if (status === 'maximum_capacity') {
    return (
      <div>
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
              <div>
                <div>
                  <img src={mapUrl} alt={store.name} />
                </div>
              </div>
            </div>
            <div><Link href="/" legacyBehavior><a><i className="fa fa-chevron-left"></i> Volver</a></Link></div>
            <p className="congrats-hour">En breve nos estaremos comunicando con vos para confirmar la reserva</p>
          </div>
        </div>
        <Footer />
      </div>
    );
  }

  return (
    <div>
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
            <div>
              <div>
                <img src={mapUrl} alt={store.name} />
              </div>
            </div>
          </div>
          <div><Link href="/" legacyBehavior><a><i className="fa fa-chevron-left"></i> Volver</a></Link></div>
          <div className="button-actions">
            <Link href={`/reserva/${brandNameToClass(brand.name)}/modificar/${reservation.token}`} legacyBehavior><a><i className="fa fa-edit"></i> Modificar la reserva</a></Link>
            <Link href={`/reserva/${brandNameToClass(brand.name)}/cancelar/${reservation.token}`} legacyBehavior><a><i className="fa fa-times"></i> Cancelar la reserva</a></Link>
          </div>
          <p className="congrats-hour">Recordá que tenés hasta 15 minutos de tolerancia para no perder la mesa</p>
        </div>
      </div>
      <Footer />
    </div>
  );
};

export default Congrats;