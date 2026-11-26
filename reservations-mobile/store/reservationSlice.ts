import { createSlice, PayloadAction } from '@reduxjs/toolkit';

interface ReservationState {
  reservations: any[];
  reservation: any;
  isFetching: boolean;
  lastError: any;
  lastReservation: any;
  updatedAt: string | null;
  unsavedReservation: any;
}

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
    fetchingReservation(state) {
      state.isFetching = true;
    },
    fetchedReservation(state, action: PayloadAction<any>) {
      state.reservations.push(action.payload);
      state.updatedAt = new Date().toISOString();
      state.isFetching = false;
      state.unsavedReservation = {};
      state.lastReservation = action.payload;
    },
    updateReservationById(state, action: PayloadAction<any>) {
      const idx = state.reservations.findIndex(r => r.id === action.payload.id || r.token === action.payload.token);
      if (idx !== -1) {
        state.reservations[idx] = { ...state.reservations[idx], ...action.payload };
      }
    },
    getReservation(state, action: PayloadAction<any>) {
      state.updatedAt = new Date().toISOString();
      state.isFetching = false;
      state.unsavedReservation = {};
      state.reservation = action.payload;
    },
    unsaveReservation(state, action: PayloadAction<any>) {
      state.unsavedReservation = action.payload;
    },
    errorFetchingReservation(state, action: PayloadAction<any>) {
      state.isFetching = false;
      state.lastError = action.payload;
    },
    resetReservation(state) {
      Object.assign(state, initialState);
    },
    removeReservation(state, action: PayloadAction<number>) {
      state.reservations.splice(action.payload, 1);
    },
    removeReservationByToken(state, action: PayloadAction<string>) {
      state.reservations = state.reservations.filter(r => r.token !== action.payload);
    },
  },
});

export const {
  fetchingReservation,
  fetchedReservation,
  updateReservationById,
  getReservation,
  unsaveReservation,
  errorFetchingReservation,
  resetReservation,
  removeReservation,
  removeReservationByToken,
} = reservationSlice.actions;

export default reservationSlice.reducer;
