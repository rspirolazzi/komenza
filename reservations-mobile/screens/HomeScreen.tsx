import { useNavigation, useRoute } from '@react-navigation/native';
import React, { useState } from 'react';
import { ImageBackground, SafeAreaView, StyleSheet, Text } from 'react-native';
import { useDispatch, useSelector } from 'react-redux';
import ErrorMessage from '../components/ErrorMessage';
import ReservationForm, { ReservationFormData } from '../components/ReservationForm';
import ValidationErrors from '../components/ValidationErrors';
import { createReservation } from '../domain/usecases/createReservation';
import { fetchedReservation } from '../store/reservationSlice';

const brandBackgrounds: Record<string, any> = {
  'komenza': require('../assets/images/landing.jpg'),
  'bar-ejemplo': require('../assets/images/landing.jpg'), // Puedes cambiar la imagen si tienes una específica para Bar Ejemplo
};

export default function HomeScreen() {
  const route = useRoute<any>();
  const navigation = useNavigation<any>();
  const { brand, brandName } = route.params || {};
  const backgroundImage = brand ? brandBackgrounds[brand] : undefined;
  const [validationErrors, setValidationErrors] = useState<{ [key: string]: string[] }>({});
  const [errorMessage, setErrorMessage] = useState<string | null>(null);
  // FIX: useSelector must be at the top level, not inside handleReservation
  const selectedBrand = useSelector((state: any) => state.general.selectedBrand);
  const dispatch = useDispatch();

  const handleReservation = async (data: ReservationFormData) => {
    try {
      setValidationErrors({}); // Limpia errores previos
      setErrorMessage(null); // Limpia error visual previo
      const result = await createReservation(data, selectedBrand);
      if (result.data) {
        dispatch(fetchedReservation(result.data));
        navigation.navigate('Congrats', {
          brand: selectedBrand?.name || '',
          brandName,
          reservation: result.data,
          storeId: data.store_id,
        });
      } else {
        dispatch(fetchedReservation({ ...data, status: 'error' }));
        if (result.validationErrors) setValidationErrors(result.validationErrors);
        if (result.error) setErrorMessage(result.error);
      }
    } catch (error) {
      setErrorMessage('Error en la reserva.');
      console.error('Error en la reserva:', error);
    }
  };

  return (
    <ImageBackground
      source={backgroundImage}
      style={styles.background}
      resizeMode="cover"
      imageStyle={{ opacity: 0.18 }}
    >
      <SafeAreaView style={styles.container}>
        <Text style={styles.title}>{brandName ? `${brandName} - Reserva` : 'Reserva'}</Text>
        <ErrorMessage message={errorMessage} />
        <ValidationErrors errors={validationErrors} />
        <ReservationForm onSubmit={handleReservation} />
        <Text style={styles.subtitle}>Pantalla de reserva para la marca: {brand || 'N/A'}</Text>
      </SafeAreaView>
    </ImageBackground>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#f8fafc',
    padding: 16,
  },
  title: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#1e293b',
    marginBottom: 12,
  },
  subtitle: {
    fontSize: 18,
    color: '#64748b',
    textAlign: 'center',
  },
  background: {
    flex: 1,
    width: '100%',
    height: '100%',
  },
});
