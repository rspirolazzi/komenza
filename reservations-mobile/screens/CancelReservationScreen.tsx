import React, { useState } from 'react';
import { Button, StyleSheet, Text, View } from 'react-native';
import { useDispatch } from 'react-redux';
import ErrorMessage from '../components/ErrorMessage';
import { cancelReservation } from '../domain/usecases/cancelReservation';
import { removeReservationByToken } from '../store/reservationSlice';

export default function CancelReservationScreen({ route, navigation }: any) {
  const { reservation } = route?.params || {};
  const dispatch = useDispatch();
  const [errorMessage, setErrorMessage] = useState<string | null>(null);
  const [successMessage, setSuccessMessage] = useState<string | null>(null);
  const [loading, setLoading] = useState(false);

  const handleCancel = async () => {
    setErrorMessage(null);
    setSuccessMessage(null);
    setLoading(true);
    try {
      const result = await cancelReservation(reservation?.token);
      if (result.success) {
        dispatch(removeReservationByToken(reservation.token));
        setSuccessMessage('Reserva cancelada con éxito');
        setTimeout(() => {
          navigation.goBack();
        }, 1200);
      } else {
        setErrorMessage(result.error || 'No se pudo cancelar la reserva.');
      }
    } catch (error) {
      setErrorMessage('Ocurrió un error inesperado.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Cancelar reserva</Text>
      <ErrorMessage message={errorMessage} />
      {successMessage && (
        <View style={{ backgroundColor: '#bbf7d0', padding: 10, borderRadius: 8, marginBottom: 10 }}>
          <Text style={{ color: '#166534', fontSize: 14 }}>{successMessage}</Text>
        </View>
      )}
      <Button title={loading ? 'Cancelando...' : 'Cancelar reserva'} onPress={handleCancel} disabled={loading || !!successMessage} />
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
});
