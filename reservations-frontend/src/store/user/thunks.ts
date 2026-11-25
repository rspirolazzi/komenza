import { createAsyncThunk } from '@reduxjs/toolkit';
import { User } from '@/types/user';
import UserService from '@/services/User';

// Ejemplo: obtener usuario actual
export const fetchUser = createAsyncThunk<User, void, { rejectValue: string }>(
  'user/fetchUser',
  async (_, { rejectWithValue }) => {
    try {
      const response = await UserService.getCurrentUser();
      return response;
    } catch (error: any) {
      return rejectWithValue(error.message || 'Error al obtener usuario');
    }
  }
);

// Ejemplo: repetir última reserva
export const fetchRepeatLastReservation = createAsyncThunk<any, void, { rejectValue: string }>(
  'user/fetchRepeatLastReservation',
  async (_, { rejectWithValue }) => {
    try {
      const response = await UserService.getRepeatLastReservation();
      return response;
    } catch (error: any) {
      return rejectWithValue(error.message || 'Error al obtener última reserva');
    }
  }
);

// Agrega aquí otros thunks según sea necesario
