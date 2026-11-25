<?php

namespace App\Repositories;

use App\Models\Admin\ReservationFlat;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ReservationFlatRepository
 * @package App\Repositories
 * @version March 13, 2018, 2:37 pm UTC
 *
 * @method ReservationFlat findWithoutFail($id, $columns = ['*'])
 * @method ReservationFlat find($id, $columns = ['*'])
 * @method ReservationFlat first($columns = ['*'])
*/
class ReservationFlatRepository extends BaseRepository
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
        return ReservationFlat::class;
    }

    public function create(array $attributes)
    {
        // Si se provee brand_id, obtener el company_id desde la marca
        if (isset($attributes['brand_id'])) {
            $brand = \App\Models\Admin\Brand::find($attributes['brand_id']);
            if ($brand) {
                $attributes['company_id'] = $brand->company_id;
            }
        }
        return parent::create($attributes);
    }

    public function update(array $attributes, $id)
    {
        // Si se provee brand_id, obtener el company_id desde la marca
        if (isset($attributes['brand_id'])) {
            $brand = \App\Models\Admin\Brand::find($attributes['brand_id']);
            if ($brand) {
                $attributes['company_id'] = $brand->company_id;
            }
        }
        return parent::update($attributes, $id);
    }
}
