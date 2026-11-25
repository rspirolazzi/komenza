import { createSlice, PayloadAction } from '@reduxjs/toolkit';
import { Reservation, ReservationState } from '@/types/reservation';

const initialState: ReservationState = {
  reservations: [],
  reservation: {},
  isFetching: false,
  lastError: null,
  lastReservation: null,
  updatedAt: null,
  unsavedReservation: {},
};

const reservationSlice = createSlice({
  name: 'reservation',
  initialState,
  reducers: {
    unsaveReservation(state, action: PayloadAction<Reservation>) {
      state.unsavedReservation = action.payload;
    },
    fetchedReservation(state, action: PayloadAction<Reservation>) {
      state.reservations.push(action.payload);
      state.updatedAt = new Date();
      state.isFetching = false;
      state.unsavedReservation = {};
      state.lastReservation = action.payload;
    },
    getReservation(state, action: PayloadAction<Reservation>) {
      state.updatedAt = new Date();
      state.isFetching = false;
      state.unsavedReservation = {};
      state.reservation = action.payload;
    },
    fetchingReservation(state) {
      state.isFetching = true;
    },
    errorFetchingReservation(state, action: PayloadAction<any>) {
      state.isFetching = false;
      state.lastError = action.payload;
    },
  },
});

export const {
  unsaveReservation,
  fetchedReservation,
  getReservation,
  fetchingReservation,
  errorFetchingReservation,
} = reservationSlice.actions;
export default reservationSlice.reducer;
