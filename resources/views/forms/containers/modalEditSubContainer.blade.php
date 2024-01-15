
{{Form::Open(array('action'=>array('StFormController@updateSubContainer',$idcont,$idsubc,$id),'method'=>'POST','autocomplete'=>'off','id'=>'formMainEditSubcontainer'))}}
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label id="nombresubcontenedoredit--label">* Nombre del sub contenedor</label> <br>
            <input class="form-control" name="nombresubcontenedoredit"  value="{{$nombre_campo}}" type="text" style="width:100%">
            <span id="nombresubcontenedoredit-error" class="text-red"></span>
        </div>
    </div>



    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-yellow pull-right" name="btnSubmitEdit">Confirmar</button>
    </div>
</div>
{{Form::Close()}}
{{-- ========================================================================================================================== --}}
{{--                                                            VALIDACION                                                      --}}
{{-- ========================================================================================================================== --}}
<script>
    var campos = ['nombresubcontenedoredit'];
    ValidateAjax("formMainEditSubcontainer",campos,"btnSubmitEdsit",'/forms/subcontainer/update/{{$idcont}}/{{$idsubc}}/{{$id}}' ,"POST");
</script>
