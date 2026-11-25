import { createNativeStackNavigator } from '@react-navigation/native-stack';
import * as React from 'react';
import BrandsScreen from '../screens/BrandsScreen';
import CancelReservationScreen from '../screens/CancelReservationScreen';
import CongratsScreen from '../screens/CongratsScreen';
import EditReservationScreen from '../screens/EditReservationScreen';
import HomeScreen from '../screens/HomeScreen';

const Stack = createNativeStackNavigator();

export default function AppNavigator() {
  return (
    <Stack.Navigator initialRouteName="Brands">
      <Stack.Screen name="Brands" component={BrandsScreen} options={{ title: 'Elige una marca' }} />
      <Stack.Screen name="Home" component={HomeScreen} options={{ title: 'Reserva' }} />
      <Stack.Screen name="EditReservation" component={EditReservationScreen} options={{ title: 'Modificar reserva' }} />
      <Stack.Screen name="CancelReservation" component={CancelReservationScreen} options={{ title: 'Cancelar reserva' }} />
      <Stack.Screen name="Congrats" component={CongratsScreen} options={{ title: '¡Gracias!' }} />
      <Stack.Screen name="ReservationsList" component={require('../screens/ReservationsListScreen').default} options={{ title: 'Mis Reservas' }} />
    </Stack.Navigator>
  );
}
