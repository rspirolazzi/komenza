@extends('layouts.app')
@section('css')

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">

@endsection

@section('content')
    <section class="content-header">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="pull-left">Reportes</h1>
            </div>
        </div>
    </section>
    <div class="content" style="background: white">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>

        <form action="{{route('admin.reports.index')}}" method="POST" id="report">
            <div class="panel panel-primary">
                <div class="panel-heading">Reporte de visitas en este mes
                    <div class="pull-right"
                         style="width: 150px">{{ Form::select('report1_current_month', $months->pluck('date', 'date'), \Request::get('report1_current_month', date('m-Y')), ['class'=>'form-control input-sm']) }}</div>
                </div>
                <div class="panel-body">
                    <div class="col-sm-12">
                        <p class="text-right"><button name="export_to_pdf" value="1" class="btn btn-info btn-sm" type="submit">Generar reporte PDF <i
                                        class="fa fa-file-pdf-o"></i></button></p>
                        <table class="tb table table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th>Sucursal</th>
                                <th>Marca</th>
                                <th># de Reservaciones</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($report1 as $index => $data)
                                <tr>
                                    <td>{{ $data->store_name }}</td>
                                    <td>{{ $data->brand_name }}</td>
                                    <td>{{ $data->count }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Tienda</th>
                                <th>Marca</th>
                                <th># de Reservaciones</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="panel panel-primary">
                <div class="panel-heading">Usuarios con más reservaciones
                    <div class="pull-right"
                         style="width: 150px">{{ Form::select('report2_current_month', $months->pluck('date', 'date'), \Request::get('report2_current_month', date('m-Y')), ['class'=>'form-control input-sm']) }}</div>
                </div>
                <div class="panel-body">
                    <div class="col-sm-12">
                        <p class="text-right"><button name="export_to_pdf" value="2" class="btn btn-info btn-sm" type="submit">Generar reporte PDF <i
                                        class="fa fa-file-pdf-o"></i></button></p>
                        <table class="tb table table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th>Usuario</th>
                                <th># de Reservaciones</th>

                            </tr>
                            </thead>
                            <tbody>

                            @foreach($report2 as $index => $data)
                                <tr>
                                    <td>{{ $data->email }} <b>{{ $data->name}}</b></td>
                                    <td>{{ $data->count }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Usuario</th>
                                <th># de Reservaciones</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="panel panel-primary">
                <div class="panel-heading">Usuarios con más reservaciones en sucursales
                    <div class="pull-right"
                         style="width: 150px">
                        {{ Form::select('report3_current_month', $months->pluck('date', 'date'), \Request::get('report3_current_month', date('m-Y')), ['class'=>'form-control input-sm']) }}
                    </div>
                </div>
                <div class="panel-body">
                    <div class="col-sm-12">
                        <p class="text-right"><button name="export_to_pdf" value="3" class="btn btn-info btn-sm" type="submit">Generar reporte PDF <i
                                        class="fa fa-file-pdf-o"></i></button></p>
                        <table class="tb table table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Sucursal</th>
                                <th># de Reservaciones</th>

                            </tr>
                            </thead>
                            <tbody>

                            @foreach($report3 as $index => $data)
                                <tr>
                                    <td>{{ $data->email }} <b>{{ $data->name}}</b></td>
                                    <td>{{ $data->brand_name }} - {{ $data->store_name }}</td>
                                    <td>{{ $data->count }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Usuario</th>
                                <th>Sucursal</th>
                                <th># de Reservaciones</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="panel panel-primary">
                <div class="panel-heading">Cantidad de reservas por turno y sucursal
                    <div class="pull-right"
                         style="width: 150px">{{ Form::select('report4_current_month', $months->pluck('date', 'date'), \Request::get('report4_current_month', date('m-Y')), ['class'=>'form-control input-sm']) }}</div>
                </div>
                <div class="panel-body">
                    <div class="col-sm-12">
                        <p class="text-right"><button name="export_to_pdf" value="4" class="btn btn-info btn-sm" type="submit">Generar reporte PDF <i
                                        class="fa fa-file-pdf-o"></i></button></p>
                        <table class="tb table table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th>Turno</th>
                                <th>Sucursal</th>
                                <th># de Reservaciones</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($report4 as $index => $data)
                                <tr>
                                    <td>{{ $data->periodOfDay}}</td>
                                    <td>{{ $data->brand_name }} - {{ $data->store_name }}</td>
                                    <td>{{ $data->count }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Turno</th>
                                <th>Sucursal</th>
                                <th># de Reservaciones</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>


            <div class="panel panel-primary">
                <div class="panel-heading">Usuarios que reservaron</div>
                <div class="panel-body">
                    <div class="col-sm-12">
                        <p class="text-right"><button name="export_to_pdf" value="5" class="btn btn-info btn-sm" type="submit">Generar reporte PDF <i class="fa fa-file-pdf-o"></i></button></p>
                        <table class="tb table table-striped table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th>Email</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Celular</th>
                                <th>Fec Nac.</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($report5 as $index => $data)
                                <tr>
                                    <td>{{ $data->email}}</td>
                                    <td>{{ $data->name}}</td>
                                    <td>{{ $data->last_name}}</td>
                                    <td>{{ $data->mobile}}</td>
                                    <td>{{ $data->birthday}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Email</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Celular</th>
                                <th>Fec Nac.</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function () {
            $('select.form-control').on('change', function(){
                $('#report').submit();
            });
            $('.tb').DataTable({
                language: {
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sSearch": "Buscar:",
                    "sUrl": "",
                    "sInfoThousands": ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });
        });
    </script>
@endsection

