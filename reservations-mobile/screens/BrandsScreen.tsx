import { useNavigation } from '@react-navigation/native';
import React from 'react';
import { ActivityIndicator, StyleSheet, Text, TouchableOpacity, View } from 'react-native';
import { useDispatch } from 'react-redux';

import BrandButton from '../components/BrandButton';
import { setBrand } from '../store/generalSlice';

type Brand = {
  id: number;
  name: string;
  logo: any;
  route: string;
  brandKey: string;
};

const brands: Brand[] = [
  {
    id: 1,
    name: 'Komenza',
    logo: require('../assets/images/logo-komenza.png'),
    route: 'Home',
    brandKey: 'komenza',
  },
  {
    id: 2,
    name: 'Bar Ejemplo',
    logo: require('../assets/images/logo-bar-ejemplo.png'),
    route: 'Home',
    brandKey: 'bar-ejemplo',
  },
];

export default function BrandsScreen() {
  const navigation = useNavigation<any>();
  const dispatch = useDispatch();
  const [loading, setLoading] = React.useState(false);

  const handleBrandPress = (brand: Brand) => {
    setLoading(true);
    dispatch(setBrand(brand));
    setTimeout(() => {
      setLoading(false);
      navigation.navigate(brand.route, { brand: brand.brandKey, brandName: brand.name });
    }, 500);
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Elige una marca para reservar</Text>
      {loading && <ActivityIndicator size="large" color="#1e293b" style={{ margin: 20 }} />}
      <TouchableOpacity
        style={styles.reservationsButton}
        onPress={() => navigation.navigate('ReservationsList')}
      >
        <Text style={styles.reservationsButtonText}>Ver mis reservas</Text>
      </TouchableOpacity>
      <View style={styles.brandsRow}>
        {brands.map((brand) => (
          <BrandButton
            key={brand.id}
            name={brand.name}
            logo={brand.logo}
            disabled={loading}
            onPress={() => handleBrandPress(brand)}
          />
        ))}
      </View>
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
  brandsRow: {
    flexDirection: 'row',
    justifyContent: 'center',
    gap: 24,
  },
  reservationsButton: {
    backgroundColor: '#1e293b',
    paddingVertical: 12,
    paddingHorizontal: 24,
    borderRadius: 8,
    marginBottom: 24,
    alignSelf: 'center',
  },
  reservationsButtonText: {
    color: '#fff',
    fontSize: 18,
    fontWeight: 'bold',
    textAlign: 'center',
  },
});
