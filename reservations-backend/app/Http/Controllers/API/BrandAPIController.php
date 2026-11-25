<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateBrandAPIRequest;
use App\Http\Requests\API\UpdateBrandAPIRequest;
use App\Models\Admin\Brand;
use App\Repositories\BrandRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Criteria\API\CompanyBrandCriteria;

/**
 * Class BrandController
 * @package App\Http\Controllers\API
 */

class BrandAPIController extends AppBaseController
{
    /** @var  BrandRepository */
    private $brandRepository;

    public function __construct(BrandRepository $brandRepo)
    {
        $this->brandRepository = $brandRepo;
    }

    /**
     * Display a listing of the Brand.
     * GET|HEAD /brands
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->brandRepository->pushCriteria(new CompanyBrandCriteria($request));
        $this->brandRepository->pushCriteria(new RequestCriteria($request));
        $this->brandRepository->pushCriteria(new LimitOffsetCriteria($request));
        $brands = $this->brandRepository->all();

        return $this->sendResponse($brands->toArray(), 'Las marcas se obtuvo con éxito');
    }

    /**
     * Store a newly created Brand in storage.
     * POST /brands
     *
     * @param CreateBrandAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateBrandAPIRequest $request)
    {
        $input = $request->all();

        $brands = $this->brandRepository->create($input);

        return $this->sendResponse($brands->toArray(), 'La marca se guardó con éxito');
    }

    /**
     * Display the specified Brand.
     * GET|HEAD /brands/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Brand $brand */
        $brand = $this->brandRepository->findWithoutFail($id);

        if (empty($brand)) {
            return $this->sendError('La marca no se encontro');
        }

        return $this->sendResponse($brand->toArray(), 'La marca se obtuvo con éxito');
    }

    /**
     * Update the specified Brand in storage.
     * PUT/PATCH /brands/{id}
     *
     * @param  int $id
     * @param UpdateBrandAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBrandAPIRequest $request)
    {
        $input = $request->all();

        /** @var Brand $brand */
        $brand = $this->brandRepository->findWithoutFail($id);

        if (empty($brand)) {
            return $this->sendError('La marca no se encontro');
        }

        $brand = $this->brandRepository->update($input, $id);

        return $this->sendResponse($brand->toArray(), 'La marca se actualizó con éxito');
    }

    /**
     * Remove the specified Brand from storage.
     * DELETE /brands/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Brand $brand */
        $brand = $this->brandRepository->findWithoutFail($id);

        if (empty($brand)) {
            return $this->sendError('La marca no se encontro');
        }

        $brand->delete();

        return $this->sendResponse($id, 'La marca deleted successfully');
    }
}
