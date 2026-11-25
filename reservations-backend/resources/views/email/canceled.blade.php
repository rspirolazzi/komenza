@extends('layouts.email')

@section('subject')
    Tu reserva en {{ $reservation->brand->name }} fue cancelada
@endsection

@section('header')
    <!-- Email Header : BEGIN -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%"
           style="max-width: 600px;">
        <tr>
            <td style="padding: 20px 0; text-align: center">
                @if($reservation->brand->logo)
                    <img src="{{config('app.url')}}/{{ ltrim($reservation->brand->logo, '/') }}" width="200" alt="{{ $reservation->brand->name }}" border="0" style="height: auto;">
                @else
                    <span style="font-family: sans-serif; font-size: 26px; font-weight: bold; color: {{ $reservation->brand->primaryColor }};">{{ $reservation->brand->name }}</span>
                @endif
            </td>
        </tr>
    </table>
@endsection

@section('content')
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td style="padding: 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #5E6B65;">
                <h1 style="margin: 0 0 10px 0; font-family: sans-serif; font-size: 24px; line-height: 125%; color: #17211D; font-weight: normal;">Tu reserva fue cancelada</h1>
                <p style="margin: 0;">{{ $reservation->user->name }}, cancelamos tu reserva en <strong>{{ $reservation->brand->name }} {{ $reservation->store->name }}</strong> del <strong>{{ $reservation->reservation_date->format('d/m/Y') }} a las {{ App\Helper\Helper::getFormatTime($reservation->reservation_time) }} hs</strong> para {{ $reservation->people }} personas.</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 0 40px 50px 40px; text-align: center; font-family: sans-serif; font-size: 15px; line-height: 140%;">
                <p style="margin: 0;"><a href="{{ $reservation->getWebUrl() }}" style="background: {{ $reservation->brand->primaryColor }}; border: 15px solid {{ $reservation->brand->primaryColor }}; font-family: sans-serif; font-size: 13px; line-height: 110%; text-align: center; text-decoration: none; border-radius: 3px; font-weight: bold;" class="button-a"> <span style="color:#ffffff;" class="button-link">Hacer una nueva reserva</span></a></p>
            </td>
        </tr>
    </table>
@endsection

@section('reminder')
@endsection
