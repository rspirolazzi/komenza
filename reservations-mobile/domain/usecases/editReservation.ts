// Caso de uso para editar una reserva
import { ReservationFormData } from '../../components/ReservationForm';
import ReservationService from '../../services/ReservationService';

export interface EditReservationResult {
  data?: any;
  error?: string;
  validationErrors?: { [key: string]: string[] };
}

export async function editReservation(
  data: ReservationFormData
): Promise<EditReservationResult> {
  try {
    const payload = { ...data };
    const apiResponse = await ReservationService.edit(payload);
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
