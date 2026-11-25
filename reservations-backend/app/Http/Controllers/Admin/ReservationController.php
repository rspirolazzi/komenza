<?php

namespace App\Http\Controllers\Admin;

use App\Criteria\Admin\ReservationAuthCriteria;
use App\Criteria\Admin\ReservationCriteria;
use App\Criteria\Admin\ReservationOpenCriteria;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Admin\CreateReservationRequest;
use App\Http\Requests\Admin\UpdateReservationRequest;
use App\Mail\MailTest;
use App\Models\Admin\Reservation;
use App\Models\Admin\Store;
use App\Repositories\Admin\ReservationRepository;
use App\User;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Response;
use PDF;

class ReservationController extends AppBaseController
{
    /** @var  ReservationRepository */
    private $reservationRepository;

    public function __construct(ReservationRepository $reservationRepo)
    {
        $this->reservationRepository = $reservationRepo;
    }

    /**
     * Display a listing of the Reservation.
     *
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index(Request $request)
    {
        $reservations=[];
        $selectedDate = $request->get('reservation_date', \Carbon\Carbon::today()->format('Y-m-d'));

        $this->reservationRepository->pushCriteria(new ReservationCriteria($request));
        $this->reservationRepository->pushCriteria(new ReservationAuthCriteria($request));
        $this->reservationRepository->scopeQuery(function ($query) use ($request, $selectedDate) {
            if ($request->get('reservation_date', null) == null) {
                $query->where('reservation_date', $selectedDate);
            }
            return $query;
        });
        $reservations = $this->reservationRepository->paginate(50);

        $this->reservationRepository->resetCriteria();
        $this->reservationRepository->resetScope();
        $this->reservationRepository->pushCriteria(new ReservationAuthCriteria($request));
        $this->reservationRepository->pushCriteria(new ReservationOpenCriteria($request));
        $reservationsOpen = $this->reservationRepository->paginateOpen(50);

        \Log::debug('Reservas obtenidas', [
            'reservations' => $reservations->toArray(),
            'reservationsOpen' => $reservationsOpen->toArray(),
            'selectedDate' => $selectedDate,
        ]);


        return view('admin.reservations.index')
            ->with([
                'reservations' => $reservations,
                'reservationsOpen' => $reservationsOpen,
                'selectedDate' => $selectedDate,
            ]);
    }

    public function print(Request $request)
    {
        $this->reservationRepository->pushCriteria(new ReservationCriteria($request));
        $this->reservationRepository->pushCriteria(new ReservationAuthCriteria($request));
        $reservations = $this->reservationRepository->all();

        $pdf = PDF::loadView('admin.pdf.reservations', compact('reservations'));

        return @$pdf->download('reservaciones.pdf');
    }

    /**
     * Display a listing of the Reservation.
     *
     * @param Request $request
     * @return Response
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function listIndex(Request $request)
    {
        $this->reservationRepository->pushCriteria(new ReservationCriteria($request));
        $this->reservationRepository->pushCriteria(new ReservationAuthCriteria($request));
        $reservations = $this->reservationRepository->paginate(50);


        return view('admin.reservations.list')
            ->with([
                'reservations' => $reservations,
            ]);
    }

    /**
     * Show the form for creating a new Reservation.
     *
     * @return Response
     */
    public function create()
    {

        return view('admin.reservations.create');
    }

    /**
     * Store a newly created Reservation in storage.
     *
     * @param CreateReservationRequest $request
     *
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CreateReservationRequest $request)
    {
        $input = $request->only('comment','name', 'last_name', 'email', 'mobile', 'birthday', 'store_id', 'people', 'reservation_date', 'reservation_hour', 'reservation_min');
        Log::info('Buscando usuario');
        $user = User::where('email', $input['email'])
            ->orWhere('mobile', $input['mobile'])
            ->first();
        if ($user == null) {
            Log::info('El usuario no existe así que se intenta crear');
            $user = User::create($input);
            Log::info('El usuario se creo con exito', $user->toArray());
        }

        $input['user_id'] = $user->id;
        $store = Store::find($input['store_id']);
        $input['brand_id'] = $store->brand_id;
        //$input['reservation_time'] = Carbon::createFromTime(, $input['reservation_min'], null, Carbon::createFromFormat('yyyy-mm-dd', $input['reservation_date']));
        $input['reservation_time'] = Carbon::createFromFormat('Y-m-d', $input['reservation_date'])
            ->hour($input['reservation_hour'])
            ->minute($input['reservation_min'])
            ->second(00);
        $input['admin_id'] = Auth::user()->id;
        $reservation = $this->reservationRepository->create($input);
        if ($reservation) {
            Log::debug('la reserva se genero con exito', $reservation->toArray());
            Flash::success('La reserva se guardó con éxito.');

            return redirect(route('admin.reservations.index'));
        } else {
            Log::error('Ocurrio un error al intentar generar la reserva', $input);
            Flash::error('Ocurrio un error al intentar generar la reserva.');
            return redirect(route('admin.reservations.create'));
        }

    }

    /**
     * Display the specified Reservation.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $reservation = $this->reservationRepository->findWithoutFail($id);

        if (empty($reservation)) {
            Flash::error('La reserva no se encontro');

            return redirect(route('admin.reservations.index'));
        }

        return view('admin.reservations.show')->with('reservation', $reservation);
    }

    /**
     * Show the form for editing the specified Reservation.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $reservation = $this->reservationRepository->findWithoutFail($id);

        if (empty($reservation)) {
            Flash::error('La reserva no se encontro');

            return redirect(route('admin.reservations.index'));
        }

        return view('admin.reservations.edit')->with('reservation', $reservation);
    }

    /**
     * Update the specified Reservation in storage.
     *
     * @param  int $id
     * @param UpdateReservationRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateReservationRequest $request)
    {
        $reservation = $this->reservationRepository->findWithoutFail($id);

        if (empty($reservation)) {
            Flash::error('La reserva no se encontro');

            return redirect(route('admin.reservations.index'));
        }

        $reservation = $this->reservationRepository->update($request->all(), $id);

        Flash::success('La reserva se actualizó con éxito.');

        return redirect(route('admin.reservations.index'));
    }

    /**
     * Remove the specified Reservation from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $reservation = $this->reservationRepository->findWithoutFail($id);

        if (empty($reservation)) {
            Flash::error('La reserva no se encontro');

            return redirect(route('admin.reservations.index'));
        }

        $this->reservationRepository->delete($id);

        Flash::success('La reserva deleted successfully.');

        return redirect(route('admin.reservations.index'));
    }


    public function updateReservation(Request $request)
    {
        //dd($request->all());
        $reservation = Reservation::where('id', $request->input('id'))->first();
        // dd($reservation);
        $reservation->people = $request->input('comensales');
        $reservation->reservation_date = $request->input('date');
        $reservation->comment = $request->input('comment');
        $reservation->reservation_time = $request->input('time');
        $reservation->status = $request->input('status');
        $reservation->admin_id = Auth::user()->id;
        $reservation->save();

        return redirect()->back();
    }
    public function testEmail(Request $request){
        if (!empty($request->get('email'))) {
            Log::debug('Se envia un mail de test a '.$request->get('email'));
            Mail::to($request->get('email'))->send(new MailTest());
        }
        die("End");
    }
}
