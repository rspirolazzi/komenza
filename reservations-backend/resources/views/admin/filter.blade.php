<div class="">
    {!! Form::open(['route' => 'admin.transactions.index', 'method'=>'GET']) !!}
    <div class="input-group">
        {!! Form::text('search', null, ['class' => 'form-control', 'placeholder'=>'']) !!}
        <span class="input-group-btn">
            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
        </span>
    </div>
    {!! Form::close() !!}
</div>