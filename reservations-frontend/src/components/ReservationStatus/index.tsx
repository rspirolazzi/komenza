import React from 'react';
import Link from 'next/link';

interface ReservationStatusProps {
  notFound: boolean;
  company?: string;
}

const ReservationStatus: React.FC<ReservationStatusProps> = ({ notFound, company }) =>
  notFound ? (
    <div role="alert">
      <p>Esta reserva ya no está disponible. Puede que esté cancelada o finalizada.</p>
      {company && <Link href={`/${company}`}>Hacer una nueva reserva</Link>}
    </div>
  ) : (
    <p role="status">
      <i className="fa fa-spin fa-spinner" aria-hidden="true"></i> Cargando la información. Aguardá por favor
    </p>
  );

export default ReservationStatus;
