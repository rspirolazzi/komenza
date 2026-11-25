<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateStoreRequest;
use App\Http\Requests\Admin\UpdateStoreRequest;
use App\Models\Admin\Store;
use App\Repositories\Admin\StoreRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class StoreController extends AppBaseController
{
    /** @var  StoreRepository */
    private $storeRepository;

    public function __construct(StoreRepository $storeRepo)
    {
        $this->storeRepository = $storeRepo;
    }

    /**
     * Display a listing of the Store.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->storeRepository->pushCriteria(new RequestCriteria($request));
        $stores = $this->storeRepository->paginate(25);

        return view('admin.stores.index')
            ->with('stores', $stores);
    }

    /**
     * Show the form for creating a new Store.
     *
     * @return Response
     */
    public function create()
    {
        $brands = app(\App\Repositories\Admin\BrandRepository::class)->all()->pluck('name', 'id');
        return view('admin.stores.create', compact('brands'));
    }

    /**
     * Store a newly created Store in storage.
     *
     * @param CreateStoreRequest $request
     *
     * @return Response
     */
    public function store(CreateStoreRequest $request)
    {
        $input = $request->all();

        $store = $this->storeRepository->create($input);

        Flash::success('La Sucursal se guardó con éxito.');

        return redirect(route('admin.stores.index'));
    }

    /**
     * Display the specified Store.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $store = $this->storeRepository->findWithoutFail($id);

        if (empty($store)) {
            Flash::error('La Sucursal no se encontro');

            return redirect(route('admin.stores.index'));
        }

        return view('admin.stores.show')->with('store', $store);
    }

    /**
     * Show the form for editing the specified Store.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $store = $this->storeRepository->findWithoutFail($id);

        if (empty($store)) {
            Flash::error('La Sucursal no se encontro');

            return redirect(route('admin.stores.index'));
        }
        $brands = app(\App\Repositories\Admin\BrandRepository::class)->all()->pluck('name', 'id');
        return view('admin.stores.edit', compact('store', 'brands'));
    }

    /**
     * Update the specified Store in storage.
     *
     * @param  int $id
     * @param UpdateStoreRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateStoreRequest $request)
    {
        $store = $this->storeRepository->findWithoutFail($id);

        if (empty($store)) {
            Flash::error('La Sucursal no se encontro');

            return redirect(route('admin.stores.index'));
        }

        $store = $this->storeRepository->update($request->all(), $id);

        Flash::success('La Sucursal se actualizó con éxito.');

        return redirect(route('admin.stores.index'));
    }

    /**
     * Remove the specified Store from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $store = $this->storeRepository->findWithoutFail($id);

        if (empty($store)) {
            Flash::error('La Sucursal no se encontro');

            return redirect(route('admin.stores.index'));
        }

        $this->storeRepository->delete($id);

        Flash::success('La Sucursal deleted successfully.');

        return redirect(route('admin.stores.index'));
    }

    /**
     * Show the form for editing the specified Store.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function delivery($id)
    {
        $store = $this->storeRepository->findWithoutFail($id);

        if (empty($store)) {
            Flash::error('Sucursal no encontrada');

            return redirect(route('admin.stores.delivery'));
        }

        return view('admin.stores.delivery')
            ->with('store', $store);
    }

    /**
     * Update the specified Store in storage.
     *
     * @param  int $id
     * @param Request $request
     *
     * @return Response
     */
    public function storeDelivery($id, Request $request)
    {
        $store = $this->storeRepository->findWithoutFail($id);


        $delivery_times = [];
        //if()

        if (empty($store)) {
            Flash::error('Sucursal no encontrada');

            return redirect(route('admin.stores.index'));
        }

        $time_custom = $request->get('delivery_time');
        foreach ($time_custom as $index => $value) {
            if (is_null($value['start_day']) || is_null($value['start_hour']) || is_null($value['start_min']) || is_null($value['end_day']) || is_null($value['end_hour']) || is_null($value['end_min'])) {
                //Flash::error('Debes seleccionar al menos un tipo de horario');
                //return redirect(route('admin.stores.delivery'));
            } else {
                $delivery_times[] = $value;
            }
        }

        $this->storeRepository->update(['delivery_times' => json_encode($delivery_times)], $id);
        Flash::success('El horario se agrego con �xito.');
        if ($request->get('submit') == 'add_and_continue') {
            return redirect(route('admin.stores.delivery', [$id]));
        }
        return redirect(route('admin.stores.edit', [$id]));
    }

    /**
     * Copy the specified Store.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function copy($id)
    {
        $store = $this->storeRepository->findWithoutFail($id);
        if (empty($store)) {
            Flash::error('La Sucursal no se encontro');
            return redirect(route('admin.stores.index'));
        }
        $newStore = $store->replicate();
        $newStore->name = 'COPY - ' . $store->name;
        $newStore->save();
        return redirect()->route('admin.stores.edit', [$newStore->id]);
    }
}
