@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Logs</h1>
        <br><br>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('admin.auditories.filter')
                @include('admin.auditories.table')
            </div>
        </div>
        <div class="text-center">

            @include('adminlte-templates::common.paginate', ['records' => $auditories])

        </div>
    </div>
    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Auditoría: <span id="id_detail"></span></h4>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <tr>
                            <td>Fecha:</td>
                            <td><span id="date_detail"></span></td>
                        </tr>
                        <tr>
                            <td>Usuario:</td>
                            <td><span id="user_detail"></span></td>
                        </tr>
                        <tr>
                            <td>Evento:</td>
                            <td><span id="event_detail"></span></td>
                        </tr>
                        <tr>
                            <td>Tipo:</td>
                            <td><span id="type_detail"></span></td>
                        </tr>
                        <tr>
                            <td>Valor nuevo:</td>
                            <td><span id="new_detail"></span></td>
                        </tr>
                        <tr>
                            <td>Valor viejo: <span id="old_detail"></span>
                        </tr>
                        <tr>
                            <td>url:</td>
                            <td><p id="url_detail"></p></td>
                        </tr>
                        <tr>
                            <td>ip:</td>
                            <td><span id="ip_detail"></span></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('.row-detail-js').click(function () {
            var id = $(this).attr("id");
            $.ajax({
                url: "{{ route('admin.auditories.show') }}",
                data: {
                    'id': id
                },
                success: function (data) {
                    // console.log(data);
                    var array_new = [];
                    $.each(data.new_values, function (key, value) {
                        array_new.push(value);
                    });

                    var array_old = [];
                    $.each(data.old_values, function (key, value) {
                        array_old.push(value);
                    });

                    $('#id_detail').text(data.id);
                    $('#date_detail').text(data.created_at);

                    if (data.user_complete != null) {
                        var clear_name = '';
                        var clear_last = '';
                        if (data.user_complete.name != null) {
                            clear_name = data.user_complete.name;
                        }
                        if (data.user_complete.last_name != null) {
                            clear_last = data.user_complete.last_name;
                        }
                        $('#user_detail').text(clear_name + ' ' + clear_last);
                    }

                    $('#event_detail').text(data.event);
                    $('#type_detail').text(data.auditable_type);
                    $('#new_detail').text(array_new);
                    $('#old_detail').text(array_old);
                    $('#url_detail').text(data.url);
                    $('#ip_detail').text(data.ip_address);
                    $('#myModal').modal();

                }
            });
        });
    </script>
@endsection

