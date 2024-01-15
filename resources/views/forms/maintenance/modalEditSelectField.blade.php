{{Form::Open(array('action'=>array('StFormController@updateSelectfield',$idcampo,$id),'method'=>'POST','autocomplete'=>'off','id'=>'formMainEditSelectField'))}}
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label id="nombreselectEdit--label">* Nombre del campo</label> <br>
            <input class="form-control" name="nombreselectEdit"  value="{{$nombre_campo}}" type="text" style="width:100%">
            <span id="nombreselectEdit-error" class="text-red"></span>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 tipo_select">
        <div class="form-group">
            <label id="tiposelectedit--label">* Tipo de select</label><br>
            <div class="checkbox  text-center">
                <label><input type="radio" class="tipo_selectedit" name="tiposelectedit" @if($type == 'select') checked @endif value="normal"> <b>Normal</b>  </label>
                <label><input type="radio" class="tipo_selectedit" name="tiposelectedit" @if($type == 'select2') checked @endif value="select2"> <b>Con buscador</b>  </label>
                <label><input type="radio" class="tipo_selectedit" name="tiposelectedit" @if($type == 'multiple') checked @endif value="multiple"> <b>Múltiple</b>  </label>
            </div>
            <center><span id="tiposelectedit-error" class="text-red font-weight-bold"></span></center>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 opciones_selectedit" style="padding-bottom:20px">
        <label id="myOptionsSelectedit--label">* Opciones select</label> <br>
        <span id="myOptionsSelectedit-error" class="text-red"></span>
        <button type="button" class="btn btn-success pull-right add_input_button_selectedit">Más</button>
        @php    $a = 0; @endphp
        @foreach ($options as $optE)
            <div style="padding-bottom:10px;">
                <input type="text" name="myOptionsSelectedit[]" value="{{ $optE['mostraropt'] }}" class="inputmultipleSelect form-control-append" placeholder="Ej. Malo" style="width:30%">
                @if ($a != 0)
                    <a class="remove_inputselectedit cursor-pointer" title="Borrar" >
                        <svg class="icon text-muted iconhover" style="margin-bottom:5px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                    </a>
                @else
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                @endif
            </div>
            @php    $a++;   @endphp
        @endforeach
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-yellow pull-right" name="btnSubmitEditSelectField">Confirmar</button>
    </div>
</div>
{{Form::Close()}}
<script>
    $('.tipo_selectedit').iCheck({
        radioClass: 'iradio_square-yellow',
        increaseArea: '5%'
    });
    // ================  Select ================================
    $(".add_input_button_selectedit").click(function (e) {
        $(document).ready(function(){
            $(".select2-selection").addClass('form-select2').css('border-color','#ccc');
            $(".select2-selection--single").addClass('form-selectcont');
        });
        e.preventDefault();
        $(".opciones_selectedit").append(
        '<div style="padding-bottom:10px">'+
            '<input type="text" name="myOptionsSelectedit[]" class="inputmultipleSelect form-control-append" placeholder="Ej. Bueno" style="width:30%">&nbsp;'+
            '<a href="#" class="remove_inputselectedit" title="Borrar">'+
                '<svg class="icon text-muted iconhover" style="margin-bottom:5px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
            '</a>'+
        '</div>');
        $('.select2oc2').select2({
            minimumResultsForSearch: -1
        });
    });

    $(".opciones_selectedit").on("click", ".remove_inputselectedit", function (e) {
        e.preventDefault();
        $(this).parent('div').remove();
    })
</script>
{{-- ========================================================================================================================== --}}
{{--                                                            VALIDACION                                                      --}}
{{-- ========================================================================================================================== --}}
<script>
    var campos = ['nombreselectEdit','texto_tipoedit'];


    $("#formMainEditSelectField").on('submit', function(e) {
        e.preventDefault();
        var registerForm = $("#formMainEditSelectField");
        var formData = new FormData($("#formMainEditSelectField")[0]);
        $.each(campos, function( indice, valor ) {
            $("#"+valor+"-error").html( "" );
            var inputtype = $("[name="+valor+"]").attr("type");
        });
        $('#myOptionsSelectedit-error' ).html( '' );
        $('input[name^=myOptionsSelectedit]').map(function(idx, elem) {
            $(elem).removeClass('is-invalid').addClass('is-valid');
        }).get();

        $.ajax({
            url: '/forms/maintenance/updateSelectfield/{{$idcampo}}/{{$id}}',
            type: "POST",
            data:formData,
            contentType: false,
            processData: false,
            success:function(data) {
                if(data.alerta) {
                    toastr.error(data.mensaje);
                    $("[name=btnSubmitEditSelectField]").attr('disabled',false)
                }
                if(data.success) {
                    var contid = (data.contid) ? '?contid='+data.contid : "";
                    var subconte = (data.subconte) ? '&subc='+data.subconte : "";
                    $("[name=btnSubmitEditSelectField]").attr('disabled',true)
                    window.location.href = '/forms/maintenance/{{ $id }}'+contid+subconte;
                }
            },
            error: function(data){
                if(data.responseJSON.errors) {
                    var sw_select = sw_select = sw_select = 0;
                    $.each(data.responseJSON.errors, function( index, value ) {
                        if (~index.indexOf("myOptionsSelectedit")){
                            sw_select = 1;
                        }else{
                            $('#'+index+'-error' ).html( '&nbsp;<i class="fa fa-ban"></i> '+value );
                            var inputtype = $("[name="+index+"]").attr("type");
                        }
                    });
                    if(sw_select == 1){
                        $('#myOptionsSelectedit-error' ).html( '&nbsp;<i class="fa fa-ban"></i> Debe llenar todos los campos de opciones de select.' );
                        $('input[name^=myOptionsSelectedit]').map(function(idx, elem) {
                            if ( $(elem).val() == "" )  $(elem).removeClass('is-valid').addClass('is-invalid');
                        }).get();
                    }

                    var indexaux = []; var camposaux =[]; var i=0;
                    $.each(campos, function( indice, valor ) {
                        if(data.responseJSON.errors[valor]){
                            indexaux[i] = indice;  i++;
                        }
                        var j = indice;
                        camposaux[j] = valor;
                    });
                    $("[name=btnSubmitEditSelectField]").attr('disabled',false);
                }
                if(typeof(data.status) != "undefined" && data.status != null && data.status == '401'){
                    window.location.reload();
                }
            }
        });
    });
</script>