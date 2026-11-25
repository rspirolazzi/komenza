<!-- Nombre Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Nombre:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Last Nombre Field -->
<div class="form-group col-sm-6">
    {!! Form::label('last_name', 'Last Name:') !!}
    {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', 'Email:') !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
</div>

<!-- Birthday Field -->
<div class="form-group col-sm-6">
    {!! Form::label('birthday', 'Birthday:') !!}
    {!! Form::date('birthday', null, ['class' => 'form-control']) !!}
</div>

<!-- Mobile Field -->
<div class="form-group col-sm-6">
    {!! Form::label('mobile', 'Mobile:') !!}
    {!! Form::text('mobile', null, ['class' => 'form-control']) !!}
</div>

<!-- Store Field -->
<div class="form-group col-sm-6">
    {!! Form::label('store', 'Store:') !!}
    {!! Form::select('store', ], null, ['class' => 'form-control']) !!}
</div>

<!-- Comensales Field -->
<div class="form-group col-sm-6">
    {!! Form::label('people', 'Comensales:') !!}
    {!! Form::select('people', ], null, ['class' => 'form-control']) !!}
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

<!-- Brand Field -->
<div class="form-group col-sm-6">
    {!! Form::label('brand', 'Brand:') !!}
    {!! Form::select('brand', ], null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.reservationFlats.index') !!}" class="btn btn-default">Cancelar</a>
</div>
