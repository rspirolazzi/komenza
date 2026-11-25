@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Reservas (planas)</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('admin.reservationFlats.create') !!}">Nuevo registro</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.reservation_flats.table')
            </div>
        </div>
        <div class="text-center">
        
        @include('adminlte-templates::common.paginate', ['records' => $reservationFlats])

        </div>
    </div>
@endsection

