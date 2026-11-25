<?php

use Illuminate\Database\Seeder;

class ReservationTableSeed extends Seeder
{

    public static $reservations = [

    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$reservations as $row) {
            \App\Models\Admin\Reservation::create($row);
        }
    }
}
