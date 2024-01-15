
{{Form::Open(array('action'=>array('StFormController@storeSerie',code($form->id)),'method'=>'post','id'=>'formStoreSerie'))}}
<div class="row">
    {{-- ===========================================================================================================================================================
                                                            CREAR SERIE O AÑADIR CAMPOS A SERIE YA EXISTENTE
    ================================================================================================================================================================ --}}
    <div class="container-fluid hidden" style="border: solid 1px lightgray; border-radius: 3px; padding: 2px; padding-top: -15px;">
        <div class="row" style="padding-left:10px; padding-right:10px">
            @if (count($nombre_serie)>0)
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <div class="checkbox text-center" style="seriesw--label">
                            <label><input type="radio" name="seriesw" id="newserie" value="new_serie" checked > <b>Crear Nueva Serie</b>  </label>
                            <label><input type="radio" name="seriesw" id="addserie" value="add_serie"> <b>Añadir Campo a Serie</b>  </label>
                        </div>
                        <center><span id="seriesw-error" class="text-red font-weight-bold"></span></center>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add_serie" style="display:none">
                    <div class="form-group" id="nombreserie-sel2">
                        <label id="nombreserie--label">* Seleccionar Serie</label><br>
                        <select name="nombreserie" id="nombreserie" class="form-control selector-modal" style="width: 100%">
                            <option value="" hidden>Seleccionar</option>
                            @foreach ($nombre_serie as $keyser => $nomser)
                                <option value="{{$keyser}}">{{$nomser}}</option>
                            @endforeach
                        </select>
                        <span id="nombreserie-error" class="text-red"></span>
                    </div>
                </div>
            @endif
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 new_serie" >
                <div class="form-group">
                    <label id="name_new_serie--label">* Nombre de serie:</label> <br>
                    <input type="text" name="name_new_serie" class="form-control" style="width: 100%;" placeholder="Nombre de serie que se mostrará en el procedimiento">
                    <span id="name_new_serie-error" class="text-red"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 new_serie" >
        <div class="form-group">
            <label id="name_new_serie--label">* Nombre de serie:</label> <br>
            <input type="text" name="name_new_serie" class="form-control" style="width: 100%;" placeholder="Nombre de serie que se mostrará en el procedimiento">
            <span id="name_new_serie-error" class="text-red"></span>
        </div>
    </div>

    {{-- ===========================================================================================================================================================
                                                            ASIGNAR SERIE A CONTENEDOR
    ================================================================================================================================================================ --}}
    <div class="container-fluid container_newserie" style="border: solid 1px lightgray; border-radius: 3px; padding: 2px; padding-top: -15px;margin-top: 10px;">
        <div class="row" style="padding-left:10px; padding-right:10px">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <h3>ASIGNAR GRÁFICO A UN CONTENEDOR</h3>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="form-group" id="seriecontenedorid-sel2">
                    <label id="seriecontenedorid--label">* Contenedor</label><br>
                    <select name="seriecontenedorid" id="seriecontenedorid" class="form-control selector-modal" style="width: 100%">
                        <option value="" hidden>Seleccionar</option>
                        @foreach ($containers as $cont)
                            <option value="{{$cont['id']}}">{{$cont['mostrar']}}</option>
                        @endforeach
                    </select>
                    <span id="seriecontenedorid-error" class="text-red"></span>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" id="seriedivsubcont">
                <div class="form-group" id="subcontenedor-sel2">
                    <label id="subcontenedor--label">* Sub contenedor</label><br>
                    <select name="subcontenedor" class="form-control seriesalidaprueba selector-modal" style="width: 100%" data-placeholder="Sub contenedores" title="Primero seleccionar contenedor">
                    </select>
                    <span id="subcontenedor-error" class="text-red"></span>
                </div>
            </div>
        </div>
    </div>

    {{-- ===========================================================================================================================================================
                                                            GRAFICOS DE LA SERIE
    ================================================================================================================================================================ --}}
    <div class="container-fluid container_newserie" style="border: solid 1px lightgray; border-radius: 3px; padding: 2px; padding-top: -15px;margin-top: 10px;">
        <div class="row" style="padding-left:10px; padding-right:10px">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <h3>SELECCIONAR GRÁFICO</h3>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 tipo_grafico">
                <div class="form-group" id="selectgrafico-sel2">
                    <label id="selectgrafico--label">* Tipo de gráfico</label><br>
                    <select name="selectgrafico" class="form-control selector-modal selectgraficoid" id="selectgraficoid" style="width: 100%">
                        <option value="" hidden>Seleccionar</option>
                        <option value="serie_graf" >Matricial (MxN)</option>
                        <option value="xvsy_graf" >X vs Y</option>
                    </select>
                    <span id="selectgrafico-error" class="text-red"></span>
                </div>
            </div>

            {{-- Serie grafica --}}
            <div class="valores_grafico_serie" style="display: none">
                <div class="row">
                    <div class=" col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label id="valmin--label">* Valor Minimo:</label> <br>
                            <input type="text" class="form-control numero" name="valmin" style="width: 100%;">
                            <span id="valmin-error" class="text-red"></span>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" >
                        <div class="form-group">
                            <label id="valmax--label numero">* Valor máximo:</label> <br>
                            <input type="text" class="form-control numero" name="valmax" style="width: 100%;">
                            <span id="valmax-error" class="text-red"></span>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" >
                        <img src="{{asset('imagenes/mxn1.png') }}" class="img_grafico" style="display:none" id="img_seriemxn">
                    </div>
                </div>
            </div>

            {{-- Serie grafica XY --}}
            <div class="valores_grafico_xy" style="display: none">
                <div class="row" style="margin-left:0px">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group" id="tipo_grafico_xy-sel2">
                            <label id="tipo_grafico_xy--label">* Tipo de gráfico</label><br>
                            <select name="tipo_grafico_xy" class="form-control selector-modal tipograficoid" id="tipograficoid" style="width: 100%">
                                <option value="" hidden>Seleccionar</option>
                                <option value="grafico_coordenadas" >Coordenadas</option>
                                <option value="grafico_area" >Área</option>
                                <option value="grafico_barras" >Barras</option>
                            </select>
                            <span id="tipo_grafico_xy-error" class="text-red"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label id="nombre_eje_x--label">* Nombre de eje X:</label> <br>
                            <input class="form-control" type="text" name="nombre_eje_x" style="width: 100%;">
                            <span id="nombre_eje_x-error" class="text-red"></span>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label id="nombre_eje_y--label">* Nombre de eje Y:</label> <br>
                            <input class="form-control" type="text" name="nombre_eje_y" style="width: 100%;">
                            <span id="nombre_eje_y-error" class="text-red"></span>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" >
                        <img src="{{asset('imagenes/barras_xy.png') }}" class="img_grafico" style="display:none" id="img_barras">
                        <img src="{{asset('imagenes/lineal_xy.png') }}" class="img_grafico" style="display:none" id="img_lineal">
                        <img src="{{asset('imagenes/area_xy.png') }}" class="img_grafico" style="display:none" id="img_area">
                    </div>
                </div>
            </div>

            {{-- Serie multiple PENDIENTE --}}
            <div class="valores_serie_multiple" style="display: none">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <button type="button" class="btn btn-success pull-right add_multiple_button">Agregar más Campos </button> <br>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>Nombre de Campo 1:</label> <br>
                        <input class="form-control" type="text" name="nombre_mult_1" id="nombre_mult_1" style="width: 100%;">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>Nombre de Campo 2:</label> <br>
                        <input class="form-control" type="text" name="nombre_mult_2" id="nombre_mult_2" style="width: 100%;">
                    </div>
                </div>
                <div class="multiple_more"></div>
            </div>
        </div>
    </div>
    {{-- ===========================================================================================================================================================
                                                                AÑADIR CAMPOS A SERIE
    ================================================================================================================================================================ --}}
    <div class="container-fluid hidden" style="border: solid 1px lightgray; border-radius: 3px; padding: 2px; padding-top: -15px;margin-top: 10px;">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="margin-top:10px">
            <h3>
                <label class="checkbox">
                    ¿AÑADIR CAMPOS A SERIE?&nbsp;&nbsp;&nbsp;
                    <input type="checkbox" class="checkaddfields" name="checkaddfields" value="1" >
                </label>
            </label>
            </h3>
        </div>
        <div class="row" style="padding-left:10px; padding-right:10px;display:none" id="addFieldsSerie">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                <div class="form-group">
                    <label id="serieinputType--label">* Tipo de Campo</label>
                    <div class="checkbox text-center" id="serieinputType--label">
                        <label><input type="radio" name="serieinputType" id="serieradioin" value="radio"> <b>Radio</b>  </label>
                        <label><input type="radio" name="serieinputType" id="seriecheckin" value="checkbox"> <b>Checkbox</b>  </label>
                        <label><input type="radio" name="serieinputType" id="serietextoin" value="texto"> <b>Texto</b>  </label>
                        <label><input type="radio" name="serieinputType" id="serieselectin" value="select"> <b>Select</b>  </label>
                    </div>
                    <center><span id="serieinputType-error" class="text-red font-weight-bold"></span></center>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 serienombreinput" style="display:none">
                <div class="form-group">
                    <label id="seriefield_name--label">* Nombre del campo asignado a la serie:</label> <br>
                    <input type="text" class="form-control" name="seriefield_name" style="width: 100%;" placeholder="Nombre que se mostrará en el procedimiento" >
                    <span id="seriefield_name-error" class="text-red"></span>
                </div>
            </div>
            {{-- Radio Button --}}
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 serieopciones_radio" style="display:none; padding-bottom:20px">
                <label id="seriemyOptionsRadio--label">* Opciones Radio</label> <br>
                <span id="seriemyOptionsRadio-error" class="text-red"></span>
                <button type="button" class="btn btn-success pull-right serieadd_input_button_radio">Más</button>
                <div style="padding-bottom:10px;" class="seriemoreradio0">
                    <input type="text" name="seriemyOptionsRadio[]" class="inputmultipleRadio form-control-append" placeholder="Opciones que podrá escoger" style="width:48%">
                    <a><i class="fa fa-trash-alt" style="color:transparent"></i></a>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <select class="select2oc" name="myOptionsColorSerie[]" style="width:94.05px">
                        <option selected>Rojo</option>
                        <option >Amarillo</option>
                        <option>Verde</option>
                        <option>Azul</option>
                        <option>Naranja</option>
                        <option>Morado</option>
                    </select>
                </div>
            </div>
            {{-- CheckBox --}}
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 serieopciones_check" style="display:none; padding-bottom:20px">
                <label id="seriemyOptionsCheck--label">* Opciones Check</label> <br>
                <span id="seriemyOptionsCheck-error" class="text-red"></span>
                <button type="button" class="btn btn-success pull-right serieadd_input_button_check">Agregar</button>
                <div style="padding-bottom:10px"><input type="text" name="seriemyOptionsCheck[]" class="inputmultipleCheck form-control-append" placeholder="Opciones que podrá escoger" style="width:48%"></div>
            </div>
            {{-- Select --}}
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 serietipo_select" style="display:none">
                <div class="form-group">
                    <label>* Tipo de Select</label><br>
                    <span id="serietiposelect-error" class="text-red"></span>
                    <div class="checkbox text-center">
                        <label><input type="radio" class="serietipo_select" name="serietiposelect" id="selnormal" value="normal" checked> <b>Normal</b>  </label>
                        <label><input type="radio" class="serietipo_select" name="serietiposelect" id="selbuscador" value="select2"> <b>Con Buscador</b>  </label>
                        <label><input type="radio" class="serietipo_select" name="serietiposelect" id="selmultiple" value="multiple"> <b>Múltiple</b>  </label>
                    </div>
                    <center><span id="serietiposelect-error" class="text-red font-weight-bold"></span></center>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 serieopciones_select" style="display:none; padding-bottom:20px">
                <label id="seriemyOptionsSelect--label">* Opciones Select</label> <br>
                <span id="seriemyOptionsSelect-error" class="text-red"></span>
                <button type="button" class="btn btn-success pull-right serieadd_input_button_select">Agregar</button>
                <div style="padding-bottom:10px"><input type="text" name="seriemyOptionsSelect[]" class="inputmultipleSelect form-control-append" placeholder="Opciones que podrá escoger" style="width:48%"></div>
            </div>
            {{-- Texto --}}
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 seriecontenedor_texto_tipo" style="display:none; padding-bottom:20px">
                <label id="serietexto_tipo--label">* Tipo de Texto </label> <br>
                <div class="checkbox text-center">
                    <label><input type="radio" class="serietipo_texto" name="serietexto_tipo" value="text"> <b>Caja de Texto</b>  </label>
                    <label><input type="radio" class="serietipo_texto" name="serietexto_tipo" value="textarea"> <b>Área de Texto</b>  </label>
                    <label><input type="radio" class="serietipo_texto" name="serietexto_tipo" value="date"> <b>Fecha</b>  </label>
                    <label><input type="radio" class="serietipo_texto" name="serietexto_tipo" value="time"> <b>Hora</b>  </label>
                    <label><input type="radio" class="serietipo_texto" name="serietexto_tipo" value="number"> <b>Numérico</b>  </label>
                    <label><input type="radio" class="serietipo_texto" name="serietexto_tipo" value="money"> <b>Moneda</b></label>
                </div>
                <center><span id="serietexto_tipo-error" class="text-red font-weight-bold"></span></center>
            </div>
        </div>
    </div><br>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"  style="padding-top:20px" >
        <button type="button" class="btn btn-ghost-secondary pull-left cerrarmodal" data-dismiss="modal" >Cancelar</button>
        <button type="submit" class="btn btn-yellow pull-right" name="btnSubmitSerie">Registrar campo</button>
    </div>
