<?php

namespace App\Http\Controllers\API;

use App\Events\Reservation as ReservationEvent;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateReservationFlatAPIRequest;
use App\Mail\ReservationExceeds;
use App\Mail\ReservationOk;
use App\Models\Admin\Brand;
use App\Models\Admin\Reservation;
use App\Models\Admin\ReservationFlat;
use App\Models\Admin\Store;
use App\Repositories\ReservationFlatRepository;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Response;

/**
 * Class ReservationFlatController
 * @package App\Http\Controllers\API
 */
class ReservationFlatAPIController extends AppBaseController
{
    public static $ERROR_MAXIMUM_CAPACITY = 'MAXIMUM_CAPACITY';
    public static $ERROR_MAXIMUM_BY_STORE= 'MAXIMUM_BY_STORE';
    public static $ERROR_MAXIMUM_BY_BRAND= 'MAXIMUM_BY_BRAND';
    public static $ERROR_UNKNOW= 'UNKNOW';
    /** @var  ReservationFlatRepository */
    private $reservationFlatRepository;

    public function __construct(ReservationFlatRepository $reservationFlatRepo)
    {
        $this->reservationFlatRepository = $reservationFlatRepo;
    }

    /**
     * Store a newly created ReservationFlat in storage.
     * POST /reservationFlats
     *
     * @param CreateReservationFlatAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateReservationFlatAPIRequest $request)
    {
        // Logging de entrada al método store
        \Log::info('ReservationFlatAPIController@store called', [
            'method' => $request->getMethod(),
            'input' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        try {
            $input = $request->all();
            $reservationFlat = $this->reservationFlatRepository->create($input);
            if ($reservationFlat) {
                $brand = Brand::find($reservationFlat->brand_id);
                if ($brand) {
                    $reservationFlat->brand = $brand->name;
                }
                $store = Store::find($reservationFlat->store_id);
                if ($store) {
                    $reservationFlat->store = $store->name;
                }

                if($store == null){
                    Log::error('La sucursal elegida no existe', ['reservationFlat'=>$reservationFlat]);
                    return $this->sendError('La sucursal elegida no existe');
                }

                if($brand == null){
                    Log::error('La marca seleccionada no existe', ['reservationFlat'=>$reservationFlat]);
                    return $this->sendError('La marca seleccionada no existe');
                }
                if($store->brand_id != $brand->id){
                    Log::error('Ocurrio un error al seleccionar la sucursal', ['reservationFlat'=>$reservationFlat]);
                    return $this->sendError('Ocurrio un error al seleccionar la sucursal');
                }
                Log::info('Intentando guardar la reserva Flat', $reservationFlat->toArray());
                $reservationFlat->setToken();
                if ($reservationFlat->save()) {
                    Log::info('Buscando usuario');
                    $user = User::where('email', $reservationFlat->email)
                        ->orWhere('mobile', $reservationFlat->mobile)
                        ->first();
                    if ($user == null) {
                        Log::info('El usuario no existe así que se intenta crear');
                        $user = User::create($reservationFlat->toArray());
                        Log::info('El usuario se creo con exito', $user->toArray());
                    }
                    $data = $reservationFlat->toArray();
                    $data['user_id'] = $user->id;

                    if(!config('komenza.allow_reservation_store', true)){
                        Log::info('Validando si el usuario ya genero una reserva para este local');
                        $reservationExist = Reservation::where('user_id', $user->id)
                            ->where('store_id', $reservationFlat->store_id)
                            ->where('reservation_date', '>=', $reservationFlat->reservation_date)
                            ->where('reservation_date', '<=', $reservationFlat->reservation_date)
                            ->whereIn('status', [Reservation::$STATUS_OPEN,Reservation::$STATUS_MAXIMUM_CAPACITY])
                            ->first()
                        ;

                        if ($reservationExist!=null && $reservationExist->id != null) {
                            Log::error('El usuario ya genero una reserva para este local en este día', [
                                'user' => $user->toArray(),
                                'reservationFlat' => $reservationFlat->toArray(),
                            ]);
                            return $this->sendError('Ya tenes una reserva en curso para esta sucursal. Podes ingresar al email que te enviamos para modificar o cancelar la reserva.');
                        }
                    }

                    if(!config('komenza.allow_reservation_brand', true)){
                        Log::info('Validando si el usuario ya genero una reserva para esta marca');
                        $reservationExist = Reservation::where('user_id', $user->id)
                            ->where('brand_id', $reservationFlat->brand_id)
                            ->where('reservation_date', '>=', $reservationFlat->reservation_date)
                            ->where('reservation_date', '<=', $reservationFlat->reservation_date)
                            ->whereIn('status', [Reservation::$STATUS_OPEN,Reservation::$STATUS_MAXIMUM_CAPACITY])
                            ->first()
                        ;

                        if ($reservationExist!=null && $reservationExist->id != null) {
                            Log::error('El usuario ya genero una reserva para esta marca en este día', [
                                'user' => $user->toArray(),
                                'reservationFlat' => $reservationFlat->toArray(),
                            ]);
                            return $this->sendError('Ya tenes una reserva en curso. Solo podes realizar una por dìa. Podes ingresar al email que te enviamos para modificar o cancelar la reserva.');
                        }
                    }

                    Log::info('Se intenta generar la reserva');
                    $reservation = Reservation::create($data);
                    Log::info('La reserva se creo con exito', $reservation->toArray());
                    event(new ReservationEvent($reservation));
                    Log::info('Validando si la reserva excede el limite de personas diario');
                    $count = Reservation::where('reservation_date', '>=', $reservation->reservation_date)
                        ->where('reservation_date', '<=', $reservation->reservation_date)
                        ->where('store_id', $reservationFlat->store_id)
                        ->whereIn('status', [Reservation::$STATUS_OPEN, Reservation::$STATUS_ATTENDED])
                        ->sum('people');
                    Log::debug('Cantidad de espacios reservados en el día de la reserva', [
                        'date' => $reservation->reservation_date,
                        'count' => $count,
                        'store' => $reservation->store->name,
                        'max' => $reservation->store->max_persons_day,
                    ]);
                    if ((((int)$reservation->store->max_persons_day>=((int) $count + (int)$reservation->people)))) {
                        Log::debug('Se envia el email de confirmacion');
                        Mail::to($reservation->user->email)->send(new ReservationOk($reservation));
                    } else {
                        $reservation->status = Reservation::$STATUS_MAXIMUM_CAPACITY;
                        $reservation->save();
                        Log::debug('Se envia el email de confirmacion pero con capacidad excedida', ['store_id' => $reservation->store_id, 'cantidad' => $count, 'maximo' => $reservation->store->max_persons_day]);
                        //Mail::to($reservation->user->email)->send(new ReservationExceeds($reservation));
                    }
                }
            }
            $response = $reservation->toFrontArray();
            $response['allowReservation'] = !($reservation->status === Reservation::$STATUS_MAXIMUM_CAPACITY);
            return $this->sendResponse($response, 'La reserva Flat se guardó con éxito');
        } catch (\Exception $ex) {
            Log::error('Ocurrio un error al intentar generar la reserva', [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
                'message' => $ex->getMessage(),
                'trace' => $ex->getTraceAsString(), // Agrega el stack trace aquí
            ]);
            return $this->sendError('Ocurrio un error inesperado');
        }
    }

    /**
     * Display the specified ReservationFlat.
     * GET|HEAD /reservationFlats/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ReservationFlat $reservationFlat */
        $reservationFlat = $this->reservationFlatRepository->findWithoutFail($id);

        if (empty($reservationFlat)) {
            return $this->sendError('La reserva Flat no se encontro');
        }

        return $this->sendResponse($reservationFlat->toArray(), 'La reserva Flat se obtuvo con éxito');
    }


}
