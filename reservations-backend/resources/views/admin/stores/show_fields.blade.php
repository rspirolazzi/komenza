<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $store->id !!}</p>
</div>

<!-- Nombre Field -->
<div class="form-group">
    {!! Form::label('name', 'Nombre:') !!}
    <p>{!! $store->name !!}</p>
</div>

<!-- Marca Field -->
<div class="form-group">
    {!! Form::label('brand_id', 'Marca:') !!}
    <p>{!! $store->brand->name !!}</p>
</div>

<!-- Dirección Field -->
<div class="form-group">
    {!! Form::label('address', 'Dirección:') !!}
    <p>{!! $store->address !!}</p>
</div>

<!-- Teléfono Field -->
<div class="form-group">
    {!! Form::label('telephone', 'Teléfono:') !!}
    <p>{!! $store->telephone !!}</p>
</div>

<!-- Creado Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Creado:') !!}
    <p>{!! $store->created_at !!}</p>
</div>

<!-- Actualizado Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Actualizado:') !!}
    <p>{!! $store->updated_at !!}</p>
</div>

