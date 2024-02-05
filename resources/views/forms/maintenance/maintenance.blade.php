@extends ('layouts.admin', ['title_template' => "Campos: $form->name"])
@section('extracss')
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

    .campos_add_serie{
        padding-bottom:20px;
        margin-bottom:20px;
    }

    @media  (max-width: 1322px){
        .campos_add_serie{
            padding-bottom:50px;
        }
    }

    .label_serie{
        width: 350px;
        overflow:hidden;
        white-space:nowrap;
        text-overflow: ellipsis;
    }

    .highcharts-container {
        margin: 0 auto;
    }
    .select2-search--inline {
        display: contents;
    }
    .select2-search__field:placeholder-shown {
        width: 100% !important;
    }
    .icon-tabler {
        width: 25px;
        height: 25px;
        stroke-width: 1.25;
        margin-bottom: 2px;
    }
    .step-item.active {
        color: #f7a600;
        font-size: 15px;
        font-weight: bold;
    }
    @media  (max-width: 550px){
        .steplabel{
            display:none;
        }
        .stepMax{
            display:inline !important;
        }
    }

</style>
<link rel="stylesheet" href="{{asset('/plugins/iCheck/all.css')}}">
<link rel="stylesheet" href="{{asset('/plugins/timepicker/bootstrap-timepicker.min.css')}}">
<link rel="stylesheet" href="{{asset('/dist/css/colorSelect2.css?1')}}">
@endsection

@section ('contenidoHeader')
    @php    $contid = isset($_GET['contid']) ? $_GET['contid'] : "";    @endphp
    <div class="steps steps-counter steps-yellow" style="margin:0 !important">
        <a href="/forms/container/{{ code($form->id) }}" class="step-item">
            <span class="steplabel">Paso 1<br> Registrar contenedores</span>
            <span class="stepMax" hidden>Contenedores</span>
        </a>
        <a class="step-item active">
            <span class="steplabel">Paso 2<br> Registrar campos</span>
            <span class="stepMax" hidden>Campos</span>
        </a>
        @if ($form->check_letter == 1)
            <a href="/forms/letter/{{ code($form->id) }}" class="step-item">
                <span class="steplabel">Paso 3<br> Registrar carta</span>
                <span class="stepMax" hidden>Carta</span>
            </a>
        @endif
    </div>
@endsection

@section ('contenido')
@php
    $contid = isset($_GET['contid']) ? $_GET['contid'] : "";
    $subc = isset($_GET['subc']) ? $_GET['subc'] : "";
    $toastr = isset($_GET['toastr']) ? $_GET['toastr'] : "";
    $salidascript = $salidascript_xy = $salidaradio = "";
    $contid = isset($_GET['contid']) ? $_GET['contid'] : "";
    $contaux = 0;

    $styleSerie = (themeMode() == 'D') ? 'background-color:#2b3847;' : 'background-color:#f9f9f9;';
    $styleOrden = (themeMode() == 'D') ? 'class="text-white"' : '';
    $style = themeMode()=='D' ? 'background-color:#2e2e2c; border:0.2px solid #ccc;color:#2e2e2c' : 'background-color:#F9F9F9; border:0.2px solid #ccc; ';
@endphp

<div class="row " style="margin-bottom:20px">
    <div class="col-auto">
        <div class="page-pretitle">
            {{ nameEmpresa() }}
        </div>
        <h1 class="titulomod">
            {{$form->name}} &nbsp;
            @if ($form->state == 2)
                <i class="fa fa-check-circle text-center" style="font-size: 18px; color:green;" title="Finalizado"> </i>
            @endif
        </h1>
    </div>

    <div class="col-auto ms-auto">
        <div class="btn-list">
            <a href="/forms" class="btn btn-outline-secondary border border-secondary font-weight-bold" title="Ver todos los formularios">
                <i class="fa fa-list-ul fa-lg"></i>&nbsp;&nbsp;
                <span class="d-none d-sm-inline-block">
                    Ver todos los formularios
                </span>
            </a>
        </div>
    </div>
</div>

<div class="row">
    @if ($form->state == 2)
        <div class="text-center text-yellow" style="font-size:20px"><b> FORMULARIO:</b> {{$form->name}} </div> <br>
    @elseif(permisoAdminJefe())
        {{Form::Open(array('action'=>array('StFormController@storeMaintenance',code($form->id)),'method'=>'POST','id'=>'formMaintenanceForms'))}}
            <div class="offset-lg-1 col-lg-10">
                <div class="text-center text-yellow" style="font-size:20px"><b> REGISTRAR CAMPO NUEVO</b> </div> <br>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label>Categoría</label> <br>
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                    <svg class="icon" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="5" cy="5" r="1" />
                                        <circle cx="12" cy="5" r="1" />
                                        <circle cx="19" cy="5" r="1" />
                                        <circle cx="5" cy="12" r="1" />
                                        <circle cx="12" cy="12" r="1" />
                                        <circle cx="19" cy="12" r="1" />
                                        <circle cx="5" cy="19" r="1" />
                                        <circle cx="12" cy="19" r="1" />
                                        <circle cx="19" cy="19" r="1" />
                                    </svg>
                                </span>
                                <input class="form-control input-incon" type="text" value="{{ $form->categoriaLiteral }}" disabled >
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label> Tipo</label><br>
                            <input class="form-control" name="nombreform" type="text" value="{{$form->types->name}}" disabled>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group" id="contenedorid--sel2">
                            <label id="contenedorid--label">
                                * Contenedor
                                <a href="/forms/container/{{code($form->id)}}" title="Editar Contenedores" target="_blank" class="text-yellow">
                                    <i class="fas fa-plus-circle"></i>
                                </a>
                            </label>
                            <select name="contenedorid" id="contenedorid" class="form-control selector selContenedor" style="width: 100%">
                            </select>
                            <span id="contenedorid-error" class="text-red"></span>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group" id="subcontenedorprinc-sel2">
                            <label id="subcontenedorprinc--label">* Sub contenedor</label><br>
                            <select name="subcontenedorprinc" id="subcontenedorprinc" class="form-control selector selSubContenedor" style="width: 100%" data-placeholder="Sub contenedores" title="Primero seleccione un contenedor">
                            </select>
                            <span id="subcontenedorprinc-error" class="text-red"></span>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <div class="form-group">
                            <label id="inputType--label">* Tipo de campo</label>
                            <div class="checkbox  text-center">
                                <label class="opt_depcont"><input type="radio" name="inputType" id="radioin" value="radio"> <b>Radio</b>  </label>
                                <label class="opt_depcont"><input type="radio" name="inputType" id="checkin" value="checkbox"> <b>Checkbox</b>  </label>
                                <label class="opt_depcont"><input type="radio" name="inputType" id="textoin" value="texto"> <b>Texto</b>  </label>
                                <label class="opt_depcont"><input type="radio" name="inputType" id="selectin" value="select"> <b>Select</b>  </label>
                                <label>
                                    <input type="radio" name="inputType" id="seriein" value="serie" href="/forms/series/modalcreate/{{code($form->id)}}"> <b>Gráficos</b>
                                </label>
                            </div>
                            <center><span id="inputType-error" class="text-red font-weight-bold"></span></center>
                        </div>
                    </div>
                    <div class="campos_princ" style="display:none">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 nombreinput" style="display:none">
                            <div class="form-group" id="field_name--label">
                                <label>* Nombre del Campo:</label> <br>
                                <input type="text" name="field_name" id="field_name" class="form-control" style="width: 100%;" placeholder="Nombre que se mostrará en el formulario">
                                <span id="field_name-error" class="text-red"></span>
                            </div>
                        </div>
                        {{-- ==================================================================================================================================================
                                                                                                RADIO BUTTON
                            ==================================================================================================================================================  --}}
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 opciones_radio" style="display:none; padding-bottom:20px">
                            <div class="d-flex justify-content-between">
                                <div class="mb-2">
                                    <label id="myOptionsRadio--label">* Opciones radio</label><br>
                                    <span id="myOptionsRadio-error" class="text-red"></span> <br>
                                    <span id="myOptionsRadioMin-error" class="text-red"></span>
                                </div>
                                <div>
                                    <button type="button" class="avatar avatar-upload add_input_button_radio" style="font-weight:bold; ">
                                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                                        <span class="avatar-upload-text" style="color">Agregar</span>
                                    </button>
                                </div>
                            </div>
                            <div style="padding-bottom:10px;" class="moreradio">
                                <input type="text" name="myOptionsRadio[]" class="inputmultipleRadio form-control-append" placeholder="Opciones que podrá escoger" style="width:48%">&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <select class="select2oc" name="myOptionsColor[]" style="width: 92px;">
                                    <option >Rojo</option>
                                    <option >Amarillo</option>
                                    <option >Verde</option>
                                    <option selected>Azul</option>
                                    <option >Naranja</option>
                                    <option >Morado</option>
                                </select>
                                <div class="row" style="margin-top:10px">
                                    <div class="offset-lg-1 col-lg-4 col-md-4 offset-xs-1 col-xs-5 offset-sm-1 col-sm-5 delete_dep0" style="padding: 10px 0px 0px 10px; margin-bottom:20px; display:none;{!! $style !!}">
                                        <div class="row">
                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                                <b>Campo dependiente</b>
                                                <a class="text-yellow" data-toggle="popover" data-trigger="hover"
                                                        data-content=
                                                        "<p class='text-justify' style='font-size: 11px;'>
                                                            Para agregar campos dependientes estos deben estar creados previamente.<br>
                                                            Se listaran los campos que pertenezcan al Sub Contenedor en la parte superior de este formulario.
                                                        </p>">
                                                    <i class="fa fa-info-circle fa-md"></i>
                                                </a>&nbsp;
                                                <a data-toggle="popover" onclick="listFieldsDependCheck()" data-trigger="hover" data-content="<span style='font-size: 11px;'> Limpiar opciones seleccionadas </span>">
                                                    <i class="fa fa-brush" style="color:#52b788"></i>
                                                </a>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center">
                                                <a class="remove_input_dep0" data-toggle="popover" data-trigger="hover" data-content="<span style='font-size: 11px; color:red'> Borrar Campo Dependiente </span>">
                                                    <svg class="icon text-muted iconhover" style="margin-bottom:5px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 salidaselectdep0"  style="padding: 10px 0px 10px 20px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ==================================================================================================================================================
                                                                                                CHECKBOX
                        ==================================================================================================================================================  --}}
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 opciones_check" style="display:none; padding-bottom:20px">

                            <div class="d-flex justify-content-between">
                                <div>
                                    <label id="myOptionsCheck--label">* Opciones checkbox</label> <br>
                                    <span id="myOptionsCheck-error" class="text-red"></span> <br>
                                    <span id="myOptionsCheckMin-error" class="text-red"></span>
                                </div>
                                <div>
                                    <button type="button" class="avatar avatar-upload add_input_button_check" style="font-weight:bold; ">
                                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                                        <span class="avatar-upload-text" style="color">Agregar</span>
                                    </button>
                                </div>
                            </div>
                            <div style="padding-bottom:10px">
                                <input type="text" name="myOptionsCheck[]" class="inputmultipleCheck form-control-append" placeholder="Opciones que podrá escoger" style="width:48%">
                            </div>
                        </div>
                        {{-- ==================================================================================================================================================
                                                                                                SELECT
                        ==================================================================================================================================================  --}}
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 tipo_select" style="display:none">
                            <div class="form-group">
                                <label id="tiposelect--label">* Tipo de Select</label><br>
                                <div class="checkbox  text-center">
                                    <label><input type="radio" class="tipo_select" name="tiposelect" id="selnormal" value="normal" checked> <b>Normal</b>  </label>
                                    <label><input type="radio" class="tipo_select" name="tiposelect" id="selbuscador" value="select2"> <b>Con Buscador</b>  </label>
                                    <label><input type="radio" class="tipo_select" name="tiposelect" id="selmultiple" value="multiple"> <b>Múltiple</b>  </label>
                                </div>
                                <center><span id="tiposelect-error" class="text-red font-weight-bold"></span></center>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 opciones_select" style="display:none; padding-bottom:20px">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <label id="myOptionsSelect--label">* Opciones select</label> <br>
                                    <span id="myOptionsSelect-error" class="text-red"></span> <br>
                                    <span id="myOptionsSelectMin-error" class="text-red"></span> <br>
                                </div>
                                <div>
                                    <button type="button" class="avatar avatar-upload add_input_button_select" style="font-weight:bold; ">
                                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                                        <span class="avatar-upload-text" style="color">Agregar</span>
                                    </button>
                                </div>
                            </div>
                            <div style="padding-bottom:10px">
                                <input type="text" name="myOptionsSelect[]" class="inputmultipleSelect form-control-append" placeholder="Opciones que podrá escoger" style="width:48%">
                            </div>
                        </div>

                        {{-- ==================================================================================================================================================
                                                                                                TEXTO
                        ==================================================================================================================================================  --}}
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 contenedor_texto_tipo" style="display:none; padding-bottom:20px">
                            <label id="texto_tipo--label">* Tipo de Texto </label> <br>
                            <div class="checkbox  text-center">
                                <label><input type="radio" class="tipo_texto" name="texto_tipo" value="text" id="tipotexto"> <b>Caja de texto</b>  </label>
                                <label><input type="radio" class="tipo_texto" name="texto_tipo" value="textarea"> <b>Área de texto</b>  </label>
                                <label><input type="radio" class="tipo_texto" name="texto_tipo" value="date"> <b>Fecha</b>  </label>
                                <label><input type="radio" class="tipo_texto" name="texto_tipo" value="time"> <b>Hora</b>  </label>
                                <label><input type="radio" class="tipo_texto" name="texto_tipo" value="datetime"> <b>Fecha y hora</b>  </label>
                                <label><input type="radio" class="tipo_texto" name="texto_tipo" value="number"> <b>Numérico</b>  </label>
                                <label><input type="radio" class="tipo_texto" name="texto_tipo" value="money"> <b>Moneda</b></label>
                            </div>
                            <center><span id="texto_tipo-error" class="text-red font-weight-bold"></span></center>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 boton_form" id="divboton"  style="padding-bottom:20px">
                            <div class="help-block pull-right" id="msgerror" style="display: none">
                                <strong class="text-red" >El nombre de campo ya está en Uso</strong>
                            </div>
                            <button type="submit" class="btn btn-yellow pull-right" name="btnSubmitMaint">Registrar campo</button>
                            <input type="text" id="opt" name="opt" hidden>
                        </div>
                    </div>
                </div>
            </div>
        {{ Form::close() }}
    @endif
