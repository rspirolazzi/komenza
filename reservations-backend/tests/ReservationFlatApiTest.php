<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReservationFlatApiTest extends TestCase
{
    use MakeReservationFlatTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateReservationFlat()
    {
        $reservationFlat = $this->fakeReservationFlatData();
        $this->json('POST', '/api/v1/reservationFlats', $reservationFlat);

        $this->assertApiResponse($reservationFlat);
    }

    /**
     * @test
     */
    public function testReadReservationFlat()
    {
        $reservationFlat = $this->makeReservationFlat();
        $this->json('GET', '/api/v1/reservationFlats/'.$reservationFlat->id);

        $this->assertApiResponse($reservationFlat->toArray());
    }

    /**
     * @test
     */
    public function testUpdateReservationFlat()
    {
        $reservationFlat = $this->makeReservationFlat();
        $editedReservationFlat = $this->fakeReservationFlatData();

        $this->json('PUT', '/api/v1/reservationFlats/'.$reservationFlat->id, $editedReservationFlat);

        $this->assertApiResponse($editedReservationFlat);
    }

    /**
     * @test
     */
    public function testDeleteReservationFlat()
    {
        $reservationFlat = $this->makeReservationFlat();
        $this->json('DELETE', '/api/v1/reservationFlats/'.$reservationFlat->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/reservationFlats/'.$reservationFlat->id);

        $this->assertResponseStatus(404);
    }
}
