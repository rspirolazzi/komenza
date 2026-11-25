import { ReservationPayload } from '@/services/ReservationService';
import { useNavigation } from '@react-navigation/native';
import React from 'react';
import { FlatList, SafeAreaView, StyleSheet, Text } from 'react-native';
import { useDispatch, useSelector } from 'react-redux';
import ReservationCard from '../components/ReservationCard';

export default function ReservationsListScreen() {
  const navigation = useNavigation<any>();
  const dispatch = useDispatch();
  // Obtiene todas las reservas del estado global
  const reservations: ReservationPayload[] = useSelector((state: any) => state.reservation.reservations);

  // Acción inline para eliminar una reserva por índice
  const handleDeleteReservation = (idx: number) => {
    dispatch({ type: 'reservation/removeReservation', payload: idx });
  };

  return (
    <SafeAreaView style={styles.container}>
      <Text style={styles.title}>Mis Reservas</Text>
      {reservations.length === 0 ? (
        <Text style={styles.empty}>No hay reservas realizadas.</Text>
      ) : (
        <FlatList
          data={reservations}
          keyExtractor={(_, idx) => idx.toString()}
          renderItem={({ item, index }) => (
            <ReservationCard
              reservation={item}
              onEdit={() => navigation.navigate('EditReservation', { reservation: item })}
              onCancel={() => navigation.navigate('CancelReservation', { reservation: item })}
              onDelete={() => handleDeleteReservation(index)}
            />
          )}
        />
      )}
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f8fafc',
    padding: 16,
  },
  title: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#1e293b',
    marginBottom: 16,
    textAlign: 'center',
  },
  empty: {
    fontSize: 18,
    color: '#64748b',
    textAlign: 'center',
    marginTop: 32,
  },
});
