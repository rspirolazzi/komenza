<?php

namespace App\Criteria\Admin;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class CompanyAuthCriteria implements CriteriaInterface
{
    public function apply($model, RepositoryInterface $repository)
    {
        $user = auth()->user();
        if ($user && isset($user->company_id)) {
            return $model->where('company_id', $user->company_id);
        }
        return $model;
    }
}