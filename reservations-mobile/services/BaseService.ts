import AsyncStorage from '@react-native-async-storage/async-storage';
import axios from 'axios';
import { Platform } from 'react-native';

// Intenta obtener la variable desde process.env (para desarrollo) o desde Constants.manifest.extra (para producción Expo)
const API_URL = process.env.EXPO_PUBLIC_API_URL

export const ACCESS_TOKEN = 'ACCESS_TOKEN_SESSION';

export class BaseService {
  _URL: string;

  constructor() {
    if (!API_URL) throw new Error('API_URL is not defined in .env or app config');
    this._URL = API_URL;
  }

  async getJWT() {
    if (Platform.OS === 'web') {
      return globalThis?.localStorage?.getItem(ACCESS_TOKEN) || null;
    } else {
      try {
        return await AsyncStorage.getItem(ACCESS_TOKEN);
      } catch (e) {
        console.error('[BaseService] Error getting JWT from AsyncStorage', e);
        return null;
      }
    }
  }
  async setJWT(token: string) {
    if (Platform.OS === 'web') {
      globalThis?.localStorage?.setItem(ACCESS_TOKEN, token);
    } else {
      try {
        await AsyncStorage.setItem(ACCESS_TOKEN, token);
      } catch (e) {
        console.error('[BaseService] Error setting JWT in AsyncStorage', e);
      }
    }
  }
  async delJWT() {
    if (Platform.OS === 'web') {
      globalThis?.localStorage?.removeItem(ACCESS_TOKEN);
    } else {
      try {
        await AsyncStorage.removeItem(ACCESS_TOKEN);
      } catch (e) {
        console.error('[BaseService] Error removing JWT from AsyncStorage', e);
      }
    }
  }

  async postWithJWT(uri: string, params: any = {}) {
    params.token = await this.getJWT();
    try {
      return await axios.post(`${this._URL}${uri}`, params);
    } catch (error) {
      console.error(`[BaseService] POST with JWT error:`, error, { uri, params });
      throw error;
    }
  }
  async getWithJWT(uri: string, params: any = {}) {
    params.token = await this.getJWT();
    try {
      return await axios.get(`${this._URL}${uri}`, { params });
    } catch (error) {
      console.error(`[BaseService] GET with JWT error:`, error, { uri, params });
      throw error;
    }
  }
  async post<T>(uri: string, params: any = {}) {
    try {
      return await axios.post<T>(`${this._URL}${uri}`, params);
    } catch (error: any) {
      if (error.response) {
        // El backend respondió con un status fuera del rango 2xx
        console.error(`[BaseService] POST error:`, {
          uri,
          params,
          status: error.response.status,
          data: error.response.data,
          headers: error.response.headers,
          errors: error.response.errors,
          error: error.response.error,
        });
      } else if (error.request) {
        // No hubo respuesta del backend
        console.error(`[BaseService] POST error: No response received`, {
          uri,
          params,
          request: error.request,
        });
      } else {
        // Otro error
        console.error(`[BaseService] POST error:`, error.message);
      }
      throw error;
    }
  }
  async get<T=any>(uri: string, params: any = {}) {
    try {
      const response = await axios.get<T>(`${this._URL}${uri}`, { params });
      return response.data;
    } catch (error: any) {
      if (error.response) {
        console.error(`[BaseService] GET error:`, {
          uri,
          params,
          status: error.response.status,
          data: error.response.data,
          headers: error.response.headers,
        });
      } else if (error.request) {
        console.error(`[BaseService] GET error: No response received`, {
          uri,
          params,
          request: error.request,
        });
      } else {
        console.error(`[BaseService] GET error:`, error.message);
      }
      throw error;
    }
  }
}
