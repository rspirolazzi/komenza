<!-- Cliente Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', 'Cliente:') !!}
    {!! Form::select('user_id', [], null, ['class' => 'form-control']) !!}
</div>

<!-- Sucursal Field -->
<div class="form-group col-sm-6">
    {!! Form::label('store_id', 'Sucursal:') !!}
    {!! Form::select('store_id', [], null, ['class' => 'form-control']) !!}
</div>

<!-- Marca Field -->
<div class="form-group col-sm-6">
    {!! Form::label('brand_id', 'Marca:') !!}
    {!! Form::select('brand_id', [], null, ['class' => 'form-control']) !!}
</div>

<!-- Comensales Field -->
<div class="form-group col-sm-6">
    {!! Form::label('people', 'Comensales:') !!}
    {!! Form::select('people', [], null, ['class' => 'form-control']) !!}
</div>

<!-- Fecha Field -->
<div class="form-group col-sm-6">
    {!! Form::label('reservation_date', 'Fecha:') !!}
    {!! Form::date('reservation_date', null, ['class' => 'form-control']) !!}
</div>

<!-- Reservation Time Field -->
<div class="form-group col-sm-6">
    {!! Form::label('reservation_time', 'Reservation Time:') !!}
    {!! Form::text('reservation_time', null, ['class' => 'form-control']) !!}
</div>

<!-- Comment Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('comment', 'Comment:') !!}
    {!! Form::textarea('comment', null, ['class' => 'form-control']) !!}
</div>

<!-- Admin Notes Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('admin_notes', 'Admin Notes:') !!}
    {!! Form::textarea('admin_notes', null, ['class' => 'form-control']) !!}
</div>

<!-- Estado Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Estado:') !!}
    {!! Form::select('status', [], null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.reservations.index') !!}" class="btn btn-default">Cancelar</a>
</div>
