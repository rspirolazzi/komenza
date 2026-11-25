<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Store;
use InfyOm\Generator\Common\BaseRepository;
use Illuminate\Support\Facades\Auth;

/**
 * Class StoreRepository
 * @package App\Repositories\Admin
 * @version March 13, 2018, 1:10 pm UTC
 *
 * @method Store findWithoutFail($id, $columns = ['*'])
 * @method Store find($id, $columns = ['*'])
 * @method Store first($columns = ['*'])
 */
class StoreRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'brand_id',
        'address',
        'telephone'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Store::class;
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
