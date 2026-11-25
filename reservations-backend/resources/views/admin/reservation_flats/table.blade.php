<table class="table table-responsive" id="reservationFlats-table">
    <thead>
        <tr>
            <th>Nombre</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Birthday</th>
        <th>Mobile</th>
        <th>Store</th>
        <th>Comensales</th>
        <th>Fecha</th>
        <th>Reservation Time</th>
        <th>Brand</th>
            <th colspan="3">Acción</th>
        </tr>
    </thead>
    <tbody>
    @foreach($reservationFlats as $reservationFlat)
        <tr>
            <td>{!! $reservationFlat->name !!}</td>
            <td>{!! $reservationFlat->last_name !!}</td>
            <td>{!! $reservationFlat->email !!}</td>
            <td>{!! $reservationFlat->birthday !!}</td>
            <td>{!! $reservationFlat->mobile !!}</td>
            <td>{!! $reservationFlat->store !!}</td>
            <td>{!! $reservationFlat->people !!}</td>
            <td>{!! $reservationFlat->reservation_date !!} ({!! $reservationFlat->reservation->diffForHumans() !!})</td>
            <td>{!! $reservationFlat->reservation_time !!}</td>
            <td>{!! $reservationFlat->brand !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.reservationFlats.destroy', $reservationFlat->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('admin.reservationFlats.show', [$reservationFlat->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('admin.reservationFlats.edit', [$reservationFlat->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>