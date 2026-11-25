<table class="table table-responsive" id="brands-table">
    <thead>
        <tr>
            <th>Nombre</th>
        <th>Logo</th>
            <th>Compañía</th>
            <th colspan="3">Acción</th>
        </tr>
    </thead>
    <tbody>
    @foreach($brands as $brand)
        <tr>
            <td>{!! $brand->name !!}</td>
            <td>{!! $brand->logo !!}</td>
            <td>{!! $brand->company ? $brand->company->name : '' !!}</td>
            <td>
                {!! Form::open(['route' => ['admin.brands.destroy', $brand->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('admin.brands.show', [$brand->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('admin.brands.edit', [$brand->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>