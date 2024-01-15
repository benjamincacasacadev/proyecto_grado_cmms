<style>
    .select2-container--default .select2-selection--single {
        border: 1px solid #aaa;
        border-radius: 0px;
        height: 35px !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='rgba%28110, 117, 130, 0.44%29' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") ;
        background-repeat: no-repeat;
        background-position: right .75rem center;
        background-size: 16px 12px;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
</style>

{{Form::Open(array('action'=>array('StFormController@updateRadiofield',$idcampo,$id),'method'=>'POST','autocomplete'=>'off','id'=>'formMainEditRadioField'))}}
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label id="nombreradioEdit--label">* Nombre del campo</label> <br>
            <input class="form-control" name="nombreradioEdit"  value="{{$nombre_campo}}" type="text" style="width:100%">
            <span id="nombreradioEdit-error" class="text-red"></span>
        </div>
    </div>
    @if ($radioDep == 1)
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="alert alert-warning" role="alert">
                <h4 class="alert-title">
                    Información importante
                </h4>
                <div class="text-muted">
                    Debe tener en cuenta que el campo radio a modificar tiene campos dependientes asociados y por esta razón NO SE PODRÁN EDITAR las opciones previamente guardadas.
                </div>
            </div>
        </div>
    @endif

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 opciones_radioedit" style="padding-bottom:20px">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
            <label id="myOptionsRadioedit--label">* Opciones Radio</label><br>
            <span id="myOptionsRadioedit-error" class="text-red"></span>
            <button type="button" class="btn btn-success pull-right add_input_button_radioedit">Más</button>
        </div>
        @php    $a = 0; @endphp
        @foreach ($options as $optE)
            <div style="padding-bottom:10px;" class="moreradioedit">
                <input type="text" name="myOptionsRadioedit[]" value="{{ $optE['mostraropt'] }}" class="inputmultiple form-control-append" placeholder="Ej. Malo" style="width:30%" @if ($radioDep == 1) readonly @endif >
                @if ($radioDep == 0)
                    @if ($a != 0)
                        <a class="remove_inputedit cursor-pointer" title="Borrar" >
                            <svg class="icon text-muted iconhover" style="margin-bottom:5px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                        </a>
                    @else
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    @endif
                @else
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                @endif
                &nbsp;&nbsp;&nbsp;
                <select class="select2oc" name="myOptionsColorEdit[]" style="width: 92px;">
                    <option @if($optE['hex'] == '#D54E21') selected @endif>Rojo</option>
                    <option @if($optE['hex'] == '#FFCC33') selected @endif>Amarillo</option>
                    <option @if($optE['hex'] == '#008D4C') selected @endif>Verde</option>
                    <option @if($optE['hex'] == '#367FA9') selected @endif>Azul</option>
                    <option @if($optE['hex'] == '#DE8650') selected @endif>Naranja</option>
                    <option @if($optE['hex'] == '#A77A94') selected @endif>Morado</option>
                </select>
            </div>
            @php    $a++;   @endphp
        @endforeach
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-yellow pull-right" name="btnSubmitEditRadioField">Confirmar</button>
    </div>
</div>
{{Form::Close()}}
<script>
    $(document).ready(function(){
        $(".select2-selection").addClass('form-select2').css('border-color','#ccc');
        $(".select2-selection--single").addClass('form-selectcont');
    });
    $('.select2oc').select2({
        minimumResultsForSearch: -1
    });

    $('.select2oc').select2({
        dropdownParent: $('#modalEditar')
    });
    $('select.select2oc:not(.normal)').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent().parent()
        });
    });

    // ================  Radio Button ================================
    $(".add_input_button_radioedit").click(function (e) {
        $(document).ready(function(){
            $(".select2-selection").addClass('form-select2').css('border-color','#ccc');
            $(".select2-selection--single").addClass('form-selectcont');
        });
        e.preventDefault();
        $(".opciones_radioedit").append(
        '<div style="padding-bottom:10px">'+
            '<input type="text" name="myOptionsRadioedit[]" class="inputmultiple form-control-append" placeholder="Ej. Bueno" style="width:30%">&nbsp;'+
            '<a href="#" class="remove_inputedit" title="Borrar">'+
                '<svg class="icon text-muted iconhover" style="margin-bottom:5px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
            '</a>'+
            '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
            '<select class="select2oc2" name="myOptionsColorEdit[]" style="padding-right:10px !important;width:92px" >'+
                '<option>Rojo</option>'+
                '<option>Amarillo</option>'+
                '<option >Verde</option>'+
                '<option selected>Azul</option>'+
                '<option>Naranja</option>'+
                '<option>Morado</option>'+
            '</select>'+
        '</div>');
        $('.select2oc2').select2({
            minimumResultsForSearch: -1
        });
    });

    $(".opciones_radioedit").on("click", ".remove_inputedit", function (e) {
        e.preventDefault();
        $(this).parent('div').remove();
    })
