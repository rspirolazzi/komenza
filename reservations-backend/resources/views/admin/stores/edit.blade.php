@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Sucursal: {!! $store->name !!}
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($store, ['route' => ['admin.stores.update', $store->id], 'method' => 'patch']) !!}

                        @include('admin.stores.fields')
                   <!-- Submit Field -->
                       <div class="form-group col-sm-12">
                           {!! Form::submit('Guardar cambios', ['class' => 'btn btn-primary btn-gradient']) !!}
                           <a href="{!! route('admin.stores.delivery', [$store->id]) !!}" class="btn btn-warning btn-gradient">Horarios</a>
                           <a href="{!! route('admin.stores.index') !!}" class="btn btn-default btn-gradient">Volver</a>
                       </div>
                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection