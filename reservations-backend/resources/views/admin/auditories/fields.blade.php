<!-- Nombre Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Nombre:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Marca Field -->
<div class="form-group col-sm-6">
    {!! Form::label('brand_id', 'Marca:') !!}
    {!! Form::select('brand_id', ], null, ['class' => 'form-control']) !!}
</div>

<!-- Dirección Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('address', 'Dirección:') !!}
    {!! Form::textarea('address', null, ['class' => 'form-control']) !!}
</div>

<!-- Teléfono Field -->
<div class="form-group col-sm-6">
    {!! Form::label('telephone', 'Teléfono:') !!}
    {!! Form::text('telephone', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.stores.index') !!}" class="btn btn-default">Cancelar</a>
</div>