</div>

{{-- VISTA PREVIA --}}
<div class="row">
    <div class=" col-lg-12 col-md-offset-0 col-md-12 col-sm-12 col-xs-12">
        <div class="text-center text-yellow" style="font-size:20px"><b> Vista previa del formulario</b></div> <br>

        @if ( (is_array($maintenance) && count($maintenance)>0) || count($estados)>0 )
            {{-- CABECERA TABS --}}
            <ul class="nav nav-tabs" data-toggle="tabs">
                @php
                    $containers_array = $containers->toArray();
                    $contfirst = array_shift($containers_array);
                    $contid = ($contid!="") ? $contid : $contfirst['id'];
                @endphp
                @foreach ($containers as $i=>$cont)
                    @if ($cont['id']==$contid)
                        <li>
                            <a class="nav-link active" data-name="{{$cont['id']}}" href="#tab_{{$cont['id']}}"  data-toggle="tab">{{$cont['mostrar']}} </a>
                        </li>
                    @else
                        <li>
                            <a class="nav-link " data-name="{{$cont['id']}}" href="#tab_{{$cont['id']}}" data-toggle="tab">{{$cont['mostrar']}} </a>
                        </li>
                    @endif
                @endforeach
            </ul>

            {{-- CONTENIDO TABS --}}
            <div class="card-body">
                <div class="tab-content">
                    @foreach ($containers as $cont)
                        <div class=" tab-pane  @if($cont['id']==$contid){{'active'}}@endif" id="tab_{{$cont['id']}}">
                            @if (isset($cont['subcontainer']) && $cont['subcontainer'] != "")
                                @php $subcontain = collect($cont['subcontainer'])->sortBy('orden'); @endphp
                                @foreach ($subcontain as $item)
                                    <div class="accordion" id="accordion_{{delete_charspecial($item['val'])}}">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button" type="button" data-toggle="collapse" data-target="#{{$item['val']}}---collapse" data-pk="subc_{{$item['val']}}" aria-expanded="true">
                                                    <span class="text-yellow spantitulo" style="font-size:17px">
                                                        <svg class="icon icon-tabler" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="5" width="18" height="14" rx="3" /><line x1="3" y1="10" x2="21" y2="10" /><line x1="7" y1="15" x2="7.01" y2="15" /><line x1="11" y1="15" x2="13" y2="15" /></svg>
                                                            {{$item['mostrar']}}
                                                    </span>
                                                </button>
                                            </h2>
                                            <div id="{{delete_charspecial($item['val'])}}---collapse" class="accordion-collapse collapse" data-bs-parent="#accordion_{{delete_charspecial($item['val'])}}">
                                                <div class="accordion-body pt-0">
                                                    <div class="row">
                                                        {{-- =====================================================================================================================
                                                                                                        ESTADOS FORMULARIO
                                                        ===================================================================================================================== --}}
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" >
                                                            @foreach ($estados as $key => $value)
                                                                @if ($value['contenedor'] == $cont['id'] &&  $item['val'] == $value['subcontenedor'])
                                                                    <center>
                                                                    <label class="text-uppercase" style="font-size:20px">
                                                                        @if ( isset($value['label']) ) {{$value['label']}} @else Condición final  @endif
                                                                        @if ($form->state != 2)
                                                                            <a style="margin-bottom:5px" rel="modalDeleteState" data-modpop="popover" data-trigger="hover" href="/forms/maintenance/deleteStateModal/{{code($form->id)}}"
                                                                                data-content="<span class='text-justify' style='font-size: 11px;color:red;'><b>Eliminar campo</b></span>">
                                                                                <svg class="icon text-red iconhover" style="margin-bottom:4px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" /><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                                                </svg>
                                                                            </a>
                                                                        @endif
                                                                    </label>
                                                                    </center>
                                                                    <div class="checkbox text-center" style="margin-top:10px">
                                                                        @php $opciones = collect($value['opciones'])->sortBy('orden'); @endphp
                                                                        @foreach($opciones as $keyopt=>$opcion)
                                                                            @php
                                                                                $nameradioestado = $key;
                                                                                $colorradioestado = isset($opcion['color']) ? $opcion['color'] : "blue";
                                                                                $datosradioestado = isset($datosguardados['&estado&']['valor']) ? $datosguardados['&estado&']['valor'] : "";
                                                                                $datosradioestado = explode("___",$datosradioestado);
                                                                                $valorradioestado = $opcion['id'].'___'.$opcion['hex'];
                                                                            @endphp
                                                                            <label>
                                                                                <input class="{{$opcion['color']}}" type="radio" name="{!!$nameradioestado!!}" value="{!!$valorradioestado!!}" {{($datosradioestado[0]==$opcion['id']) ? 'checked' : ""}} >
                                                                                <b style="color: {{$opcion['hex']}}">{!! $opcion['mostrar'] !!}</b>
                                                                            </label>
                                                                        @endforeach
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 " style="margin-bottom:10px">
                                                                        <label> Información adicional de estado</label>
                                                                    </div>
                                                                    @foreach($opciones as $keyopt=>$opcion)
                                                                        @php
                                                                            $nameradioestado = $key;
                                                                            $datostextareaestado = isset($datosguardados['&estado&--'.$keyopt.'--textareasta']['valor']) ? $datosguardados['&estado&--'.$keyopt.'--textareasta']['valor'] : "";
                                                                            $datoscheckestado = isset($datosguardados['&estado&--'.$keyopt.'--checksta']['valor']) ? 1 : 0;

                                                                            $campo = preg_replace('/\s+/', ' ',$keyopt);
                                                                            $campo = trim($campo);
                                                                            $campo = strtolower(str_replace(" ","_",$campo));
                                                                            $campo = is_numeric($campo) ? "_".$campo : $campo;
                                                                            $campo = delete_charspecial($campo);
                                                                            $campo = cleanAll($campo);
                                                                        @endphp
                                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                            <div class="checkbox" style="padding-bottom:14px">
                                                                                <label>
                                                                                    <input class="checkboxestados {{$opcion['color']}}__check {{$campo}}__swta" type="checkbox" name="{{$nameradioestado}}--{{$keyopt}}--checksta" @if($datoscheckestado == 1) checked @endif>
                                                                                    <b style="color: {{$opcion['hex']}}">{!! $opcion['mostrar'] !!}</b>
                                                                                </label>
                                                                            </div>
                                                                            <div class="{{$campo}}__swta"  @if($datoscheckestado == 0) style="display:none" @endif>
                                                                                <textarea name="{!!$nameradioestado!!}--{{$keyopt}}--textareasta" style="width:100%; resize: none;border:1px solid {{$opcion['hex']}}" rows="4" placeholder="{{$opcion['mostrar']}}" class="form-control">{!! $datostextareaestado!!}</textarea>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 " style="margin-top:20px">
                                                                        <label> Recomendaciones</label>
                                                                    </div>
                                                                    @php
                                                                        $datosmostrarrecom = isset($datosguardados['recomendaciones']['valor']) ? $datosguardados['recomendaciones']['valor'] : "";
                                                                    @endphp
                                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" style="border-top:20px;margin-bottom:30px">
                                                                        <textarea name="recomendaciones" style="width:100%; resize: none;" rows="4" placeholder="Recomendaciones" class="form-control">{!! $datosmostrarrecom !!}</textarea>
                                                                    </div>
                                                                    <div class="{{$valorradioestado}}"></div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                        {{-- =====================================================================================================================
                                                                                                        TODOS LOS CAMPOS
                                                        ===================================================================================================================== --}}
                                                        @php
                                                            $camposinput = collect($maintenance)->sortBy('orden'); $ccam = 1;
                                                            $generadoseriesalida = $generadoseriesalidamult = $generadoseriesalida_xy = $generadoseriesalida_simp = $stringhighchart = $datos_graficoxy = "";
                                                        @endphp
                                                        @foreach($camposinput as $key=>$campo)
                                                            @if ($campo['container'] == $cont['id'] &&  $item['val'] == $campo['subcontainer'] )
                                                                @php
                                                                    $clasepadre = isset($campo['clase_padre']) ? $campo['clase_padre'] : "";
                                                                    $radiopadreid = isset($campo['radiopadre_id']) ? delete_charspecial($campo['radiopadre_id']) : "";
                                                                    $col_6 = ($campo['type'] != 'radio' && $campo['type'] != 'checkbox' && $campo['type'] != 'textarea' && $campo['type'] != 'serie')  ? "col-lg-6 col-md-6 col-sm-12 col-xs-12": "col-lg-12 col-md-12 col-sm-12 col-xs-12";
                                                                    $namecampo = $campo["id"];
                                                                    $datosmostrar = isset($datosguardados[$campo['id']]['valor']) ? $datosguardados[$campo['id']]['valor'] : "";
                                                                    $styleDate = ($campo['type'] != 'date' && $campo['type'] != 'time')  ? "margin-bottom:15px;" : "margin-bottom:10px;";
                                                                    $styleRadioDep = "";
                                                                    if($radiopadreid != ""){
                                                                        if($campo['type'] != 'serie')
                                                                            $styleRadioDep = 'padding-bottom:15px; border: solid 1px lightgray; border-radius: 3px; '.$styleSerie .' display:none;';
                                                                        else
                                                                            $styleRadioDep = 'padding-bottom:15px; backgroud-color: transparent !important;display:none;';
                                                                    }
                                                                @endphp
                                                                <div class="{{$col_6}} subc_{{delete_charspecial($item['val'])}} {{$radiopadreid}} {{$clasepadre}}" id="{{$radiopadreid}}" style="{{$styleDate}} {!! $styleRadioDep !!} ">
                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-left">
                                                                        @if ($campo['type'] != 'serie')
                                                                            <label>{!! $campo['mostrar'] !!} </label>
                                                                            @if ($form->state != 2)
                                                                                @if(permisoAdminJefe())
                                                                                    {{-- Editar y eliminar campo --}}
                                                                                    <a rel="modalEliminar" href="/forms/maintenance/deletemodal/{{ $campo['id'] }}/{{ code($form->id) }}" class="pull-right mt-1" title="Eliminar campo" style="cursor:pointer">
                                                                                        <svg class="icon text-muted font-weight-bold" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" /><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                                                        </svg>
                                                                                    </a> &ensp;
                                                                                    <a rel="modalEditar" href="/forms/maintenance/editmodal/{{ $campo['id'] }}/{{ code($form->id) }}"  class="pull-right mt-1" title="Editar campo" style="cursor:pointer">
                                                                                        <svg  class="icon iconhover text-yellow" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" /><path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" /><line x1="16" y1="5" x2="19" y2="8" /></svg>
                                                                                    </a>&ensp;
                                                                                    {{-- Desasociar campo --}}
                                                                                    @if ($radiopadreid != "")
                                                                                        <a rel="modalDesasociar" class="pull-right mt-1" data-modpop="popover" data-trigger="hover" href="/forms/modaldetachfield/{{$campo['id']}}/{{code($form->id)}}"
                                                                                            data-content="<span class='text-justify' style='font-size: 11px;color:#368BB9;'><b>Desasociar Campo</b></span>">
                                                                                            <svg class="icon" style="margin-bottom:8px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5" /><path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5" /><line x1="16" y1="21" x2="16" y2="19" /><line x1="19" y1="16" x2="21" y2="16" /><line x1="3" y1="8" x2="5" y2="8" /><line x1="8" y1="3" x2="8" y2="5" />
                                                                                            </svg>
                                                                                        </a>
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                @switch($campo['type'])
                                                                    @case('text')
                                                                        <input type="text" class="checkbox form-control" name="{!! $namecampo !!}" style="width:100%" value="{{$datosmostrar}}">
                                                                    @break
                                                                    @case('textarea')
                                                                        <textarea name="{!!$namecampo!!}" class="form-control" style="width:100%; resize: none" rows="4">{{$datosmostrar}}</textarea>
                                                                    @break
                                                                    @case('date')
                                                                        <div class="input-icon">
                                                                            <span class="input-icon-addon">
                                                                                <i id="iconForm" class="far fa-calendar-alt"></i>
                                                                            </span>
                                                                            <input name="{!! $namecampo !!}" class="checkbox form-control input-incon datepicker" placeholder="dd/mm/YY" style="width:100%" value="{{$datosmostrar}}">
                                                                        </div>
                                                                    @break
                                                                    @case('time')
                                                                        <div class="input-icon">
                                                                            <span class="input-icon-addon">
                                                                                <i id="iconForm" class="far fa-clock"></i>
                                                                            </span>
                                                                            <input name="{!! $namecampo !!}" class="checkbox form-control input-incon timepicker" placeholder="HH:mm" style="width:100%" value="{{$datosmostrar}}">
                                                                        </div>
                                                                    @break
                                                                    @case('datetime')
                                                                        <div class='input-group date datetimepicker'>
                                                                            <span class="input-group-addon">
                                                                                <span class="glyphicon glyphicon-calendar"></span>
                                                                            </span>
                                                                            <input type='text' class="form-control" name="{!! $namecampo !!}" value="{{$datosmostrar}}" placeholder="dd/mm/YYYY HH:mm"/>
                                                                        </div>
                                                                    @break
                                                                    @case('number')
                                                                        <input type="text" class="checkbox form-control numero" name="{!! $namecampo !!}" style="width:100%" value="{{$datosmostrar}}">
                                                                    @break
                                                                    @case('money')
                                                                        <input type="text" class="checkbox form-control moneda" name="{!! $namecampo !!}" style="width:100%" value="{{$datosmostrar}}">
                                                                    @break
                                                                    @case('select')
                                                                        @if(empty($campo['options']))
                                                                            Campo incompleto, no se registrará
                                                                        @else
                                                                            <div class="row">
                                                                            <select class=" checkbox form-control"  name="{!! $namecampo !!}" id="{!! $campo['id'] !!}">
                                                                                <option value="" hidden>Seleccionar</option>
                                                                                @foreach($campo['options'] as $keyopt=>$opcion)
                                                                                    <option value="{!! $opcion['val'] !!}">{!! $opcion['mostraropt'] !!}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            </div>
                                                                        @endif
                                                                    @break
                                                                    @case('select2')
                                                                        @if(empty($campo['options'])) Campo incompleto, no se registrará
                                                                        @else
                                                                            @php
                                                                                if( isset($campo['multiple']) ){
                                                                                    $namecamposelect = $campo["id"].'|'.$campo['container'].'|'.$campo['subcontainer'].'[]';
                                                                                    $datossel = isset($datosguardados[$campo['id']]) ? $datosguardados[$campo['id']]['valor'] :[];
                                                                                }
                                                                                else{
                                                                                    $namecamposelect = $campo["id"].'|'.$campo['container'].'|'.$campo['subcontainer'];
                                                                                    $datossel = isset($datosguardados[$campo['id']]['valor']) ? $datosguardados[$campo['id']]['valor'] : "";
                                                                                }
                                                                                @endphp
                                                                            <div class="row" style="padding-bottom:20px">
                                                                                @if ( isset($campo['multiple']) )
                                                                                    <select class="form-control selector" name="{!! $namecamposelect !!}" id="{!! $campo['id'] !!}" multiple data-placeholder="Seleccione uno o más" style="width:100%">
                                                                                        @foreach($campo['options'] as $keyopt=>$opcion)
                                                                                            @if(in_array($opcion['val'],$datossel))
                                                                                                <option value="{!! $opcion['val'] !!}" selected>{!! $opcion['mostraropt'] !!} </option>
                                                                                            @else
                                                                                                <option value="{!! $opcion['val'] !!}">{!! $opcion['mostraropt'] !!} </option>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </select>
                                                                                @else
                                                                                    <select class="form-control selector" name="{!!$namecamposelect!!}" id="{!!$campo['id']!!}" style="width:100%">
                                                                                        <option value="" hidden>Seleccionar</option>
                                                                                        @foreach($campo['options'] as $keyopt=>$opcion)
                                                                                            <option value="{!! $opcion['val'] !!}" @if ($opcion['val'] == $datossel) selected @endif>{!! $opcion['mostraropt'] !!} </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                @endif
                                                                            </div>
                                                                        @endif
                                                                    @break
                                                                    @case('checkbox')
                                                                        @if(empty($campo['options']))
                                                                            Campo incompleto, no se registrará
                                                                        @else
                                                                            @php $check_order = collect($campo['options'])->sortBy('ordencheck'); @endphp
                                                                        <div class="checkbox text-center" style="padding-bottom:14px">
                                                                            @foreach($check_order as $keyopt=>$opcion)
                                                                                @php
                                                                                    $namecheck = $campo["id"].'|'.$campo['container'].'|'.$campo['subcontainer'].'[]';
                                                                                    $datossel = isset($datosguardados[$campo['id']]['valor']) ? $datosguardados[$campo['id']]['valor'] : [];
                                                                                @endphp
                                                                                <label>
                                                                                @if(in_array($opcion['val'],$datossel))
                                                                                    <input class="checkboxid" type="{!!$campo['type']!!}" name="{!!$namecheck!!}" value="{!!$opcion['val']!!}" id="{!!$key.$keyopt!!}" checked>
                                                                                    <b>{!! $opcion['mostraropt'] !!}</b>
                                                                                @else
                                                                                    <input class="checkboxid" type="{!!$campo['type']!!}" name="{!!$namecheck!!}" value="{!!$opcion['val']!!}" id="{!!$key.$keyopt!!}">
                                                                                    <b>{!! $opcion['mostraropt'] !!}</b>
                                                                                @endif
                                                                                </label>
                                                                            @endforeach
                                                                        </div>
                                                                        @endif
                                                                    @break
                                                                    @case('radio')
                                                                        @if(empty($campo['options']))
                                                                            Campo incompleto, no se registrará
                                                                        @else
                                                                            <div class="checkbox text-center">
                                                                                @php
                                                                                    $campodependiente = $campodependienteUNO = $campodependienteDOS = "";
                                                                                    $datosradio = isset($datosguardados[$campo['id']]) ? $datosguardados[$campo['id']]['valor'] : "";
                                                                                    $datosradio = explode("___",$datosradio);
                                                                                    $optionsr = collect($campo['options'])->sortBy('orden');
                                                                                @endphp
                                                                                @foreach($optionsr as $keyopt=>$opcion)
                                                                                    @php
                                                                                        $nameradio = $key;
                                                                                        $colorradio = isset($opcion['color']) ? $opcion['color'] : "blue";
                                                                                        $hexradio = isset($opcion['hex']) ? $opcion['hex'] : "";
                                                                                        $valueradio = isset($opcion['hex']) ? $opcion['val'].'___'.$opcion['hex'] : $opcion['val'];
                                                                                    @endphp
                                                                                    <label>
                                                                                        <input class="{{$colorradio}} {{$campo["id"]}}" data-radioclass ="{{delete_charspecial($campo["id"])}}" data-radioval = "{{delete_charspecial($opcion['val'])}}"
                                                                                        type="radio" name="{!! $nameradio !!}" style="height: 90px"
                                                                                        value="{{ $valueradio}}" id="{!! $opcion["val"] !!}" {{($datosradio[0]==$opcion["val"]) ? "checked" : ""}}>
                                                                                        <b style="color: {{$hexradio}}">{!! $opcion["mostraropt"] !!}</b>
                                                                                    </label>
                                                                                @endforeach
                                                                            </div>
                                                                        @endif
                                                                    @break
                                                                    @case('serie')
                                                                        @php $tipo_grafico = isset($campo['tipografico']) ? $campo['tipografico'] : "";@endphp
                                                                        @if ( $tipo_grafico == "xvsy_graf" )
                                                                            @php
                                                                                $generadoseriesalida_xy = "";
                                                                                $generadoseriesalida_xy .=
                                                                                '<div class="text-center" style="margin-bottom:10px; margin-top:20px">
                                                                                    <b style="font-size:19px">'.mb_strtoupper($campo["mostrar"]).'</b>';
                                                                                    if ($form->state != 2){
                                                                                        if ($radiopadreid != "")
                                                                                            $generadoseriesalida_xy .=
                                                                                            '<a style="margin-bottom:5px" rel="modalDesasociar" data-modpop="popover" data-trigger="hover" href="/forms/modaldetachfield/'.$campo['id'].'/'.code($form->id).'"
                                                                                                data-content="<span class=\'text-justify text-yellow\' style=\'font-size: 11px;color:#368BB9;\'><b>Desasociar Campo</b></span>">
                                                                                                <svg class="icon" style="margin-bottom:4px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5" /><path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5" /><line x1="16" y1="21" x2="16" y2="19" /><line x1="19" y1="16" x2="21" y2="16" /><line x1="3" y1="8" x2="5" y2="8" /><line x1="8" y1="3" x2="8" y2="5" />
                                                                                                </svg>
                                                                                            </a>&nbsp;';

                                                                                        $generadoseriesalida_xy .=
                                                                                        '<a style="margin-bottom:5px" rel="modalEditar" href="/forms/maintenance/editmodal/'.$campo['id'].'/'.code($form->id).' " title="Editar campo"style="cursor:pointer">
                                                                                            <svg class="icon iconhover text-yellow"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" /><path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" /><line x1="16" y1="5" x2="19" y2="8" /></svg>
                                                                                        </a>
                                                                                        <a style="margin-bottom:5px" rel="modalEliminar" data-modpop="popover" data-trigger="hover" href="/forms/maintenance/deletemodal/'.$campo['id'].'/'.code($form->id).'"
                                                                                            data-content="<span class=\'text-justify\' style=\'font-size: 11px;color:red;\'><b>Eliminar Campo</b></span>">
                                                                                            <svg class="icon text-red iconhover" style="margin-bottom:4px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" /><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                                                            </svg>
                                                                                        </a>';
                                                                                    }
                                                                                    $generadoseriesalida_xy .=
                                                                                '</div>';

                                                                                    // =====================================================================================================================
                                                                                    //                                        tipo de campos asociados a la serie
                                                                                    // =====================================================================================================================
                                                                                        $campos_sort = isset($campo['campos']) ? collect($campo['campos'])->sortBy('orden_serie') : [];
                                                                                        $contaux=0;
                                                                                        foreach ($campos_sort as $keyser =>$serie){
                                                                                            if ($keyser != 'nro_x_serie' && $keyser != 'campos_x_serie') $contaux++;
                                                                                        }
                                                                                        if($contaux>0){
                                                                                        $generadoseriesalida_xy .=
                                                                                    "<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 campos_add_serie' style='border: solid 1px lightgray; border-radius: 3px; ".$styleSerie."';>
                                                                                        <div class='row' style='margin-left:10px;margin-right:10px'>";
                                                                                            if(count($campos_sort)>1)
                                                                                                    $generadoseriesalida_xy .=
                                                                                                "<div class='text-right' style='color:#2489C5'>
                                                                                                    <a rel='modalOrdenSerie' href='/forms/ordenserie/".$cont['id']."/".$item['val']."/".$campo['id']."/".code($form->id)."'>
                                                                                                        <b>Cambiar Orden</b>
                                                                                                    </a>
                                                                                                </div>";
                                                                                            foreach ($campos_sort as $keyser =>$serie){
                                                                                                $nombreserieInputs_xy = "&serie_xy&|".$campo["id"]."|".$serie['id'];
                                                                                                $datosserie_xy = isset($datosguardados [$campo["id"]] [$serie['id']] ['valor']) ? $datosguardados [$campo["id"]] [$serie['id']] ['valor']: "";
                                                                                                $options = isset($serie['options']) ? collect($serie['options'])->sortBy('orden') : [];
                                                                                                $multiple = isset($serie['multiple']) ? $serie['multiple'] : "";
                                                                                                $href = $campo['id'].'/'.$serie['id'].'/'.code($form->id);
                                                                                                $generadoseriesalida_xy .= tipoCampoSerie($nombreserieInputs_xy, $datosserie_xy, $serie["mostrar"], $serie['type'], $options, $href,$contaux, $multiple);
                                                                                            }
                                                                                        $generadoseriesalida_xy .=
                                                                                        "</div>
                                                                                    </div>";
                                                                                        }
                                                                                        $nombreejex = str_replace(" ","_",$campo["nombre_eje_x"]);
                                                                                        $nombreejey = str_replace(" ","_",$campo["nombre_eje_y"]);
                                                                                        $datos_ejex = ['20','40','60','80','100'];
                                                                                        $randomNumber = range(0, 100);
                                                                                        shuffle($randomNumber );
                                                                                        $datos_ejey = array_slice($randomNumber ,0,5);
                                                                                        $seriegen_x = "&grafXY&|".$campo["id"]."|".$nombreejex."[]";
                                                                                        $seriegen_y = "&grafXY&|".$campo["id"]."|".$nombreejey."[]";

                                                                                        $dataname_seriegen_y = delete_charspecial($seriegen_y);
                                                                                        $dataname_seriegen_x = delete_charspecial($seriegen_x);

                                                                                        $generadoseriesalida_xy .=
                                                                                    '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px;margin-top:20px">
                                                                                        <label>'.$campo["nombre_eje_x"].'</label> <i>(Eje X)</i>
                                                                                        <span class="form-help" data-toggle="popover" data-trigger="hover"
                                                                                            data-content=
                                                                                            "<p style=\'font-size: 11px; text-align: justify\' >
                                                                                                Para generar más campos presione la tecla <b>ENTER</b> cuando este posicionado en el área de texto. <br>
                                                                                                Para eliminar campos presione el icono de Borrar (Basurero). <br>
                                                                                                <b> NOTA </b> <br>
                                                                                                Como el Gráfico es bidimensional (X vs Y) los campos se generarán y se eliminarán en ambos ejes.
                                                                                            </p>"
                                                                                            data-original-title="<span style=\'font-size: 12px; font-weight:bold;\' >Información</span>">
                                                                                            ?
                                                                                        </span>
                                                                                    </div>';

                                                                                        $generadoseriesalida_xy .=
                                                                                    '<div class="row '.$dataname_seriegen_x.' "  style="margin-bottom:10px">';
                                                                                        if(count($datos_ejex) == 0){
                                                                                            $generadoseriesalida_xy .=
                                                                                            '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 " style="margin-bottom:20px">
                                                                                                <input type="text" class=" numero generado_x form-control-append '.$dataname_seriegen_x.'_" name="'.$seriegen_x.'" data-namex="'.$seriegen_x.'" data-namey="'.$seriegen_y.'" data-clasex="'.$dataname_seriegen_x.'" data-clasey="'.$dataname_seriegen_y.'" style="width: 60%">
                                                                                            </div>';
                                                                                        }else{
                                                                                            foreach ($datos_ejex as $keje=>$_ejex) {
                                                                                                $generadoseriesalida_xy .='
                                                                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 dinput '.$dataname_seriegen_x.$keje.'" style="margin-bottom:20px">
                                                                                                    <input type="text" class="numero generado_x form-control-append '.$dataname_seriegen_x.'_" name="'.$seriegen_x.'" value="'.$_ejex.'" data-namex="'.$seriegen_x.'" data-namey="'.$seriegen_y.'"  data-clasex="'.$dataname_seriegen_x.'" data-clasey="'.$dataname_seriegen_y.'" style="width: 60%"> &nbsp;';
                                                                                                        if($keje != 0){
                                                                                                            $generadoseriesalida_xy .=
                                                                                                        '<a class="remove_input_prev" title="Borrar Dato">
                                                                                                            <svg class="icon text-muted iconhover" style="margin-bottom:5px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                                                                        </a>';
                                                                                                        }
                                                                                                    $generadoseriesalida_xy .='
                                                                                                </div>';
                                                                                            }
                                                                                        }
                                                                                        $generadoseriesalida_xy .='
                                                                                    </div>';

                                                                                        $generadoseriesalida_xy .=
                                                                                    '<div class="row '.$dataname_seriegen_y.'">
                                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                            <label>'.$campo["nombre_eje_y"].'</label> <i>(Eje Y)</i><br>
                                                                                        </div>';
                                                                                        if(count($datos_ejey) == 0){
                                                                                            $generadoseriesalida_xy .='
                                                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="margin-bottom:20px">
                                                                                                <input type="text" class="numero generado_x form-control-append '.$dataname_seriegen_x.'_" name="'.$seriegen_y.'" data-namex="'.$seriegen_x.'" data-namey="'.$seriegen_y.'" data-clasex="'.$dataname_seriegen_x.'" data-clasey="'.$dataname_seriegen_y.'" style="width: 60%">
                                                                                            </div>';
                                                                                        }else{
                                                                                            foreach ($datos_ejey as $keje=>$_ejey) {
                                                                                                $generadoseriesalida_xy .='
                                                                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 '.$dataname_seriegen_x.$keje.'" style="margin-bottom:20px">
                                                                                                    <input type="text" class="numero generado_x form-control-append '.$dataname_seriegen_x.'_" name="'.$seriegen_y.'" value="'.$_ejey.'" data-namex="'.$seriegen_x.'" data-namey="'.$seriegen_y.'" data-clasex="'.$dataname_seriegen_x.'" data-clasey="'.$dataname_seriegen_y.'" style="width: 60%"> &nbsp;';
                                                                                                        if($keje != 0){
                                                                                                            $generadoseriesalida_xy .=
                                                                                                            '<a class="remove_input_prev'.$keje.'" title="Borrar Dato">
                                                                                                                <svg class="icon text-muted iconhover" style="margin-bottom:5px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                                                                            </a>';
                                                                                                        }
                                                                                                    $generadoseriesalida_xy .=
                                                                                                '</div>';
                                                                                                if(isset($datos_ejex[$keje]))   $datos_graficoxy .= "[".$datos_ejex[$keje]." , ".$_ejey."],";
                                                                                            }
                                                                                        }
                                                                                        $generadoseriesalida_xy .='
                                                                                    </div>';

                                                                                    $nombrecont_chart_xy = $campo['id']."__".$campo['type'];
                                                                                    $tipo_graf_xy = isset($campo['tipo_de_grafico_xy']) ? $campo['tipo_de_grafico_xy'] : "";
                                                                                        if($tipo_graf_xy == 'grafico_barras') $tipo_graf_high_xy = 'column';
                                                                                        elseif($tipo_graf_xy == 'grafico_area') $tipo_graf_high_xy = 'area';
                                                                                        else $tipo_graf_high_xy = 'spline';
                                                                                    $salidascript_xy .= highchartXY($nombrecont_chart_xy, $campo['mostrar'], $campo["nombre_eje_x"], $campo["nombre_eje_y"], $datos_graficoxy, $tipo_graf_high_xy);
                                                                                    $datos_graficoxy = "";

                                                                                        $generadoseriesalida_xy .='
                                                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:20px">
                                                                                        <div id="'.$nombrecont_chart_xy.'"></div>
                                                                                    </div>';
                                                                            @endphp
                                                                            {!! $generadoseriesalida_xy !!}
                                                                        @elseif($tipo_grafico == "serie_graf")
                                                                            @php
                                                                                $generadoseriesalida = "";
                                                                                $nroXserie = isset($datosguardados[$campo['id']]['nro_x_serie']) ? $datosguardados[$campo['id']]['nro_x_serie'] : 2;
                                                                                $campoXserie = isset($datosguardados[$campo['id']]['campos_x_serie']) ? $datosguardados[$campo['id']]['campos_x_serie'] : 12;
                                                                                $campos_sort = isset($campo['campos']) ? collect($campo['campos'])->sortBy('orden_serie') : [];
                                                                                $classSerieDep = $idSerieDep = $styleSerieDep = '';
                                                                                if(isset($campo['radiopadre_id'])){
                                                                                    $classSerieDep = $radiopadreid.' '.$clasepadre;
                                                                                    $idSerieDep = delete_charspecial($radiopadreid);
                                                                                    $styleSerieDep = 'display:none;';
                                                                                }
                                                                                    $generadoseriesalida .=
                                                                                    '<div class="text-center" style="margin-bottom:10px; margin-top:20px">
                                                                                        <b style="font-size:17px">'. mb_strtoupper($campo["mostrar"]).'</b>';
                                                                                        if ($form->state != 2){
                                                                                            // Desasociar campo
                                                                                            if ($radiopadreid != ""){
                                                                                                $generadoseriesalida .=
                                                                                                '<a style="margin-bottom:5px" rel="modalDesasociar" data-modpop="popover" data-trigger="hover" href="/forms/modaldetachfield/'.$campo['id'].'/'.code($form->id).'"
                                                                                                    data-content="<span class=\'text-justify text-yellow\' style=\'font-size: 11px;color:#368BB9;\'><b>Desasociar Campo</b></span>">
                                                                                                    <svg class="icon" style="margin-bottom:4px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5" /><path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5" /><line x1="16" y1="21" x2="16" y2="19" /><line x1="19" y1="16" x2="21" y2="16" /><line x1="3" y1="8" x2="5" y2="8" /><line x1="8" y1="3" x2="8" y2="5" />
                                                                                                    </svg>
                                                                                                </a>&nbsp;';
                                                                                            }
                                                                                            // Eliminar y editar campo
                                                                                            $generadoseriesalida .=
                                                                                            '<a style="margin-bottom:5px" rel="modalEditar" href="/forms/maintenance/editmodal/'.$campo['id'].'/'.code($form->id).' " title="Editar campo"style="cursor:pointer">
                                                                                                <svg class="icon iconhover text-yellow" style="margin-bottom:4px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" /><path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" /><line x1="16" y1="5" x2="19" y2="8" /></svg>
                                                                                            </a>
                                                                                            <a style="margin-bottom:5px"  rel="modalEliminar" data-modpop="popover" data-trigger="hover" href="/forms/maintenance/deletemodal/'.$campo['id'].'/'.code($form->id).' "
                                                                                                data-content="<span class=\'text-justify\' style=\'font-size: 11px;color:red;\'><b>Eliminar Campo</b></span>">
                                                                                                <svg class="icon text-red iconhover" style="margin-bottom:4px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" /><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                                                                </svg>
                                                                                            </a>';
                                                                                        }
                                                                                        $generadoseriesalida .=
                                                                                    '</div>';

                                                                                    $generadoseriesalida .= '<div class="row">';
                                                                                    // Numeros por serie y Campos por serie
                                                                                    foreach ($campos_sort as $keyser =>$serie){
                                                                                        if ($keyser == 'nro_x_serie' || $keyser == 'campos_x_serie'){
                                                                                            $nombreserie = $campo["id"]."|".$serie['id'];
                                                                                            $generadoseriesalida .=
                                                                                            '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                                                                <label>'.$serie["mostrar"].' </label>';
                                                                                                    if ($keyser == "nro_x_serie") $generadoseriesalida .=
                                                                                                '<input type="text" class="numerosd form-control" name="'.$nombreserie.'" value="'.$nroXserie.'" style="width:100%; font-size: 18px; font-weight:bold; padding-right:10px">';
                                                                                                    elseif ($keyser == "campos_x_serie") $generadoseriesalida .=
                                                                                                '<input type="text" class="numerosd form-control" name="'.$nombreserie.'" value="'.$campoXserie.'" style="width:100%; font-size: 18px; font-weight:bold; padding-right:10px">';
                                                                                                    $generadoseriesalida .=
                                                                                            '</div>';
                                                                                        }
                                                                                    }
                                                                                    $generadoseriesalida .= '</div>';

                                                                                    for ($ca = 1; $ca <= $nroXserie; $ca++){
                                                                                        $datosserie = isset( $datosguardados[$campo['id']."|".$ca] ) ? $datosguardados[$campo['id']."|".$ca]  : "";
                                                                                        $generadoseriesalida .=
                                                                                        '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:10px">
                                                                                            <p style="color:#2489C5; font-size:17px; text-align:center" ><b>SERIE '.$ca.'</b></p>';
                                                                                            $contaux=0;
                                                                                            foreach ($campos_sort as $keyser =>$serie){
                                                                                                if ($keyser != 'nro_x_serie' && $keyser != 'campos_x_serie') $contaux++;
                                                                                            }
                                                                                            if($contaux>0){
                                                                                                $generadoseriesalida .=
                                                                                                "<div class='row campos_add_serie' style='border: solid 1px lightgray; border-radius: 3px; margin-top:10px;".$styleSerie."'>";
                                                                                                        if($contaux>1)
                                                                                                        $generadoseriesalida .=
                                                                                                    "<div class='text-right' style='color:#2489C5'>
                                                                                                        <a rel='modalOrdenSerie' href='/forms/ordenserie/".$cont['id']."/".$item['val']."/".$campo['id']."/".code($form->id)."'>
                                                                                                            <b ".$styleOrden.">Cambiar Orden</b>
                                                                                                        </a>
                                                                                                    </div>";
                                                                                                    $generadoseriesalida .=
                                                                                                    '<div class="row" style="padding-left:10px; padding-right:10px">';
                                                                                                    foreach ($campos_sort as $keyser =>$serie){
                                                                                                        if ($keyser != 'nro_x_serie' && $keyser != 'campos_x_serie'){
                                                                                                            $nombreserieInputs = "&serie&|".$campo["id"]."|".$serie['id']."|".$ca;
                                                                                                            $mostrardatoserie = isset($datosserie[$serie['id']]['valor']) ? $datosserie[$serie['id']]['valor']: "";
                                                                                                            $options = isset($serie['options']) ? collect($serie['options'])->sortBy('orden') : [];
                                                                                                            $multiple = isset($serie['multiple']) ? $serie['multiple'] : "";
                                                                                                            $href = $campo['id'].'/'.$serie['id'].'/'.code($form->id);
                                                                                                            $generadoseriesalida .= tipoCampoSerie($nombreserieInputs, $mostrardatoserie, $serie["mostrar"], $serie['type'], $options, $href, $contaux, $multiple);
                                                                                                        }
                                                                                                    }
                                                                                                    $generadoseriesalida .=
                                                                                                    "</div>
                                                                                                </div>";
                                                                                            }
                                                                                                $caaux = $nroXserie; $saux = 1; $countDatosHigh = 0;
                                                                                                $name_min = "&serie&|".$campo["id"]."|&minimo&|".$ca;
                                                                                                $name_max = "&serie&|".$campo["id"]."|&maximo&|".$ca;
                                                                                                $valmin = isset($datosserie["&minimo&"]['valor']) ? $datosserie["&minimo&"]['valor'] : $campo['valmin'];
                                                                                                $valmax = isset($datosserie["&maximo&"]['valor']) ? $datosserie["&maximo&"]['valor'] : $campo['valmax'];

                                                                                                $generadoseriesalida .=
                                                                                            '<div class="row" style="margin-top:10px;margin-bottom:10px">
                                                                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                                                                    <b>Valor Mínimo</b> <br>
                                                                                                    <input type="text" class="numero form-control" name="'.$name_min.'" value="'.$valmin.'" style="width:100%; color: #549FC6; font-size: 18px; font-weight:bold; padding-right:10px">
                                                                                                </div>
                                                                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                                                                    <b>Valor Máximo</b> <br>
                                                                                                    <input type="text" class="numero form-control" name="'.$name_max.'" value="'.$valmax.'" style="width:100%; color: #549FC6; font-size: 18px; font-weight:bold; padding-right:10px">
                                                                                                </div>
                                                                                            </div>';
                                                                                                $generadoseriesalida .=
                                                                                            "<div class='row'>";
                                                                                                for ($s = 1; $s <= $campoXserie; $s++){
                                                                                                    $seriegen = "&serie&|".$campo["id"]."|&seriegener&|".$ca."|".$s;
                                                                                                    $datosseriegen = rand($valmin, $valmax);
                                                                                                    if($datosseriegen != ""){
                                                                                                        $stringhighchart .= "[".$saux.", ".$caaux.", ".$datosseriegen."],"; // Datos Grafico
                                                                                                        $countDatosHigh++;
                                                                                                    }
                                                                                                    if($saux % 6 == 0){ $caaux--; $saux=0; } $saux++; // Cantidad de Datos por FIla Grafico
                                                                                                    $generadoseriesalida .=
                                                                                                    '<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12" style="margin-bottom:10px">
                                                                                                        <b>'.substr($campo["mostrar"],0,3)." ".$s.'</b><br>
                                                                                                        <input type="text" class="numero form-control" value="'.$datosseriegen.'" name="'. $seriegen .'" placeholder="'.substr($campo["mostrar"],0,3)." ".$s.'" style="width:90%;">
                                                                                                    </div>';
                                                                                                }
                                                                                                $generadoseriesalida .=
                                                                                            "</div>
                                                                                        </div>";
                                                                                        $nombrecont_chart = $campo['id']."__".$campo['type']."__".$ca;
                                                                                        $generadoseriesalida .=
                                                                                        '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div id="'.$nombrecont_chart.'"></div></div>';
                                                                                        $arrayaux[$ca] =  $stringhighchart;
                                                                                        if($stringhighchart != ""){
                                                                                            $titulohighchart = "'".$campo['mostrar']." Serie ".$ca."'";
                                                                                            $salidascript .= highchartHeatMap($nombrecont_chart, $titulohighchart, $arrayaux[$ca], intVal($valmax), intVal($valmin), $countDatosHigh);
                                                                                        }
                                                                                        $stringhighchart = "";
                                                                                    }
                                                                            @endphp
                                                                            {!! $generadoseriesalida !!}
                                                                        @elseif($tipo_grafico == "serie_simple")
                                                                            @php
                                                                                $generadoseriesalida_simp = "";
                                                                                $nroXserieSimple = 3;
                                                                                    $generadoseriesalida_simp .=
                                                                                "<div class='text-center' style='margin-bottom:10px; margin-top:20px'>
                                                                                    <b style='font-size:17px'>". mb_strtoupper($campo['mostrar']) ."</b>
                                                                                    <span style='margin-bottom:4px' class='form-help' data-toggle='popover' data-trigger='hover'
                                                                                        data-content=
                                                                                        '<p style=\"font-size: 11px; text-align: justify\" >
                                                                                            Los campos de la serie simple se generarán según el valor numérico de esta caja de texto. <br>
                                                                                            Una vez introducidos los datos presione <b>Guardar Datos</b> para que los campos de la serie se generen.
                                                                                        </p>'
                                                                                        data-original-title='<span style=\"font-size: 12px; font-weight:bold;\">Información</span>'>
                                                                                        ?
                                                                                    </span>";
                                                                                    if ($form->state != 2) {
                                                                                        if ($radiopadreid != ""){
                                                                                            // Desasociar campo
                                                                                            $generadoseriesalida_simp .=
                                                                                            '&nbsp;<a rel="modalDesasociar" href="/forms/modaldetachfield/'.$campo['id'].'/'.code($form->id).'" title="Desasociar campo" style="cursor:pointer">
                                                                                                <svg class="icon" style="margin-bottom:4px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5" /><path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5" /><line x1="16" y1="21" x2="16" y2="19" /><line x1="19" y1="16" x2="21" y2="16" /><line x1="3" y1="8" x2="5" y2="8" /><line x1="8" y1="3" x2="8" y2="5" /></svg>
                                                                                            </a>&nbsp;';
                                                                                        }
                                                                                        // Editar y eliminar campo
                                                                                        $generadoseriesalida_simp .=
                                                                                        '<a rel="modalEditar" href="/forms/maintenance/editmodal/'.$campo['id'].'/'.code($form->id).' " title="Editar campo"style="cursor:pointer">
                                                                                            <svg class="icon iconhover text-yellow" style="margin-bottom:4px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" /><path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" /><line x1="16" y1="5" x2="19" y2="8" /></svg>
                                                                                        </a>
                                                                                        <a rel="modalEliminar" href="/forms/maintenance/deletemodal/'.$campo['id'].'/'.code($form->id).' " title="Eliminar campo" style="cursor:pointer">
                                                                                            <svg class="icon text-red" style="margin-bottom:4px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" /><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                                                            </svg>
                                                                                        </a>';
                                                                                    }
                                                                                    $generadoseriesalida_simp .=
                                                                                "</div>";
                                                                                    $generadoseriesalida_simp .=
                                                                                "<div class='row' style='margin-bottom:20px;'>
                                                                                    <label>Número de series</label>
                                                                                    <input class='numerosd form-control' type='text' value='".$nroXserieSimple."' style='width:100%; font-size: 18px; font-weight:bold; padding-right:10px'>
                                                                                </div>";

                                                                                // {{-- =====================================================================================================================
                                                                                //                                        tipo de campos asociados a la serie
                                                                                // ===================================================================================================================== --}}
                                                                                for ($ca = 1; $ca <= $nroXserieSimple; $ca++){
                                                                                    $campos_sort = collect($campo['campos'])->sortBy('orden_serie');
                                                                                    $caxs = 0;
                                                                                    foreach ($campos_sort as $keyser =>$serie){
                                                                                        if ($keyser != '&nro_x_serie_simple&') $caxs++;
                                                                                    }
                                                                                    $generadoseriesalida_simp .=
                                                                                    '<div class="row campos_add_serie" style=" border: solid 1px lightgray; border-radius: 3px; '.$styleSerie.'";>';
                                                                                    $generadoseriesalida_simp .=
                                                                                        $caxs>1 ?
                                                                                        '<div class="pull-right" style="color:#2489C5;margin-top:10px">
                                                                                            <a rel="modalOrdenSerie" href="/forms/ordenserie/'.$cont['id'].'/'.$item['val'].'/'.$campo['id'].'/'.code($form->id).'">
                                                                                                <b>Cambiar Orden</b>
                                                                                            </a>
                                                                                        </div>' : '';
                                                                                        $generadoseriesalida_simp .=
                                                                                        '<p style="color:#2489C5; font-size:17px; text-align:center; padding-top:10px" ><b>SERIE '.$ca.'</b></p>';
                                                                                        $datosserie_simp = isset( $datosguardados[$campo['id']."|".$ca] ) ? $datosguardados[$campo['id']."|".$ca]  : null;
                                                                                        foreach ($campos_sort as $keyser =>$serie){
                                                                                            if ($keyser != '&nro_x_serie_simple&'){
                                                                                                $nombreserieInputs = "&serie&|".$campo["id"]."|".$serie['id']."|".$ca;
                                                                                                $mostrardatoserie = isset($datosserie_simp[$serie['id']]['valor']) ? $datosserie_simp[$serie['id']]['valor']: "";
                                                                                                $options = isset($serie['options']) ? collect($serie['options'])->sortBy('orden') : [];
                                                                                                $multiple = isset($serie['multiple']) ? $serie['multiple'] : "";
                                                                                                $href = $campo['id'].'/'.$serie['id'].'/'.code($form->id);
                                                                                                $generadoseriesalida_simp .= tipoCampoSerie($nombreserieInputs, $mostrardatoserie, $serie["mostrar"], $serie['type'], $options, $href, $caxs, $multiple);
                                                                                            }
                                                                                        }
                                                                                        $generadoseriesalida_simp .=
                                                                                    '</div>';
                                                                                }
                                                                            @endphp
                                                                            {!! $generadoseriesalida_simp !!}
                                                                        @endif
                                                                    @break
                                                                    @default    Campo incompleto, no se registrará
                                                                @endswitch
                                                                </div>

                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endforeach
                </div> <!-- /.tab-content -->
            </div>
            <br><br>

        @else
            <div class="offset-lg-2 col-lg-8 text-center text-yellow">
                <h1 class="titulomod">
                    <svg class="icon icon-tabler" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><circle cx="12" cy="12" r="9"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    No cuenta con campos registrados
                </h1>
            </div>
        @endif
    </div>
    <input type="text" id="contenedor_id" hidden name="&&contenedor_id&&" value="{{$contid}}" >
