<?php

namespace App\Criteria\Admin;

use App\Admin;
use App\Models\Admin\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use JWTAuth;
use JWTAuthException;
/**
 * Class UserCriteriaCriteria.
 *
 * @package namespace App\Criteria\API;
 */
class ReservationOpenCriteria implements CriteriaInterface
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->where('status', $this->request->get('status', Reservation::$STATUS_OPEN));
        return $model;
    }
}