</div>
{{Form::Close()}}

<script>

    AutoNumeric.multiple('.numero',{
        modifyValueOnWheel: false,
        digitGroupSeparator : '',
        minimumValue: 0
    });
    $('.checkaddfields').iCheck({
        checkboxClass: 'icheckbox_square-yellow',
    }).on('ifChecked', function (event) {
        $("#addFieldsSerie").show();
    }).on('ifUnchecked', function (event) {
        $("#addFieldsSerie").hide();
    });
    $('#modalCreateSerie').on('hidden.bs.modal', function () {
        $('#seriein').iCheck('uncheck');
    })
    // Ajax para generar subcontainer de Series
    $('#seriecontenedorid').change(function () {
        selectsubcontainerserie();
    });

    $(document).ready(function(){
        $(".select2-selection").addClass('form-select2').css('border-color','#ccc');
        $(".select2-selection--single").addClass('form-selectcont');
    });
    function selectsubcontainerserie(){
        var query = $("#seriecontenedorid").val();
        var idform = "{{code($form->id)}}";
        if (query != '') {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('forms.subcontainerajax') }}",
                method: "POST",
                data: { query: query, _token: _token, idform: idform},
                success: function (salida) {
                    $('.seriesalidaprueba').html(salida.selectxd1);
                }
            });
        }
    }

    $('.selector-modal').select2({
        dropdownParent: $('#modalCreateSerie')
    });
    $('.select2oc').select2({ minimumResultsForSearch: -1 });

    $('select.selector-modal:not(.normal)').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent().parent()
        });
    });

    $("select.tipograficoid").change(function(){
        var option = $(this).children("option:selected").val();
        if(option == "grafico_coordenadas"){
            $('#img_lineal').show();
            $('#img_barras').hide();
            $('#img_area').hide();
        }else{
            if(option == "grafico_barras"){
                $('#img_barras').show();
                $('#img_lineal').hide();
                $('#img_area').hide();
            }else{
                if(option == "grafico_area"){
                    $('#img_area').show();
                    $('#img_lineal').hide();
                    $('#img_barras').hide();
                }
            }
        }
    });

    $("select.selectgraficoid").change(function(){
        var option = $(this).children("option:selected").val();
        if (option == 'serie_simple'){
            $('.checkaddfields').iCheck('check');
            $('.checkaddfields').iCheck('disable');
        }else{
            $('.checkaddfields').iCheck('uncheck');
            $('.checkaddfields').iCheck('enable');
        }

        if(option == "serie_graf"){
            $('.valores_grafico_serie').show();
            $('.valores_grafico_xy').hide();
            $('.valores_serie_multiple').hide();
            $('#img_seriemxn').show();
        }else{
            if(option == "xvsy_graf"){
                $('.valores_grafico_xy').show();
                $('.valores_grafico_serie').hide();
                $('.valores_serie_multiple').hide();
            }else{
                if(option == "serie_multiple"){
                    $('.valores_serie_multiple').show();
                    $('.valores_grafico_xy').hide();
                    $('.valores_grafico_serie').hide();
                }else{
                    // serie_multiple
                    $('.valores_grafico_xy').hide();
                    $('.valores_grafico_serie').hide();
                    $('.valores_serie_multiple').hide();
                }
            }
            $('#img_seriemxn').hide();
        }
    });

    var max_fields_eje = 5;
    var xeje=1;
    $(".add_eje_button").click(function (e) {
        e.preventDefault();
        if (xeje < max_fields_eje) {
            $(".eje_more").append(
                '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">'+
                    '<a href="#" class="remove_eje" title="Borrar Campos" style="margin-left:225px;"><svg class="icon text-muted iconhover" style="margin-bottom:5px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></a><br>'+
                    '<div class="form-group">'+
                        '<label>Nombre de Campo Adicional:</label> <br>'+
                        '<input type="text" name="nombre_eje_more[]" class="nombre_eje_more" multiple style="width: 100%;">'+
                    '</div>'+
                '</div>');
            xeje++;
        }else{  alert("llegó al máximo de opciones permitidas") }
    });

    var max_multiple = 3;
    var xmult=1;
    $(".add_multiple_button").click(function (e) {
        e.preventDefault();
        if (xmult < max_multiple) {
            $(".multiple_more").append(
                '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:15px">'+
                    '<b>Nombre de Campo:</b>&nbsp;&nbsp;'+'<a href="#" class="remove_eje" title="Borrar Campos"><svg class="icon text-muted iconhover" style="margin-bottom:5px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></a><br>'+
                    '<input type="text" name="nombre_multiple_more[]" class="nombre_multiple_more" multiple style="width: 100%;">'+
                '</div>');
            xmult++;
        }else{  alert("llegó al máximo de opciones permitidas") }
    });


    $(".eje_more").on("click", ".remove_eje", function (e) {
        e.preventDefault();
        $(this).parent('div').remove();
        xeje--;
    })

        var max_fields = 10;
        // ================  Radio Button ================================
        var x = 1;
        var h = 1;
        var rPadre = 0;
        $(".serieadd_input_button_radio").click(function (e) {
            e.preventDefault();
            if (x < max_fields) {
                $(".serieopciones_radio").append(
                    '<div style="padding-bottom:10px" class="seriemoreradio'+h+'">'+
                        '<input type="text" name="seriemyOptionsRadio[]" class="inputmultipleRadio form-control-append" placeholder="Opciones que podrá escoger" style="width:48%"/>&nbsp;&nbsp;'+
                        '<a href="#" class="remove_input" title="Borrar"><svg class="icon text-muted iconhover" style="margin-bottom:5px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></a>'+
                        '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
                        '<select class="select2oc2" name="myOptionsColorSerie[]"  style="width:94.05px">'+
                            '<option >Rojo</option>'+
                            '<option >Amarillo</option>'+
                            '<option >Verde</option>'+
                            '<option selected>Azul</option>'+
                            '<option >Naranja</option>'+
                            '<option >Morado</option>'+
                        '</select>'+
                    '</div>');
                x++;
                h++;
            }else{  alert("llegó al máximo de opciones permitidas") }
            $('.select2oc2').select2({ minimumResultsForSearch: -1 });
        });
        $(".serieopciones_radio").on("click", ".remove_input", function (e) {
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
        })


        $('#serieradioin').iCheck({
            radioClass: 'iradio_square-yellow',
            increaseArea: '5%'
        }).on('ifChecked', function (event) {
            $('.serienombreinput').show(1000);
            $('.serieopciones_radio').show(1000);
            $('.serieopciones_check').hide(1000);
            $('.serieopciones_select').hide(1000);
            $('.serietipo_select').hide(1000);
            $('.seriecontenedor_texto_tipo').hide(1000);
            $('.serietipo_serie').hide(1000);
        });
        // ================  Checkbox ================================
        var y = 1;
        $(".serieadd_input_button_check").click(function (e) {
            e.preventDefault();
            if (y < max_fields) {
                y++;
                $(".serieopciones_check").append(
                    '<div style="padding-bottom:10px">'+
                        '<input type="text" name="seriemyOptionsCheck[]" class="inputmultipleCheck form-control-append" placeholder="Opciones que podrá escoger" style="width:48%"/>&nbsp;&nbsp;'+
                        '<a href="#" class="remove_input" title="Borrar">'+
                            '<svg class="icon text-muted iconhover" style="margin-bottom:5px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
                        '</a>'+
                    '</div>');
            }else{  alert("llegó al máximo de opciones permitidas") }
        });
        $(".serieopciones_check").on("click", ".remove_input", function (e) {
            e.preventDefault();
            $(this).parent('div').remove();
            y--;
        })
        $('#seriecheckin').iCheck({
            radioClass: 'iradio_square-yellow',
            increaseArea: '5%'
        }).on('ifChecked', function (event) {
            $('.serienombreinput').show(1000);
            $('.serieopciones_check').show(1000);
            $('.serieopciones_radio').hide(1000);
            $('.serieopciones_select').hide(1000);
            $('.serietipo_select').hide(1000);
            $('.seriecontenedor_texto_tipo').hide(1000);
            $('.serietipo_serie').hide(1000);
        });
        // ================  Select ================================
        var z = 1;
        $(".serieadd_input_button_select").click(function (e) {
            e.preventDefault();
            if (z < max_fields) {
                z++;
                $(".serieopciones_select").append(
                    '<div style="padding-bottom:10px">'+
                        '<input type="text" name="seriemyOptionsSelect[]" class="inputmultipleSelect form-control-append" placeholder="Opciones que podrá escoger" style="width:48%"/>&nbsp;&nbsp;'+
                        '<a href="#" class="remove_input" title="Borrar"><svg class="icon text-muted iconhover" style="margin-bottom:5px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></a>'+
                    '</div>');
            }else{  alert("llegó al máximo de opciones permitidas") }
        });
        $(".serieopciones_select").on("click", ".remove_input", function (e) {
            e.preventDefault();
            $(this).parent('div').remove();
            z--;
        })
        $('#serieselectin').iCheck({
            radioClass: 'iradio_square-yellow',
            increaseArea: '5%'
        }).on('ifChecked', function (event) {
            $('.serienombreinput').show(1000);
            $('.serieopciones_select').show(1000);
            $('.serietipo_select').show(1000);
            $('.serieopciones_check').hide(1000);
            $('.serieopciones_radio').hide(1000);
            $('.seriecontenedor_texto_tipo').hide(1000);
            $('.serietipo_serie').hide(1000);
        });

        // ================  Text ================================
        $('#serietextoin').iCheck({
            radioClass: 'iradio_square-yellow',
            increaseArea: '5%'
        }).on('ifChecked', function (event) {
            $('.serienombreinput').show(1000);
            $('.seriecontenedor_texto_tipo').show(1000);
            $('.serieopciones_select').hide(1000);
            $('.serietipo_select').hide(1000);
            $('.serieopciones_check').hide(1000);
            $('.serieopciones_radio').hide(1000);
            $('.serietipo_serie').hide(1000);
        });


    $('#newserie').iCheck({
        radioClass: 'iradio_square-yellow',
        increaseArea: '5%'
    }).on('ifChecked', function (event) {
        $('.checkaddfields').iCheck('enable');
        $('.new_serie').show(1000);
        $('.add_serie').hide(1000);
        $('.container_newserie').show(1000);
        $('.checkgrafico').show(1000);
    });

    $('#addserie').iCheck({
        radioClass: 'iradio_square-yellow',
        increaseArea: '5%'
    }).on('ifChecked', function (event) {
        $('.checkaddfields').iCheck('check');
        $('.checkaddfields').iCheck('disable');
        $('.new_serie').hide(1000);
        $('.add_serie').show(1000);
        $('.container_newserie').hide(1000);
        $('.checkgrafico').hide(1000);
    });


    $('#grafico_si').iCheck({
        radioClass: 'iradio_square-yellow',
        increaseArea: '5%'
    }).on('ifChecked', function (event) {

    });

    $('#grafico_no').iCheck({
        radioClass: 'iradio_square-yellow',
        increaseArea: '5%'
    }).on('ifChecked', function (event) {
    });

    $('.serietipo_texto').iCheck({
        radioClass: 'iradio_square-yellow',
        increaseArea: '5%'
    });

    $('.serietipo_select').iCheck({
        radioClass: 'iradio_square-yellow',
        increaseArea: '5%'
    });

