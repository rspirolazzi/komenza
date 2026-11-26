// Caso de uso para cancelar una reserva
import ReservationService from '../../services/ReservationService';

export interface CancelReservationResult {
  success?: boolean;
  error?: string;
}

export async function cancelReservation(token?: string): Promise<CancelReservationResult> {
  if (!token) {
    return { error: 'No encontramos los datos de la reserva.' };
  }
  try {
    await ReservationService.cancel(token);
    return { success: true };
  } catch (error: any) {
    return {
      error: error.message || 'Ocurrió un error al cancelar la reserva.'
    };
  }
}
