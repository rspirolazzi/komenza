import { configureStore } from '@reduxjs/toolkit';
import { persistStore, persistReducer } from 'redux-persist';
import storage from 'redux-persist/lib/storage';
import { combineReducers } from 'redux';
import generalReducer from './general/slice';
import reservationReducer from './reservation/slice';
import userReducer from './user/slice';

const rootReducer = combineReducers({
  general: generalReducer,
  reservation: reservationReducer,
  user: userReducer,
});

const persistConfig = {
  key: 'root',
  storage,
  whitelist: ['general', 'reservation', 'user'], // ajusta según lo que quieras persistir
};

const persistedReducer = persistReducer(persistConfig, rootReducer);

export const store = configureStore({
  reducer: persistedReducer,
});

export const persistor = persistStore(store);

export type RootState = ReturnType<typeof store.getState>;
export type AppDispatch = typeof store.dispatch;
