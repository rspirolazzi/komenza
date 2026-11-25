import { createSlice, PayloadAction } from '@reduxjs/toolkit';
import { Brand } from '@/types/brand'; // Debes crear este tipo según tu modelo

interface GeneralState {
  selectedBrand: Brand | {};
  brands: Brand[];
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
    setBrand(state, action: PayloadAction<number>) {
      const found = state.brands.find(b => b.id === action.payload);
      state.selectedBrand = found || {};
      state.isFetching = false;
    },
    unSetBrand(state) {
      state.selectedBrand = {};
      state.isFetching = false;
    },
    fetchingBrands(state) {
      state.brands = [];
      state.isFetching = true;
    },
    fetchedBrands(state, action: PayloadAction<Brand[]>) {
      state.brands = action.payload;
      state.isFetching = false;
    },
    errorFetchingBrands(state) {
      state.isFetching = false;
    },
  },
});

export const { setBrand, unSetBrand, fetchingBrands, fetchedBrands, errorFetchingBrands } = generalSlice.actions;
export default generalSlice.reducer;
