<!-- Id Field -->
<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('id', 'Id:') !!}
        <p class="form-control form-static-control">{!! $user->id !!}</p>
    </div>
</div>

<!-- Nombre Field -->
<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('name', 'Nombre:') !!}
        <p class="form-control form-static-control">{!! $user->name !!}</p>
    </div>
</div>

<!-- Apellido Field -->
<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('last_name', 'Apellido:') !!}
        <p class="form-control form-static-control">{!! $user->last_name !!}</p>
    </div>
</div>


<!-- Email Field -->
<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('email', 'Email:') !!}
        <p class="form-control form-static-control">{!! $user->email !!}</p>
    </div>
</div>

<!-- Sucursal Field -->
<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('store_id', 'Sucursal:') !!}
        <p class="form-control form-static-control">
            @if(!is_null($user->store))
                {!! $user->store->name !!}
            @endif
        </p>
    </div>
</div>

<!-- Creado Field -->
<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('created_at', 'Creado:') !!}
        <p class="form-control form-static-control">{!! $user->created_at !!}</p>
    </div>
</div>

<!-- Actualizado Field -->
<div class="col-md-6">
    <div class="form-group">
        {!! Form::label('updated_at', 'Actualizado:') !!}
        <p class="form-control form-static-control">{!! $user->updated_at !!}</p>
    </div>
</div>

