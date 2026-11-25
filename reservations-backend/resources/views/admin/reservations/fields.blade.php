<div class="form-group col-sm-6">
    <div class="panel panel-default">
        <div class="panel-heading">Cliente</div>
        <div class="panel-body">
            <div class="form-group">
                {!! Form::label('name', 'Nombre:') !!}
                {!! Form::text('name',  null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('last_name', 'Apellido:') !!}
                {!! Form::text('last_name',  null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('email', 'Email:') !!}
                {!! Form::email('email',  null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('mobile', 'Celular:') !!}
                {!! Form::text('mobile',  null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('birthday', 'Cumpleaños:') !!}
                {!! Form::text('birthday',  null, ['class' => 'form-control', 'data-toogle'=>'datepicker']) !!}
                <p class="text-info">El formato de fecha a ingresar es yyyy-mm-dd</p>
            </div>
        </div>
    </div>
</div>
<div class="form-group col-sm-6">
    <div class="panel panel-default">
        <div class="panel-heading">Reserva</div>
        <div class="panel-body">
            <div class="form-group">
                {!! Form::label('store_id', 'Sucursal:') !!}
                <select class="form-control" name="store_id" id="store_id">
                        <option value=""></option>
                    @foreach(App\Models\Admin\Store::all() as $store)
                        <option value="{{$store->id}}">{{$store->brand->shortName}} - {{$store->name}}</option>
                    @endforeach
                </select>

            </div>

            <div class="form-group">
                {!! Form::label('people', 'Comensales:') !!}
                {!! Form::number('people', 2, ['class' => 'form-control']) !!}
            </div>

            <!-- Fecha Field -->
            <div class="form-group">
                {!! Form::label('reservation_date', 'Fecha de reserva:') !!}
                {!! Form::text('reservation_date', Carbon\Carbon::now()->format('Y-m-d'), ['class' => 'form-control', 'data-toogle'=>'datepicker']) !!}
                <p class="text-info">El formato de fecha a ingresar es yyyy-mm-dd</p>
            </div>

            <!-- Reservation Time Field -->
            <div class="form-group">
                {!! Form::label('reservation_time', 'Horario:') !!}
                <div class="row">
                <div class="col-xs-6">{!! Form::select('reservation_hour', [10=>10,11=>11,12=>12,13=>13,14=>14,15=>15,16=>16,17=>17,18=>18,19=>19,20=>20,21=>21,22=>22,23=>23,24=>24], Carbon\Carbon::now()->hour, ['class' => 'form-control']) !!}</div>
                <div class="col-xs-6">{!! Form::select('reservation_min', [0=>'00',30=>30], Carbon\Carbon::now()->minute >= 30 ? 30 : 0, ['class' => 'form-control']) !!}</div>
                </div>
            </div>

            <!-- Admin Notes Field -->
            <div class="form-group">
                {!! Form::label('comment', 'Comentario:') !!}
                {!! Form::textarea('comment', null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.reservations.index') !!}" class="btn btn-default">Cancelar</a>
</div>
@section('scripts')
    <script type="application/javascript">
        $(function(){
            $('[data-toogle=datepicker]').datepicker({
                language: 'es',
                format: "yyyy-mm-dd",
            });
        });
    </script>
@endsection