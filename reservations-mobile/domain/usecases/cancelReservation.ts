// Caso de uso para cancelar una reserva
import ReservationService from '../../services/ReservationService';

export interface CancelReservationResult {
  success?: boolean;
  error?: string;
}

export async function cancelReservation(reservationId: string): Promise<CancelReservationResult> {
  try {
    await ReservationService.cancel(reservationId);
    return { success: true };
  } catch (error: any) {
    return {
      error: error.message || 'Ocurrió un error al cancelar la reserva.'
    };
  }
}
