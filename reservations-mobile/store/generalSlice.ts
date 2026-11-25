import { createSlice, PayloadAction } from '@reduxjs/toolkit';

interface GeneralState {
  selectedBrand: any;
  brands: any[];
  isFetching: boolean;
}

const initialState: GeneralState = {
  selectedBrand: {},
  brands: [],
  isFetching: false,
};

const generalSlice = createSlice({
  name: 'general',
  initialState,
  reducers: {
    setBrand(state, action: PayloadAction<any>) {
      state.selectedBrand = action.payload;
      state.isFetching = false;
    },
    unsetBrand(state) {
      state.selectedBrand = {};
      state.isFetching = false;
    },
    fetchingBrands(state) {
      state.brands = [];
      state.isFetching = true;
    },
    fetchedBrands(state, action: PayloadAction<any[]>) {
      state.brands = action.payload;
      state.isFetching = false;
    },
    resetGeneral(state) {
      Object.assign(state, initialState);
    },
  },
});

export const {
  setBrand,
  unsetBrand,
  fetchingBrands,
  fetchedBrands,
  resetGeneral,
} = generalSlice.actions;

export default generalSlice.reducer;
