@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Reservation Flat
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admin.reservation_flats.show_fields')
                    <a href="{!! route('admin.reservationFlats.index') !!}" class="btn btn-default">Volver al listado</a>
                </div>
            </div>
        </div>
    </div>
@endsection
