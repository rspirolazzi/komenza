@extends('layouts.app')
@section('scripts')
    <script type="text/javascript" src="//www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages': ['bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var result = [];
            try {
                result = {!! json_encode($barChartReservationByStore) !!};
            } catch (e) {
                result = [];
                console.log(e)
            }
            var data = google.visualization.arrayToDataTable(result);

            var options = {
                bars: 'vertical', colors: ['#0F4C3A', '#F2A93B', '#5E6B65'] // Required for Material Bar Charts.
            };

            var chart = new google.charts.Bar(document.getElementById('barchart_material'));

            chart.draw(data, google.charts.Bar.convertOptions(options));
        }
    </script>
@endsection
@section('content')
    <div class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-xs-12"><h3>Total de reservas en el d&iacute;a de hoy: </h3></div>
            @foreach($reservationStoreToday as $reservation)
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon info-box-icon--brand"><i class="fa fa-line-chart"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{$reservation->store_name}}</span>
                            <span class="info-box-number">{{$reservation->count}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
            @endforeach
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="text-center">
                                    <strong>Reservas realizadas en meses</strong>
                                </p>
                                <div class="chart">
                                    <div id="barchart_material" style="width: 859px;"></div>
                                </div>
                                <!-- /.chart-responsive -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">

                            <!-- /.col -->
                            <div class="col-md-12">
                                <p class="text-center">
                                    <strong>Reservas abiertas en el d&iacute;a</strong>
                                </p>
                                @foreach($openReservationStoreToday as $reservation)
                                    <?php
                                    $value = round(($reservation->count * 100) / $reservation->max_persons_day, 0);
                                    $label = 'aqua';
                                    if ($value <= 25) {
                                        $label = 'aqua';
                                    } elseif ($value <= 50) {
                                        $label = 'green';
                                    } elseif ($value <= 75) {
                                        $label = 'yellow';
                                    } else {
                                        $label = 'red';
                                    }
                                    ?>
                                    <div class="progress-group">
                                        <span class="progress-text">{{$reservation->store_name}}</span>
                                        <span class="progress-number"><b>{{$reservation->count}}</b>/{{$reservation->max_persons_day}}</span>

                                        <div class="progress sm">
                                            <div class="progress-bar progress-bar-{{$label}}"
                                                 style="width: {{$value}}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
@endsection
