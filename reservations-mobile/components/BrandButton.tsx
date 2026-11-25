import React from 'react';
import { Image, StyleSheet, Text, TouchableOpacity } from 'react-native';

interface BrandButtonProps {
  name: string;
  logo: any;
  disabled?: boolean;
  onPress: () => void;
}

const BrandButton: React.FC<BrandButtonProps> = ({ name, logo, disabled, onPress }) => (
  <TouchableOpacity
    style={styles.brandButton}
    onPress={onPress}
    disabled={disabled}
    accessibilityLabel={`Reservar en ${name}`}
  >
    <Image source={logo} style={styles.logo} resizeMode="contain" />
    <Text style={styles.brandName}>{name}</Text>
  </TouchableOpacity>
);

const styles = StyleSheet.create({
  brandButton: {
    alignItems: 'center',
    marginHorizontal: 16,
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 16,
    elevation: 2,
    shadowColor: '#000',
    shadowOpacity: 0.1,
    shadowRadius: 4,
    shadowOffset: { width: 0, height: 2 },
  },
  logo: {
    width: 100,
    height: 100,
    marginBottom: 12,
  },
  brandName: {
    fontSize: 18,
    fontWeight: '600',
    color: '#334155',
  },
});

export default BrandButton;
