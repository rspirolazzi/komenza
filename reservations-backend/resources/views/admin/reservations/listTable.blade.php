<table class="table table-responsive" id="reservations-table">
    <thead>
    <tr>
        <th>Cliente</th>
        <th>Celular</th>
        <th>Sucursal</th>
        <th>Comensales</th>
        <th>Fecha</th>
        <th>Horario</th>
        <th>Estado</th>
    </tr>
    </thead>
    <tbody>
    @if(sizeof($reservations)>0)
        @foreach($reservations as $reservation)
            <tr class="reservation_id" id="{{ $reservation->id }}" data-json="{{json_encode($reservation->toArray())}}">
                <td id="user_name_table">{!! $reservation->user->fullName !!}</td>
                <td id="telephone_table">{!! $reservation->user->mobile or 'sin telefono' !!}</td>
                <td><span class="badge badge-light">{!! $reservation->store->brand->shortName !!}</span> {!! $reservation->store->name !!}</td>
                <td id="people">{!! $reservation->people !!}</td>
                <td>{!! $reservation->reservation_date->format('d/m') !!} <span class="hide" id="date">{!! $reservation->reservation_date->format('Y-n-d') !!}</span></td>
                <td id="time">{!! $reservation->reservation_time !!}</td>
                <td id="status">{!! Form::reservationStatus($reservation->status) !!}</td>
                <input type="hidden" name="comment" class="comment" value="{{ $reservation->comment }}">
                <input type="hidden" name="time" class="time" value="{{ $reservation->reservation_time }}">
            </tr>
        @endforeach
        @else
        <tr class="info text-center">
            <td colspan="100%">No hay registros</td>
        </tr>
        @endif
    </tbody>
</table>