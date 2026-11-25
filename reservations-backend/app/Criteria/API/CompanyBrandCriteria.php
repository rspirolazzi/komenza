<?php

namespace App\Criteria\Api;

use App\Models\Admin\Company;
use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class CompanyBrandCriteria implements CriteriaInterface
{
    /**
     * @var Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply criteria in query repository
     *
     * @param \Illuminate\Database\Eloquent\Builder $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $companyName = $this->request->get('company');
        if ($companyName) {
            $company = Company::where('name', $companyName)->first();
            if ($company) {
                $model = $model->where('company_id', $company->id);
            } else {
                // Si no existe la compañía, devolver vacío
                $model = $model->whereRaw('1=0');
            }
        }
        return $model;
    }
}