<!-- Nombre Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Nombre:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Logo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('logo', 'Logo:') !!}
    {!! Form::file('logo') !!}
    @if(isset($brand) && $brand->logo)
        <div class="mt-2">
            <img src="/{{ $brand->logo }}" alt="Logo" style="max-width:120px;max-height:120px;">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="delete_logo" value="1"> Eliminar logo
                </label>
            </div>
        </div>
    @endif
</div>
<div class="clearfix"></div>


<!-- Fondos Field -->
<div class="form-group col-sm-12">
    {!! Form::label('backgrounds', 'Fondos de la app (máx 20):') !!}
    {!! Form::file('backgrounds[]', ['multiple' => true, 'accept' => 'image/*']) !!}
    @if(isset($brand))
        <div class="row mt-2">
            @foreach($brand->backgrounds as $bg)
                <div class="col-xs-6 col-sm-3 mb-2">
                    <img src="/{{ $bg->image }}" alt="Fondo" style="max-width:120px;max-height:120px;">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="delete_backgrounds[]" value="{{ $bg->id }}"> Eliminar
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Styles Field -->
<div class="form-group col-sm-12">
    {!! Form::label('styles', 'Estilos personalizados (JSON):') !!}
    <small class="form-text text-muted">
        Ejemplo:<br>
        <code>{<br>&nbsp;&nbsp;&quot;primary&quot;: &quot;#ff6600&quot;,<br>&nbsp;&nbsp;&quot;secondary&quot;: &quot;#333333&quot;,<br>&nbsp;&nbsp;&quot;custom_css&quot;: &quot;.header { font-size: 1.2em; }&quot;<br>}</code>
    </small>
    {!! Form::textarea('styles', null, ['class' => 'form-control', 'rows' => 5, 'placeholder' => '{\n  "primary": "#ff6600",\n  "secondary": "#333333",\n  "custom_css": ".header { font-size: 1.2em; }"\n}']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.brands.index') !!}" class="btn btn-default">Cancelar</a>
</div>
