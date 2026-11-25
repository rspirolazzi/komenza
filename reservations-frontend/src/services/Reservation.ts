import { AxiosResponse, Reservation } from '@/types/reservation';
import Base from './Base';

class ReservationService extends Base {
    save(data: Record<string, any>): Promise<AxiosResponse<Reservation>> {
        try {
            return this.post<AxiosResponse<Reservation>>('reservation', data).then(resp => resp);
        } catch (e) {
            console.error(e);
            throw e;
        }
    }
    edit(data: Record<string, any>): Promise<any> {
        try {
            const { token } = data;
            if (token) {
                return this.post(`reservation/${token}`, data).then(resp => resp);
            } else {
                throw Error('Es necesario enviar el token');
            }
        } catch (e) {
            console.error(e);
            throw e;
        }
    }
    cancel(data: Record<string, any>): Promise<any> {
        try {
            const { token } = data;
            if (token) {
                return this.post(`reservation/cancel/${token}`, data).then(resp => resp);
            } else {
                throw Error('Es necesario enviar el token');
            }
        } catch (e) {
            console.error(e);
            throw e;
        }
    }
    getReservation(brandId: number, token: string): Promise<any> {
        try {
            return this.get('reservation', { brandId, token })
                .then(resp => resp);
        } catch (e) {
            console.error(e);
            throw e;
        }
    }
}

export default new ReservationService();
