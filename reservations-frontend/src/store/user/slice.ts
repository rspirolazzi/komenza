import { createSlice, PayloadAction } from '@reduxjs/toolkit';
import { User, UserState } from '@/types/user';
import { fetchUser, fetchRepeatLastReservation } from './thunks';

const initialState: UserState = {
  user: null,
  repeatLastReservation: null,
  isFetching: false,
  error: null,
};

const userSlice = createSlice({
  name: 'user',
  initialState,
  reducers: {
    setUser(state, action: PayloadAction<User | null>) {
      state.user = action.payload;
    },
    setRepeatLastReservation(state, action: PayloadAction<any>) {
      state.repeatLastReservation = action.payload;
    },
    clearUser(state) {
      state.user = null;
      state.repeatLastReservation = null;
    },
  },
  extraReducers: (builder) => {
    builder
      .addCase(fetchUser.pending, (state) => {
        state.isFetching = true;
        state.error = null;
      })
      .addCase(fetchUser.fulfilled, (state, action) => {
        state.isFetching = false;
        state.user = action.payload;
      })
      .addCase(fetchUser.rejected, (state, action) => {
        state.isFetching = false;
        state.error = action.payload as string || 'Error al obtener usuario';
      })
      .addCase(fetchRepeatLastReservation.pending, (state) => {
        state.isFetching = true;
        state.error = null;
      })
      .addCase(fetchRepeatLastReservation.fulfilled, (state, action) => {
        state.isFetching = false;
        state.repeatLastReservation = action.payload;
      })
      .addCase(fetchRepeatLastReservation.rejected, (state, action) => {
        state.isFetching = false;
        state.error = action.payload as string || 'Error al obtener última reserva';
      });
  },
});

export const { setUser, setRepeatLastReservation, clearUser } = userSlice.actions;
export default userSlice.reducer;
