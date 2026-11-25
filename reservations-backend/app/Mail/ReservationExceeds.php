<?php

namespace App\Mail;

use App\Models\Admin\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationExceeds extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
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
        return $this->view('email.exceeds')
            ->from(config('mail.from_by_brand.'.$this->reservation->brand->shortName) ?: config('mail.from.address'),$this->reservation->brand->name)
            ->with([
                'reservation'=>$this->reservation,
                'url'=>config('app.url'),
        ]);
    }
}
