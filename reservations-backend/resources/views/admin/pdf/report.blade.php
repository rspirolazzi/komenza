@extends('layouts.pdf')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3>{!! $title !!} <small>{!! $date !!}</small></h3>
        </div>
        <div class="col-md-12">

            <table class="table table-striped">
                <thead>
                <tr>
                    @foreach($columns as $column => $key)
                        <th>{!! $column !!}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @if(sizeof($result)>0)
                    @foreach($result as $row)
                        <tr>
                            @foreach($columns as $column => $key)
                                <td>{!! $row->{$key} !!}</td>
                            @endforeach
                        </tr>
                    @endforeach
                @else
                    <tr class="info text-center">
                        <td colspan="100%">No hay registros</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection