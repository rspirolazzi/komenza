<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateReservationFlatRequest;
use App\Http\Requests\Admin\UpdateReservationFlatRequest;
use App\Repositories\Admin\ReservationFlatRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class ReservationFlatController extends AppBaseController
{
    /** @var  ReservationFlatRepository */
    private $reservationFlatRepository;

    public function __construct(ReservationFlatRepository $reservationFlatRepo)
    {
        $this->reservationFlatRepository = $reservationFlatRepo;
    }

    /**
     * Display a listing of the ReservationFlat.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->reservationFlatRepository->pushCriteria(new RequestCriteria($request));
        $reservationFlats = $this->reservationFlatRepository->paginate(25);

        return view('admin.reservation_flats.index')
            ->with('reservationFlats', $reservationFlats);
    }

    /**
     * Show the form for creating a new ReservationFlat.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.reservation_flats.create');
    }

    /**
     * Store a newly created ReservationFlat in storage.
     *
     * @param CreateReservationFlatRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreateReservationFlatRequest $request)
    {
        $input = $request->all();

        $reservationFlat = $this->reservationFlatRepository->create($input);

        Flash::success('La reserva Flat se guardó con éxito.');

        return redirect(route('admin.reservationFlats.index'));
    }

    /**
     * Display the specified ReservationFlat.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $reservationFlat = $this->reservationFlatRepository->findWithoutFail($id);

        if (empty($reservationFlat)) {
            Flash::error('La reserva Flat no se encontro');

            return redirect(route('admin.reservationFlats.index'));
        }

        return view('admin.reservation_flats.show')->with('reservationFlat', $reservationFlat);
    }

    /**
     * Show the form for editing the specified ReservationFlat.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $reservationFlat = $this->reservationFlatRepository->findWithoutFail($id);

        if (empty($reservationFlat)) {
            Flash::error('La reserva Flat no se encontro');

            return redirect(route('admin.reservationFlats.index'));
        }

        return view('admin.reservation_flats.edit')->with('reservationFlat', $reservationFlat);
    }

    /**
     * Update the specified ReservationFlat in storage.
     *
     * @param  int              $id
     * @param UpdateReservationFlatRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateReservationFlatRequest $request)
    {
        $reservationFlat = $this->reservationFlatRepository->findWithoutFail($id);

        if (empty($reservationFlat)) {
            Flash::error('La reserva Flat no se encontro');

            return redirect(route('admin.reservationFlats.index'));
        }

        $reservationFlat = $this->reservationFlatRepository->update($request->all(), $id);

        Flash::success('La reserva Flat se actualizó con éxito.');

        return redirect(route('admin.reservationFlats.index'));
    }

    /**
     * Remove the specified ReservationFlat from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $reservationFlat = $this->reservationFlatRepository->findWithoutFail($id);

        if (empty($reservationFlat)) {
            Flash::error('La reserva Flat no se encontro');

            return redirect(route('admin.reservationFlats.index'));
        }

        $this->reservationFlatRepository->delete($id);

        Flash::success('La reserva Flat deleted successfully.');

        return redirect(route('admin.reservationFlats.index'));
    }
}
