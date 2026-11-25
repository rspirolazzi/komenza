<?php

namespace App\Events;

use App\Models\Admin\Reservation as ReservationModel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Reservation
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /* @var ReservationModel */
    protected $reservation;

    /**
     * Create a new event instance.
     *
     * @param ReservationModel $model
     */
    public function __construct(ReservationModel $model)
    {
        $this->reservation = $model;
    }

    /**
     * @return ReservationModel
     */
    public function getReservation(){
        return $this->reservation;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('reservation');
    }
}
