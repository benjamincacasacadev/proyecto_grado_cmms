
{{Form::Open(array('action'=>array('StFormController@updateSeriefield',$idcampo,$id),'method'=>'POST','autocomplete'=>'off','id'=>'formMainEditSerieField'))}}
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label id="nombreSerieEdit--label">* Nombre del campo</label> <br>
            <input class="form-control" name="nombreSerieEdit" value="{{$nombre_campo}}" type="text" style="width:100%">
            <span id="nombreSerieEdit-error" class="text-red"></span>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-yellow pull-right" name="btnSubmitEditSerieField">Confirmar</button>
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
    var campos = ['nombreSerieEdit'];
    $("#formMainEditSerieField").on('submit', function(e) {
        e.preventDefault();
        var registerForm = $("#formMainEditSerieField");
        var formData = new FormData($("#formMainEditSerieField")[0]);
        $.each(campos, function( indice, valor ) {
            $("#"+valor+"-error").html( "" );
        });

        $.ajax({
            url: '/forms/maintenance/updateSeriefield/{{$idcampo}}/{{$id}}',
            type: "POST",
            data:formData,
            contentType: false,
            processData: false,
            success:function(data) {
                if(data.alerta) {
                    toastr.error(data.mensaje);
                    $("[name=btnSubmitEditSerieField]").attr('disabled',false)
                }
                if(data.success) {
                    var contid = (data.contid) ? '?contid='+data.contid : "";
                    var subconte = (data.subconte) ? '&subc='+data.subconte : "";
                    $("[name=btnSubmitEditSerieField]").attr('disabled',true)
                    window.location.href = '/forms/maintenance/{{ $id }}'+contid+subconte;
                }
            },
            error: function(data){
                if(data.responseJSON.errors) {
                    $.each(data.responseJSON.errors, function( index, value ) {
                        $('#'+index+'-error' ).html( '&nbsp;<i class="fa fa-ban"></i> '+value );
                    });
                    var indexaux = []; var camposaux =[]; var i=0;
                    $.each(campos, function( indice, valor ) {
                        if(data.responseJSON.errors[valor]){
                            indexaux[i] = indice;  i++;
                        }
                        var j = indice;
                        camposaux[j] = valor;
                    });
                    $("[name=btnSubmitEditSerieField]").attr('disabled',false);
                }
                if(typeof(data.status) != "undefined" && data.status != null && data.status == '401'){
                    window.location.reload();
                }
            }
        });
    });
</script>
