import { BaseService } from './BaseService';

// Update ReservationPayload to use backend-expected attribute names
export type ReservationPayload = {
  name: string;
  last_name: string;
  email: string;
  mobile: string;
  birthday: string;
  reservation_date: string;
  reservation_time: string;
  people: number;
  store_id?: number;
  brand_id?: number;
  
  admin_notes?: string;
  allowReservation?: boolean;
  brand?: { logo: string; name: string; shortName: string };
  comment?: string;
  id?: number;
  status?: string;
  status_message?: string;
  store?: { address: string; brand_id: number; lat: string; lon: string; name: string; telephone: string | null; text_schedule: string };
  token?: string;
  user?: { birthday: string; email: string; last_name: string; mobile: string; name: string; telephone: string | null };
};
export type ReservationResponse = {
  data: ReservationPayload,
  "message": "La reserva Flat se guardó con éxito", "success": true
}
class ReservationService extends BaseService {
  async save(data: ReservationPayload) {
    return this.post<ReservationResponse>('reservation', data).then(resp => resp.data);
  }
  async edit(data: ReservationPayload) {
    if (!data.token) throw new Error('Token requerido');
    return this.post<ReservationResponse>(`reservation/${data.token}`, data).then(resp => resp.data);
  }
  async cancel(token: string) {
    return this.post<ReservationResponse>(`reservation/cancel/${token}`).then(resp => resp.data);
  }
  async getReservation(brandId: string, token: string) {
    return this.get<ReservationResponse>('reservation', { brandId, token }).then(resp => resp.data);
  }
}

export default new ReservationService();
