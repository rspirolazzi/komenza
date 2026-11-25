<table class="table table-responsive" id="stores-table">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Usuario</th>
            <th>Evento</th>
            <th>Tipo</th>
        </tr>
    </thead>
    <tbody>
    @foreach($auditories as $auditory)
        <tr class="row-detail-js" id="{{ $auditory->id }}">
            <td>{!! $auditory->created_at->format('d-m-Y H:i:s') !!}</td>
            <td>@if(!is_null($auditory->user_id))
                    <a target="_blank" href="{!! route('admin.users.show', [$auditory->user_id]) !!}">{!! $auditory->user->name !!}</a>
                @endif
            </td>
            <td>{!! $auditory->event !!}</td>
            <td>{!! $auditory->auditable_type !!}</td>
        </tr>
    @endforeach
    </tbody>
</table>