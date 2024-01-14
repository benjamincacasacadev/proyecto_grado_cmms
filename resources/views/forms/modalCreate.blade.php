<link rel="stylesheet" href="{{asset('/plugins/iCheck/all.css')}}">
{!! Form::open( array('route' =>'forms.store','method'=>'POST','autocomplete'=>'off','files'=>'true','id'=>'formCreateForms','onsubmit'=>'btnSubmit.disabled = true; return true;' ))!!}
<div class="row">
    {!! datosRegistro('create') !!}
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <label id="nombre--label"> * Nombre del formulario</label> <br>
            <input class="form-control" name="nombre" type="text" placeholder="Nombre del procedimiento">
            <span id="nombre-error" class="text-red"></span>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <label id="sigla--label"> * Sigla</label> <br>
            <input class="form-control" name="sigla" type="text" placeholder="Sigla de formulario">
            <span id="sigla-error" class="text-red"></span>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
        <div class="form-group" id="categoria-sel2">
            <label id="categoria--label">* Categoria de activo</label>
            <select name="categoria" class="form-control form-select selector-modal" style="width: 100%">
                <option value="" hidden>Seleccionar</option>
                <option value="0">Aire Acondicionado de confort</option>
                <option value="1">Aires de precision</option>
                <option value="2">Banco de baterias de litio</option>
                <option value="3">Equipo inversor</option>
                <option value="4">Equipo rectificador</option>
                <option value="5">Equipo UPS</option>
                <option value="6">Estabilizador</option>
                <option value="7">Grupos Electrógenos</option>
                <option value="8">Reconectador de media tension</option>
                <option value="9">Tablero Banco de capacitores</option>
                <option value="10">Tablero de transferencia ATS</option>
            </select>
            <span id="categoria-error" class="text-red"></span>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group" id="tipo-sel2">
            <label id="tipo--label">* Tipo</label> <br>
            <select name="tipo" class="form-control selector-modal" style="width:100%">
                <option value="" hidden>Seleccionar</option>
                @foreach ($types as $type)
                    <option value="{{ $type->id }}">{{$type->name}}</option>
                @endforeach
            </select>
            <span id="tipo-error" class="text-red"></span>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label id="checkcarta--label">* Incluir carta de presentación</label> <br>
            <div class="checkbox  text-center">
                <label><input type="radio" class="checkcarta" name="checkcarta" value="si"> <b>Sí</b>  </label>
                <label><input type="radio" class="checkcarta" name="checkcarta" value="no"> <b>No</b>  </label>
            </div>
            <center><span id="checkcarta-error" class="text-red font-weight-bold"></span></center>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-yellow pull-right" name="btnSubmit">Registrar</button>
    </div>
</div>
{{Form::Close()}}

<script src="{{asset('/plugins/iCheck/icheck.min.js')}}"></script>
<script>
    $('select.selector-modal:not(.normal)').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent().parent()
        });
    });

    $(document).ready(function () {
        $("#formCreateForms .select2-selection").addClass('form-select2').css('border-color','#ccc');
        $("#formCreateForms .select2-selection--single").addClass('form-selectcont');
    });

    $('.checkcarta').iCheck({
        radioClass: 'iradio_square-yellow',
        increaseArea: '5%'
    });

    var campos = ['nombre','sigla','categoria','tipo','checkcarta'];
    ValidateAjax("formCreateForms",campos,"btnSubmit","{{route('forms.store')}}","POST","/forms");
</script>
