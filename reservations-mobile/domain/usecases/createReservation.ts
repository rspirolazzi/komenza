// Caso de uso para crear una reserva
import { ReservationFormData } from '../../components/ReservationForm';
import ReservationService from '../../services/ReservationService';

export interface CreateReservationResult {
  data?: any;
  error?: string;
  validationErrors?: { [key: string]: string[] };
}

export async function createReservation(
  data: ReservationFormData,
  selectedBrand: { name: string; id: number }
): Promise<CreateReservationResult> {
  try {
    const reservation = `${data.reservation_date} ${data.reservation_time}:00`;
    let payload: any = {
      name: data.name,
      store_id: data.store_id,
      last_name: data.last_name,
      email: data.email,
      mobile: data.mobile,
      birthday: data.birthday,
      reservation_date: data.reservation_date,
      reservation_time: data.reservation_time,
      people: data.people,
      notes: data.notes,
      brand: selectedBrand.name,
      brand_id: selectedBrand.id,
      reservation,
    };
    const apiResponse = await ReservationService.save(payload);
    return { data: { ...payload, ...apiResponse?.data } };
  } catch (error: any) {
    if (
      error.response &&
      error.response.status === 422 &&
      error.response.data &&
      error.response.data.errors
    ) {
      return {
        error: 'Ocurrió un error de validación.',
        validationErrors: error.response.data.errors,
      };
    }
    return {
      error: error.message || 'Ocurrió un error inesperado. Por favor vuelva a intentarlo.',
    };
  }
}
