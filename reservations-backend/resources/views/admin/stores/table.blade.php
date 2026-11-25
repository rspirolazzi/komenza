<table class="table table-responsive" id="stores-table">
    <thead>
        <tr>
            <th>Nombre</th>
        <th>Marca</th>
        <th>Dirección</th>
        <th>Teléfono</th>
            <th colspan="3">Acción</th>
        </tr>
    </thead>
    <tbody>
    @foreach($stores as $store)
        <tr>
            <td>{!! $store->name !!}</td>
            <td>{!! $store->brand->name!!}</td>
            <td>{!! $store->address !!}</td>
            <td>{!! $store->telephone !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.stores.destroy', $store->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('admin.stores.show', [$store->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('admin.stores.edit', [$store->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    <a href="{{ route('admin.stores.copy', [$store->id]) }}" class='btn btn-info btn-xs'><i class="glyphicon glyphicon-duplicate"></i> Copy</a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>