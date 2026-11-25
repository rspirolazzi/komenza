<!-- Id Field -->
<div class="form-group">
    {!! Form::label('id', 'Id:') !!}
    <p>{!! $brand->id !!}</p>
</div>

<!-- Nombre Field -->
<div class="form-group">
    {!! Form::label('name', 'Nombre:') !!}
    <p>{!! $brand->name !!}</p>
</div>

<!-- Logo Field -->
<div class="form-group">
    {!! Form::label('logo', 'Logo:') !!}
    @if($brand->logo)
        <div><img src="/{{ $brand->logo }}" alt="Logo" style="max-width:120px;max-height:120px;"></div>
    @endif
    <p>{{ $brand->logo }}</p>
</div>

<!-- Fondos Field -->
<div class="form-group">
    {!! Form::label('backgrounds', 'Fondos de la app:') !!}
    <div class="row">
        @foreach($brand->backgrounds as $bg)
            <div class="col-xs-6 col-sm-3 mb-2">
                <img src="/{{ $bg->image }}" alt="Fondo" style="max-width:120px;max-height:120px;">
                <p>{{ $bg->image }}</p>
            </div>
        @endforeach
    </div>
</div>

<!-- Creado Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Creado:') !!}
    <p>{!! $brand->created_at !!}</p>
</div>

<!-- Actualizado Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Actualizado:') !!}
    <p>{!! $brand->updated_at !!}</p>
</div>

<!-- Compañía Field -->
<div class="form-group">
    {!! Form::label('company_id', 'Compañía:') !!}
    <p>{!! $brand->company ? $brand->company->name : '' !!}</p>
</div>

