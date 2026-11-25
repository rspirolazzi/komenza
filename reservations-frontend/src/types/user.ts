// Tipos para el usuario y el estado de user

export interface User {
  id: string;
  name: string;
  last_name: string;
  email: string;
  mobile: string;
  birthday: string;
  // Agrega aquí otros campos relevantes del usuario
}

export interface UserState {
  user: User | null;
  repeatLastReservation?: any; // Ajustar tipo si es necesario
  isFetching: boolean;
  error?: string | null;
}