</div>

{{-- Modal eliminar --}}
<div class="modal modal-danger fade modal-slide-in-right" aria-hidden="true" role="dialog" id="modalEliminar">
    <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
        </div>
    </div>
</div>

<div class="modal modal-danger fade modal-slide-in-right" aria-hidden="true" role="dialog" id="modalDeleteState" data-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
        </div>
    </div>
</div>

{{-- Modal eliminar campo en serie --}}
<div class="modal modal-danger fade modal-slide-in-right" aria-hidden="true" role="dialog" id="modalEliminarSerie" data-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
        </div>
    </div>
</div>
{{-- Modal desasociar --}}
<div class="modal modal-yellow fade modal-slide-in-right" aria-hidden="true" role="dialog" id="modalDesasociar" data-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
        </div>
    </div>
</div>
{{-- Modal ordenar --}}
<div class="modal modalCyan fade modal-slide-in-right" aria-hidden="true" role="dialog" id="modalOrdenSerie" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Orden de Campos</h5>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
{{-- modal crear campo serie --}}
<div class="modal modalPrimary fade modal-slide-in-right" aria-hidden="true" role="dialog" id="modalCreateSerie" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar gráfico</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

{{-- modal crear campo estado --}}
<div class="modal modalPrimary fade modal-slide-in-right" aria-hidden="true" role="dialog" id="modalCreateEstados" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Estado principal del formulario</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal modalCyan fade modal-slide-in-right" aria-hidden="true" role="dialog" id="modalEditar" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar campo</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal modalCyan fade modal-slide-in-right" aria-hidden="true" role="dialog" id="modalEditarSerie" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar campo serie</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>


