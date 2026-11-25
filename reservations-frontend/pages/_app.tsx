import React from 'react';
import type { AppProps } from 'next/app';
import { Provider } from 'react-redux';
import { PersistGate } from 'redux-persist/integration/react';
import { store, persistor } from '@/store/store';
import Layout from '../src/components/Layout';
import '@/styles/global.scss';
import '@/styles/site.css';
import '@/components/DatePicker/datepicker.css';
import 'moment/locale/es';
import { registerLocale } from 'react-datepicker';
import { es } from 'date-fns/locale';
import { AlertProvider } from '@/context/AlertContext';

registerLocale('es', es);

function MyApp({ Component, pageProps }: AppProps) {
  return (
    <AlertProvider>
      <Provider store={store}>
        <PersistGate loading={null} persistor={persistor}>
          <Layout>
            <Component {...pageProps} />
          </Layout>
        </PersistGate>
      </Provider>
    </AlertProvider>
  );
}

export default MyApp;
