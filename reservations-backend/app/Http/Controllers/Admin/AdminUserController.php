<?php

namespace App\Http\Controllers\Admin;

use App\Criteria\Admin\CompanyAuthCriteria;
use App\Http\Requests\Admin\CreateAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Repositories\Admin\AdminRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class AdminUserController extends AppBaseController
{
    /** @var  AdminRepository */
    private $adminRepository;

    public function __construct(AdminRepository $adminRepo)
    {
        $this->adminRepository = $adminRepo;
    }

    /**
     * Display a listing of the Admin.
     *
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        $this->adminRepository->pushCriteria(new RequestCriteria($request));
        $users = $this->adminRepository->all();

        return view('admin.admin_users.index')
            ->with('users', $users);
    }

    /**
     * Show the form for creating a new Admin.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.admin_users.create');
    }

    /**
     * Store a newly created Admin in storage.
     *
     * @param CreateAdminRequest $request
     *
     * @return Response
     */
    public function store(CreateAdminRequest $request)
    {
        $input = $request->all();

        $user = $this->adminRepository->create($input);

        Flash::success('Admin se guardó con éxito.');

        return redirect(route('admin.admin_users.index'));
    }

    /**
     * Display the specified Admin.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $this->adminRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('Admin no se encontro');

            return redirect(route('admin.admin_users.index'));
        }

        return view('admin.admin_users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified Admin.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->adminRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('Admin no se encontro');

            return redirect(route('admin.admin_users.index'));
        }

        return view('admin.admin_users.edit')->with('user', $user);
    }

    /**
     * Update the specified Admin in storage.
     *
     * @param  int              $id
     * @param UpdateAdminRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAdminRequest $request)
    {
        $user = $this->adminRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('Admin no se encontro');

            return redirect(route('admin.admin_users.index'));
        }

        $user = $this->adminRepository->update($request->all(), $id);

        Flash::success('Admin se actualizó con éxito.');

        return redirect(route('admin.admin_users.index'));
    }

    /**
     * Remove the specified Admin from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->adminRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('Admin no se encontro');

            return redirect(route('admin.admin_users.index'));
        }

        $this->adminRepository->delete($id);

        Flash::success('Admin deleted successfully.');

        return redirect(route('admin.admin_users.index'));
    }
}
