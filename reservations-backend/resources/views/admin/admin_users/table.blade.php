<table class="table table-responsive" id="users-table">
    <thead>
    <tr>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Email</th>
        <th>Sucursal</th>
        <th>Compañia</th>
        <th>Acción</th>
    </tr>
    </thead>
    <tbody>
    @if(sizeof($users)>0)
        @foreach($users as $user)
            <tr>
                <td>{!! $user->name !!}</td>
                <td>{!! $user->last_name !!}</td>
                <td>{!! $user->email !!}</td>
                <td>
                    @if(!is_null($user->store))
                    {!! $user->store->name !!}
                    @endif
                </td>
                <td>
                    {!! $user->company->name !!}
                </td>
                <td>
                    {!! Form::open(['route' => ['admin.admin_users.destroy', $user->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('admin.admin_users.show', [$user->id]) !!}" class='btn btn-default btn-xs'><i
                                    class="glyphicon glyphicon-eye-open"></i></a>
                        <a href="{!! route('admin.admin_users.edit', [$user->id]) !!}" class='btn btn-default btn-xs'><i
                                    class="glyphicon glyphicon-edit"></i></a>
                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
    @else
        <tr class="info text-center">
            <td colspan="100%">No hay registros</td>
        </tr>
    @endif
    </tbody>
</table>