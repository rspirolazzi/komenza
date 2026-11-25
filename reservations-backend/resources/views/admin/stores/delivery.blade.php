@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Selección de horario para: {!! $store->name !!}
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($store, ['route' => ['admin.stores.storeDelivery', $store->id], 'method' => 'put']) !!}
                        @include('admin.stores.deliveryFields')
                       <div class="form-group col-sm-12">
                           {!! Form::submit('Guardar cambios', ['class' => 'btn btn-primary btn-gradient']) !!}
                           <a href="{!! route('admin.stores.edit',[$store->id]) !!}" class="btn btn-default btn-gradient">Volver</a>
                       </div>
                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection