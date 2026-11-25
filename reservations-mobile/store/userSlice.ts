import { createSlice, PayloadAction } from '@reduxjs/toolkit';

interface UserState {
  user: any;
  repeatLastReservation: any;
}

const initialState: UserState = {
  user: {},
  repeatLastReservation: {},
};

const userSlice = createSlice({
  name: 'user',
  initialState,
  reducers: {
    fetchUserReservation(state, action: PayloadAction<any>) {
      state.user = action.payload;
    },
    repeatLastReservation(state, action: PayloadAction<any>) {
      state.repeatLastReservation = action.payload;
    },
    resetUser(state) {
      Object.assign(state, initialState);
    },
  },
});

export const {
  fetchUserReservation,
  repeatLastReservation,
  resetUser,
} = userSlice.actions;

export default userSlice.reducer;
