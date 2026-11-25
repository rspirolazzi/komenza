<!-- Nombre Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Nombre:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Marca Field -->
<div class="form-group col-sm-6">
    {!! Form::label('brand_id', 'Marca:') !!}
    {!! Form::select('brand_id', $brands, null, ['class' => 'form-control']) !!}
</div>

<!-- Dirección Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('address', 'Dirección:') !!}
    {!! Form::text('address', null, ['class' => 'form-control']) !!}
</div>

<!-- Teléfono Field -->
<div class="form-group col-sm-6">
    {!! Form::label('telephone', 'Teléfono:') !!}
    {!! Form::text('telephone', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('max_persons', 'Personas:') !!}
    {!! Form::number('max_persons', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-6">
    {!! Form::label('max_persons_day', 'Máximo de personas por día:') !!}
    {!! Form::number('max_persons_day', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group col-sm-12">
    <div class="panel panel-default">
        <h5 class="panel-heading">Configuracion de localizacion</h5>
        <div class="panel-body">
            <div class="row">
                <div class="form-group col-sm-6">
                    {!! Form::label('lat', 'Latitud:') !!}
                    {!! Form::text('lat', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group col-sm-6">
                    {!! Form::label('lon', 'Longitud:') !!}
                    {!! Form::text('lon', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group col-sm-6">
                    {!! Form::label('map_zoom', 'Zoom del mapa:') !!}
                    {!! Form::select('map_zoom',[
                    20=>20,
                    19=>19,
                    18=>18,
                    17=>17,
                    16=>16,
                    15=>15,
                    14=>14,] , null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Texto de horario Field -->
<div class="form-group col-sm-12">
    {!! Form::label('text_schedule', 'Texto de horario:') !!}
    {!! Form::textarea('text_schedule', null, ['class' => 'form-control', 'rows' => 3]) !!}
</div>
