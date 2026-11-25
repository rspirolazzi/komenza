<?php

namespace App\Mail;

use App\Models\Admin\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationCanceled extends Mailable
{
    use Queueable, SerializesModels;

    protected $reservation;

    /**
     * Create a new message instance.
     *
     * @param Reservation $reservation
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $brand = $this->reservation->brand;

        return $this->view('email.canceled')
            ->from(config('mail.from_by_brand.' . $brand->shortName) ?: config('mail.from.address'), $brand->name)
            ->with(['reservation' => $this->reservation])
            ->subject('Tu reserva fue cancelada');
    }
}
