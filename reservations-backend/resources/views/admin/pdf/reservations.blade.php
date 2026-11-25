@extends('layouts.pdf')

@section('content')
    <table class="table table-striped">
        <colgroup>
            <col width="20%"/>
            <col width="10%"/>
            <col width="20%"/>
            <col width="10%"/>
            <col width="5%"/>
            <col width="5%"/>
            <col width="15%"/>
            <col width="1%"/>
        </colgroup>
        <thead>
        <tr>
            <th>Cliente</th>
            <th>Celular</th>
            <th>Sucursal</th>
            <th>Comensales</th>
            <th>Fecha</th>
            <th>Horario</th>
            <th>Comentario</th>
        </tr>
        </thead>
        <tbody>
        @if(sizeof($reservations)>0)
            @foreach($reservations as $reservation)
                <tr>
                    <td >{!! $reservation->user->fullNameWithEmail !!}</td>
                    <td >{!! $reservation->user->mobile or 'sin telefono' !!}</td>
                    <td><span class="badge badge-light">{!! $reservation->store->brand->shortName !!}</span> {!! $reservation->store->name !!}</td>
                    <td >{!! $reservation->people !!}</td>
                    <td>{!! $reservation->reservation_date->format('d/m') !!} <span class="hide" >{!! $reservation->reservation_date->format('Y-n-d') !!}</span></td>
                    <td >{!! App\Helper\Helper::getFormatTime($reservation->reservation_time) !!}</td>
                    <td><span data-toggle="tooltip" title="{!! $reservation->comment !!}">{!! str_limit($reservation->comment, 10, '...') !!}</span></td>
                    <td >{!! Form::reservationStatus($reservation->status) !!}</td>
                    <td>{{ $reservation->comment }}</td>
                </tr>
            @endforeach
        @else
            <tr class="info text-center">
                <td colspan="100%">No hay registros</td>
            </tr>
        @endif
        </tbody>
    </table>
@endsection