<?php

namespace App\Repositories\Admin;

use App\Models\Admin\Reservation;
use InfyOm\Generator\Common\BaseRepository;
use Illuminate\Support\Facades\Auth;

/**
 * Class ReservationRepository
 * @package App\Repositories\Admin
 * @version March 28, 2018, 6:21 pm UTC
 *
 * @method Reservation findWithoutFail($id, $columns = ['*'])
 * @method Reservation find($id, $columns = ['*'])
 * @method Reservation first($columns = ['*'])
*/
class ReservationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'store_id',
        'brand_id',
        'people',
        'reservation_date',
        'reservation_time',
        'status',
        'user.name'=>'like',
        'user.last_name'=>'like',
        'user.email'=>'like',
        'user.mobile'=>'like',
        'user.telephone'=>'like',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Reservation::class;
    }

    /**
     * Retrieve all data of repository, paginated
     *
     * @param null $limit
     * @param array $columns
     * @param string $method
     *
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function paginateOpen($limit = null, $columns = ['*'], $method = "paginate")
    {
        $this->applyCriteria();
        $this->applyScope();
        $limit = is_null($limit) ? config('repository.pagination.limit', 15) : $limit;
        $results = $this->model->{$method}($limit, $columns);
        $results->appends(app('request')->query());
        $this->resetModel();

        return $this->parserResult($results);
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
