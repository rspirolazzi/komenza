import React from 'react';
import { StyleSheet, Text, View } from 'react-native';

interface ReservationCardProps {
  reservation: any;
  onEdit: () => void;
  onCancel: () => void;
  onDelete: () => void;
}

const ReservationCard: React.FC<ReservationCardProps> = ({ reservation, onEdit, onCancel, onDelete }) => (
  <View style={styles.card}>
    <Text style={styles.label}>Nombre: <Text style={styles.value}>{reservation.name}({reservation.token})</Text></Text>
    <Text style={styles.label}>Fecha: <Text style={styles.value}>{reservation.reservation_date}</Text></Text>
    <Text style={styles.label}>Hora: <Text style={styles.value}>{reservation.reservation_time}</Text></Text>
    <Text style={styles.label}>Personas: <Text style={styles.value}>{reservation.people}</Text></Text>
    <Text style={styles.label}>Sucursal: <Text style={styles.value}>{reservation.store?.name}</Text></Text>
    <Text style={styles.label}>Marca: <Text style={styles.value}>{reservation.brand?.name}</Text></Text>
    <View style={{ flexDirection: 'row', marginTop: 8, gap: 8 }}>
      <Text style={styles.editButton} onPress={onEdit}>Modificar</Text>
      <Text style={styles.cancelButton} onPress={onCancel}>Cancelar</Text>
      <Text style={styles.deleteButton} onPress={onDelete}>Eliminar</Text>
    </View>
  </View>
);

const styles = StyleSheet.create({
  card: {
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 16,
    marginBottom: 12,
    elevation: 2,
    shadowColor: '#000',
    shadowOpacity: 0.08,
    shadowRadius: 4,
    shadowOffset: { width: 0, height: 2 },
  },
  label: {
    fontWeight: 'bold',
    color: '#1e293b',
    fontSize: 16,
  },
  value: {
    fontWeight: 'normal',
    color: '#334155',
  },
  editButton: {
    backgroundColor: '#2563eb',
    color: '#fff',
    paddingVertical: 8,
    paddingHorizontal: 16,
    borderRadius: 6,
    fontWeight: 'bold',
    marginRight: 8,
    overflow: 'hidden',
  },
  cancelButton: {
    backgroundColor: '#ef4444',
    color: '#fff',
    paddingVertical: 8,
    paddingHorizontal: 16,
    borderRadius: 6,
    fontWeight: 'bold',
    overflow: 'hidden',
  },
  deleteButton: {
    backgroundColor: '#64748b',
    color: '#fff',
    paddingVertical: 8,
    paddingHorizontal: 16,
    borderRadius: 6,
    fontWeight: 'bold',
    overflow: 'hidden',
  },
});

export default ReservationCard;
