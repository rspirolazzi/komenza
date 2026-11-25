<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateStoreAPIRequest;
use App\Http\Requests\API\UpdateStoreAPIRequest;
use App\Models\Admin\Store;
use App\Repositories\StoreRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class StoreController
 * @package App\Http\Controllers\API
 */

class StoreAPIController extends AppBaseController
{
    /** @var  StoreRepository */
    private $storeRepository;

    public function __construct(StoreRepository $storeRepo)
    {
        $this->storeRepository = $storeRepo;
    }

    /**
     * Display a listing of the Store.
     * GET|HEAD /stores
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        \Log::info('StoreAPIController@index called', [
            'method' => $request->getMethod(),
            'input' => $request->all(),
            'headers' => $request->headers->all()
        ]);
        $this->storeRepository->pushCriteria(new RequestCriteria($request));
        $this->storeRepository->pushCriteria(new LimitOffsetCriteria($request));
        $stores = $this->storeRepository->all();
        \Log::debug('StoreAPIController@index called', [
            '$stores' => $stores->toArray(),
        ]);

        return $this->sendResponse($stores->toArray(), 'Las Sucursales se obtuvo con éxito');
    }

    /**
     * Store a newly created Store in storage.
     * POST /stores
     *
     * @param CreateStoreAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateStoreAPIRequest $request)
    {
        \Log::info('StoreAPIController@store called', [
            'method' => $request->getMethod(),
            'input' => $request->all(),
            'headers' => $request->headers->all()
        ]);
        $input = $request->all();

        $stores = $this->storeRepository->create($input);

        return $this->sendResponse($stores->toArray(), 'La Sucursal se guardó con éxito');
    }

    /**
     * Display the specified Store.
     * GET|HEAD /stores/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        \Log::info('StoreAPIController@show called', [
            'id' => $id,
        ]);
        /** @var Store $store */
        $store = $this->storeRepository->findWithoutFail($id);

        if (empty($store)) {
            return $this->sendError('La Sucursal no se encontro');
        }

        return $this->sendResponse($store->toArray(), 'La Sucursal se obtuvo con éxito');
    }

    /**
     * Update the specified Store in storage.
     * PUT/PATCH /stores/{id}
     *
     * @param  int $id
     * @param UpdateStoreAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateStoreAPIRequest $request)
    {
        \Log::info('StoreAPIController@update called', [
            'id' => $id,
            'method' => $request->getMethod(),
            'input' => $request->all(),
            'headers' => $request->headers->all()
        ]);
        $input = $request->all();

        /** @var Store $store */
        $store = $this->storeRepository->findWithoutFail($id);

        if (empty($store)) {
            return $this->sendError('La Sucursal no se encontro');
        }

        $store = $this->storeRepository->update($input, $id);

        return $this->sendResponse($store->toArray(), 'La Sucursal se actualizó con éxito');
    }

    /**
     * Remove the specified Store from storage.
     * DELETE /stores/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        \Log::info('StoreAPIController@destroy called', [
            'id' => $id,
        ]);
        /** @var Store $store */
        $store = $this->storeRepository->findWithoutFail($id);

        if (empty($store)) {
            return $this->sendError('La Sucursal no se encontro');
        }

        $store->delete();

        return $this->sendResponse($id, 'La Sucursal deleted successfully');
    }
}
