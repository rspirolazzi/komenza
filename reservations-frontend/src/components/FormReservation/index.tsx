import React, { useState } from 'react';
import { useRouter } from 'next/router';
import FormPersonalInfo from './FormPersonalInfo';
import FormStoreReservation from './FormStoreReservation';

const FormReservation: React.FC = () => {
    const [showFormPersonalInfo, setShowFormPersonalInfo] = useState(true);
    const router = useRouter();

    const nextForm = () => {
        setShowFormPersonalInfo(false);
    };
    const previousForm = () => {
        setShowFormPersonalInfo(true);
    };
    const redirect = (token:string) => {
        const { company } = router.query as { company?: string };
        router.push(`/${company}/gracias/${token}`);
    };

    if (showFormPersonalInfo) {
        return <FormPersonalInfo onContinue={nextForm} />;
    } else {
        return <FormStoreReservation isEditable={false} goBack={previousForm} handleRedirect={redirect} title={'estas a punto de finalizar tu reserva'} />;
    }
};

export default FormReservation;