</script>
{{-- ========================================================================================================================== --}}
{{--                                                            VALIDACION                                                      --}}
{{-- ========================================================================================================================== --}}
<script>
    var campos = ['nombreradioEdit','texto_tipoedit'];


    $("#formMainEditRadioField").on('submit', function(e) {
        e.preventDefault();
        var registerForm = $("#formMainEditRadioField");
        var formData = new FormData($("#formMainEditRadioField")[0]);
        $.each(campos, function( indice, valor ) {
            $("#"+valor+"-error").html( "" );
            var inputtype = $("[name="+valor+"]").attr("type");
            if(inputtype != 'radio')    $("[name="+valor+"]").removeClass('is-invalid').addClass('is-valid');
            $(".programadodiv").css('border','1px solid transparent');
        });
        $('#myOptionsRadioedit-error' ).html( '' );
        $('input[name^=myOptionsRadioedit]').map(function(idx, elem) {
            $(elem).removeClass('is-invalid').addClass('is-valid');
        }).get();

        $.ajax({
            url: '/forms/maintenance/updateRadiofield/{{$idcampo}}/{{$id}}',
            type: "POST",
            data:formData,
            contentType: false,
            processData: false,
            success:function(data) {
                if(data.alerta) {
                    toastr.error(data.mensaje);
                    $("[name=btnSubmitEditRadioField]").attr('disabled',false)
                }
                if(data.success) {
                    var contid = (data.contid) ? '?contid='+data.contid : "";
                    var subconte = (data.subconte) ? '&subc='+data.subconte : "";
                    $("[name=btnSubmitEditRadioField]").attr('disabled',true)
                    window.location.href = '/forms/maintenance/{{ $id }}'+contid+subconte;
                }
            },
            error: function(data){
                if(data.responseJSON.errors) {
                    var sw_radio = sw_check = sw_select = 0;
                    $.each(data.responseJSON.errors, function( index, value ) {
                        if (~index.indexOf("myOptionsRadioedit")){
                            sw_radio = 1;
                        }else{
                            $('#'+index+'-error' ).html( '&nbsp;<i class="fa fa-ban"></i> '+value );
                            var inputtype = $("[name="+index+"]").attr("type");
                            if(inputtype != 'radio')    $("[name="+index+"]").removeClass('is-valid').addClass('is-invalid');
                        }
                    });
                    if(sw_radio == 1){
                        $('#myOptionsRadioedit-error' ).html( '&nbsp;<i class="fa fa-ban"></i> Debe llenar todos los campos de opciones de radio.' );
                        $('input[name^=myOptionsRadioedit]').map(function(idx, elem) {
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
                    $("[name=btnSubmitEditRadioField]").attr('disabled',false);
                }
                if(typeof(data.status) != "undefined" && data.status != null && data.status == '401'){
                    window.location.reload();
                }
            }
        });
    });

</script>
