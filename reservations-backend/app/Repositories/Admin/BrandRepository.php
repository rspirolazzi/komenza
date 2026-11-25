<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Brand;
use InfyOm\Generator\Common\BaseRepository;
use Illuminate\Support\Facades\Auth;

/**
 * Class BrandRepository
 * @package App\Repositories\Admin
 * @version March 13, 2018, 1:08 pm UTC
 *
 * @method Brand findWithoutFail($id, $columns = ['*'])
 * @method Brand find($id, $columns = ['*'])
 * @method Brand first($columns = ['*'])
 */
class BrandRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'company_id',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Brand::class;
    }
    public function boot()
    {
        $this->pushCriteria(app(\App\Criteria\Admin\CompanyAuthCriteria::class));
    }


    public function create(array $attributes)
    {
        if (isset($attributes['styles']) && is_string($attributes['styles'])) {
            json_decode($attributes['styles']);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $attributes['styles'] = null; // o puedes lanzar una excepción
            }
        }
        if (Auth::check()) {
            $attributes['company_id'] = Auth::user()->company_id;
        }
        return parent::create($attributes);
    }

    public function update(array $attributes, $id)
    {
        if (isset($attributes['styles']) && is_string($attributes['styles'])) {
            json_decode($attributes['styles']);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $attributes['styles'] = null; // o puedes lanzar una excepción
            }
        }
        if (Auth::check()) {
            $attributes['company_id'] = Auth::user()->company_id;
        }
        return parent::update($attributes, $id);
    }
}
