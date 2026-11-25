<?php

namespace App\Criteria\Admin;

use Illuminate\Http\Request;
use JWTAuth;
use JWTAuthException;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class UserAuthCriteria.
 *
 * @package namespace App\Criteria\API;
 */
class UserAuthCriteria implements CriteriaInterface
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
       $model = $model->whereNull('role');
        return $model;
    }
}
