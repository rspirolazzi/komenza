<?php

use Faker\Factory as Faker;
use App\Models\Admin\ReservationFlat;
use App\Repositories\ReservationFlatRepository;

trait MakeReservationFlatTrait
{
    /**
     * Create fake instance of ReservationFlat and save it in database
     *
     * @param array $reservationFlatFields
     * @return ReservationFlat
     */
    public function makeReservationFlat($reservationFlatFields = [])
    {
        /** @var ReservationFlatRepository $reservationFlatRepo */
        $reservationFlatRepo = App::make(ReservationFlatRepository::class);
        $theme = $this->fakeReservationFlatData($reservationFlatFields);
        return $reservationFlatRepo->create($theme);
    }

    /**
     * Get fake instance of ReservationFlat
     *
     * @param array $reservationFlatFields
     * @return ReservationFlat
     */
    public function fakeReservationFlat($reservationFlatFields = [])
    {
        return new ReservationFlat($this->fakeReservationFlatData($reservationFlatFields));
    }

    /**
     * Get fake data of ReservationFlat
     *
     * @param array $postFields
     * @return array
     */
    public function fakeReservationFlatData($reservationFlatFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'last_name' => $fake->word,
            'email' => $fake->word,
            'birthday' => $fake->word,
            'mobile' => $fake->word,
            'store' => $fake->randomDigitNotNull,
            'people' => $fake->randomDigitNotNull,
            'reservation_date' => $fake->word,
            'reservation_time' => $fake->word,
            'brand' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $reservationFlatFields);
    }
}
