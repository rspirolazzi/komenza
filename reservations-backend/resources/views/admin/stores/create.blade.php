@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Sucursal
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'admin.stores.store']) !!}

                        @include('admin.stores.fields')
                    <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::submit('Agregar Sucursal', ['class' => 'btn btn-primary btn-gradient']) !!}
                            <a href="{!! route('admin.stores.index') !!}" class="btn btn-default btn-gradient">Volver</a>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
