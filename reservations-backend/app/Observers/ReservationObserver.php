<?php

namespace App\Observers;

use App\Mail\ReservationCanceled;
use App\Models\Admin\Reservation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReservationObserver
{
    /**
     * Avisa al cliente cuando la reserva pasa a cancelada, desde la web, la app o el panel.
     * No avisa por reservas pasadas; deja un día de margen porque la app usa UTC.
     *
     * @param Reservation $reservation
     */
    public function updated(Reservation $reservation)
    {
        if (!$reservation->isDirty('status') || $reservation->status != Reservation::$STATUS_CANCELED) {
            return;
        }

        // Una falla del correo no debe impedir la cancelación
        try {
            $user = $reservation->user;
            if (!$user || !$user->email || $reservation->reservation_date->lt(today()->subDay())) {
                return;
            }
            Mail::to($user->email)->send(new ReservationCanceled($reservation));
        } catch (\Exception $ex) {
            Log::error('No se pudo enviar el email de cancelacion', [
                'reservation_id' => $reservation->id,
                'message' => $ex->getMessage(),
            ]);
        }
    }
}
