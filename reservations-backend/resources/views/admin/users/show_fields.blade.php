<!-- Id Field -->
<div class="form-group col-md-6">
    {!! Form::label('id', 'Id:') !!}
    <p class="form-control form-static-control">{!! $user->id !!}</p>
</div>

<!-- Nombre Field -->
<div class="form-group col-md-6">
    {!! Form::label('name', 'Nombre:') !!}
    <p class="form-control form-static-control">{!! $user->name !!}</p>
</div>

<!-- Last Name Field -->
<div class="form-group col-md-6">
    {!! Form::label('last_name', 'Last Name:') !!}
    <p class="form-control form-static-control">{!! $user->last_name !!}</p>
</div>

<!-- Teléfono Field -->
<div class="form-group col-md-6">
    {!! Form::label('telephone', 'Teléfono:') !!}
    <p class="form-control form-static-control">{!! $user->telephone !!}</p>
</div>

<!-- birthday Field -->
<div class="form-group col-md-6">
    {!! Form::label('birthday', 'Fecha Nac:') !!}
    <p class="form-control form-static-control">{!! $user->birthday !!}</p>
</div>

<!-- Mobile Field -->
<div class="form-group col-md-6">
    {!! Form::label('mobile', 'Mobile:') !!}
    <p class="form-control form-static-control">{!! $user->mobile !!}</p>
</div>

<!-- Email Field -->
<div class="form-group col-md-6">
    {!! Form::label('email', 'Email:') !!}
    <p class="form-control form-static-control">{!! $user->email !!}</p>
</div>

<!-- Sucursal Field -->
<div class="form-group col-md-6">
    {!! Form::label('store_id', 'Sucursal:') !!}
    <p class="form-control form-static-control">{!! $user->store_id !!}</p>
</div>

<!-- Creado Field -->
<div class="form-group col-md-6">
    {!! Form::label('created_at', 'Creado:') !!}
    <p class="form-control form-static-control">{!! $user->created_at !!}</p>
</div>

<!-- Actualizado Field -->
<div class="form-group col-md-6">
    {!! Form::label('updated_at', 'Actualizado:') !!}
    <p class="form-control form-static-control">{!! $user->updated_at !!}</p>
</div>

