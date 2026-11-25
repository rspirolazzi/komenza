import BrandRandomImage from '@/components/BrandRandomImage';
import Footer from '@/components/Footer';
import FormReservation from '@/components/FormReservation';
import { RootState } from '@/store/store';
import { Brand } from '@/types/brand';
import React from 'react';
import { useSelector } from 'react-redux';

const Home: React.FC = () => {
    const selectedBrand = useSelector((state: RootState) => (state.general as any)?.selectedBrand || {});
    const brandName = (selectedBrand as Brand)?.name || '';
    
    

    return (
        <BrandRandomImage>
            <h1>{brandName} - Reserva</h1>
            <div className="bg-agile">
                <div className="book-reservation">
                    <FormReservation />
                </div>
            </div>
            <Footer />
        </BrandRandomImage>
    );
};

export default Home;
