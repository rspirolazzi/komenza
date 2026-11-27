import { createSlice, PayloadAction } from '@reduxjs/toolkit';
import { User, UserState } from '@/types/user';

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
});

export const { setUser, setRepeatLastReservation, clearUser } = userSlice.actions;
export default userSlice.reducer;
