<?php

namespace App\Criteria\Admin;

use App\Admin;
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
class ReservationAuthCriteria implements CriteriaInterface
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
        if (Auth::user()->role == Admin::$ROLE_STORE) {
            $model = $model->where('store_id', Auth::user()->store_id);
        }elseif (Auth::user()->role == Admin::$ROLE_BRAND) {
            $model = $model->where('brand_id', Auth::user()->brand_id);
        }
        return $model;
    }
}
