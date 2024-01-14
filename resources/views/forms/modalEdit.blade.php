<link rel="stylesheet" href="{{asset('/plugins/iCheck/all.css')}}">
<div class="modal-header">
    <h5 class="modal-title font-weight-bold">
        <i class="fa fa-plus"></i>
        Editar formulario: {{ $form->name }}
    </h5>
    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    {!! Form::open( array('route' =>'forms.store','method'=>'POST','autocomplete'=>'off','files'=>'true','id'=>'formEditForms','onsubmit'=>'btnSubmitEdit.disabled = true; return true;' ))!!}
    <div class="row">
        {!! datosRegistro('create') !!}
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <label id="nombreedit--label"> * Nombre del formulario</label> <br>
                <input class="form-control" name="nombreedit" type="text" placeholder="Nombre del procedimiento" value="{{ $form->name }}">
                <span id="nombreedit-error" class="text-red"></span>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <label id="siglaedit--label"> * Sigla</label> <br>
                <input class="form-control" name="siglaedit" type="text" placeholder="Sigla de formulario" value="{{ $form->sigla }}">
                <span id="siglaedit-error" class="text-red"></span>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
            <div class="form-group" id="categoriaedit-sel2">
                <label id="categoriaedit--label">* Categoria de activo</label>
                <select name="categoriaedit" class="form-control form-select selector-modal-edit" style="width: 100%">
                    <option value="" hidden>Seleccionar</option>
                    <option @if($form->category_id == '0') selected @endif value="0">Aire Acondicionado de confort</option>
                    <option @if($form->category_id == '1') selected @endif value="1">Aires de precision</option>
                    <option @if($form->category_id == '2') selected @endif value="2">Banco de baterias de litio</option>
                    <option @if($form->category_id == '3') selected @endif value="3">Equipo inversor</option>
                    <option @if($form->category_id == '4') selected @endif value="4">Equipo rectificador</option>
                    <option @if($form->category_id == '5') selected @endif value="5">Equipo UPS</option>
                    <option @if($form->category_id == '6') selected @endif value="6">Estabilizador</option>
                    <option @if($form->category_id == '7') selected @endif value="7">Grupos Electrógenos</option>
                    <option @if($form->category_id == '8') selected @endif value="8">Reconectador de media tension</option>
                    <option @if($form->category_id == '9') selected @endif value="9">Tablero Banco de capacitores</option>
                    <option @if($form->category_id == '10') selected @endif value="10">Tablero de transferencia ATS</option>
                </select>
                <span id="categoriaedit-error" class="text-red"></span>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group" id="tipoedit-sel2">
                <label id="tipoedit--label">* Tipo</label> <br>
                <select name="tipoedit" class="form-control selector-modal-edit" style="width:100%">
                    <option value="" hidden>Seleccionar</option>
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}" @if($form->type_id == $type->id) selected @endif>{{ $type->name }}</option>
                    @endforeach
                </select>
                <span id="tipoedit-error" class="text-red"></span>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label id="checkcartaedit--label">* Incluir carta de presentación</label> <br>
                <div class="checkbox  text-center">
                    <label><input type="radio" class="checkcartaedit" name="checkcartaedit" value="si" @if($form->check_letter == '1') checked @endif> <b>Sí</b>  </label>
                    <label><input type="radio" class="checkcartaedit" name="checkcartaedit" value="no" @if($form->check_letter == '0') checked @endif> <b>No</b>  </label>
                </div>
                <center><span id="checkcartaedit-error" class="text-red font-weight-bold"></span></center>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-yellow pull-right" name="btnSubmitEdit">Modificar</button>
        </div>
    </div>
    {{Form::Close()}}
</div>

<script src="{{asset('/plugins/iCheck/icheck.min.js')}}"></script>
<script>
    $('select.selector-modal-edit:not(.normal)').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent().parent()
        });
    });

    $(document).ready(function () {
        $("#formEditForms .select2-selection").addClass('form-select2').css('border-color','#ccc');
        $("#formEditForms .select2-selection--single").addClass('form-selectcont');
    });

    $('.checkcartaedit').iCheck({
        radioClass: 'iradio_square-yellow',
        increaseArea: '5%'
    });

    var campos = ['nombreedit','siglaedit','categoriaedit','tipoedit','checkcartaedit'];
    ValidateAjax("formEditForms",campos,"btnSubmitEdit","{{ route('forms.update',code($form->id) )}}","POST","/forms");
</script>
