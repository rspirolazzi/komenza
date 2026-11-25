import { createAsyncThunk } from '@reduxjs/toolkit';
import reservationService from '../../services/Reservation';
import { toApiError } from '../../utils/error';
import {
  unsaveReservation,
  fetchedReservation,
  getReservation as setReservation,
  fetchingReservation,
  errorFetchingReservation,
} from './slice';

export const saveReservation = createAsyncThunk(
  'reservation/save',
  async (data: any, { dispatch, getState, rejectWithValue }) => {
    try {
      dispatch(fetchingReservation());
      dispatch(unsaveReservation(data));
      // @ts-ignore
      const state: any = getState();
      if (!state.general.selectedBrand?.id) {
        throw Error('No se selecciono ninguna marca');
      }
      const user = state.user?.user || {};
      const reservation = {
        ...data,
        brand_id: state.general.selectedBrand.id,
        ...user
      };
      const resp = await reservationService.save(reservation);
      dispatch(fetchedReservation(resp.data));
      return resp.data;
    } catch (error) {
      const apiError = toApiError(error);
      dispatch(errorFetchingReservation(apiError));
      return rejectWithValue(apiError);
    }
  }
);

export const editReservation = createAsyncThunk(
  'reservation/edit',
  async (data: any, { dispatch, getState, rejectWithValue }) => {
    try {
      dispatch(fetchingReservation());
      dispatch(unsaveReservation(data));
      // @ts-ignore
      const state: any = getState();
      if (!state.general.selectedBrand?.id) {
        throw Error('No se selecciono ninguna marca');
      }
      const reservation = {
        ...data,
        brand: state.general.selectedBrand.name,
        brand_id: state.general.selectedBrand.id,
      };
      const resp = await reservationService.edit(reservation);
      dispatch(fetchedReservation(resp.data));
      return resp.data;
    } catch (error) {
      const apiError = toApiError(error);
      dispatch(errorFetchingReservation(apiError));
      return rejectWithValue(apiError);
    }
  }
);

export const cancelReservation = createAsyncThunk(
  'reservation/cancel',
  async (data: any, { dispatch, getState, rejectWithValue }) => {
    try {
      dispatch(fetchingReservation());
      // @ts-ignore
      const state: any = getState();
      if (!state.general.selectedBrand?.id) {
        throw Error('No se selecciono ninguna marca');
      }
      const reservation = {
        ...data,
        brand: state.general.selectedBrand.name,
        brand_id: state.general.selectedBrand.id,
      };
      await reservationService.cancel(reservation);
      // No hay dispatch de fetchedReservation porque no hay respuesta útil
      return true;
    } catch (error) {
      const apiError = toApiError(error);
      dispatch(errorFetchingReservation(apiError));
      return rejectWithValue(apiError);
    }
  }
);

export const fetchReservation = createAsyncThunk(
  'reservation/fetch',
  async ({ brandId, token }: { brandId: number; token: string }, { dispatch, rejectWithValue }) => {
    try {
      dispatch(fetchingReservation());
      const resp = await reservationService.getReservation(brandId, token);
      dispatch(setReservation(resp.data));
      return resp.data;
    } catch (error) {
      const apiError = toApiError(error);
      dispatch(errorFetchingReservation(apiError));
      return rejectWithValue(apiError);
    }
  }
);
