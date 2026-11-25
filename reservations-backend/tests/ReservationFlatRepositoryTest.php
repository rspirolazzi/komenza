<?php

use App\Models\Admin\ReservationFlat;
use App\Repositories\ReservationFlatRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReservationFlatRepositoryTest extends TestCase
{
    use MakeReservationFlatTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var ReservationFlatRepository
     */
    protected $reservationFlatRepo;

    public function setUp()
    {
        parent::setUp();
        $this->reservationFlatRepo = App::make(ReservationFlatRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateReservationFlat()
    {
        $reservationFlat = $this->fakeReservationFlatData();
        $createdReservationFlat = $this->reservationFlatRepo->create($reservationFlat);
        $createdReservationFlat = $createdReservationFlat->toArray();
        $this->assertArrayHasKey('id', $createdReservationFlat);
        $this->assertNotNull($createdReservationFlat['id'], 'Created ReservationFlat must have id specified');
        $this->assertNotNull(ReservationFlat::find($createdReservationFlat['id']), 'ReservationFlat with given id must be in DB');
        $this->assertModelData($reservationFlat, $createdReservationFlat);
    }

    /**
     * @test read
     */
    public function testReadReservationFlat()
    {
        $reservationFlat = $this->makeReservationFlat();
        $dbReservationFlat = $this->reservationFlatRepo->find($reservationFlat->id);
        $dbReservationFlat = $dbReservationFlat->toArray();
        $this->assertModelData($reservationFlat->toArray(), $dbReservationFlat);
    }

    /**
     * @test update
     */
    public function testUpdateReservationFlat()
    {
        $reservationFlat = $this->makeReservationFlat();
        $fakeReservationFlat = $this->fakeReservationFlatData();
        $updatedReservationFlat = $this->reservationFlatRepo->update($fakeReservationFlat, $reservationFlat->id);
        $this->assertModelData($fakeReservationFlat, $updatedReservationFlat->toArray());
        $dbReservationFlat = $this->reservationFlatRepo->find($reservationFlat->id);
        $this->assertModelData($fakeReservationFlat, $dbReservationFlat->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteReservationFlat()
    {
        $reservationFlat = $this->makeReservationFlat();
        $resp = $this->reservationFlatRepo->delete($reservationFlat->id);
        $this->assertTrue($resp);
        $this->assertNull(ReservationFlat::find($reservationFlat->id), 'ReservationFlat should not exist in DB');
    }
}
