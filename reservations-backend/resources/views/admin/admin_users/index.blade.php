@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Usuarios Administradores</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('admin.admin_users.create') !!}">Nuevo registro</a>
        </h1>
    </section>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form>
                            <div class="input-group">
                                <input type="text" class="form-control" id="search" name="search"
                                       placeholder="Busca por nombre, correo o celular" value="{{ Request::get('search')}}"/>
                                <span class="input-group-btn">
                                <button class="btn btn-primary "><i class="fa fa-search"></i></button>
                            </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.admin_users.table')
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection

