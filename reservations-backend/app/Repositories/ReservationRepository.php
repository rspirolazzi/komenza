<?php

namespace App\Repositories;

use App\Models\Admin\Reservation;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ReservationRepository
 * @package App\Repositories
 * @version March 13, 2018, 2:37 pm UTC
 *
 */
class ReservationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'last_name',
        'email',
        'birthday',
        'mobile',
        'store',
        'people',
        'reservation_date',
        'reservation_time',
        'brand'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Reservation::class;
    }

    /**
     * @param $brandId
     * @param $token
     * @param array $columns
     * @return Reservation|mixed|void
     */
    public function findByTokenWithoutFail($token)
    {
        try {
            return $this->model
                ->where('token', $token)
                ->whereIn('status', [Reservation::$STATUS_OPEN, Reservation::$STATUS_MAXIMUM_CAPACITY])
                ->first();
        } catch (Exception $e) {
            return;
        }
    }
    /**
     * @param $brandId
     * @param $token
     * @param array $columns
     * @return Reservation|mixed|void
     */
    public function findByTokenAndBrandWithoutFail($token, $brandId)
    {
        try {
            return $this->model
                ->where('brand_id', $brandId)
                ->where('token', $token)
                ->whereIn('status', [Reservation::$STATUS_OPEN, Reservation::$STATUS_MAXIMUM_CAPACITY])
                ->first();
        } catch (Exception $e) {
            return;
        }
    }
}
