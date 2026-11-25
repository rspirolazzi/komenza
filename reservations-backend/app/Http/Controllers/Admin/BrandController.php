<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateBrandRequest;
use App\Http\Requests\Admin\UpdateBrandRequest;
use App\Repositories\Admin\BrandRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class BrandController extends AppBaseController
{
    /** @var  BrandRepository */
    private $brandRepository;

    public function __construct(BrandRepository $brandRepo)
    {
        $this->brandRepository = $brandRepo;
    }

    /**
     * Display a listing of the Brand.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->brandRepository->pushCriteria(new RequestCriteria($request));
        $brands = $this->brandRepository->paginate(25);

        return view('admin.brands.index')
            ->with('brands', $brands);
    }

    /**
     * Show the form for creating a new Brand.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Store a newly created Brand in storage.
     *
     * @param CreateBrandRequest $request
     *
     * @return Response
     */
    public function store(CreateBrandRequest $request)
    {
        $input = $request->all();

        // Manejo de logo
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoDir = 'img/brand';
            // Si la marca ya existe, usa su id, si no, crea la marca primero
            if (isset($brand) && $brand->id) {
                $logoDir .= '/' . $brand->id;
            }
            // Si es creación, aún no existe el id, así que lo asignamos después de crear la marca
            $logoName = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logoPath = $logoDir . '/' . $logoName;
            if (!file_exists(public_path($logoDir))) {
                mkdir(public_path($logoDir), 0777, true);
            }
            $logo->move(public_path($logoDir), $logoName);
            $input['logo'] = $logoPath;
        }

        $brand = $this->brandRepository->create($input);

        // Manejo de fondos (hasta 20)
        if ($request->hasFile('backgrounds')) {
            $backgrounds = $request->file('backgrounds');
            $count = 0;
            $bgDir = 'img/brand/' . $brand->id;
            if (!file_exists(public_path($bgDir))) {
                mkdir(public_path($bgDir), 0777, true);
            }
            foreach ($backgrounds as $background) {
                if ($background && $count < 20) {
                    $bgName = 'bg_' . $brand->id . '_' . time() . '_' . $count . '.' . $background->getClientOriginalExtension();
                    $bgPath = $bgDir . '/' . $bgName;
                    $background->move(public_path($bgDir), $bgName);
                    \App\Models\Admin\BrandBackground::create([
                        'brand_id' => $brand->id,
                        'image' => $bgPath,
                    ]);
                    $count++;
                }
            }
        }

        Flash::success('La marca se guardó con éxito.');

        return redirect(route('admin.brands.index'));
    }

    /**
     * Display the specified Brand.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $brand = $this->brandRepository->findWithoutFail($id);

        if (empty($brand)) {
            Flash::error('La marca no se encontro');

            return redirect(route('admin.brands.index'));
        }

        return view('admin.brands.show')->with('brand', $brand);
    }

    /**
     * Show the form for editing the specified Brand.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $brand = $this->brandRepository->findWithoutFail($id);

        if (empty($brand)) {
            Flash::error('La marca no se encontro');

            return redirect(route('admin.brands.index'));
        }

        return view('admin.brands.edit')->with('brand', $brand);
    }

    /**
     * Update the specified Brand in storage.
     *
     * @param  int              $id
     * @param UpdateBrandRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBrandRequest $request)
    {
        $brand = $this->brandRepository->findWithoutFail($id);

        if (empty($brand)) {
            Flash::error('La marca no se encontro');

            return redirect(route('admin.brands.index'));
        }

        $input = $request->all();
        if (isset($input['backgrounds']) && is_array($input['backgrounds'])) {
            unset($input['backgrounds']);
        }
        
        // Manejo de logo
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoDir = 'img/brand';
            // Si la marca ya existe, usa su id, si no, crea la marca primero
            if (isset($brand) && $brand->id) {
                $logoDir .= '/' . $brand->id;
            }
            // Si es creación, aún no existe el id, así que lo asignamos después de crear la marca
            $logoName = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logoPath = $logoDir . '/' . $logoName;
            if (!file_exists(public_path($logoDir))) {
                mkdir(public_path($logoDir), 0777, true);
            }
            $logo->move(public_path($logoDir), $logoName);
            $input['logo'] = $logoPath;
        }

        // Eliminar logo si se solicita
        if (isset($request['delete_logo']) && $request['delete_logo'] == '1' && $brand->logo) {
            $logoPath = public_path($brand->logo);
            if (file_exists($logoPath)) {
                @unlink($logoPath);
            }
            $input['logo'] = null;
        }

        $brand = $this->brandRepository->update($input, $id);

        // Eliminar fondos seleccionados
        if (isset($request['delete_backgrounds'])) {
            $deleteIds = $request['delete_backgrounds'];
            foreach ($deleteIds as $bgId) {
                $bg = new \App\Models\Admin\BrandBackground();
                $bg = $bg->where('id', $bgId)->first();
                if ($bg) {
                    $bgPath = public_path($bg->image);
                    if (file_exists($bgPath)) {
                        @unlink($bgPath);
                    }
                    $bg->delete();
                }
            }
        }

        // Manejo de fondos (hasta 20)
        if ($request->hasFile('backgrounds')) {
            $backgrounds = $request->file('backgrounds');
            $count = 0;
            $bgDir = 'img/brand/' . $brand->id;
            if (!file_exists(public_path($bgDir))) {
                mkdir(public_path($bgDir), 0777, true);
            }
            foreach ($backgrounds as $background) {
                if ($background && $count < 20) {
                    $bgName = 'bg_' . $brand->id . '_' . time() . '_' . $count . '.' . $background->getClientOriginalExtension();
                    $bgPath = $bgDir . '/' . $bgName;
                    $background->move(public_path($bgDir), $bgName);
                    \App\Models\Admin\BrandBackground::create([
                        'brand_id' => $brand->id,
                        'image' => $bgPath,
                    ]);
                    $count++;
                }
            }
        }

        Flash::success('La marca se actualizó con éxito.');

        return redirect(route('admin.brands.index'));
    }

    /**
     * Remove the specified Brand from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $brand = $this->brandRepository->findWithoutFail($id);

        if (empty($brand)) {
            Flash::error('La marca no se encontro');

            return redirect(route('admin.brands.index'));
        }

        $this->brandRepository->delete($id);

        Flash::success('La marca deleted successfully.');

        return redirect(route('admin.brands.index'));
    }
}
