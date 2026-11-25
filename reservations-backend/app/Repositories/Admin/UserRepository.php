<?php

namespace App\Repositories\Admin;

use App\User;
use InfyOm\Generator\Common\BaseRepository;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserRepository
 * @package App\Repositories\Admin
 * @version March 28, 2018, 8:04 pm UTC
 *
 * @method User findWithoutFail($id, $columns = ['*'])
 * @method User find($id, $columns = ['*'])
 * @method User first($columns = ['*'])
*/
class UserRepository extends BaseRepository
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
        return User::class;
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
