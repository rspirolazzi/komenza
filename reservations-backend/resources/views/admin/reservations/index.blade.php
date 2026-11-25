@extends('layouts.app')

@section('css')
<link rel="stylesheet" type="text/css" href="/css/zabuto_calendar.min.css">
@endsection

@section('content')
<section class="content-header">
    <div class="row">
        <div class="col-sm-12">
            <h1 class="pull-left">
                Reservas {!! $selectedDate !!}</h1>
            <h1 class="pull-right">
                <form method="post" action="{!! route('admin.reservations.print') !!}">
                    <div class="btn-group">
                        <a href="{!! route('admin.reservations.create') !!}" class="btn btn-success "><i class="fa fa-plus"></i> Nueva reserva</a>
                        <a class="btn btn-default active"><i class="fa fa-calendar-o"></i></a>
                        <a class="btn btn-default " href="{!! route('admin.reservations.list') !!}"><i class="fa fa-list"></i></a>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-print"></i></button>
                        <input type="hidden" name="reservation_date" value="{{Request::get('reservation_date',\Carbon\Carbon::today()->format('Y-m-d'))}}" />
                    </div>
                </form>
            </h1>
        </div>
    </div>
</section>
<div class="content">
    <div class="col-md-12">
        <form action="{{route('admin.reservations.index')}}" method="get">
            <div class="input-group">
                <input type="text" class="form-control" id="user_term" aria-describedby="emailHelp" name="user_term" value="{!! Request::get('user_term') !!}" placeholder="Busca por correo, nombre, apellido o celular" value="" />
                @if(Auth::user()->role == \App\Admin::$ROLE_ADMIN)
                <span class="input-group-btn" style="width:0px;"></span>
                {!! Form::select('store_id', \App\Models\Admin\Store::pluckStores(), Request::get('store_id'), ["class"=>"form-control", "placeholder"=>'Por tienda'] ) !!}
                @elseif(Auth::user()->role == \App\Admin::$ROLE_BRAND)
                <span class="input-group-btn" style="width:0px;"></span>
                {!! Form::select('store_id', \App\Models\Admin\Store::pluckStoresByBrand(Auth::user()->brand_id), Request::get('store_id'), ["class"=>"form-control", "placeholder"=>'Por tienda'] ) !!}
                @endif
                <span class="input-group-btn" style="width:0px;"></span>
                {!! Form::select('status', ["open"=>__('messages.open'),"attended"=>__('messages.attended'),"not_attended"=>__('messages.not_attended'),"canceled"=>__('messages.canceled')], Request::get('status'), ["class"=>"form-control", "placeholder"=>'Por estado',]);!!}
                <input type="hidden" name="reservation_date" value="{{Request::get('reservation_date',\Carbon\Carbon::today()->format('Y-m-d'))}}" />
                <span class="input-group-btn"><button class="btn btn-primary "><i class="fa fa-search"></i></button></span>
            </div>
        </form>
    </div>
    <hr />
    <div class="clearfix"></div>
    @include('flash::message')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-sm-9">
            <div class="box box-primary">
                <div class="box-body">
                    @include('admin.reservations.table')
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <a class="btn btn-primary btn-block btm-sm" href="{{route('admin.reservations.index')}}">Ir a las
                reservas
                de hoy</a>
            <div id="calendar"></div>
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
                <form method="put" id="target" action="{{ route('admin.reservations.update_reservation') }}">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="modal_people">Comensales:</label>
                                <input type="number" step="0" class="form-control" id="modal_people" name="comensales" />
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="date_S">Fecha:</label>
                                <input class="modal_date_input form-control" type="text" name="date" value="">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="time">Horario:</label>
                                <input class="modal_time_input form-control" type="text" name="time" value="" data-time-format="H:i">
                            </div>
                        </div>
                        <div class="col-sm-12">
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
<script src="/js/zabuto_calendar.min.js"></script>

