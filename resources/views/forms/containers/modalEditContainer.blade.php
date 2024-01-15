
{{Form::Open(array('action'=>array('StFormController@updateContainer',$idcont,$id),'method'=>'POST','autocomplete'=>'off','id'=>'formMainEditContainer'))}}
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label id="nombrecontenedoredit--label">* Nombre del contenedor</label> <br>
            <input class="form-control" name="nombrecontenedoredit" value="{{$nombre_campo}}" type="text" style="width:100%">
            <span id="nombrecontenedoredit-error" class="text-red"></span>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-yellow pull-right" name="btnSubmitEditContainer">Confirmar</button>
    </div>
</div>
{{Form::Close()}}
{{-- ========================================================================================================================== --}}
{{--                                                            VALIDACION                                                      --}}
{{-- ========================================================================================================================== --}}
<script>
    var campos = ['nombrecontenedoredit'];
    ValidateAjax("formMainEditContainer",campos,"btnSubmitEditContainers",'/forms/container/update/{{$idcont}}/{{$id}}' ,"POST");
</script>
