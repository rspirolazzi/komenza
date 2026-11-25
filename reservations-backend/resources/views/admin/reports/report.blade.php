@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="row">
            <div class="col-sm-12">
                <h1>Reporte de visitas en este mes
                    <a class="btn btn-primary pull-right" href="{{ route('admin.reports.report1') }}?format=pdf">Generar reporte
                        PDF <i class="fa fa-file-pdf-o"></i></a>
                </h1>
            </div>
        </div>
    </section>
    <div class="content" style="background: white">
        <table class="tb table table-striped table-bordered" style="width:100%">
            <thead>
            <tr>
                <th>Tienda</th>
                <th>Marca</th>
                <th># de Personas</th>

            </tr>
            </thead>
            <tbody>
            @foreach($report1 as $index => $data)
                <tr>
                    <td>{{ $data->store_name }}</td>
                    <td>{{ $data->brand_name }}</td>
                    <td>{{ $data->people }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th>Tienda</th>
                <th>Marca</th>
                <th># de Personas</th>
            </tr>
            </tfoot>
        </table>
    </div>
@endsection

