<?php

namespace App\Listeners;

use App\Events\Reservation as ReservationEvent;
use App\Mail\ReservationOk;
use App\Models\Admin\Reservation as ReservationModel;
use App\User;
use Illuminate\Support\Facades\Log;
use Mail;

class Reservation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ReservationEvent $event
     * @return void
     */
    public function handle(ReservationEvent $event)
    {
        //Log::debug('Accion vacia en Listner de Reserva');
    }
}
