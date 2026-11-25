<?php

namespace App\Repositories\Admin;

use App\Models\Admin\ReservationFlat;
use InfyOm\Generator\Common\BaseRepository;
use Illuminate\Support\Facades\Auth;

/**
 * Class ReservationFlatRepository
 * @package App\Repositories\Admin
 * @version March 13, 2018, 1:00 pm UTC
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
        'brand'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ReservationFlat::class;
    }
    public function boot()
    {
        $this->pushCriteria(app(\App\Criteria\Admin\CompanyAuthCriteria::class));
    }

    public function create(array $attributes)
    {
        if (Auth::check()) {
            $attributes['company_id'] = Auth::user()->company_id;
        }
        return parent::create($attributes);
    }

    public function update(array $attributes, $id)
    {
        if (Auth::check()) {
            $attributes['company_id'] = Auth::user()->company_id;
        }
        return parent::update($attributes, $id);
    }
}
