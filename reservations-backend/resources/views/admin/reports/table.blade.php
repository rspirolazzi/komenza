<table class="table table-responsive" id="reservations-table">
    <thead>
    <tr>
        <th>Cliente</th>
        <th>Celular</th>
        <th>Sucursal</th>
        <th>Marca</th>
        <th>Comensales</th>
        <th>Fecha</th>
        <th>Estado</th>
        <th colspan="3">Acción</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reservations as $reservation)
        <tr class="reservation_id" id="{{ $reservation->id }}">
            <td id="user_name_table">{!! $reservation->user_name !!}</td>
            <td id="telephone_table">{!! $reservation->user_telephone or 'sin telefono' !!}</td>
            <td>{!! $reservation->store_name !!}</td>
            <td>{!! $reservation->brand_name !!}</td>
            <td id="people">{!! $reservation->people !!}</td>
            <td id="date">{!! $reservation->reservation_date !!}</td>
            <td id="status">{!! $reservation->status !!}</td>
            <input type="hidden" name="comment" class="comment" value="{{ $reservation->comment }}">
            <input type="hidden" name="time" class="time" value="{{ $reservation->reservation_time }}">
            <td>
                {!! Form::open(['route' => ['admin.reservations.destroy', $reservation->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('admin.reservations.show', [$reservation->id]) !!}"
                       class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('admin.reservations.edit', [$reservation->id]) !!}"
                       class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>