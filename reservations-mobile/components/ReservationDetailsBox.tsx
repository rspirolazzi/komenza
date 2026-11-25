import React from 'react';
import { StyleSheet, Text, View } from 'react-native';

interface ReservationDetailsBoxProps {
  reservation: any;
  store?: any;
}

const ReservationDetailsBox: React.FC<ReservationDetailsBoxProps> = ({ reservation, store }) => (
  <View style={styles.detailsBox}>
    <Text style={styles.label}>Hola {reservation?.name}, estos son los datos de tu reserva:</Text>
    <View style={styles.separator} />
    <Text style={styles.detail}><Text style={styles.bold}>Día:</Text> {reservation?.reservation_date}</Text>
    <Text style={styles.detail}><Text style={styles.bold}>Horario:</Text> {reservation?.reservation_time}</Text>
    {store && <>
      <Text style={styles.detail}><Text style={styles.bold}>Dirección:</Text> {store.address}</Text>
      <Text style={styles.detail}><Text style={styles.bold}>Teléfono:</Text> {store.telephone}</Text>
    </>}
    <Text style={styles.detail}><Text style={styles.bold}>Personas:</Text> {reservation?.people}</Text>
  </View>
);

const styles = StyleSheet.create({
  detailsBox: {
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 20,
    marginBottom: 16,
    width: '100%',
    maxWidth: 400,
    elevation: 2,
    shadowColor: '#000',
    shadowOpacity: 0.08,
    shadowRadius: 4,
    shadowOffset: { width: 0, height: 2 },
  },
  label: {
    fontSize: 18,
    fontWeight: '500',
    marginBottom: 8,
    color: '#1e293b',
  },
  separator: {
    height: 1,
    backgroundColor: '#e5e7eb',
    marginVertical: 8,
  },
  detail: {
    fontSize: 16,
    marginBottom: 4,
    color: '#334155',
  },
  bold: {
    fontWeight: 'bold',
    color: '#1e293b',
  },
});

export default ReservationDetailsBox;
