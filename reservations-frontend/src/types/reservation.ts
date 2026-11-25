export interface Reservation {
  id: number;
  brand: string;
  brand_id: number;
  user?: any;
  [key: string]: any;
}

export interface ReservationState {
  reservations: Reservation[];
  reservation: Reservation | {};
  isFetching: boolean;
  lastError: any;
  lastReservation: Reservation | null;
  updatedAt: Date | null;
  unsavedReservation: Reservation | {};
}

export interface AxiosResponse<T> {
  data: T,
  message: string
  success: boolean;
}