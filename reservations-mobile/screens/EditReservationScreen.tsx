import { useNavigation, useRoute } from '@react-navigation/native';
import React, { useEffect, useState } from 'react';
import { StyleSheet, Text, View } from 'react-native';
import { useDispatch, useSelector } from 'react-redux';
import ErrorMessage from '../components/ErrorMessage';
import ReservationForm, { ReservationFormData } from '../components/ReservationForm';
import ValidationErrors from '../components/ValidationErrors';
import { editReservation } from '../domain/usecases/editReservation';
import StoresService from '../services/StoresService';
import { updateReservationById } from '../store/reservationSlice';

export default function EditReservationScreen() {
  const route = useRoute<any>();
  const navigation = useNavigation<any>();
  const { reservation } = route.params || {};
  const dispatch = useDispatch();
  const [errorMessage, setErrorMessage] = useState<string | null>(null);
  const [successMessage, setSuccessMessage] = useState<string | null>(null);
  const [storeName, setStoreName] = useState<string>('');
  const [validationErrors, setValidationErrors] = useState<{ [key: string]: string[] }>({});
  const selectedBrand = useSelector((state: any) => state.general.selectedBrand);
  const brandName = selectedBrand?.name || reservation?.brand || '';

  useEffect(() => {
    if (selectedBrand?.id) {
      StoresService.getStores().then((stores) => {
        
        const store = stores.find((s: any) => String(s.id) === String(reservation.store_id));
        setStoreName(store ? store.address : '');
      });
    }
  }, [selectedBrand?.id]);

  // Maneja la edición de la reserva
  const handleEditReservation = async (data: ReservationFormData) => {
    setErrorMessage(null);
    setSuccessMessage(null);
    setValidationErrors({});
    try {
      const result = await editReservation(data);
      if (result.data) {
        dispatch(updateReservationById({ ...data, ...result.data }));
        setSuccessMessage('Reserva modificada con éxito');
        setTimeout(() => {
          navigation.goBack();
        }, 1200);
      } else {
        if (result.validationErrors) setValidationErrors(result.validationErrors);
        if (result.error) setErrorMessage(result.error);
      }
    } catch (error) {
      setErrorMessage('Ocurrió un error inesperado.');
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Modificar reserva</Text>
      {brandName || storeName ? (
        <Text style={styles.brandName}>
          {brandName && <Text>{brandName}</Text>}
          {brandName && storeName ? ' - ' : ''}
          {storeName && <Text>{storeName}</Text>}
        </Text>
      ) : null}
      <ErrorMessage message={errorMessage} />
      <ValidationErrors errors={validationErrors} />
      {successMessage && (
        <View style={{ backgroundColor: '#bbf7d0', padding: 10, borderRadius: 8, marginBottom: 10 }}>
          <Text style={{ color: '#166534', fontSize: 14 }}>{successMessage}</Text>
        </View>
      )}
      <ReservationForm onSubmit={handleEditReservation} initialValues={reservation} />
    </View>
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
    fontSize: 24,
    fontWeight: 'bold',
    color: '#1e293b',
    marginBottom: 12,
  },
  brandName: {
    fontSize: 18,
    color: '#64748b',
    marginBottom: 10,
    textAlign: 'center',
  },
});
