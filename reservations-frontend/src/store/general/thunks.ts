import { createAsyncThunk } from '@reduxjs/toolkit';
import brandService from '../../services/Brands';
import { toApiError } from '../../utils/error';
import { setBrand, unSetBrand, fetchingBrands, fetchedBrands, errorFetchingBrands } from './slice';

export const fetchBrands = createAsyncThunk(
  'general/fetchBrands',
  async (company: string, { dispatch, rejectWithValue }) => {
    try {
      dispatch(fetchingBrands());
      const data = await brandService.getBrands(company);
      dispatch(fetchedBrands(data.data));
      return data.data;
    } catch (error) {
      dispatch(errorFetchingBrands());
      return rejectWithValue(toApiError(error));
    }
  }
);

export const selectBrand = (brandId: number) => (dispatch: any) => {
  dispatch(unSetBrand());
  dispatch(setBrand(brandId));
};

export const deselectBrand = () => (dispatch: any) => {
  dispatch(unSetBrand());
};
