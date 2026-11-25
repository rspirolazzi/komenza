<?php

namespace App\Repositories\Admin;

use App\Admin;
use InfyOm\Generator\Common\BaseRepository;
use Illuminate\Support\Facades\Auth;

/**
 * Class AdminRepository
 * @package App\Repositories\Admin
 * @version March 28, 2018, 8:04 pm UTC
 *
 * @method Admin findWithoutFail($id, $columns = ['*'])
 * @method Admin find($id, $columns = ['*'])
 * @method Admin first($columns = ['*'])
*/
class AdminRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'=>'like',
        'last_name'=>'like',
        'telephone'=>'like',
        'birthday'=>'like',
        'mobile'=>'like',
        'email'=>'like',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Admin::class;
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
