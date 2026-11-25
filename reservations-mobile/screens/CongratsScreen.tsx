import { useNavigation, useRoute } from '@react-navigation/native';
import React, { useEffect, useState } from 'react';
import { SafeAreaView, StyleSheet, Text, View } from 'react-native';
import ReservationDetailsBox from '../components/ReservationDetailsBox';
import StoresService from '../services/StoresService';

export default function CongratsScreen() {
  const route = useRoute<any>();
  const navigation = useNavigation<any>();
  const { brandName, reservation, storeId } = route.params || {};
  const [store, setStore] = useState<any>(null);

  useEffect(() => {
    if (storeId) {
      StoresService.getStores().then((stores: any[]) => {
        const found = stores.find(s => s.id == storeId);
        setStore(found);
      });
    }
  }, [storeId]);

  return (
    <SafeAreaView style={styles.container}>
      <Text style={styles.title}>¡Gracias! La reserva se realizó con éxito</Text>
      <ReservationDetailsBox reservation={reservation} store={store} />
      <Text style={styles.subtitle}>Recordá que tenés hasta 15 minutos de tolerancia para no perder la mesa.</Text>
      <View style={{ marginTop: 24 }}>
        <Text
          style={{
            color: '#fff',
            backgroundColor: '#1e293b',
            paddingVertical: 12,
            paddingHorizontal: 24,
            borderRadius: 8,
            fontSize: 18,
            textAlign: 'center',
            overflow: 'hidden',
          }}
          onPress={() => navigation.navigate('Brands')}
        >
          Hacer otra reserva
        </Text>
      </View>
    </SafeAreaView>
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
    textAlign: 'center',
  },
  subtitle: {
    fontSize: 16,
    color: '#64748b',
    textAlign: 'center',
    marginTop: 12,
  },
});