<script>
    var dataEvents = [
        @foreach($reservationsOpen as $reservation) {
            'date': '{!! $reservation->reservation_date->format("Y-m-d") !!}',
            'title': '{{ $reservation->user->fullName }} - {{ $reservation->user->telephone or "s/n" }} - {{ $reservation->reservation_time }}',
            'badge': false,
            "classname": '{!! ($reservation->reservation_date->format("Y-m-d") === $selectedDate) ? "zabuto_calendar_grade2":"zabuto_calendar_grade1"  !!}',
        },
        @endforeach
    ];
</script>


<script type="application/javascript">
    $(document).ready(function() {
        $('.modal_date_input').datepicker({
            language: 'es',
            format: "yyyy-mm-dd",
        });
        $('.modal_time_input').timepicker()
        $('[data-toggle=tooltip]').tooltip()
        $el = $("#calendar")
        $el.zabuto_calendar({
            language: "es",
            today_markup: '<span class="badge bg-primary">[day]</span>',
            legend: [],
            cell_border: true,
            today: true,
            show_days: true,
            weekstartson: 0,
            nav_icon: {
                prev: '<i class="fa fa-chevron-circle-left"></i>',
                next: '<i class="fa fa-chevron-circle-right"></i>'
            },
            action_nav: function() {
                return myNavFunction(this.id);
            },
            events: dataEvents,
            legend: [{
                    type: "text",
                    label: "Hoy",
                    badge: parseInt('{!! sizeof($reservations) !!}')
                },
                {
                    type: "block",
                    label: "Fecha con reservas",
                    classname: "zabuto_calendar_grade1"
                },
                {
                    type: "block",
                    label: "Fecha seleccionada",
                    classname: "zabuto_calendar_grade2"
                },

            ],
        });

        $el.on('zabuto:calendar:day', function(e) {
            var date = e.date
            if (date) {
                date = date.toISOString().split('T')[0];
                window.location.href = "{{route('admin.reservations.index')}}?reservation_date=" + date
            }
            return false
        });


    });

    function myDateFunction(id, fromModal) {

        $("#date-popover").hide();
        if (fromModal) {
            $("#" + id + "_modal").modal("hide");
        }
        var date = $("#" + id).data("date");
        var hasEvent = $("#" + id).data("hasEvent");
        if (hasEvent && !fromModal) {
            return false;
        }
        $("#date-popover-content").html('You clicked on date ' + date);
        $("#date-popover").show();
        return true;
    }

    function myNavFunction(id) {

        $("#date-popover").hide();
        var nav = $("#" + id).data("navigation");
        var to = $("#" + id).data("to");
        //console.log('nav ' + nav + ' to: \ + to.month + '/' + to.year);
    }
</script>

<script>
    $('.reservation_id').click(function() {
        var data = $(this).data('json')
        var id = data.id;
        var people = data.people;
        var user_table = data.user.name + ', ' + data.user.last_name;
        var telephone_table = data.user.mobile;
        var comment = data.comment;
        var time = data.reservation_time;
        var date = data.reservation_date;
        var status = data.status;
        var options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        var n_date = new Date(date);

        $('#modal_date').text(n_date.toLocaleDateString("es-ES", options));
        $('#modal_hour').text(time);
        $('.modal_date_input').val(date?.split(' ')[0])
        $('.modal_time_input').val(time)
        $('.modal_id').val(id);
        $('#modal_comment').text(comment);
        $('#modal_client').text(user_table);
        $('#modal_telephone').text(telephone_table);
        $('#modal_comment').text(comment);
        $('#modal_people').val(people).add('selected');
        $('select[name="status"]').val(status);
        $('#myModal').modal();
    });

    $('.status_change').click(function(event) {
        event.preventDefault();
        $('.status_change').removeClass('status-option--selected');
        $(this).addClass('status-option--selected');
        var status = $(this).attr('status');
        $('.modal_status').val(status);
    });
</script>
@endsection