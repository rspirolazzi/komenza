@extends('layouts.app')
@section('content')
    <section class="content-header">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="pull-left">Reservas</h1>
                <h1 class="pull-right">
                    <div class="btn-group">
                        <a class="btn btn-default " href="{!! route('admin.reservations.index') !!}"><i
                                    class="fa fa-calendar-o"></i></a>
                        <a class="btn btn-default active"><i class="fa fa-list"></i></a>
                    </div>
                </h1>
            </div>
        </div>
    </section>
    <div class="content">
        <div class="col-md-12">
            <form action="{{route('admin.reservations.list')}}" method="get">
                <div class="input-group">
                    <input type="text" class="form-control" id="user_term" aria-describedby="emailHelp" name="user_term" value="{!! Request::get('user_term') !!}" placeholder="Busca por correo, nombre, apellido o celular" value=""/>
                    @if(Auth::user()->role == \App\Admin::$ROLE_ADMIN)
                        <span class="input-group-btn" style="width:0px;"></span>
                        {!! Form::select('store_id', \App\Models\Admin\Store::pluckStores(), Request::get('store_id'), ["class"=>"form-control", "placeholder"=>'Por tienda'] ) !!}
                    @elseif(Auth::user()->role == \App\Admin::$ROLE_BRAND)
                        <span class="input-group-btn" style="width:0px;"></span>
                        {!! Form::select('store_id', \App\Models\Admin\Store::pluckStoresByBrand(Auth::user()->brand_id), Request::get('store_id'), ["class"=>"form-control", "placeholder"=>'Por tienda'] ) !!}
                    @endif
                    <span class="input-group-btn"><button class="btn btn-primary "><i class="fa fa-search"></i></button></span>
                </div>
            </form>
        </div>
        <hr/>
        <div class="clearfix"></div>
        @include('flash::message')
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-body">
                        @include('admin.reservations.listTable')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modificar reserva del día <span id="modal_date"></span> a las <span
                                id="modal_hour"></span></h4>
                </div>
                <div class="modal-body">
                    <form id="target" action="{{ route('admin.reservations.update_reservation') }}">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="modal_people">Comensales:</label>
                                    <select class="form-control" id="modal_people" name="comensales">
                                        @foreach(range(1,20) as $n)
                                            <option value="{{ $n }}">{{ $n }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="date_S">Fecha:</label>
                                    <input class="modal_date_input form-control" type="date" name="date" value="">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="comment">Comentario:</label>
                                    <textarea class="form-control" rows="5" id="modal_comment"
                                              name="comment"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <h4>Datos de usuario</h4>
                                <p>Cliente: <span id="modal_client"></span></p>
                                <p>Celular: <span id="modal_telephone"></span></p>
                                <br><br>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 form-inline">
                                <button type="submit" class="btn btn-primary send" href="#">Aceptar modificaciones
                                </button>
                                <select name="status" class="form-control">
                                    <option value="open">{{__('messages.open')}}</option>
                                    <option value="attended">{{__('messages.attended')}}</option>
                                    <option value="not_attended">{{__('messages.not_attended')}}</option>
                                    <option value="canceled">{{__('messages.canceled')}}</option>
                                </select>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                        <input type="hidden" class="modal_id" name="id" value="">
                    </form>
                </div>
            </div>

        </div>
    </div>
    <div id="date-popover"></div>
@endsection

@section('scripts')
    <script>
        $('.reservation_id').click(function () {
            var data = $(this).data('json')
            var id = data.id;
            var people = data.people;
            var user_table = data.user.name + ', ' + data.user.last_name;
            var telephone_table = data.user.mobile;
            var comment = data.comment;
            var time = data.reservation_time;
            var date = data.reservation_date;
            var status = data.status;
            var options = {year: 'numeric', month: 'long', day: 'numeric'};
            var n_date = new Date(date);

            $('#modal_date').text(n_date.toLocaleDateString("es-ES", options));
            $('#modal_hour').text(time);
            $('.modal_date_input').val(date?.split(' ')[0])
            $('.modal_id').val(id);
            $('#modal_comment').text(comment);
            $('#modal_client').text(user_table);
            $('#modal_telephone').text(telephone_table);
            $('#modal_comment').text(comment);
            $('#modal_people').val(people).add('selected');
            $('select[name="status"]').val(status);
            $('#myModal').modal();
        });

        $('.status_change').click(function (event) {
            event.preventDefault();
            $('.status_change').removeClass('status-option--selected');
            $(this).addClass('status-option--selected');
            var status = $(this).attr('status');
            $('.modal_status').val(status);
        });
    </script>
@endsection