</script>


<script>
    var campos = ['seriesw','nombreserie','name_new_serie', 'seriecontenedorid','subcontenedor','selectgrafico','valmin','valmax','tipo_grafico_xy','nombre_eje_x','nombre_eje_y','serieinputType','seriefield_name','seriemyOptionsRadio','seriemyOptionsCheck','serietiposelect','seriemyOptionsSelect','serietexto_tipo'];
    $("#formStoreSerie").on('submit', function(e) {
        e.preventDefault();
        var registerForm = $("#formStoreSerie");
        var formData = new FormData($("#formStoreSerie")[0]);
        $.each(campos, function( indice, valor ) {
            $("#"+valor+"-error").html( "" );
            var inputtype = $("[name="+valor+"]").attr("type");
            if(inputtype != 'radio')    $("[name="+valor+"]").removeClass('is-invalid').addClass('is-valid');
            $("select[name="+valor+"]").removeClass('is-invalid-select').addClass('is-valid-select').removeClass('select2-selection');
            $("#formStoreSerie #"+valor+"-sel2 .select2-selection").removeClass('is-invalid-select').addClass('is-valid-select');
            $("#formStoreSerie #"+valor+"-sel2 .select2-selection").css('border','1px solid #5eba00');
            $(".programadodiv").css('border','1px solid transparent');
        });
        $('input[name^=seriemyOptionsRadio]').map(function(idx, elem) {
            $(elem).removeClass('is-invalid').addClass('is-valid');
        }).get();
        $('input[name^=seriemyOptionsCheck]').map(function(idx, elem) {
            $(elem).removeClass('is-invalid').addClass('is-valid');
        }).get();
        $('input[name^=seriemyOptionsSelect]').map(function(idx, elem) {
            $(elem).removeClass('is-invalid').addClass('is-valid');
        }).get();

        $.ajax({
            url: "{{route('forms.serie.store',code($form->id))}}",
            type: "POST",
            data:formData,
            contentType: false,
            processData: false,
            success:function(data) {
                if(data.alerta) {
                    toastr.error(data.mensaje);
                    $("[name=btnSubmitSerie]").attr('disabled',false)
                }
                if(data.success) {
                    var contid = (data.contid) ? '?contid='+data.contid : "";
                    var subconte = (data.subconte) ? '&subc='+data.subconte : "";
                    $("[name=btnSubmitSerie]").attr('disabled',true)
                    window.location.href = "/forms/maintenance/{{ code($form->id) }}"+contid+subconte;
                }
            },
            error: function(data){
                if(data.responseJSON.errors) {
                    var sw_radio = sw_check = sw_select = 0;
                    $.each(data.responseJSON.errors, function( index, value ) {
                        if (~index.indexOf("seriemyOptionsRadio")){
                            sw_radio = 1;
                        }else if (~index.indexOf("seriemyOptionsCheck")){
                            sw_check = 1;
                        }else if (~index.indexOf("seriemyOptionsSelect")){
                            sw_select = 1;
                        }else{
                            $('#'+index+'-error' ).html( '&nbsp;<i class="fa fa-ban"></i> '+value );
                            var inputtype = $("[name="+index+"]").attr("type");
                            if(inputtype != 'radio')    $("[name="+index+"]").removeClass('is-valid').addClass('is-invalid');
                            $("select[name="+index+"]").removeClass('is-valid-select').addClass('is-invalid-select').removeClass('select2-selection');
                            $("#formStoreSerie #"+index+"-sel2 .select2-selection").removeClass('is-valid-select').addClass('is-invalid-select');
                            $("#formStoreSerie #"+index+"-sel2 .select2-selection").css('border','1px solid #cd201f');
                        }
                    });
                    if(sw_radio == 1){
                        $('#seriemyOptionsRadio-error' ).html( '&nbsp;<i class="fa fa-ban"></i> Debe llenar todos los campos de opciones de radio.' );
                        $('input[name^=seriemyOptionsRadio]').map(function(idx, elem) {
                            if ( $(elem).val() == "" )  $(elem).removeClass('is-valid').addClass('is-invalid');
                        }).get();
                    }
                    if(sw_check == 1){
                        $('#seriemyOptionsCheck-error' ).html( '&nbsp;<i class="fa fa-ban"></i> Debe llenar todos los campos de opciones de check.' );
                        $('input[name^=seriemyOptionsCheck]').map(function(idx, elem) {
                            if ( $(elem).val() == "" )  $(elem).removeClass('is-valid').addClass('is-invalid');
                        }).get();
                    }if(sw_select == 1){
                        $('#seriemyOptionsSelect-error' ).html( '&nbsp;<i class="fa fa-ban"></i> Debe llenar todos los campos de opciones de select.' );
                        $('input[name^=seriemyOptionsSelect]').map(function(idx, elem) {
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
                    var menor = Math.min.apply(null, indexaux);
                    $('#'+camposaux[menor]+'--label')[0].scrollIntoView({behavior: 'smooth'});
                    $("[name=btnSubmitSerie]").attr('disabled',false);
                }
                if(typeof(data.status) != "undefined" && data.status != null && data.status == '401'){
                    window.location.reload();
                }
            }
        });
    });
</script>
