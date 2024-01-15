
{{Form::Open(array('action'=>array('StFormController@updateTextfield',$idcampo,$id),'method'=>'POST','autocomplete'=>'off','id'=>'formMainEditTextField'))}}
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label id="nombretextEdit--label">* Nombre del campo</label> <br>
            <input class="form-control" name="nombretextEdit" value="{{$nombre_campo}}" type="text" style="width:100%">
            <span id="nombretextEdit-error" class="text-red"></span>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-bottom:20px">
        <label id="texto_tipoedit--label">* Tipo de texto </label> <br>
        <div class="checkbox text-center">
            <label><input type="radio" class="tipo_textoedit" name="texto_tipoedit" @if($type=='text') checked @endif value="text"> <b>Caja de Texto</b>  </label>
            <label><input type="radio" class="tipo_textoedit" name="texto_tipoedit" @if($type=='textarea') checked @endif value="textarea"> <b>Área de Texto</b>  </label>
            <label><input type="radio" class="tipo_textoedit" name="texto_tipoedit" @if($type=='date') checked @endif value="date"> <b>Fecha</b>  </label>
            <label><input type="radio" class="tipo_textoedit" name="texto_tipoedit" @if($type=='time') checked @endif value="time"> <b>Hora</b>  </label>
            <label><input type="radio" class="tipo_textoedit" name="texto_tipoedit" @if($type=='datetime') checked @endif value="datetime"> <b>Fecha y Hora</b>  </label>
            <label><input type="radio" class="tipo_textoedit" name="texto_tipoedit" @if($type=='number') checked @endif value="number"> <b>Numérico</b>  </label>
            <label><input type="radio" class="tipo_textoedit" name="texto_tipoedit" @if($type=='money') checked @endif value="money"> <b>Moneda</b></label>
        </div>
        <center><span id="texto_tipoedit-error" class="text-red font-weight-bold"></span></center>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-yellow pull-right" name="btnSubmitEditTextField">Confirmar</button>
    </div>
</div>
{{Form::Close()}}
<script>
    $('.tipo_textoedit').iCheck({
        radioClass: 'iradio_square-blue',
        increaseArea: '5%'
    });
</script>
{{-- ========================================================================================================================== --}}
{{--                                                            VALIDACION                                                      --}}
{{-- ========================================================================================================================== --}}
<script>
    var campos = ['nombretextEdit','texto_tipoedit'];
    ValidateAjax("formMainEditTextField",campos,"btnSubmitEditTextField",'/forms/maintenance/updateTextfield/{{$idcampo}}/{{$id}}' ,"POST");
</script>
