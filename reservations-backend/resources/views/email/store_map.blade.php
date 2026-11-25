@if($store->directionsUrl)
    <tr>
        <td style="padding: 40px 40px 44px 40px; font-family: sans-serif; font-size: 15px; line-height: 140%; color: #5E6B65; text-align: center;">
            <p style="margin: 0 0 26px 0;">{{ $store->address }}</p>
            @if($store->staticMapUrl)
                <p style="margin: 0 0 16px 0;"><img src="{{ $store->staticMapUrl }}" alt="Mapa de {{ $store->name }}" width="500" border="0" style="width: 100%; max-width: 500px; height: auto;"></p>
            @endif
            <p style="margin: 0;"><a href="{{ $store->directionsUrl }}" style="background: {{ $color }}; border: 15px solid {{ $color }}; font-family: sans-serif; font-size: 13px; line-height: 110%; text-align: center; text-decoration: none; border-radius: 3px; font-weight: bold;" class="button-a"> <span style="color:#ffffff;" class="button-link">C&oacute;mo llegar</span></a></p>
        </td>
    </tr>
@endif
