<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Mail\ReservationExceeds;
use App\Mail\ReservationOk;
use App\Models\Admin\Reservation;
use App\Repositories\ReservationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Response;
use App\Events\Reservation as ReservationEvent;

/**
 * Class ReservationController
 * @package App\Http\Controllers\API
 */
class ReservationAPIController extends AppBaseController
{
    /** @var  ReservationRepository */
    private $reservationRepository;

    public function __construct(ReservationRepository $reservationRepo)
    {
        $this->reservationRepository = $reservationRepo;
    }

    /**
     * Display the specified Reservation.
     * GET|HEAD /reservations/{id}
     *
     * @param Request $request
     * @return Response
     */
    public function show(Request $request)
    {
        \Log::info('ReservationAPIController@show called', [
            'method' => $request->getMethod(),
            'input' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        /** @var Reservation $reservation */
        $brandId = $request->get('brandId');
        $token = $request->get('token');
        $reservation = $this->reservationRepository->findByTokenAndBrandWithoutFail($token, $brandId);

        if (empty($reservation)) {
            return $this->sendError('La reserva no se encontro');
        }

        return $this->sendResponse($reservation->toFrontArray(), 'La reserva se obtuvo con éxito');
    }

    /**
     * Update the specified Reservation in storage.
     * PUT/PATCH /reservations/{id}
     *
     * @param $token
     * @param Request $request
     *
     * @return Response
     */
    public function update($token, Request $request)
    {
        \Log::info('ReservationAPIController@update called', [
            'method' => $request->getMethod(),
            'token' => $token,
            'input' => $request->all(),
            'headers' => $request->headers->all()
        ]);
        try {
            $input = $request->only(['store_id','reservation_time','reservation_date','people','comment',]);
            
            /** @var Reservation $reservation */
            $reservation = $this->reservationRepository->findByTokenWithoutFail($token);
            $reservation->fill($input);

            if (empty($reservation)) {
                return $this->sendError('La reserva no se encontro');
            }

            $brand = $reservation->brand;
            $store = $reservation->store;

            if ($store == null) {
                Log::error('La sucursal elegida no existe', ['reservation' => $reservation]);
                return $this->sendError('La sucursal elegida no existe');
            }

            if ($brand == null) {
                Log::error('La marca seleccionada no existe', ['reservation' => $reservation]);
                return $this->sendError('La marca seleccionada no existe');
            }
            if ($store->brand_id != $brand->id) {
                Log::error('Ocurrio un error al seleccionar la sucursal', ['reservation' => $reservation]);
                return $this->sendError('Ocurrio un error al seleccionar la sucursal');
            }

            if (!config('komenza.allow_reservation_store', true)) {
                Log::info('Validando si el usuario ya genero una reserva para este local');
                $reservationExist = Reservation::where('user_id', $reservation->user->id)
                    ->where('store_id', $reservation->store->id)
                    ->where('reservation_date', '>=', $reservation->reservation_date)
                    ->where('reservation_date', '<=', $reservation->reservation_date)
                    ->whereIn('status', [Reservation::$STATUS_OPEN, Reservation::$STATUS_MAXIMUM_CAPACITY])
                    ->where('id', '<>', $reservation->id)
                    ->first();

                if ($reservationExist != null && $reservationExist->id != null) {
                    Log::error('El usuario ya genero una reserva para este local en este día', [
                        'user' => $reservation->user->toArray(),
                        'reservation' => $reservation->toArray(),
                    ]);
                    return $this->sendError('Ya tenes una reserva en curso para esta sucursal. Podes ingresar al email que te enviamos para modificar o cancelar la reserva.');
                }
            }

            if (!config('komenza.allow_reservation_brand', true)) {
                Log::info('Validando si el usuario ya genero una reserva para esta marca');
                $reservationExist = Reservation::where('user_id', $reservation->user->id)
                    ->where('brand_id', $reservation->brand->id)
                    ->where('reservation_date', '>=', $reservation->reservation_date)
                    ->where('reservation_date', '<=', $reservation->reservation_date)
                    ->whereIn('status', [Reservation::$STATUS_OPEN, Reservation::$STATUS_MAXIMUM_CAPACITY])
                    ->where('id', '<>', $reservation->id)
                    ->first();

                if ($reservationExist != null && $reservationExist->id != null) {
                    Log::error('El usuario ya genero una reserva para esta marca en este día', [
                        'user' => $reservation->user->toArray(),
                        'reservation' => $reservation->toArray(),
                    ]);
                    return $this->sendError('Ya tenes una reserva en curso. Solo podes realizar una por dìa. Podes ingresar al email que te enviamos para modificar o cancelar la reserva.');
                }
            }

            Log::info('Se intenta actualizar la reserva');

            $reservation = $this->reservationRepository->update($input, $reservation->id);

            Log::info('La reserva se actualizo con exito', $reservation->toArray());
            event(new ReservationEvent($reservation));
            Log::info('Validando si la reserva excede el limite de personas diario');
            $count = Reservation::where('reservation_date', '>=', $reservation->reservation_date)
                ->where('reservation_date', '<=', $reservation->reservation_date)
                ->where('store_id', $reservation->store->id)
                ->whereIn('status', [Reservation::$STATUS_OPEN, Reservation::$STATUS_ATTENDED])
                ->sum('people');
            Log::debug('Cantidad de espacios reservados en el día de la reserva', [
                'date' => $reservation->reservation_date,
                'count' => $count,
                'store' => $reservation->store->name,
                'max' => $reservation->store->max_persons_day,
            ]);
            if ((((int)$reservation->store->max_persons_day >= ((int)$count + (int)$reservation->people)))) {
                Log::debug('Se envia el email de confirmacion');
                Mail::to($reservation->user->email)->send(new ReservationOk($reservation));
            } else {
                $reservation->status = Reservation::$STATUS_MAXIMUM_CAPACITY;
                $reservation->save();
                Log::debug('Se envia el email de confirmacion pero con capacidad excedida', ['store_id' => $reservation->store->id, 'cantidad' => $count, 'maximo' => $reservation->store->max_persons_day]);
                Mail::to($reservation->user->email)->send(new ReservationExceeds($reservation));
            }

            return $this->sendResponse($reservation->toFrontArray(), 'La reserva se actualizó con éxito');

        } catch (\Exception $ex) {
            Log::error('Ocurrio un error al intentar generar la reserva', [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
                'message' => $ex->getMessage(),
            ]);
            return $this->sendError('Ocurrio un error inesperado');
        }
    }

    /**
     * Update the specified Reservation in storage.
     * PUT/PATCH /reservations/{id}
     *
     * @param $token
     * @return Response
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function cancel($token)
    {
        \Log::info('ReservationAPIController@cancel called', [
            'token' => $token,
        ]);
        /** @var Reservation $reservation */
        $reservation = $this->reservationRepository->findByTokenWithoutFail($token);

        if (empty($reservation)) {
            return $this->sendError('La reserva no se encontro');
        }

        $reservation = $this->reservationRepository->update(['status' => Reservation::$STATUS_CANCELED], $reservation->id);

        return $this->sendResponse($reservation->toFrontArray(), 'La reserva se canceló con éxito');
    }
}
