@foreach($user->reservations as $reservation)
@endforeach
@if($user->reservations)
    <hr/>
    <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-sign-in"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Asistio</span>
                    <span class="info-box-number">{!! $user->reservations->filter(function($value,$key){ return $value['status'] == \App\Models\Admin\Reservation::$STATUS_ATTENDED;})->count(); !!}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-sign-out"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">No Asistio</span>
                    <span class="info-box-number">{!! $user->reservations->filter(function($value,$key){ return $value['status'] == \App\Models\Admin\Reservation::$STATUS_NOT_ATTENDED;})->count(); !!}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-times"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Cancelo</span>
                    <span class="info-box-number">{!! $user->reservations->filter(function($value,$key){ return $value['status'] == \App\Models\Admin\Reservation::$STATUS_CANCELED;})->count(); !!}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
    </div>
@endif