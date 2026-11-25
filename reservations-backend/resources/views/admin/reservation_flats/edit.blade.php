@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Reservation Flat
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($reservationFlat, ['route' => ['admin.reservationFlats.update', $reservationFlat->id], 'method' => 'patch']) !!}

                        @include('admin.reservation_flats.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection