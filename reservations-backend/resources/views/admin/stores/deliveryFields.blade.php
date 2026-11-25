@section('scripts')
    <script>
        function delete_delivery_time(id){
            if(confirm('¿Desea eliminar este horario?')){
                $('tr#delivery_time_' + id).fadeOut(function(){
                    $(this).remove()
                });
            }
        }
    </script>
@endsection
<div class="panel panel-default">
    <div class="panel-heading"><label for="type_custom">Horario definido para algunos dias </label>
    </div>
    <div class="panel-body">
        <table class="table table-strpied" id="options">
            <tr>
                <th class="text-center">Desde</th>
                <th class="text-center">Hasta</th>
                <th class="text-center"></th>
            </tr>
            @if(isset($store->delivery_times))
                @foreach($store->delivery_times as $index=> $custom)
                    <tr  id="delivery_time_{!! $index !!}">
                        <td>
                            <div class="form-group">
                                <div class="col-md-6">{!! Form::select('delivery_time['.($index+1).'][start_day]', [''=>'Día',1=>'Lunes', 2=> 'Martes', 3=> 'Miercoles' ,4=>'Jueves', 5=> 'Viernes', 6=> 'Sabado', 0=> 'Domingo'], isset($custom['start_day']) ? $custom['start_day'] : null , ['class'=>'form-control', 'required'=>true,]) !!}</div>
                                <div class="col-md-3">{!! Form::select('delivery_time['.($index+1).'][start_hour]', [1=>'01', 2=> '02',3=>'03',4=>'04',5=>'05',6=>'06',7=>'07',8=>'08', 9=> '09',10=>'10',11=>'11', 12=> '12',13=>'13',14=>'14',15=>'15',16=>'16',17=>'17',18=>'18', 19=> '19',20=>'20',21=>'21', 22=> '22',23=>'23', 24=>'24'], isset($custom['start_hour']) ? $custom['start_hour'] : null, ['class'=>'form-control', 'required'=>true,]) !!}</div>
                                <div class="col-md-3">{!! Form::select('delivery_time['.($index+1).'][start_min]', [0=>'00',30=>'30'], isset($custom['start_min']) ? $custom['start_min'] : null, ['class'=>'form-control', 'required'=>true,]) !!}</div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <div class="col-md-6">{!! Form::select('delivery_time['.($index+1).'][end_day]', [''=>'Día',1=>'Lunes', 2=> 'Martes', 3=> 'Miercoles' ,4=>'Jueves', 5=> 'Viernes', 6=> 'Sabado', 0=> 'Domingo'], isset($custom['end_day']) ? $custom['end_day'] : null, ['class'=>'form-control', 'required'=>true,]) !!}</div>
                                <div class="col-md-3">{!! Form::select('delivery_time['.($index+1).'][end_hour]', [1=>'01', 2=> '02',3=>'03',4=>'04',5=>'05',6=>'06',7=>'07',8=>'08', 9=> '09',10=>'10',11=>'11', 12=> '12',13=>'13',14=>'14',15=>'15',16=>'16',17=>'17',18=>'18', 19=> '19',20=>'20',21=>'21', 22=> '22',23=>'23', 24=>'24'], isset($custom['end_hour']) ? $custom['end_hour'] : null, ['class'=>'form-control', 'required'=>true,]) !!}</div>
                                <div class="col-md-3">{!! Form::select('delivery_time['.($index+1).'][end_min]', [0=>'00',30=>'30'], isset($custom['end_min']) ? $custom['end_min'] : null, ['class'=>'form-control', 'required'=>true,]) !!}</div>
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning" onclick="delete_delivery_time({!! $index !!})"><i class="fa fa-times"></i></button>
                        </td>
                    </tr>
                @endforeach
            @endif
            <tr>
                <td>
                    <div class="form-group">
                        <div class="col-md-6">{!! Form::select('delivery_time[1000][start_day]', [''=>'Día',1=>'Lunes', 2=> 'Martes', 3=> 'Miercoles' ,4=>'Jueves', 5=> 'Viernes', 6=> 'Sabado', 0=> 'Domingo'], null, ['class'=>'form-control',]) !!}</div>
                        <div class="col-md-3">{!! Form::select('delivery_time[1000][start_hour]', [1=>'01', 2=> '02',3=>'03',4=>'04',5=>'05',6=>'06',7=>'07',8=>'08', 9=> '09',10=>'10',11=>'11', 12=> '12',13=>'13',14=>'14',15=>'15',16=>'16',17=>'17',18=>'18', 19=> '19',20=>'20',21=>'21', 22=> '22',23=>'23',24=>'24'], null, ['class'=>'form-control',]) !!}</div>
                        <div class="col-md-3">{!! Form::select('delivery_time[1000][start_min]', [0=>'00',30=>'30'], null, ['class'=>'form-control',]) !!}</div>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <div class="col-md-6">{!! Form::select('delivery_time[1000][end_day]', [''=>'Día',1=>'Lunes', 2=> 'Martes', 3=> 'Miercoles' ,4=>'Jueves', 5=> 'Viernes', 6=> 'Sabado', 0=> 'Domingo'], null, ['class'=>'form-control',]) !!}</div>
                        <div class="col-md-3">{!! Form::select('delivery_time[1000][end_hour]', [1=>'01', 2=> '02',3=>'03',4=>'04',5=>'05',6=>'06',7=>'07',8=>'08', 9=> '09',10=>'10',11=>'11', 12=> '12',13=>'13',14=>'14',15=>'15',16=>'16',17=>'17',18=>'18', 19=> '19',20=>'20',21=>'21', 22=> '22',23=>'23',24=>'24'], null, ['class'=>'form-control',]) !!}</div>
                        <div class="col-md-3">{!! Form::select('delivery_time[1000][end_min]', [0=>'00',30=>'30'], null, ['class'=>'form-control',]) !!}</div>
                    </div>
                </td>
                <td>
                    <button type="submit" name="submit" value="add_and_continue" class="btn btn-sm btn-info"><i class="fa fa-plus"></i></button>
                </td>
            </tr>
        </table>
    </div>
</div>