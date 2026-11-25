<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $reservation->id !!}</p>
</div>

<!-- Cliente Field -->
<div class="form-group">
    {!! Form::label('user_id', 'Cliente:') !!}
    <p>{!! $reservation->user_id !!}</p>
</div>

<!-- Sucursal Field -->
<div class="form-group">
    {!! Form::label('store_id', 'Sucursal:') !!}
    <p>{!! $reservation->store_id !!}</p>
</div>

<!-- Marca Field -->
<div class="form-group">
    {!! Form::label('brand_id', 'Marca:') !!}
    <p>{!! $reservation->brand_id !!}</p>
</div>

<!-- Comensales Field -->
<div class="form-group">
    {!! Form::label('people', 'Comensales:') !!}
    <p>{!! $reservation->people !!}</p>
</div>

<!-- Fecha Field -->
<div class="form-group">
    {!! Form::label('reservation_date', 'Fecha:') !!}
    <p>{!! $reservation->reservation_date !!}</p>
</div>

<!-- Reservation Time Field -->
<div class="form-group">
    {!! Form::label('reservation_time', 'Reservation Time:') !!}
    <p>{!! $reservation->reservation_time !!}</p>
</div>

<!-- Comment Field -->
<div class="form-group">
    {!! Form::label('comment', 'Comment:') !!}
    <p>{!! $reservation->comment !!}</p>
</div>

<!-- Admin Notes Field -->
<div class="form-group">
    {!! Form::label('admin_notes', 'Admin Notes:') !!}
    <p>{!! $reservation->admin_notes !!}</p>
</div>

<!-- Estado Field -->
<div class="form-group">
    {!! Form::label('status', 'Estado:') !!}
    <p>{!! $reservation->status !!}</p>
</div>

<!-- Creado Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Creado:') !!}
    <p>{!! $reservation->created_at !!}</p>
</div>

<!-- Actualizado Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Actualizado:') !!}
    <p>{!! $reservation->updated_at !!}</p>
</div>