<input type="text" hidden class="moneda">
<input type="text" hidden class="numero">


@endsection

@section('scripts')


<script src="{{asset('/plugins/iCheck/icheck.min.js')}}"></script>
<script src="{{asset('/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<script src="{{asset('/plugins/moment/moment.js')}}"></script>
<script src="{{asset('/plugins/transition.js')}}"></script>
<script src="{{asset('/plugins/collapse.js')}}"></script>
<script src="{{asset('/plugins/highchart/highcharts.js')}}"></script>
<script src="{{asset('/plugins/highchart/modules/exporting.js')}}"></script>
<script src="{{asset('/plugins/highchart/modules/heatmap.js')}}"></script>

    <script>
        $('#modalCreateSerie').on('hidden.bs.modal', function () {
            $('#seriein').iCheck('uncheck');
        })
        $('#modalCreateEstados').on('hidden.bs.modal', function () {
            $('#estadoin').iCheck('uncheck');
        })

        var subcid = "{{$subc}}";
        var aux_to = "{{$toastr}}";
        $(document).ready(function(){
            $(".select2-selection").addClass('form-select2').css('border-color','#ccc');
            $(".select2-selection--single").addClass('form-selectcont');
            if(subcid != "")    $('#'+subcid+'---collapse').addClass("show");
            $('#contenedorid').val('');
            $('#select2-contenedorid-container').html("Seleccionar");
            $('.opt_depcont').iCheck('uncheck');
            $('#estadoin').iCheck('uncheck');
            $('#seriein').iCheck('uncheck');
            $('#estadocontenedorid').val('');
            $('#select2-estadocontenedorid-container').html("Seleccionar");
            $('#seriecontenedorid').val('');
            $('#select2-seriecontenedorid-container').html("Seleccionar");
            $('#selectgraficoid').val('');
            $('#select2-selectgraficoid-container').html("Seleccionar");
            $('#nombreserie').val('');
            $('#select2-nombreserie-container').html("Seleccionar");
        });

        // Mostrar/Ocultar campos según select de contenedro
        $('#contenedorid').change(function () {
            $(".selSubContenedor").val(null).trigger('change');

            var option = $(this).children("option:selected").val();
            $('.opt_depcont').show();
            $('.campos_princ').show();
            $('.boton_form').show();
            if(option == ""){
                // $('.opt_depcont').hide();
                $('.campos_princ').hide();
                $('.nombreinput').hide();
                $('.opciones_radio').hide();
                $('.opciones_check').hide();
                $('.tipo_select').hide();
                $('.opciones_select').hide();
                $('.contenedor_texto_tipo').hide();
                $('.boton_form').hide();
                $('input[name="inputType"]').iCheck('uncheck');
            }
        });

        modalAjax("modalEliminar","modalEliminar","modal-content");
        modalAjax("modalDeleteState","modalDeleteState","modal-content");
        modalAjax("modalEliminarSerie","modalEliminarSerie","modal-content");
        modalAjax("modalDesasociar","modalDesasociar","modal-content");
        modalAjax("modalOrdenSerie","modalOrdenSerie","modal-body");
        modalAjax("modalEditar","modalEditar","modal-body");
        modalAjax("modalEditarSerie","modalEditarSerie","modal-body");

        // ==================================================================================
        // AJAX PARA GENERAR SELECT DE CAMPOS QUE PERTENECEN A UN SUBCONTAINER (RadioDep)
        // ==================================================================================
        $('#subcontenedorprinc').change(function () {
            listFieldsDependCheck();
            var option = $(this).children("option:selected").val();
            if(option != ""){
                $(".add_depend").show();
                $(".add_depend0").show();
                $(".remove_input_dep").click();
                $(".remove_input_dep0").click();
            }
        });
        $('.add_depend0').click(function () {
            listFieldsDependCheck();
            $(this).hide();
            $(".delete_dep0").show();
        });

        $('.remove_input_dep0').click(function () {
            $(".delete_dep0").hide();
            $('.add_depend0').show();
        })
        function listFieldsDependCheck(){
            var query = $("#subcontenedorprinc").val();
            var idform = "{{code($form->id)}}";
            var index = 0;
            if (query != '') {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('forms.selectDepAjax') }}",
                    method: "POST",
                    data: { query: query, _token: _token, idform: idform, index:index},
                    success: function (salida) {
                        $('.salidaselectdep0').html(salida.selectxd1);
                    },
                    complete: function () {
                        $('.bluedependiente').iCheck({
                            radioClass: 'icheckbox_flat-yellow',
                        });
                    }
                });
            }
        }
    </script>
    <script>
        // ====================================================================================================================================================================
        //                                                                   RADIO CON CAMPOS DEPENDIENTES
        // ====================================================================================================================================================================
        var max_fields = 100;
        var x = 1;
        var h = 1;
        var gqq = 0;
        var rPadre = 1;
        @if(permisoAdminJefe())
        $(".add_input_button_radio").click(function (e) {
            $(document).ready(function(){
                $(".select2-selection").addClass('form-select2').css('border-color','#ccc');
                $(".select2-selection--single").addClass('form-selectcont');
            });
            var estilo = "{!! $style !!}";
            e.preventDefault();
            if (x < max_fields) {
                gqq++;
                $(".opciones_radio").append(
                    '<div style="padding-bottom:10px;" class="moreradio'+h+'">'+
                        '<input type="text" name="myOptionsRadio[]" class="inputmultipleRadio form-control-append grupo_'+gqq+'" placeholder="Opciones que podrá escoger" style="width:48%"/>&nbsp;&nbsp;'+
                        '<a class="remove_input_radio moreradio'+h+'" id="'+h+'" title="Borrar Opción">'+
                            '<svg class="icon text-muted iconhover" style="margin-bottom:10px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
                        '</a>&nbsp;&nbsp;'+
                        '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+
                        '<select class="select2oc2" name="myOptionsColor[]" style="width:92px">'+
                            '<option >Rojo</option>'+
                            '<option >Amarillo</option>'+
                            '<option >Verde</option>'+
                            '<option selected>Azul</option>'+
                            '<option >Naranja</option>'+
                            '<option >Morado</option>'+
                        '</select>'+
                        '<div class="row" style="margin-top:10px">'+
                            '<div class="offset-lg-1 col-lg-4 col-md-4 offset-xs-1 col-xs-5 offset-sm-1 col-sm-5 delete_dep'+h+'" style="padding: 10px 0px 0px 10px; margin-bottom:20px; display:none;'+estilo+'">'+
                                '<div class="row">'+
                                    '<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">'+
                                        '<b> Campo dependiente </b> '+
                                        '<a data-toggle="popover" data-trigger="hover" data-content="<p class=\'text-justify\' style=\'font-size: 11px;\'>Para agregar los campos estos deben estar creados previamente.<br>Se listaran los campos que pertenezcan al Sub Contenedor seleccionado en la parte superior de este formulario.</p>" '+
                                            '<i class="fa fa-info-circle fa-md text-yellow"></i>'+
                                        '</a>&nbsp;'+
                                        '<a class="add_clear" id="'+h+'" data-toggle="popover" data-trigger="hover" data-content="<span style=\'font-size: 11px;\'> Limpiar opciones seleccionadas </span>"> '+
                                            '<i class="fa fa-brush" style="color:#52b788"></i>'+
                                        '</a>'+
                                    '</div>'+
                                    '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center">'+
                                        '<a class="remove_input_dep" id="'+h+'" data-toggle="popover" data-trigger="hover" data-content="<span style=\'font-size: 11px; color:red\'>Borrar Campo Dependiente </span>"> '+
                                            '<svg class="icon text-muted iconhover" style="margin-bottom:5px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
                                        '</a>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="salidaselectdep'+h+'" style="padding: 10px 0px 10px 20px;">'+'</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>'
                );
                x++;h++;
            }else{  alert("llegó al máximo de opciones permitidas") }

            var option = $("#subcontenedorprinc").children("option:selected").val();
            if(option != "" || option === "undefined" ){
                $(".add_depend").show();
                $(".add_depend0").show();
            }


            $('.select2oc2').select2({ minimumResultsForSearch: -1 });
            $(function () {
                $('[data-toggle="popover"]').popover({
                    html: true,
                    "trigger": "hover",
                    "placement": "right",
                    "container": "body",
                })
            });
        });
        @endif


        $(".opciones_radio").on("click", ".add_depend", function (e) {
            $(this).hide();
            e.preventDefault();
            rPadre = $(this).attr('id');
            $(".delete_dep"+rPadre+"").show();
            $('#subcontenedorprinc').change(function () {
                listFieldsInsideFunction();
            });

            listFieldsInsideFunction();
            function listFieldsInsideFunction(){
                var query = $("#subcontenedorprinc").val();
                var idform = "{{code($form->id)}}";
                var index = gqq;
                if (query != '') {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('forms.selectDepAjax') }}",
                        method: "POST",
                        data: { query: query, _token: _token, idform: idform, index:index},
                        success: function (salida) {
                            $(".salidaselectdep"+rPadre+"").html(salida.selectxd1);
                        },
                        complete: function () {
                            $('.bluedependiente').iCheck({
                                radioClass: 'icheckbox_flat-yellow',
                            });
                        }
                    });
                }
            }
        });

        $(".opciones_radio").on("click", ".add_clear", function (e) {
            e.preventDefault();
            rPadre = $(this).attr('id');
            listFieldsInsideFunction();
            function listFieldsInsideFunction(){
                var query = $("#subcontenedorprinc").val();
                var idform = "{{code($form->id)}}";
                var index = gqq;
                if (query != '') {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('forms.selectDepAjax') }}",
                        method: "POST",
                        data: { query: query, _token: _token, idform: idform, index:index},
                        success: function (salida) {
                            $(".salidaselectdep"+rPadre+"").html(salida.selectxd1);
                        },
                        complete: function () {
                            $('.bluedependiente').iCheck({
                                radioClass: 'icheckbox_flat-yellow',
                            });
                        }
                    });
                }
            }
        });

        $(".opciones_radio").on("click", ".remove_input_dep", function (e) {
            e.preventDefault();
            rPadre = $(this).attr('id');
            $("#"+rPadre+".add_depend").show();
            $(".delete_dep"+rPadre+"").hide();
            $(".salidaselectdep"+rPadre+"").val('').trigger('change');
            $('#subcontenedorprinc').change(function () {
                listFieldsInsideFunction();
            });
            listFieldsInsideFunction();
            function listFieldsInsideFunction(){
                var query = $("#subcontenedorprinc").val();
                var idform = "{{code($form->id)}}";
                if (query != '') {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('forms.selectDepAjax') }}",
                        method: "POST",
                        data: { query: query, _token: _token, idform: idform},
                        success: function (salida) {
                            $(".salidaselectdep"+rPadre+"").html(salida.selectxd1);
                        }
                    });
                }
            }
        });

        $(".opciones_radio").on("click", ".remove_input_radio", function (e) {
            e.preventDefault();
            rPadre = $(this).attr('id');
            var lastClass = $(this).attr('class').split(' ').pop();
            $("."+lastClass+"").remove();
            h--;
            x--;
        });

        $('#radioin').iCheck({
            radioClass: 'iradio_square-yellow',
            increaseArea: '5%'
        }).on('ifChecked', function (event) {
            $('.nombreinput').show(1000);
            $('.opciones_radio').show(1000);
            $('.opciones_check').hide(1000);
            $('.opciones_select').hide(1000);
            $('.tipo_select').hide(1000);
            $('.contenedor_texto_tipo').hide(1000);
            $('#opt').val("radio");
        });
        // ================  Checkbox ================================
        var y = 1;
        $(".add_input_button_check").click(function (e) {
            e.preventDefault();
            if (y < max_fields) {
                y++;
                $(".opciones_check").append(
                    '<div style="padding-bottom:10px">'+
                        '<input type="text" name="myOptionsCheck[]" class="inputmultipleCheck form-control-append" placeholder="Opciones que podrá escoger" style="width:48%"/>&nbsp;&nbsp;'+
                        '<a href="#" class="remove_input" title="Borrar">'+
                            '<svg class="icon text-muted iconhover" style="margin-bottom:5px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
                        '</a>'+
                    '</div>');
            }else{  alert("llegó al máximo de opciones permitidas") }
        });
        $(".opciones_check").on("click", ".remove_input", function (e) {
            e.preventDefault();
            $(this).parent('div').remove();
            y--;
        })
        $('#checkin').iCheck({
            radioClass: 'iradio_square-yellow',
            increaseArea: '5%'
        }).on('ifChecked', function (event) {
            $('.nombreinput').show(1000);
            $('.opciones_check').show(1000);
            $('.opciones_radio').hide(1000);
            $('.opciones_select').hide(1000);
            $('.tipo_select').hide(1000);
            $('.contenedor_texto_tipo').hide(1000);
            $('#opt').val("check");
        });
        // ================  Select ================================
        var z = 1;
        $(".add_input_button_select").click(function (e) {
            e.preventDefault();
            if (z < max_fields) {
                z++;
                $(".opciones_select").append(
                    '<div style="padding-bottom:10px">'+
                        '<input type="text" name="myOptionsSelect[]" class="inputmultipleSelect form-control-append" placeholder="Opciones que podrá escoger" style="width:48%"/>&nbsp;&nbsp;'+
                        '<a href="#" class="remove_input" title="Borrar">'+
                            '<svg class="icon text-muted iconhover" style="margin-bottom:5px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>'+
                        '</a>'+
                    '</div>');
            }else{  alert("llegó al máximo de opciones permitidas") }
        });
        $(".opciones_select").on("click", ".remove_input", function (e) {
            e.preventDefault();
            $(this).parent('div').remove();
            z--;
        })
        $('#selectin').iCheck({
            radioClass: 'iradio_square-yellow',
            increaseArea: '5%'
        }).on('ifChecked', function (event) {
            $('.nombreinput').show(1000);
            $('.opciones_select').show(1000);
            $('.tipo_select').show(1000);
            $('.opciones_check').hide(1000);
            $('.opciones_radio').hide(1000);
            $('.contenedor_texto_tipo').hide(1000);
            $('#opt').val("select");
        });
        // ================  Serie ================================
        $('#seriein').iCheck({
            radioClass: 'iradio_square-yellow',
            increaseArea: '5%'
        }).on('ifChecked', function (event) {
            var hidefields = ['nombreinput','opciones_select','tipo_select','opciones_check','opciones_radio','contenedor_texto_tipo'];
            $.each(hidefields, function( indice, valor ) {
                $('.'+valor).hide(1000);
            });
            // Abrir modal por ajax
            var modal = $('#modalCreateSerie').modal();
            modal.find('.modal-body').load($(this).attr('href'), function (responseText, textStatus) {
                if ( textStatus === 'success' || textStatus === 'notmodified')   modal.show();
            });
        });

        // ================  Estados ================================
        $('#estadoin').iCheck({
            radioClass: 'iradio_square-yellow',
            increaseArea: '5%'
        }).on('ifChecked', function (event) {
            $('#modalState').modal('show');
            var hidefields = ['nombreinput','opciones_select','tipo_select','opciones_check','opciones_radio','contenedor_texto_tipo'];
            $.each(hidefields, function( indice, valor ) {
                $('.'+valor).hide(1000);
            });
            // Abrir modal por ajax
            var modal = $('#modalCreateEstados').modal();
            modal.find('.modal-body').load($(this).attr('href'), function (responseText, textStatus) {
                if ( textStatus === 'success' || textStatus === 'notmodified')   modal.show();
            });
        });

        // ================  Text ================================
        $('#textoin').iCheck({
            radioClass: 'iradio_square-yellow',
            increaseArea: '5%'
        }).on('ifChecked', function (event) {
            $('.nombreinput').show(1000);
            $('.contenedor_texto_tipo').show(1000);
            $('.opciones_select').hide(1000);
            $('.tipo_select').hide(1000);
            $('.opciones_check').hide(1000);
            $('.opciones_radio').hide(1000);
            $('#opt').val("text");
        });
        $('.cerrarmodal').click(function () {
            $('#seriein').iCheck('uncheck');
        });
        $('.selector').select2();
        $('.select2oc').select2({
            minimumResultsForSearch: -1
        });
        $('.datepicker').datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy',
            todayHighlight: true
        });
        $('.timepicker').timepicker({
            showInputs: false,
            minuteStep: 15,
            showMeridian: false,
            defaultTime: null
        });
        $('.datetimepicker').datetimepicker({
            format: 'dd/mm/yyyy hh:ii',
            autoclose: true,
        });

        $('.requiredval').iCheck({
            radioClass: 'iradio_square-yellow',
            increaseArea: '5%'
        });

        $('.tipo_texto').iCheck({
            radioClass: 'iradio_square-yellow',
            increaseArea: '5%'
        });

        $('.tipo_select').iCheck({
            radioClass: 'iradio_square-yellow',
            increaseArea: '5%'
        });

        $('.checkboxid').iCheck({
            checkboxClass: 'icheckbox_flat-green',
        });

        // Radio Button para elementos dependientes
        $('.radiobutton').iCheck({
            radioClass: 'iradio_square-blue',
            increaseArea: '5%'
        }).on('ifChecked', function (event) {
            var valor = $(this).val();
            $('.'+valor+'').show(1000);
        });

        AutoNumeric.multiple('.moneda',{
            modifyValueOnWheel: false,
            minimumValue: 0
        });

        AutoNumeric.multiple('.numero',{
            modifyValueOnWheel: false,
            digitGroupSeparator : '',
            minimumValue: 0
        });

        AutoNumeric.multiple('.numeroserie',{
            modifyValueOnWheel: false,
            digitGroupSeparator : '',
            minimumValue: 0,
            decimalPlaces: 0,
        });

        $(function () {
            $('[data-toggle="popover"]').popover({
                html: true,
                "trigger": "hover",
                "placement": "right",
                "container": "body",
            });

            $('[data-modpop="popover"]').popover({
                html: true,
                "trigger": "hover",
                "placement": "right",
                "container": "body",
            })
        });

    </script>
    {{-- ============================================================================================== --}}
    {{--                                  FUNCIONES VISTA PREVIA                                        --}}
    {{-- ============================================================================================== --}}
    <script>
        // Generar campos para grafico XvsY
        $(document).on("keypress", ".generado_x", function (e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if(e.keyCode == 13) e.preventDefault();
            var clasex = $(this).attr('data-clasex');
            var clasey = $(this).attr('data-clasey');
            var namex = $(this).attr('data-namex');
            var namey = $(this).attr('data-namey');
            var namecampox = "asd";
            // alert(clasex);
            var numItems = $("."+clasex+"_").length
            numItems = numItems/2;
            if(code == 13){
                $("."+clasex+"").append(
                '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 dinput '+clasex+numItems+'" style="margin-bottom:20px">'+
                    '<input type="text" class="numero generado_x form-control-append '+clasex+'_" name="'+namex+'" data-namex="'+namex+'" data-namey="'+namey+'" data-clasex="'+clasex+'" data-clasey="'+clasey+'" style="width: 60%">&nbsp;&nbsp;'+
                    '<a  class="remove_input" title="Borrar Dato"><svg class="icon text-muted iconhover" style="margin-bottom:5px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></a>'+
                '</div>');
                $("."+clasey+"").append(
                '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 dinput '+clasex+numItems+'" style="margin-bottom:20px">'+
                    '<input type="text" class="numero generado_x form-control-append '+clasex+'_" name="'+namey+'" data-namex="'+namex+'" data-namey="'+namey+'" data-clasex="'+clasex+'" data-clasey="'+clasey+'" style="width: 60%">&nbsp;&nbsp;'+
                    '<a  class="remove_input" title="Borrar Dato"><svg class="icon text-muted iconhover" style="margin-bottom:5px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg></a>'+
                '</div>');
            }
        });
        // Eliminar campos XvsY
        $(document).on("click", ".remove_input", function (e) {
            e.preventDefault();
            var lastClass = $(this).parent('div').attr('class').split(' ').pop();
            $("."+lastClass+"").remove();
        })
        // Generar graficos Highcharts
        {!! $salidascript !!}
        {!! $salidascript_xy !!}
    </script>
    {{--  Radio Button para elementos dependientes y Checkbox --}}
    <script>
        var radiojs = ['blue','red','green','yellow','orange','purple'];
        for(var i=0; i<radiojs.length; i++){
            rcls = 'iradio_square-'+radiojs[i];
            chcls = 'icheckbox_flat-'+radiojs[i];
            $('.'+radiojs[i]+'').iCheck({
                radioClass: rcls,
                increaseArea: '5%'
            }).on('ifChanged', function (event) {
                var valor = $(this).attr('data-radioval');
                var clase = $(this).attr('data-radioclass');
                var dependiente = clase+'___'+valor;
                $('.'+clase+'').hide();
                $('[id^="'+clase+'___"]').hide();
                $('.'+dependiente+'').show();
            });

            $('.'+radiojs[i]+'').each( function(i) {
                if($(this).is(':checked')) {
                    var valor = $(this).attr('data-radioval');
                    var clase = $(this).attr('data-radioclass');
                    var dependiente = clase+'___'+valor;
                    $('.'+dependiente+'').show();
                }
            });
            $('.'+radiojs[i]+'__check').iCheck({
                checkboxClass: chcls,
            });
        }

        jQuery('.checkboxestados').each(function(){
            jQuery(this).on('ifChecked', function(){
                var lastClass = $(this).attr('class').split(' ').pop();
                $('.'+lastClass).show();
            });
            jQuery(this).on('ifUnchecked', function(){
                var lastClass = $(this).attr('class').split(' ').pop();
                $('.'+lastClass).hide();
            });
        });
    </script>

    @if ($form->sw_time != 1)
        <script>
            $('.selectortiempo').select2({
                dropdownParent: $('#tiempotrabajoModal')
            });
            $('select.selectortiempo:not(.normal)').each(function () {
                $(this).select2({
                    dropdownParent: $(this).parent().parent()
                });
            });
        </script>
    @endif

    {{-- ===========================================================================================
                                            VALIDACION PRINCIPAL
    =========================================================================================== --}}
    <script>
        var campos = ['contenedorid','subcontenedorprinc','inputType','field_name','myOptionsRadio','myOptionsCheck','tiposelect','myOptionsSelect','texto_tipo'];
        $("#formMaintenanceForms").on('submit', function(e) {
            var vnradio;
            if($("#radioin").is(':checked')) {
                $(" div.opciones_radio input").each(function(i) {
                    if (!$(this).is(':radio')) {
                        vnradio = $(this).val();
                    }else{
                        var aux = $(this).val();
                        $(this).val(aux +"|||"+ vnradio);
                    }
                });
            }
            e.preventDefault();
            var registerForm = $("#formMaintenanceForms");
            var formData = new FormData($("#formMaintenanceForms")[0]);
            $.each(campos, function( indice, valor ) {
                $("#"+valor+"-error").html( "" );
                $("#myOptionsRadioMin-error").html( "" );
                $("#myOptionsCheckMin-error").html( "" );
                $("#myOptionsSelectMin-error").html( "" );
                $("#myOptionsRadio-error").html( "" );
                $("#myOptionsCheck-error").html( "" );
                $("#myOptionsSelect-error").html( "" );
                var inputtype = $("[name="+valor+"]").attr("type");
                if(inputtype != 'radio')    $("[name="+valor+"]").removeClass('is-invalid').addClass('is-valid');
                $("select[name="+valor+"]").removeClass('is-invalid-select').addClass('is-valid-select').removeClass('select2-selection');
                $("#formMaintenanceForms #"+valor+"-sel2 .select2-selection").removeClass('is-invalid-select').addClass('is-valid-select');
                $("#formMaintenanceForms #"+valor+"-sel2 .select2-selection").css('border','1px solid #5eba00');
                $(".programadodiv").css('border','1px solid transparent');
            });
            $('input[name^=myOptionsRadio]').map(function(idx, elem) {
                $(elem).removeClass('is-invalid').addClass('is-valid');
            }).get();
            $('input[name^=myOptionsCheck]').map(function(idx, elem) {
                $(elem).removeClass('is-invalid').addClass('is-valid');
            }).get();
            $('input[name^=myOptionsSelect]').map(function(idx, elem) {
                $(elem).removeClass('is-invalid').addClass('is-valid');
            }).get();

            $.ajax({
                url: "{{route('maintenance.store',code($form->id))}}",
                type: "POST",
                data:formData,
                contentType: false,
                processData: false,
                success:function(data) {
                    if(data.alerta) {
                        toastr.error(data.mensaje);
                        $("[name=btnSubmitMaint]").attr('disabled',false)
                    }
                    if(data.success) {
                        var contid = (data.contid) ? '?contid='+data.contid : "";
                        var subconte = (data.subconte) ? '&subc='+data.subconte : "";
                        $("[name=btnSubmitMaint]").attr('disabled',true)
                        window.location.href = "/forms/maintenance/{{ code($form->id) }}"+contid+subconte;
                    }
                },
                error: function(data){
                    if(data.responseJSON.errors) {
                        var sw_radio = sw_check = sw_select = 0;
                        var sw_radioM = sw_checkM = sw_selectM = 0;
                        var indexaux2 = []; var contaux = 0; var name_campo = "";
                        $.each(data.responseJSON.errors, function( index, value ) {
                            if(index === "myOptionsRadio" ){
                                sw_radioM = 1;
                                name_campo = "myOptionsRadio";
                            }else if (~index.indexOf("myOptionsRadio.")){
                                sw_radio = 1;
                                name_campo = "myOptionsRadio";
                            }else if(index === "myOptionsCheck" ){
                                sw_checkM = 1;
                                name_campo = "myOptionsCheck";
                            }else if (~index.indexOf("myOptionsCheck.")){
                                sw_check = 1;
                                name_campo = "myOptionsCheck";
                            }else if(index === "myOptionsSelect" ){
                                sw_selectM = 1;
                                name_campo = "myOptionsSelect";
                            }else if (~index.indexOf("myOptionsSelect.")){
                                sw_select = 1;
                                name_campo = "myOptionsSelect";
                            }else{
                                $('#'+index+'-error' ).html( '&nbsp;<i class="fa fa-ban"></i> '+value );
                                var inputtype = $("[name="+index+"]").attr("type");
                                if(inputtype != 'radio')    $("[name="+index+"]").removeClass('is-valid').addClass('is-invalid');
                                $("select[name="+index+"]").removeClass('is-valid-select').addClass('is-invalid-select').removeClass('select2-selection');
                                $("#formMaintenanceForms #"+index+"-sel2 .select2-selection").removeClass('is-valid-select').addClass('is-invalid-select');
                                $("#formMaintenanceForms #"+index+"-sel2 .select2-selection").css('border','1px solid #cd201f');
                                name_campo = index;
                            }

                            if(contaux == 0){
                                var scrollpos = $('#'+name_campo+'--label').offset().top - 100;
                                $('html, body').animate({scrollTop: scrollpos }, 600);
                            }
                            contaux++;

                        });
                        if(sw_radio == 1){
                            $('#myOptionsRadio-error' ).html( '&nbsp;<i class="fa fa-ban"></i> Debe llenar todos los campos de opciones de radio.' );
                            $('input[name^=myOptionsRadio]').map(function(idx, elem) {
                                if ( $(elem).val() == "" )  $(elem).removeClass('is-valid').addClass('is-invalid');
                            }).get();
                        }
                        if(sw_radioM == 1){
                            $('#myOptionsRadioMin-error' ).html( '&nbsp;<i class="fa fa-ban"></i> Debe generar al menos 2 opciones.' );
                        }

                        if(sw_check == 1){
                            $('#myOptionsCheck-error' ).html( '&nbsp;<i class="fa fa-ban"></i> Debe llenar todos los campos de opciones de check.' );
                            $('input[name^=myOptionsCheck]').map(function(idx, elem) {
                                if ( $(elem).val() == "" )  $(elem).removeClass('is-valid').addClass('is-invalid');
                            }).get();
                        }
                        if(sw_checkM == 1){
                            $('#myOptionsCheckMin-error' ).html( '&nbsp;<i class="fa fa-ban"></i> Debe generar al menos 2 opciones.' );
                        }

                        if(sw_select == 1){
                            $('#myOptionsSelect-error' ).html( '&nbsp;<i class="fa fa-ban"></i> Debe llenar todos los campos de opciones de select.' );
                            $('input[name^=myOptionsSelect]').map(function(idx, elem) {
                                if ( $(elem).val() == "" )  $(elem).removeClass('is-valid').addClass('is-invalid');
                            }).get();
                        }
                        if(sw_selectM == 1){
                            $('#myOptionsSelectMin-error' ).html( '&nbsp;<i class="fa fa-ban"></i> Debe generar al menos 2 opciones.' );
                        }


                        $("[name=btnSubmitMaint]").attr('disabled',false);
                    }
                    if(typeof(data.status) != "undefined" && data.status != null && data.status == '401'){
                        window.location.reload();
                    }
                }
            });
        });
    </script>

    {{-- SELECT2 AJAX --}}
    {{-- url: "{{ route('users.areasajax') }}", --}}

    <script>
        var _token = $('input[name="_token"]').val();
        $(".selContenedor").select2({
            ajax: {
                url: "{{route('forms.contajax',code($form->id))}}",
                dataType: 'json',
                method: "POST",
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        _token: _token,
                    }
                    return query;
                },
            }
        });

        $(".selSubContenedor").select2({
            ajax: {
                url: "{{route('forms.subcontajax',code($form->id))}}",
                dataType: 'json',
                method: "POST",
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        container: $('#contenedorid').val(),
                        _token: _token,
                    }
                    return query;
                },
            }
        });
    </script>



@endsection