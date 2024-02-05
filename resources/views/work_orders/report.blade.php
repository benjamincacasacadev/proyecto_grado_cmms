@extends ('layouts.admin', ['title_template' => "Orden de trabajo $workorder->cod"])
@section('extracss')
    <style>
            .highcharts-container {
            margin: 0 auto;
        }
        button.accordion.active, button.accordion:hover {
            background-color: #DDDDDD;
            color: #111111;
        }
        .highcharts-tick{
            display: none;
        }

        .icon-btn {
            width: 28px !important;
            height: 28px !important;
            stroke-width: 1.25;
        }

        /* ESTILOS PARA EL FILE INPUT  */
        .file-drop-zone-title {
            padding: 0px !important;
        }
        .file-preview-frame{
            height: 150px;
        }
        .kv-file-content, .file-preview-other{
            height: 50px !important;
        }
        .file-other-icon{
            font-size: 3em !important
        }
        .krajee-default.file-preview-frame {
            left: 33%;
        }
        @media  (max-width: 991px){
            .krajee-default.file-preview-frame {
                left: 20%;
            }
        }
        @media  (max-width: 767px){
            .krajee-default.file-preview-frame {
                left: 0%;
            }
        }
        .file-caption-main, .kv-error-close{
            display: none !important;
        }

        #header_tabla{
            border-top-right-radius: 10px;
            border-top-left-radius: 10px;
            font-size:15px;
        }

        .file-caption-name{
            margin-top:5px !important;
            margin-left:20px !important;
        }
        .file-caption-icon{
            margin-top:5px !important;
        }

        @media (max-width: 767px) {
            .form-inline .form-control {
                display: inline-block;
                width: auto;
                vertical-align: middle;
            }
        }
        @media (max-width: 767px) {
            .form-inline .form-group {
                display: inline-block;
                margin-bottom: 0;
                vertical-align: middle;
            }
        }
        .highchartsheatmap{
            min-width: 500px !important;
        }

        .sizeorden{
            font-size: 14px !important;
        }
        @font-face {
            font-family: 'digital-7';
            src:  url({{asset('templates/tabler/dist/fonts/feather/digital-7.ttf') }});
        }
        .relojNumeros{
            font-family: 'digital-7', sans-serif;
            /* display: none; */
        }
        .icon-tabler {
            width: 25px;
            height: 25px;
            stroke-width: 1.25;
            margin-bottom: 2px;
        }
        .icon-trip {
            width: 30px !important;
            height: 30px !important;
            stroke-width: 1.75 !important;
        }
        .icon-svg {
            width: 30px;
            height: 30px;
            stroke-width: 2.5;
            margin-bottom: 2px;
        }

        .div-description {
            word-break: break-word;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -moz-box-orient: vertical;
            -ms-box-orient: vertical;
            box-orient: vertical;
            -webkit-line-clamp: 6;
            -moz-line-clamp: 6;
            -ms-line-clamp: 6;
            line-clamp: 6;
            overflow: hidden;
            text-align: justify;
        }

        @media (max-width:1300px) {
            .ocultar{
                display: none !important;
            }
        }

        @media  (max-width: 665px){
            .mtr-inp{
                margin-top: 0.6rem !important;
            }
        }

        .disabledTag{
            pointer-events: none;
            cursor: not-allowed !important;
        }
    </style>
    <link rel="stylesheet" href="{{asset('dist/css/bootstrap-editable.min.css')}}">
    <link href="{{asset('/plugins/fileinput/css/fileinput.min.css')}}" media="all" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('/plugins/iCheck/all.css')}}">
    <link rel="stylesheet" href="{{asset('/plugins/timepicker/bootstrap-timepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('/dist/css/colorSelect2.css?1')}}">
@endsection

@section ('contenidoHeader')
    <div class="col">
        <div class="page-pretitle">
            {{ nameEmpresa() }}
        </div>
        <h1 class="titulomod">
            <i class="fas fa-clipboard-list fa-md"></i>&nbsp;
            Informe {{ $workorder->cod }}
        </h1>
    </div>

    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="/work_orders" class="btn btn-outline-secondary border-secondary font-weight-bold" title="Ver listado de órdenes de trabajo">
                <i class="far fa-list-alt fa-lg"></i> &nbsp;
                <span class="d-none d-sm-inline-block">
                    Ver órdenes de trabajo
                </span>
            </a>
        </div>
    </div>
@endsection

@section ('contenido')
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="row">
                @php
                    $fechamin = \Carbon\Carbon::now()->subDays(10);
                    $fechamax = \Carbon\Carbon::now()->addDays(10);
                    $getResponsable = $workorder->responsableId;
                    $asset = $workorder->asset;
                @endphp
                @include('work_orders.show_header')

                @php
                $salidascript = $salidascript_xy = $salidaradio = "";
                $contid = isset($_GET['contid']) ? $_GET['contid'] : "";
                $styleSerie = 'background-color:#f9f9f9;';

            @endphp
            {{Form::Open(array('action'=>array('WorkOrdersController@updateReport',code($workorder->id)),'method'=>'post','autocomplete'=>'off', 'id'=>'formReportUpdate', 'onsubmit'=>'btnSubmitForm.disabled = true; return true;'))}}
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        @if (count($form) > 0)
                            {{-- CABECERA TABS --}}
                            <ul class="nav nav-tabs nav-tabs-top" data-toggle="tabs">
                                @php
                                    $containers_array = $containers->toArray();
                                    $contfirst = array_shift($containers_array);
                                    $contid = ($contid!="") ? $contid : $contfirst['id'];
                                @endphp
                                @foreach ($containers as $i=>$cont)
                                    <li class="nav-item">
                                        <a class="tab_selected nav-link @if ($cont['id']==$contid) active @endif" data-name="{{$cont['id']}}" href="#tab_{{$cont['orden']}}" data-toggle="tab">{{$cont['mostrar']}} </a>
                                    </li>
                                @endforeach
                                @if ($check_formCarta == 1)
                                    <li class="nav-item" >
                                        <a class="tab_selected nav-link @if('__carta__'==$contid){{'active'}}@endif" data-name="__carta__" href="#tab___carta"  data-toggle="tab" >Carta</a>
                                    </li>
                                @endif

                                <li class="nav-item">
                                    <a class="tab_selected nav-link @if('__archivos__'==$contid){{'active'}}@endif" data-name="__archivos__" href="#tab___archivos"  data-toggle="tab" >Adjuntar archivos</a>
                                </li>
                            </ul>

                            {{-- CONTENIDO TABS --}}
                            <div class="card-body">
                                <div class="tab-content">
                                    @foreach ($containers as $cont)
                                        <div class="tab-pane  @if($cont['id']==$contid){{'active'}}@endif show" id="tab_{{$cont['orden']}}">
                                            @if (isset($cont['subcontainer']) && $cont['subcontainer'] != "" )
                                                @php $subcontain = collect($cont['subcontainer'])->sortBy('orden'); @endphp
                                                @foreach ($subcontain as $item)
                                                    <div class="accordion" id="accordion_{{delete_charspecial($item['val'])}}">
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header">
                                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#{{delete_charspecial($item['val'])}}---collapse" data-pk="subc_{{$item['val']}}" aria-expanded="true">
                                                                    <span class="text-yellow" style="font-size:17px">
                                                                        <svg class="icon icon-tabler" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="5" width="18" height="14" rx="3" /><line x1="3" y1="10" x2="21" y2="10" /><line x1="7" y1="15" x2="7.01" y2="15" /><line x1="11" y1="15" x2="13" y2="15" /></svg>
                                                                        {{$item['mostrar']}}
                                                                    </span>
                                                                </button>
                                                            </h2>
                                                            <div id="{{delete_charspecial($item['val'])}}---collapse" class="accordion-collapse collapse show" data-bs-parent="#accordion_{{delete_charspecial($item['val'])}}">
                                                                <div class="accordion-body pt-0">
                                                                    <div class="row">
                                                                        {{-- =====================================================================================================================
                                                                                                                    TODOS LOS CAMPOS
                                                                        ===================================================================================================================== --}}
                                                                        @php
                                                                            $camposinput = collect($form)->sortBy('orden'); $ccam = 1;
                                                                            $generadoseriesalida = $generadoseriesalidamult = $generadoseriesalida_xy = $generadoseriesalida_simp = $stringhighchart = $datos_graficoxy = "";
                                                                        @endphp
                                                                        @foreach($camposinput as $key=>$campo)
                                                                            @if ($campo['container'] == $cont['id'] &&  $item['val'] == $campo['subcontainer'] )
                                                                                @php
                                                                                    $clasepadre = isset($campo['clase_padre']) ? $campo['clase_padre'] : "";
                                                                                    $radiopadreid = isset($campo['radiopadre_id']) ? $campo['radiopadre_id'] : "";
                                                                                    $radiodepclass = delete_charspecial($radiopadreid);
                                                                                    $col_6 = ($campo['type'] != 'radio' && $campo['type'] != 'checkbox' && $campo['type'] != 'textarea' && $campo['type'] != 'serie') ? "col-lg-6 col-md-6 col-sm-12 col-xs-12": "col-lg-12 col-md-12 col-sm-12 col-xs-12";
                                                                                    $namecampo = $campo["id"];
                                                                                    $datosmostrar = isset($datosguardados[$campo['id']]['valor']) ? $datosguardados[$campo['id']]['valor'] : "";
                                                                                @endphp
                                                                                {{-- DIV PRINCIPAL DE TODOS LOS CAMPOS --}}
                                                                                <div class="{{$col_6}} {{$radiodepclass}} {{$clasepadre}}"  style="margin-bottom:20px; @if ( $radiopadreid != "" ) padding-bottom:15px;@if ( $campo['type'] != 'serie' )border: solid 1px lightgray; border-radius: 3px; {!! $styleSerie !!} @endif display:none; @endif ">
                                                                                    @if ($campo['type'] != 'serie')
                                                                                        <label>{!! $campo['mostrar'] !!} </label>
                                                                                    @endif
                                                                                    @if ($campo['type'] == 'radio') &nbsp;&nbsp;
                                                                                        <a  class="clean--button" id="{{$campo['id']}}" data-toggle="popover"  data-trigger="hover" data-content="<span style='font-size: 11px;'> Esta opción es útil en caso de que este campo tenga campos dependientes y estos campos sigan seleccionados pero no están siendo usados.</span>" data-title="<b>Limpiar opción seleccionada</b>">
                                                                                            <i class="fa fa-brush fa-md text-primary" ></i>
                                                                                        </a>
                                                                                    @endif<br>
                                                                                    @switch($campo['type'])
                                                                                        @case('text')
                                                                                            <input type="text" class="checkbox form-control" name="{!! $namecampo !!}" style="width:100%" value="{{ $datosmostrar}}">
                                                                                        @break
                                                                                        @case('textarea')
                                                                                            <textarea name="{!!$namecampo!!}" style="width:100%; resize: none" rows="4" class="form-control">{!!$datosmostrar!!}</textarea>
                                                                                        @break
                                                                                        @case('date')
                                                                                            @php
                                                                                                if (DateTime::createFromFormat('Y-m-d', $datosmostrar) !== FALSE)  $datosmostrar = date("d/m/Y", strtotime($datosmostrar));
                                                                                            @endphp
                                                                                            <div class="input-icon">
                                                                                                <span class="input-icon-addon">
                                                                                                    <i id="iconForm" class="far fa-calendar-alt"></i>
                                                                                                </span>
                                                                                                <input name="{!! $namecampo !!}" class="checkbox form-control input-incon datepicker"  placeholder="dd/mm/YY" style="width:100%" value="{{$datosmostrar}}">
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
                                                                                                <input type='text' class="form-control" name="{!! $namecampo !!}" value="{{$datosmostrar}}"/>
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
                                                                                                @php
                                                                                                    $namecamposelect = $campo["id"].'|'.$campo['container'].'|'.$campo['subcontainer'];
                                                                                                    $datossel = isset($datosguardados[$campo['id']]['valor']) ? $datosguardados[$campo['id']]['valor'] : "";
                                                                                                    $options = isset($campo['options']) ? collect($campo['options'])->sortBy('orden') : [];
                                                                                                @endphp
                                                                                                <select class="form-control form-select"  name="{!! $namecampo !!}" id="{!! $campo['id'] !!}" style="width:100%">
                                                                                                    <option value="">Seleccionar</option>
                                                                                                    @foreach($options as $keyopt=>$opcion)
                                                                                                        <option value="{!! $opcion['val'] !!}" @if ($opcion['val'] == $datossel) selected @endif>{!! $opcion['mostraropt'] !!} </option>
                                                                                                    @endforeach
                                                                                                </select>
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
                                                                                                    $options = isset($campo['options']) ? collect($campo['options'])->sortBy('orden') : [];
                                                                                                @endphp
                                                                                                <div class="row" style="padding-bottom:20px">
                                                                                                    @if ( isset($campo['multiple']) )
                                                                                                        <select class="form-control selector" name="{!! $namecamposelect !!}" id="{!! $campo['id'] !!}" multiple data-placeholder="Seleccione uno o más" style="width:100%">
                                                                                                            @foreach($options as $keyopt=>$opcion)
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
                                                                                                            @foreach($options as $keyopt=>$opcion)
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
                                                                                            <div class="checkbox text-center" style="padding-bottom:14px">
                                                                                                @php $check_order = collect($campo['options'])->sortBy('ordencheck'); @endphp
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
                                                                                                            $valueradio = $opcion['val'];
                                                                                                        @endphp
                                                                                                        <label>
                                                                                                            <input class="{{$colorradio}} {{$campo["id"]}}" data-radioclass = "{{delete_charspecial($campo["id"])}}" data-radioval = "{{delete_charspecial($opcion['val'])}}"
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
                                                                                                    $campos_sort = isset($campo['campos']) ? collect($campo['campos'])->sortBy('orden_serie') : [];
                                                                                                    $contaux=0;
                                                                                                    foreach ($campos_sort as $keyser =>$serie){
                                                                                                        if ($keyser != 'nro_x_serie' && $keyser != 'campos_x_serie') $contaux++;
                                                                                                    }
                                                                                                        $generadoseriesalida_xy .=
                                                                                                        "<div class='text-center' style='margin-bottom:10px; margin-top:20px'>
                                                                                                            <b style='font-size:19px'>". mb_strtoupper($campo['mostrar']) ."</b>
                                                                                                        </div>";
                                                                                                        // {{-- =====================================================================================================================
                                                                                                        //                                        tipo de campos asociados a la serie
                                                                                                        // ===================================================================================================================== --}}

                                                                                                        if($contaux>0){
                                                                                                                $generadoseriesalida_xy .=
                                                                                                            "<div class='row' style='border: solid 1px lightgray; border-radius: 3px; ".$styleSerie."'; >";
                                                                                                                foreach ($campos_sort as $keyser =>$serie){
                                                                                                                    $nombreserieInputs_xy = "&serie_xy&|".$campo["id"]."|".$serie['id'];
                                                                                                                    $datosserie_xy = isset($datosguardados [$campo["id"]] [$serie['id']] ['valor']) ? $datosguardados [$campo["id"]] [$serie['id']] ['valor']: "";
                                                                                                                    $options = isset($serie['options']) ? collect($serie['options'])->sortBy('orden') : [];
                                                                                                                    $multiple = isset($serie['multiple']) ? $serie['multiple'] : "";
                                                                                                                    $generadoseriesalida_xy .= tipoCampoSerie($nombreserieInputs_xy, $datosserie_xy, $serie["mostrar"], $serie['type'], $options, null,null,$multiple);
                                                                                                                }
                                                                                                                $generadoseriesalida_xy .= "
                                                                                                            </div>";
                                                                                                        }
                                                                                                        $nombreejex = str_replace(" ","_",$campo["nombre_eje_x"]);
                                                                                                        $nombreejey = str_replace(" ","_",$campo["nombre_eje_y"]);
                                                                                                        $datos_ejey = isset($datosguardados [$campo["id"]] [$nombreejey]['valor'] ) ? $datosguardados [$campo["id"]] [$nombreejey]['valor'] : [];
                                                                                                        $datos_ejex = isset($datosguardados [$campo["id"]] [$nombreejex]['valor'] ) ? $datosguardados [$campo["id"]] [$nombreejex]['valor'] : [];

                                                                                                        $seriegen_x = "&grafXY&|".$campo["id"]."|".$nombreejex;
                                                                                                        $seriegen_y = "&grafXY&|".$campo["id"]."|".$nombreejey;

                                                                                                        $dataname_seriegen_y = delete_charspecial($seriegen_y);
                                                                                                        $dataname_seriegen_x = delete_charspecial($seriegen_x);

                                                                                                        $generadoseriesalida_xy .=
                                                                                                        '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px;margin-top:20px">
                                                                                                            <label>'.$campo["nombre_eje_x"].'</label> <i>(Eje X)</i>
                                                                                                            <a data-toggle="popover" data-trigger="hover"
                                                                                                                data-content=
                                                                                                                "<p style=\'font-size: 11px; text-align: justify\' >
                                                                                                                    Para generar más campos presione la tecla <b>ENTER</b> cuando este posicionado en el área de texto. <br>
                                                                                                                    Para eliminar campos presione el icono de Borrar (Basurero). <br>
                                                                                                                    <b> NOTA </b> <br>
                                                                                                                    Como el Gráfico es bidimensional (X vs Y) los campos se generarán y se eliminarán en ambos ejes. <br>
                                                                                                                    <b style=\'font-size: 14px;\'>IMPORTANTE:  La cantidad de datos de los ejes no puede ser mayor a 100</b>
                                                                                                                </p>"
                                                                                                                data-original-title="<span style=\'font-size: 12px; font-weight:bold;\' >Información</span>">
                                                                                                                <i class="fa fa-info-circle fa-md"></i>
                                                                                                            </a>
                                                                                                        </div>';
                                                                                                        //====================================================== EJE X
                                                                                                        $generadoseriesalida_xy .=
                                                                                                        '<div class="row '.$dataname_seriegen_x.' "  style="margin-bottom:10px">';
                                                                                                            if(count($datos_ejex) == 0){
                                                                                                                $generadoseriesalida_xy .=
                                                                                                                '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 " style="margin-bottom:20px">
                                                                                                                    <input type="text" class=" numero generado_x  form-control-append '.$dataname_seriegen_x.'_" name="'.$seriegen_x.'[]" data-namex="'.$seriegen_x.'" data-namey="'.$seriegen_y.'" data-clasex="'.$dataname_seriegen_x.'" data-clasey="'.$dataname_seriegen_y.'" style="width: 60%">
                                                                                                                </div>';
                                                                                                            }else{
                                                                                                                foreach ($datos_ejex as $keje=>$_ejex) {
                                                                                                                    $generadoseriesalida_xy .='
                                                                                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 dinput '.$dataname_seriegen_x.$keje.'" style="margin-bottom:20px">
                                                                                                                        <input type="text" class="numero generado_x form-control-append '.$dataname_seriegen_x.'_" name="'.$seriegen_x.'[]" value="'.$_ejex.'" data-namex="'.$seriegen_x.'" data-namey="'.$seriegen_y.'"  data-clasex="'.$dataname_seriegen_x.'" data-clasey="'.$dataname_seriegen_y.'" style="width: 60%"> &nbsp;';
                                                                                                                            if($keje != 0){
                                                                                                                                $generadoseriesalida_xy .=
                                                                                                                            '<a class="remove_input_prev" title="Borrar Dato"><i class="fa fa-trash-alt text-yellow cursor-pointer"></i></a>';
                                                                                                                            }
                                                                                                                        $generadoseriesalida_xy .=
                                                                                                                    '</div>';
                                                                                                                }
                                                                                                            }
                                                                                                            $generadoseriesalida_xy .=
                                                                                                        '</div>';

                                                                                                            $generadoseriesalida_xy .=
                                                                                                        '<div class="row '.$dataname_seriegen_y.'">
                                                                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                                                <label>'.$campo["nombre_eje_y"].'</label> <i>(Eje Y)</i><br>
                                                                                                            </div>';
                                                                                                            if(count($datos_ejey) == 0){
                                                                                                                $generadoseriesalida_xy .='
                                                                                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="margin-bottom:20px">
                                                                                                                    <input type="text" class="numero generado_x form-control-append '.$dataname_seriegen_x.'_" name="'.$seriegen_y.'[]" data-namex="'.$seriegen_x.'" data-namey="'.$seriegen_y.'" data-clasex="'.$dataname_seriegen_x.'" data-clasey="'.$dataname_seriegen_y.'" style="width: 60%">
                                                                                                                </div>';
                                                                                                            }else{
                                                                                                                foreach ($datos_ejey as $keje=>$_ejey) {
                                                                                                                    $generadoseriesalida_xy .='
                                                                                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2  '.$dataname_seriegen_x.$keje.'" style="margin-bottom:20px">
                                                                                                                        <input type="text" class="numero generado_x form-control-append '.$dataname_seriegen_x.'_" name="'.$seriegen_y.'[]" value="'.$_ejey.'" data-namex="'.$seriegen_x.'" data-namey="'.$seriegen_y.'" data-clasex="'.$dataname_seriegen_x.'" data-clasey="'.$dataname_seriegen_y.'" style="width: 60%"> &nbsp;';
                                                                                                                            if($keje != 0){
                                                                                                                                $generadoseriesalida_xy .=
                                                                                                                                '<a class="remove_input_prev'.$keje.'" title="Borrar Dato"><i class="fa fa-trash-alt text-yellow cursor-pointer"></i></a>';
                                                                                                                            }
                                                                                                                        $generadoseriesalida_xy .='
                                                                                                                    </div>';
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
                                                                                                    $etiqejemore = "";

                                                                                                        $generadoseriesalida_xy .=
                                                                                                    '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:20px">
                                                                                                        <div id="'.$nombrecont_chart_xy.'"></div>
                                                                                                    </div>';
                                                                                                @endphp
                                                                                                {!! $generadoseriesalida_xy !!}
                                                                                            @elseif($tipo_grafico == "serie_graf")
                                                                                                @php
                                                                                                    $nroXserie = isset($datosguardados[$campo['id']]['nro_x_serie']) ? $datosguardados[$campo['id']]['nro_x_serie'] : 0;
                                                                                                    $campoXserie = isset($datosguardados[$campo['id']]['campos_x_serie']) ? $datosguardados[$campo['id']]['campos_x_serie'] : 0;
                                                                                                    $campos_sort = isset($campo['campos']) ? collect($campo['campos'])->sortBy('orden_serie') : [];
                                                                                                    $generadoseriesalida = "";
                                                                                                    $generadoseriesalida .=
                                                                                                    "<div class='text-center'>
                                                                                                        <label style='font-size:17px;margin-top:20px'>". mb_strtoupper($campo['mostrar']) ."</label> &nbsp;
                                                                                                        <span class='form-help' data-toggle='popover' data-trigger='hover'
                                                                                                            data-content=
                                                                                                            '<p style=\"font-size: 11px; text-align: justify\" >
                                                                                                                Los campos de la serie se generarán según los valores de los campos \"Numeros Por Serie\" y \"Campos Por Serie\". <br>
                                                                                                                Una vez introducidos esos datos presione <b>Guardar Datos</b> para que los campos de la serie se generen. <br>
                                                                                                                <b style=\"font-size: 14px;\">IMPORTANTE:  Los valores de la series a generar no pueden ser mayor a 50</b>
                                                                                                            </p>'
                                                                                                            data-original-title='<span style=\"font-size: 12px; font-weight:bold;\" >Información</span>'>?
                                                                                                        </span>
                                                                                                        </label>
                                                                                                    </div>";
                                                                                                    // Inputs Numeros por serie y Campos por serie

                                                                                                    $generadoseriesalida .=
                                                                                                    '<div class="row">';
                                                                                                        foreach ($campos_sort as $keyser =>$serie){
                                                                                                            if ($keyser == 'nro_x_serie' || $keyser == 'campos_x_serie'){
                                                                                                                $nombreserie = $campo["id"]."|".$serie['id'];
                                                                                                                $generadoseriesalida .=
                                                                                                                '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                                                                                    <label>'.$serie["mostrar"].' </label>';
                                                                                                                            if ($keyser == "nro_x_serie") $generadoseriesalida .=
                                                                                                                        '<input type="text" class="numSeries80 form-control" name="'.$nombreserie.'" value="'.$nroXserie.'"  style="width:100%;  font-size: 18px; font-weight:bold; padding-right:10px">';
                                                                                                                            elseif ($keyser == "campos_x_serie") $generadoseriesalida .=
                                                                                                                        '<input type="text" class="numSeries80 form-control" name="'.$nombreserie.'" value="'.$campoXserie.'" style="width:100%;  font-size: 18px; font-weight:bold; padding-right:10px">';
                                                                                                                            $generadoseriesalida .=
                                                                                                                '</div>';
                                                                                                            }
                                                                                                        }
                                                                                                        $generadoseriesalida .=
                                                                                                    '</div>';

                                                                                                    for ($ca = 1; $ca <= $nroXserie; $ca++){
                                                                                                            $datosserie = isset( $datosguardados[$campo['id']."|".$ca] ) ? $datosguardados[$campo['id']."|".$ca]  : null;
                                                                                                            $generadoseriesalida .=
                                                                                                        '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:10px">
                                                                                                            <p style="color:#2489C5; font-size:17px; text-align:center" ><b>SERIE '.$ca.'</b></p>';
                                                                                                                // {{-- =====================================================================================================================
                                                                                                                //                                        tipo de campos asociados a la serie
                                                                                                                // ===================================================================================================================== --}}
                                                                                                                $contaux=0;
                                                                                                                foreach ($campos_sort as $keyser =>$serie){
                                                                                                                    if ($keyser != 'nro_x_serie' && $keyser != 'campos_x_serie') $contaux++;
                                                                                                                }
                                                                                                                if($contaux>0){
                                                                                                                $generadoseriesalida .=
                                                                                                            "<div class='row' style='border: solid 1px lightgray; border-radius: 3px; margin-top:10px; padding-bottom:30px; ".$styleSerie."'>";
                                                                                                                foreach ($campos_sort as $keyser =>$serie){
                                                                                                                    if ($keyser != 'nro_x_serie' && $keyser != 'campos_x_serie'){
                                                                                                                        $nombreserieInputs = "&serie&|".$campo["id"]."|".$serie['id']."|".$ca;
                                                                                                                        $mostrardatoserie = isset($datosserie[$serie['id']]['valor']) ? $datosserie[$serie['id']]['valor']: "";
                                                                                                                        $options = isset($serie['options']) ? collect($serie['options'])->sortBy('orden') : [];
                                                                                                                        $multiple = isset($serie['multiple']) ? $serie['multiple'] : "";
                                                                                                                        $generadoseriesalida .= tipoCampoSerie($nombreserieInputs, $mostrardatoserie, $serie["mostrar"], $serie['type'], $options, null,null, $multiple);
                                                                                                                    }
                                                                                                                }
                                                                                                                $generadoseriesalida .=
                                                                                                            "</div>";
                                                                                                                }

                                                                                                                $caaux = $nroXserie; $saux = 1; $countDatosHigh = 0;
                                                                                                                $name_min = "&serie&|".$campo["id"]."|&minimo&|".$ca;
                                                                                                                $name_max = "&serie&|".$campo["id"]."|&maximo&|".$ca;
                                                                                                                $valmin = isset($datosserie["&minimo&"]['valor']) ? $datosserie["&minimo&"]['valor'] : $campo['valmin'];
                                                                                                                $valmax = isset($datosserie["&maximo&"]['valor']) ? $datosserie["&maximo&"]['valor'] : $campo['valmax'];

                                                                                                                $generadoseriesalida .= '
                                                                                                            <div class="row" style="margin-top:10px;margin-bottom:10px">
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
                                                                                                                    $datosseriegen = isset($datosserie['&seriegener&_'.$s]['valor']) ? $datosserie['&seriegener&_'.$s]['valor'] : "";
                                                                                                                    if($datosseriegen != ""){
                                                                                                                        $stringhighchart .= "[".$saux.", ".$caaux.", ".$datosseriegen."],"; // Datos Grafico
                                                                                                                        $countDatosHigh++;
                                                                                                                    }
                                                                                                                    if($saux % 6 == 0){ $caaux--; $saux=0; } $saux++; // Cantidad de Datos por FIla Grafico
                                                                                                                    $generadoseriesalida .='
                                                                                                                    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12" style="margin-bottom:10px">
                                                                                                                        <b>'.mb_substr($campo["mostrar"],0,3)." ".$s.'</b><br>
                                                                                                                        <input type="text" class="numero form-control" value="'.$datosseriegen.'" name="'. $seriegen .'" placeholder="'.mb_substr($campo["mostrar"],0,3)." ".$s.'" style="width:90%;">
                                                                                                                    </div>';
                                                                                                                }
                                                                                                                $generadoseriesalida .="
                                                                                                            </div>
                                                                                                        </div>";
                                                                                                        $nombrecont_chart = $campo['id']."__".$campo['type']."__".$ca;
                                                                                                        $generadoseriesalida .='
                                                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div id="'.$nombrecont_chart.'"></div></div>';
                                                                                                        $arrayaux[$ca] =  $stringhighchart;
                                                                                                        if($stringhighchart != ""){
                                                                                                            $titulohighchart = "'".$campo['mostrar']." Serie ".$ca."'";
                                                                                                            $salidascript .= highchartHeatMap($nombrecont_chart, $titulohighchart, $arrayaux[$ca], $valmax, $valmin, $countDatosHigh);
                                                                                                        }
                                                                                                        $stringhighchart = "";
                                                                                                    }
                                                                                                @endphp
                                                                                                {!! $generadoseriesalida !!}
                                                                                            @elseif($tipo_grafico == "serie_simple")
                                                                                                @php
                                                                                                    $nroXserieSimple = isset($datosguardados[$campo['id']]['valor']) ? $datosguardados[$campo['id']]['valor'] : 0;
                                                                                                    $generadoseriesalida_simp = '';
                                                                                                    $generadoseriesalida_simp .=
                                                                                                    "<div style='margin-bottom:10px'>
                                                                                                        <b>". $campo['mostrar'] ."</b>
                                                                                                        <a data-toggle='popover' data-trigger='hover'
                                                                                                            data-content=
                                                                                                            '<p style=\"font-size: 11px; text-align: justify\" >
                                                                                                                Los campos de la serie simple se generarán según el valor numérico de esta caja de texto. <br>
                                                                                                                Una vez introducidos los datos presione <b>Guardar Datos</b> para que los campos de la serie se generen. <br>
                                                                                                                <b style=\"font-size: 14px;\">IMPORTANTE:  El valor de la serie a generar no puede ser mayor a 100</b>
                                                                                                            </p>'
                                                                                                            data-original-title='<span style=\"font-size: 12px; font-weight:bold;\" >Información</span>'>
                                                                                                            <i class='fa fa-info-circle fa-md'></i>
                                                                                                        </a>
                                                                                                    </div>";
                                                                                                    foreach ($campo['campos'] as $keyser =>$serie){
                                                                                                        if ($keyser == '&nro_x_serie_simple&'){
                                                                                                            $nombreserie = $campo["id"]."|".$serie['id'];
                                                                                                            $generadoseriesalida_simp .= '
                                                                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:20px;">';
                                                                                                                    if ($keyser == "&nro_x_serie_simple&")  $generadoseriesalida_simp .=
                                                                                                                '<input class="numSeries form-control" type="text" name="'.$nombreserie.'" value="'.$nroXserieSimple.'" style="width:100%;  font-size: 18px; font-weight:bold; padding-right:10px">';
                                                                                                                    $generadoseriesalida_simp .=
                                                                                                            '</div>';
                                                                                                        }
                                                                                                    }
                                                                                                    for ($ca = 1; $ca <= $nroXserieSimple; $ca++){
                                                                                                            $generadoseriesalida_simp .=
                                                                                                        '<div class="row" style="margin-bottom:10px; padding-bottom:30px; border: solid 1px lightgray; border-radius: 3px; '.$styleSerie.'";>
                                                                                                            <p style="color:#2489C5; font-size:17px; text-align:center; padding-top:10px" ><b>SERIE '.$ca.'</b></p>';
                                                                                                                $datosserie_simp = isset( $datosguardados[$campo['id']."|".$ca] ) ? $datosguardados[$campo['id']."|".$ca]  : null;
                                                                                                                $campos_sort = collect($campo['campos'])->sortBy('orden_serie');
                                                                                                                foreach ($campos_sort as $keyser =>$serie){
                                                                                                                    if ($keyser != '&nro_x_serie_simple&'){
                                                                                                                        $nombreserieInputs = "&serie&|".$campo["id"]."|".$serie['id']."|".$ca;
                                                                                                                        $mostrardatoserie = isset($datosserie_simp[$serie['id']]['valor']) ? $datosserie_simp[$serie['id']]['valor']: "";
                                                                                                                        $options = isset($serie['options']) ? collect($serie['options'])->sortBy('orden') : [];
                                                                                                                        $multiple = isset($serie['multiple']) ? $serie['multiple'] : "";
                                                                                                                        $generadoseriesalida_simp .= tipoCampoSerie($nombreserieInputs, $mostrardatoserie, $serie["mostrar"], $serie['type'], $options, null, null, $multiple);
                                                                                                                    }
                                                                                                                }
                                                                                                            $generadoseriesalida_simp .=
                                                                                                        '</div>';
                                                                                                    }
                                                                                                @endphp
                                                                                                {!! $generadoseriesalida_simp !!}
                                                                                            @endif
                                                                                        @break
                                                                                        @default
                                                                                            Campo incompleto, no se registrará
                                                                                        @break
                                                                                    @endswitch
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div> <!-- /collapse cuerpo -->
                                                        </div> <!-- /accordion-item -->
                                                    </div><!-- /accordion_principal -->
                                                @endforeach
                                            @endif
                                        </div>
                                    @endforeach

                                    {{-- =====================================================================================================================
                                                                                    CARTA
                                    ===================================================================================================================== --}}
                                    @if ($check_formCarta == 1)
                                        <div class=" tab-pane @if('__carta__'==$contid){{'active'}}@endif" id="tab___carta">
                                            <div class="row" style="padding-bottom:30px">
                                                @php $swCartaaux = isset($datosguardados['&checkcarta&'])? $datosguardados['&checkcarta&'] : 0;  @endphp
                                                <div class="text-center">
                                                    <div class="form-group">
                                                        <label class="cursor-pointer">
                                                            ¿Incluir carta en el informe?&nbsp;&nbsp;
                                                            <input type="checkbox" class="checkcarta" name="&checkcarta&"  @if ($swCartaaux==1 ) {{ 'checked' }} @endif>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row animated zoomIn" id="datoscarta">
                                                <div class="card">
                                                    <div class="card-status-top bg-yellow"></div>
                                                    <div class="card-body">
                                                        <h1 class="card-title text-yellow text-center">
                                                            INFORMACIÓN QUE INCLUIRÁ LA CARTA
                                                        </h1>
                                                        <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <label>Para:</label>  <br>
                                                            <textarea name="&carta&|for" id=""  rows="1" style="width:100%; resize: none" class="form-control" maxlength="150">{{$workorder->letter_for}}</textarea>
                                                        </div>
                                                        <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <label>Copia (CC):</label>  <br>
                                                            <textarea name="&carta&|copy" id=""  rows="1" style="width:100%; resize: none" class="form-control" maxlength="150">{{$workorder->letter_copy}}</textarea>
                                                        </div>
                                                        <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <label>Referencia</label>  <br>
                                                            <textarea name="&carta&|reference" id=""  rows="2" style="width:100%; resize: none" class="form-control">{{$workorder->letter_reference}}</textarea>
                                                        </div>
                                                        <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <label style="width: 100%;">Cuerpo</label> <br>
                                                            <textarea name="&carta&|body" id=""  rows="2" style="width:100%; resize: none" class="form-control">{!!purify($workorder->bodyletter)!!}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    {{-- =====================================================================================================================
                                                                                    ARCHIVOS
                                    ===================================================================================================================== --}}
                                    <div class=" tab-pane @if('__archivos__'==$contid){{'active'}}@endif" id="tab___archivos">
                                        <div class="table-responsive mt-3">
                                            @if ($workorder->estado != 'T')
                                                {{-- Informes en revision solo pueden ser editados por los que tengan permiso de validar informes --}}
                                                <div class="text-left" id="btnAdjuntarArchivo" style="margin-bottom:15px; {{(!$workorder->reportEnabled)? 'display:none' : ''}}">
                                                    <a class="btn btn-outline-yellow btn-pill border border-yellow cursor-pointer" data-target="#modalArchivos" data-toggle="modal">
                                                        <i class="fas fa-file-medical" ></i> &nbsp; Adjuntar archivos para informe
                                                    </a>
                                                </div>
                                            @endif
                                            <div id="_token" class="hidden" data-token="{{ csrf_token() }}"></div>
                                            <table class="table table-vcenter table-center table-sm table-hover" style="width:100%" id="reportArchivos" >
                                                <thead>
                                                    <th class="text-center box-title" colspan="4" id="header_tabla">ARCHIVOS ADJUNTOS PARA REPORTE FOTOGRÁFICO</th>
                                                </thead>
                                                @php
                                                    $hideSw = ($workorder->reportEnabled ) ? '' : 'display: none;';
                                                @endphp
                                                <thead>
                                                    <th width="5%" class="text-center">
                                                        <a id="iconOrdenarArch" style="{{$hideSw}}" data-toggle='popover' data-trigger='hover'
                                                            data-content=
                                                            '<p style="font-size: 11px; text-align: justify" >
                                                                Puede Ordenar los archivos haciendo clic prolongadamente en las celdas de esta fila y moviendo con el mouse a la posición deseada.
                                                            </p>'
                                                            data-original-title='<span style="font-size: 12px; font-weight:bold;" >Información</span>'>
                                                            <i class="fa fa-th-list" style="color: black"></i>
                                                        </a>
                                                    </th>
                                                    <th width="50%;" class="text-center">
                                                        Mis archivos
                                                        <span id="iconEditarArch" style="{{$hideSw}}" class="form-help" data-toggle='popover' data-trigger='hover'
                                                            data-content=
                                                            '<p style="font-size: 11px; text-align: justify" >
                                                                Puede Editar el nombre de Archivo haciendo doble clic en las celdas de esta fila. <br>
                                                                Para guardar el nuevo nombre presione la tecla ENTER.
                                                            </p>'
                                                            data-original-title='<span style="font-size: 12px; font-weight:bold;" >Información</span>'>
                                                            ?
                                                        </span>
                                                    </th>
                                                    <th width="20%" class="text-center"> Tamaño</th>
                                                    <th width="25%" class="text-center" id="optionsAdj"> Opciones</th>
                                                </thead>
                                                <tbody id="trDetalles">
                                                </tbody>
                                            </table>
                                        </div>

                                        {{-- SI EL INFORME ESTA EN PROGRESO, PAUSA, CORRECCION Y EDICION --}}
                                        @if ($workorder->estado == 'E' || $workorder->estado == 'S' || $workorder->estado == 'C')
                                            <div class="text-center mb-3">
                                                @php
                                                    $swCorrec = $workorder->estado == 'C' ? '1' : '0';
                                                @endphp
                                                {{-- ENVIAR INFORME A REVISION --}}
                                                <a href="/work_orders/modalSendRevision/{{code($workorder->id)}}/{{$swCorrec}}" rel="modalSendReport" class="btn btn-outline-yellow btn-lg font-weight-bold border border-2 border-yellow">
                                                    <svg class="icon icon-btn" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5.697"></path>
                                                        <path d="M18 12v-5a2 2 0 0 0 -2 -2h-2"></path>
                                                        <rect x="8" y="3" width="6" height="4" rx="2"></rect>
                                                        <path d="M8 11h4"></path>
                                                        <path d="M8 15h3"></path>
                                                        <circle cx="16.5" cy="17.5" r="2.5"></circle>
                                                        <path d="M18.5 19.5l2.5 2.5"></path>
                                                    </svg>
                                                    <span id="infoTextEnviar">ENVIAR INFORME A REVISIÓN</span>
                                                </a>
                                            </div>

                                        @endif

                                        {{-- SI EL INFORME ESTA EN REVISION --}}
                                        @if ($workorder->estado == 'R')
                                            @if ( permisoAdminJefe() )
                                                <div class="text-center" style="margin-bottom:15px">
                                                    <button type="button" id="botonRechazar" class="btn btn-outline-danger border border-danger" style="font-size: 18px;">
                                                        RECHAZAR INFORME
                                                    </button>

                                                    <button type="button" class="btn btn-outline-success border border-success botonvalidar" style="font-size: 18px;">
                                                        VALIDAR INFORME
                                                    </button>
                                                </div>
                                            @else
                                                <div class="text-center" style="margin-bottom:15px; font-size:20px; color: #368BB9">
                                                    <b> EL INFORME ESTÁ EN REVISIÓN PARA SU VALIDACIÓN </b>
                                                </div>
                                            @endif
                                        @endif

                                        {{--  SI EL INFORME ESTA TERMINADO --}}
                                        @if ($workorder->estado == 'T')
                                            <div class="col-12 text-center" style="margin-bottom:15px; font-size:20px; color: #398c32">
                                                <b> ESTE INFORME YA FUE ENVIADO Y VALIDADO </b>
                                            </div>
                                        @endif

                                        {{-- SI EL INFORME ESTA ANULADO --}}
                                        @if ($workorder->estado == 'X')
                                            <div class="col-12 text-center text-red" style="margin-bottom:15px; font-size:20px;">
                                                <b> ESTE INFORME FUE ANULADO </b>
                                            </div>
                                        @endif
                                    </div>
                                </div> <!-- /.tab-content -->
                            </div>

                            {{-- PIE TABS --}}
                            <ul class="nav nav-tabs nav-tabs-bottom mt-1 mb-4" data-toggle="tabs" >
                                @php
                                    $containers_array = $containers->toArray();
                                    $contfirst = array_shift($containers_array);
                                    $contid = ($contid!="") ? $contid : $contfirst['id'];
                                @endphp
                                @foreach ($containers as $i=>$cont)
                                    <li class="nav-item">
                                        <a class="tab_selected nav-link @if ($cont['id']==$contid) active @endif" data-name="{{$cont['id']}}" href="#tab_{{$cont['orden']}}" data-toggle="tab">{{$cont['mostrar']}} </a>
                                    </li>
                                @endforeach
                                @if ($check_formCarta == 1)
                                    <li class="nav-item" >
                                        <a class="tab_selected nav-link @if('__carta__'==$contid){{'active'}}@endif" data-name="__carta__" href="#tab___carta"  data-toggle="tab" >Carta</a>
                                    </li>
                                @endif

                                <li class="nav-item">
                                    <a class="tab_selected nav-link @if('__archivos__'==$contid){{'active'}}@endif" data-name="__archivos__" href="#tab___archivos"  data-toggle="tab" >Adjuntar Archivos</a>
                                </li>
                            </ul>

                            <div class="row">
                                @if ($workorder->estado != 'X')
                                    {{-- SECCION DE PDF --}}
                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                        @php
                                            $infopopover = "data-toggle='popover' data-trigger='hover'
                                            data-content='<p style=\"font-size: 11px; text-align: justify\">Para mostrar los últimos datos modificados previamente debe guardar los datos presionando <b>\"Guardar Datos\"</b>.</p>'
                                            data-original-title='<span style=\"font-size: 12px; font-weight:bold;\" >Información</span>'";
                                        @endphp
                                        <a href="/work_orders/export/{{code($workorder->id)}}" type="button" target="_blank" class="btn btn-lg btn-outline-danger border border-danger " {!! $infopopover !!} id="btnPdf">
                                            <i class="fas fa-file-pdf fa-md"></i> &ensp;
                                            <span id="textExportPDF">
                                                Ver informe en PDF
                                            </span>
                                        </a>
                                    </div>

                                    {{--  BOTON PARA GUARDAR DATOS --}}
                                    @if ($workorder->estado != 'T' && $workorder->estado != 'R')
                                        @if ($workorder->reportEnabled)
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12 " id="btnGuardar">
                                                {{-- Informes en revision solo pueden ser editados por los que tengan permiso de validar informes --}}
                                                <button type="submit" class="btn btn-yellow pull-right btn-lg" name="btnSubmitForm">
                                                    Guardar datos
                                                </button>
                                            </div>
                                        @else
                                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6" id="btnNoGuardar">
                                                <button type="button" class="btn btn-outline-orange border border-orange pull-right" style="text-align: center" disabled >
                                                    <span> No puede guardar los datos del trabajo <br> debido a que se encuentra en estado
                                                        <b class="text-uppercase"> {{$workorder->getEstado()}}  </b>.<br>
                                                        Por favor {{ $workorder->estado == 'P' ? 'inicie' : 'continue' }} el trabajo.
                                                    </span>
                                                </button>
                                            </div>
                                        @endif
                                    @endif
                                @endif

                                @if (isset($workorder->historial) && $workorder->historial != '')
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 ">
                                        <br>
                                        <p style="margin-bottom:0px; font-weight:bold">
                                            <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12.01" y2="8" /><polyline points="11 12 12 12 12 16 13 16" /></svg>
                                            HISTORIAL DEL INFORME
                                        </p>
                                        <div style="max-height: 150px; overflow-y: auto; text-align:justify;" class="text-muted">
                                            @php
                                                $historialShow = purify($workorder->historial);
                                                if(str_starts_with($workorder->historial,'<br>')){
                                                    $historialShow = purify( substr($workorder->historial, 4) );
                                                }
                                            @endphp
                                            {!! $historialShow !!}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            No tiene campos registrados.
                        @endif
                        <input type="text" id="contenedor_id" hidden name="&&contenedor_id&&" value="{{$contid}}" >
                    </div> <!-- /.col-lg-12 -->
                </div> <!-- /.row -->
            {{Form::Close()}}
            </div>
        </div>
    </div>
</div>



    {{-- ================================================================================================================================================= --}}
    {{--                                                            Modal Tiempo de trabajo                                                                --}}
    {{-- ================================================================================================================================================= --}}
    {{Form::Open(array('action'=>array('WorkOrdersController@initTimeWork',code($workorder->id)),'method'=>'post','autocomplete'=>'off', 'id'=>'formTimeWork', 'onsubmit'=>'btnSubmitTimeWork.disabled = true; return true;'))}}
        <div class="modal modal-success fade modal-slide-in-right" aria-hidden="true" role="dialog"  id="modalTime" data-backdrop="static">
            <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-status bg-green"></div>
                    <div class="modal-body text-center py-4">
                        <svg class="icon mb-2 text-green icon-xl" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><polyline points="12 7 12 12 15 15" /></svg>
                        <h3>¿Está seguro de iniciar el trabajo?</h3>
                        <div class="text-muted">
                            Una vez iniciado se medirá el tiempo total de trabajo.<br>
                            <i style="font-size:11px"> El reloj se detendrá cuando confirme el <b>ENVIAR INFORME A REVISIÓN</b> ó el <b>TERMINAR CON EDICIÓN</b> en la pestaña "Adjuntar Archivos".</i>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="w-100">
                            <div class="row">
                                <div class="col">
                                    <a class="btn @if(themeMode() == 'D') btn-secondary @endif w-100" data-dismiss="modal">
                                        Cancelar
                                    </a>
                                </div>
                                <div class="col">
                                    <button type="submit" class="btn btn-success w-100" name="btnSubmitTimeWork">Confirmar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{Form::Close()}}

    <div class="modal modalYellow fade modal-slide-in-right" aria-hidden="true" role="dialog" id="modalPauseTime" data-backdrop="static">
        <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-yellow"></div>
                <div class="modal-body text-center py-4">
                    <svg class="icon mb-2 text-yellow icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><polyline points="12 7 12 12 15 15" /></svg>
                    <h3>¿Está seguro de poner en pausa el trabajo?</h3>
                    <div class="text-muted">
                        Tiempo de trabajo actual:<br>
                        <div style="font-size:20px">
                            <span class="relojNumeros hours" id="h_modal">{{str_pad($horas, 2, "0", STR_PAD_LEFT)}}</span>
                            <span class="relojNumeros dots">:</span>
                            <span class="relojNumeros minutes" id="m_modal">{{str_pad($mins, 2, "0", STR_PAD_LEFT)}}</span>
                            <span class="relojNumeros dots">:</span>
                            <span class="relojNumeros seconds" id="s_modal">{{str_pad($segs, 2, "0", STR_PAD_LEFT)}}</span>
                        </div>
                    </div>
                    <textarea id="reasonPauseTime" rows="3" class="form-control" style="width:100%; resize:none" placeholder="Escriba el motivo de la pausa" ></textarea>
                    <span class="text-red" id="reasonPauseTime-error"></span>
                </div>

                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col">
                                <a class="btn w-100" data-dismiss="modal">
                                    Cancelar
                                </a>
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-yellow w-100" id="btnConfPauseTime">Confirmar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal de Ver Imagenes --}}
    <div class="modal modalPrimary fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalImagen" data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            </div>
        </div>
    </div>

    {{-- modal Eliminar Archivo --}}
    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalEliminarArchivo" data-backdrop="static">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog"  id="modalSendReport" data-backdrop="static">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
            </div>
        </div>
    </div>

    @include('work_orders.reports.modalUpdateFile')
    @include('work_orders.reports.modalInformeRechazar')
    @include('work_orders.reports.modalInformeValidar')

@endsection

@section ('scripts')
    <script src="{{asset('/dist/js/bootstrap-editable.min.js')}}"></script>
    <script src="{{asset('/plugins/fileinput/js/fileinput.min.js')}}"></script>
    <script src="{{asset('/plugins/iCheck/icheck.min.js')}}"></script>
    <script src="{{asset('/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
    <script src="{{asset('/plugins/moment/moment.js')}}"></script>
    <script src="{{asset('/plugins/transition.js')}}"></script>
    <script src="{{asset('/plugins/collapse.js')}}"></script>
    <script src="{{asset('/plugins/highchart/highcharts.js')}}"></script>
    <script src="{{asset('/plugins/highchart/modules/exporting.js')}}"></script>
    <script src="{{asset('/plugins/highchart/modules/heatmap.js')}}"></script>
    <script src="{{asset('plugins/ckeditor/ckeditor.js?2')}}"></script>

    {{-- ========================================================================== --}}
{{--                Generar campos para graficos con EJE XvsY                   --}}
{{-- ========================================================================== --}}
<script>
    var cantChange = 0;
    var max_fields = 5;
    var x = 1;
    $(document).on("keypress", ".generado_x", function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if(e.keyCode == 13) e.preventDefault();

        var clasex = $(this).attr('data-clasex');
        var clasey = $(this).attr('data-clasey');
        var namex = $(this).attr('data-namex');
        var namey = $(this).attr('data-namey');
        var numItems = $("."+clasex+"_").length
        var countmore = $(this).attr('data-comore');
        var clasemo ="";
        for(var i=0; i<countmore; i++){
            var clasem1 = $(this).attr('data-clasemore'+i+'');
            var namem1 = $(this).attr('data-namemore'+i+'');
            clasemo = clasemo+"data-namemore"+i+"='"+namem1+"' data-clasemore"+i+"='"+clasem1+"' ";
        }

        numItems = numItems/2;
        if(code == 13){
            // if (max_fields > x) {
                $("."+clasex+"").append(
                '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2  dinput '+clasex+numItems+'" style="margin-bottom:20px">'+
                    '<input type="text" class="form-control-append input-number generado_x form-control-append '+clasex+'_" name="'+namex+'[]" data-namex="'+namex+'" data-namey="'+namey+'" data-clasex="'+clasex+'" data-clasey="'+clasey+'" data-comore="'+countmore+'" '+clasemo+' style="width: 60%">&nbsp;&nbsp;'+
                    '<a  class="remove_input" title="Borrar Dato"><i class="fa fa-trash-alt text-yellow cursor-pointer"></i></a>'+
                '</div>');
                $("."+clasey+"").append(
                '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2  dinput '+clasex+numItems+'" style="margin-bottom:20px">'+
                    '<input type="text" class="form-control-append input-number generado_x form-control-append '+clasex+'_" name="'+namey+'[]" data-namex="'+namex+'" data-namey="'+namey+'" data-clasex="'+clasex+'" data-clasey="'+clasey+'" data-comore="'+countmore+'" '+clasemo+' style="width: 60%">&nbsp;&nbsp;'+
                    '<a  class="remove_input" title="Borrar Dato"><i class="fa fa-trash-alt text-yellow cursor-pointer"></i></a>'+
                '</div>');
                for(var i=0; i<countmore; i++){
                    var clasemore = $(this).attr('data-clasemore'+i+'');
                    var namemore = $(this).attr('data-namemore'+i+'');
                    $("."+clasemore+"").append(
                    '<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2  dinput '+clasex+numItems+'" style="margin-bottom:20px">'+
                        '<input type="text" class="form-control-append input-number generado_x form-control-append '+clasex+'_" name="'+namemore+'[]" data-namex="'+namex+'" data-namey="'+namey+'" data-clasex="'+clasex+'"  data-clasey="'+clasey+'" data-comore="'+countmore+'" '+clasemo+' style="width: 60%">&nbsp;&nbsp;'+
                        '<a  class="remove_input" title="Borrar Dato"><i class="fa fa-trash-alt text-yellow cursor-pointer"></i></a>'+
                    '</div>');
                }
                x++;
            // }else{
            //     alert("Llegó al máximo de opciones permitidas (máximo: 10 Opciones)")
            // }
            $('.input-number').on('input', function () {
                this.value = this.value.replace(/[^0-9,.-]/g, '').replace(/,/g, '.');
            });

        }
    });
    // Eliminar campos XvsY
    $(document).on("click", ".remove_input", function (e) {
        e.preventDefault();
        var lastClass = $(this).parent('div').attr('class').split(' ').pop();
        $("."+lastClass+"").remove();
        x--;
    })
    $(document).on("click", ".remove_input_prev", function (e) {
        e.preventDefault();
        var lastClass = $(this).parent("div").attr("class").split(" ").pop();
        $("."+lastClass+"").remove();
        x--;
    });
</script>

{{-- FUNCIONES HIGHCHART - DUPLICAR TABS EN LA PARTE DE ABAJO - BLOQUEAR ENTER CUANDO NO SE PERMITE EL ESTADO --}}
<script>
    modalAjax("modalEdit","modalEdit","modal-content");
    modalAjax("modalInitTripTime","modalInitTripTime","modal-content");


    {!! $salidascript !!}
    {!! $salidascript_xy !!}

    $('.nav-tabs-top a[data-toggle="tab"]').on('click', function(){
        $('.nav-tabs-bottom li a.active').removeClass('active')
        $('.nav-tabs-bottom a[href="'+$(this).attr('href')+'"]').addClass('active');
    })

    $('.nav-tabs-bottom a[data-toggle="tab"]').on('click', function(){
        $('.nav-tabs-top li a.active').removeClass('active')
        $('.nav-tabs-top a[href="'+$(this).attr('href')+'"]').addClass('active');
    })

    @if(!$workorder->reportEnabled)
        $(document).ready(function() {
            $(window).keydown(function(event){
                var inputReopen = $("#motivoReopen").is(":focus");
                if(event.keyCode == 13) {
                    if(!inputReopen){
                        event.preventDefault();
                        return false;
                    }
                }
            });
        });
    @endif
</script>

<script>

    $(document).ready(function () {
        if ($(window).width() <= 471){
            $("#infoTextEnviar").text('ENVIAR A REVISIÓN');
            $("#infoTextTerminar").text('TERMINAR CON EDICIÓN');
            $("#infoTextValidar").text('VALIDACIÓN DIRECTA');
        }else{
            $("#infoTextEnviar").text('ENVIAR INFORME A REVISIÓN');
            $("#infoTextTerminar").text('TERMINAR INFORME CON EDICIÓN');
            $("#infoTextValidar").text('VALIDACIÓN DIRECTA DEL INFORME');
        }
        $(window).resize(function() {
            if ($(window).width() <= 471){
                $("#infoTextEnviar").text('ENVIAR A REVISIÓN');
                $("#infoTextTerminar").text('TERMINAR CON EDICIÓN');
                $("#infoTextValidar").text('VALIDACIÓN DIRECTA');
            }else{
                $("#infoTextEnviar").text('ENVIAR INFORME A REVISIÓN');
                $("#infoTextTerminar").text('TERMINAR INFORME CON EDICIÓN');
                $("#infoTextValidar").text('VALIDACIÓN DIRECTA DEL INFORME');
            }
        });
    });

    @if ( $workorder->reportEnabled )
        $(document).ready(function(){
            $.fn.editable.defaults.disabled = false;
        });
    @else
        $(document).ready(function(){
            $.fn.editable.defaults.disabled = true;
        });
    @endif

    $("#btnPdf").on('click', function() {
        $("#btnPdf").addClass('disabledTag');
        $("#textExportPDF").text("Espere un momento");
        setTimeout(function () {
            $("#btnPdf").removeClass('disabledTag');
            $("#textExportPDF").text("Ver informe en PDF");
        }, 3000);

    });
</script>

<script>
    // Radio Button para elementos dependientes y Checkbox
    $(document).ready(function(){
        $(".select2-selection").addClass('form-select2').css('border-color','#ccc');
        $(".select2-selection--single").addClass('form-selectcont');
        // Mostrar / ocultar radio dependientes
        var radiojs = ['blue','red','green','yellow','orange','purple'];
        for(var i=0; i<radiojs.length; i++){
            rcls = 'iradio_square-'+radiojs[i];
            chcls = 'icheckbox_flat-'+radiojs[i];
            $('.'+radiojs[i]).iCheck({
                radioClass: rcls,
                increaseArea: '5%'
            }).on('ifChecked', function (event) {
                cantChange += 1;
                var valor = $(this).attr('data-radioval');
                var clase = $(this).attr('data-radioclass');
                var dependiente = clase+'___'+valor;
                $('.'+clase).hide();
                $('.'+dependiente).show();
            });

            $('.'+radiojs[i]).each( function(i) {
                if($(this).is(':checked')) {
                    var valor = $(this).attr('data-radioval');
                    var clase = $(this).attr('data-radioclass');
                    var dependiente = clase+'___'+valor;
                    $('.'+clase).hide();
                    $('.'+dependiente).show();
                }
            });
            $('.'+radiojs[i]+'__check').iCheck({
                checkboxClass: chcls,
            });
        }

        // Mostrar / ocultar textarea de estados con checkbox
        jQuery('.checkboxestados').each(function(){
            jQuery(this).on('ifChecked', function(){
                cantChange += 1;
                var lastClass = $(this).attr('class').split(' ').pop();
                $('.'+lastClass).show();
            });
            jQuery(this).on('ifUnchecked', function(){
                cantChange += 1;
                var lastClass = $(this).attr('class').split(' ').pop();
                $('.'+lastClass).hide();
            });
        });

        // Limpiar las opciones de radio escogidas
        $(".clean--button").click(function(){
            var buttonradio = $(this).attr('id');
            var radio = $('.'+buttonradio+'');
            radio.iCheck('uncheck');
            var valor =radio.attr('data-radioval');
            var clase =radio.attr('data-radioclass');
            var dependiente = clase+'___'+valor;
            $('.'+clase+'').hide();
            $('[id^="'+clase+'___"]').hide();
        });
    });

    $( "#btnReiniciar" ).click(function() {
        $('#modalReiniciar').modal('show');
    });
    // Abrir modales para cambio de estado
    $(".botonvalidar").click(function () {
        $('#modalValidar').modal('show');
    });
    $("#botonRechazar").click(function () {
        $('#modalRechazar').modal('show');
    });
    $("#botonsendreport").click(function () {
        $('#modalSendReportClient').modal('show');
    });

    $("#btnAbrirOT").click(function () {
        $('#modalReOpen').modal('show');
    });

    // Funcion para mostrar y ocultar el formulario de carta
    if($(".checkcarta").is(":checked")){
        $("#datoscarta").addClass('zoomIn').removeClass('zoomOut');
    }else{
        $("#datoscarta").hide();
    }

    $('.checkcarta').iCheck({
        checkboxClass: 'icheckbox_square-green',
    }).on('ifChecked', function (event) {
        cantChange += 1;
        $("#datoscarta").addClass('zoomIn').removeClass('zoomOut').show();
    }).on('ifUnchecked', function (event) {
        cantChange += 1;
        $("#datoscarta").addClass('zoomOut').removeClass('zoomIn');
        setTimeout( function(){$('#datoscarta').slideUp();} , 500);
    });

    // Detectar que tab está seleccionado por URL
    $(document).ready(function () {
        var cont_prev = "{{$contid}}";
        if(cont_prev == '__archivos__'){
            $("#btnGuardar").hide();
        }else{
            $("#btnGuardar").show();
        }

        $(".tab_selected").on("click", function (e) {
            var clase = $(this).attr('data-name');
            $("#contenedor_id").val(clase);
            if(clase == '__archivos__'){
                $("#btnGuardar").hide();
                $("#btnNoGuardar").hide();
            }else{
                $("#btnGuardar").show();
                $("#btnNoGuardar").show();
            }
        });
    });

    // ========================================================================
    //                      FUNCIONES PARA ADJUNTAR ARCHIVOS
    // ========================================================================
    $("#archivoSTReporte").fileinput({
        showUpload: true,
        showCancel: false,
        showRemove: false,
        dropZoneEnabled: true,
        browseLabel: "Buscar Archivo",
        initialCaption: "&nbsp;&nbsp;&nbsp;Seleccione un Archivo....",

        // Validación del tipo de archivo (Incluye Drag and Drop)
        showUpload: false,
        allowedFileExtensions: ["pdf","doc","docx","xls","xlsx","zip","rar"],
        // Validación del tamaño de archivo máximo a subir (Incluye Drag and Drop)
        maxFileSize: 5120,
        // Máximo tamaño a previsualizar
        maxFilePreviewSize: 5120,
        // Color de fondo de la zona Drag and Drop
        previewClass: "bg-fileinput",
        preferIconicPreview: true,
        previewFileIconSettings: {
            'docx': '<i class="fas fa-file-word text-primary"></i>',
            'xlsx': '<i class="fas fa-file-excel text-success"></i>',
            'pptx': '<i class="fas fa-file-powerpoint text-danger"></i>',
            'pdf': '<i class="fas fa-file-pdf text-danger"></i>',
            'zip': '<i class="fas fa-file-archive text-muted"></i>',
        },
        "fileActionSettings":{ "showZoom":true }
    });

    $("#imagenesSTReporte").fileinput({
        showUpload: true,
        showCancel: false,
        showRemove: false,
        dropZoneEnabled: true,
        browseLabel: "Buscar Imagen",
        initialCaption: "&nbsp;&nbsp;&nbsp;Seleccione una imagen....",

        // Validación del tipo de archivo (Incluye Drag and Drop)
        showUpload: false,
        allowedFileExtensions: ["gif","jpg","jpeg","png"],
        // Validación del tamaño de archivo máximo a subir (Incluye Drag and Drop)
        maxFileSize: 15120,
        // Máximo tamaño a previsualizar
        maxFilePreviewSize: 5120,
        // Color de fondo de la zona Drag and Drop
        previewClass: "bg-fileinput",
        preferIconicPreview: true,
        "fileActionSettings":{ "showZoom":true }
    });

    // ICHECKS MODAL ARCHIVO
    $('#radioImg').iCheck({
        radioClass: 'iradio_square-yellow',
        increaseArea: '5%'
    }).on('ifChecked', function (event) {
        $('.imagendiv').slideDown();
        $('.archivodiv').slideUp();
    })
    $('#radioFile').iCheck({
        radioClass: 'iradio_square-yellow',
        increaseArea: '5%'
    }).on('ifChecked', function (event) {
        $('.archivodiv').slideDown();
        $('.imagendiv').slideUp();
    })

    var imagenfields = ["imagen", "archivo", "titulo"];
    ValidateAjax("formSubmitFile",imagenfields,"btnSubmitFile","{{ route('reports.saveFile' )}}","POST","/workorders/report/{{ code($workorder->id) }}?contid=__archivos__");

    // ========================================================================
    //                      TABLA DE ARCHIVOS POR AJAX
    // ========================================================================
    $.fn.editable.defaults.mode = 'inline';
    $.fn.editable.defaults.toggle = 'dblclick';
    $.fn.editable.defaults.emptytext = '-';
    $.fn.editable.defaults.ajaxOptions = {type: "POST"};
    $(function () {
        var report='{{$workorder->id}}';
        var contador = 0;

        var AnchoTr = function(e, width) {
            width.children().each(function() {
                $(this).width($(this).width());
            });
            return width;
        };

        var table = $('#reportArchivos').DataTable({
            'sDom'        : 'lrtip',
            'paging'      : false,
            'lengthChange': false,
            'searching'   : false,
            'ordering'    : false,
            'info'        : false,
            'autoWidth'   : false,
            "order": [[ 1, "desc" ]],
            "pageLength": 25,
            "columnDefs": [ {
                "orderable": false,
                "targets": [0] ,
            } ],
            "processing": true,
            "serverSide": true,

            "ajax":{
                "url": "{{ route('reports.tableFiles') }}",
                'dataType': 'json',
                'type': 'POST',
                'data': {
                    "_token": "{{ csrf_token() }}",
                    report : report,
                },
                // "error": function(reason) {
                //     errorsDatatable(reason.status);
                // },
            },
            "columns": [
                    { "data": "orden", "className": "icon text-center font-weight-bold sizeorden cursor-move" },
                    { "data": "nombre" },
                    { "data": "tamanio" },
                    { "data": "opciones", "className": "text-center" }
                ],
            "drawCallback": function () {
                $('tbody#trDetalles').sortable({
                    helper: AnchoTr,
                    placeholder:'dndPlaceHolder',
                    distance:20,
                    handle: '.icon',
                    start: function (event, ui) {
                        item = ui.item;
                    },
                    stop: function () {
                        var iditem = item.attr("id");
                        var negprev = $('#'+iditem).prev("tr");
                        if( negprev[0] != undefined){
                            var idprevneg = negprev[0].id
                        }else{
                            var idprevneg = 0
                        }
                        var _token = $('input[name="_token"]').val();
                        $.ajax({
                            url: "{{ route('reports.orderFile') }}",
                            method: 'POST',
                            data: {
                                id_item: iditem,
                                prev_item: idprevneg,
                                report : report,
                                _token: _token
                            },
                        });

                        var pageURL = $(location).attr("href").split('?')[0];
                        var url = pageURL+"?contid=__archivos__";
                        tableReload();
                    },
                });

                $('.textedit').editable({
                    url: '/st_report_update',
                    params: function (params) {
                        params._token = $("#_token").data("token");
                        return params;
                    },
                    validate: function(value) {
                        if(value.trim()=='') return "Nombre de Archivo es Requerido";
                    },
                    success: function(response, newValue){
                    },
                    error: function (response, newValue) {
                        if (response.status === 500) {
                            return 'Error del Servidor. Verifique los datos ingresados.';
                        } else {
                            var errores=$.parseJSON(response.responseText)
                            return errores.errors.value[0];
                        }
                    },
                    showbuttons: false,
                });

                $('.textedit').on('save',function(e,params){
                    table.ajax.reload();
                });

                // modal Imagen
                modalAjax("modalImagen","modalImagen","modal-content");
                // modal Eliminar
                modalAjax("modalEliminarArchivo","modalEliminarArchivo","modal-content");
                //popover
                $(function () {
                    $('[data-toggle="popover"]').popover({
                        html: true,
                        "trigger": "hover",
                        "placement": "right",
                        "container": "body",
                    })
                });


                @if ( $workorder->reportEnabled )
                    $(document).ready(function(){
                        $("tbody#trDetalles").sortable("enable");
                    });
                @else
                    $(document).ready(function(){
                        $("tbody#trDetalles").sortable('cancel');
                        $("tbody#trDetalles").sortable("disable");
                    });
                @endif
            }
        });

        // Funcion para recargar el datatables de adjuntos
        function tableReload(){
            setTimeout(
                function()
                {
                    table.ajax.reload()
                }, 1000);
        }
    });

    // =====================================================================
    //            TODAS LAS FUNCIONES PARA EL FORMULARIO DE Informes
    // =====================================================================
    $('.selector').select2();
    $('.checkboxid').iCheck({
        checkboxClass: 'icheckbox_flat-green',
    }).on('ifChecked', function (event) {
        cantChange += 1;
    }).on('ifUnchecked', function (event) {
        cantChange += 1;
    });

    @if ($check_formCarta == 1)
        // DATEICKER Y TIMEPICKER
        $('.datepickerCarta').datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy',
            todayHighlight: true,
            startDate: '{{$fechamin}}',
            endDate: '{{$fechamax}}',
        });
    @endif

    // DATEICKER Y TIMEPICKER
    $('.datepicker').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true
    });
    $('.timepicker').timepicker({
        showInputs: false,
        minuteStep: 10,
        showMeridian: false,
        defaultTime: null
    });
    $('.datetimepicker').datetimepicker({
        format: 'dd/mm/yyyy hh:ii',
        autoclose: true,
    });


    AutoNumeric.multiple('.moneda',{
        modifyValueOnWheel: false,
        minimumValue: 0
    });

    AutoNumeric.multiple('.numero',{
        modifyValueOnWheel: false,
        digitGroupSeparator : '',
        // minimumValue: 0
    });

    AutoNumeric.multiple('.numerosd',{
        modifyValueOnWheel: false,
        digitGroupSeparator : '',
        minimumValue: 0,
        // decimalPlaces: 0,
    });

    AutoNumeric.multiple('.numSeries',{
        modifyValueOnWheel: false,
        digitGroupSeparator : '',
        minimumValue: 0,
        maximumValue: 100,
        decimalPlaces: 0,
    });

    AutoNumeric.multiple('.numSeries50',{
        modifyValueOnWheel: false,
        digitGroupSeparator : '',
        minimumValue: 0,
        maximumValue: 50,
        decimalPlaces: 0,
    });

    AutoNumeric.multiple('.numSeries80',{
        modifyValueOnWheel: false,
        digitGroupSeparator : '',
        minimumValue: 0,
        maximumValue: 80,
        decimalPlaces: 0,
    });

    $('.mail').iCheck({
        checkboxClass: 'icheckbox_flat-yellow',
    });

    modalAjax("modalOutcomeCreate","modalOutcomeCreate","modal-content");
</script>

<script>
    modalAjax("modalParamPDF","modalParamPDF","modal-body");
    // MODAL ENVIAR INFORME
    modalAjax("modalSendReport","modalSendReport","modal-content");
    modalAjax("modalDetener","modalDetener","modal-content");
    modalAjax("modalTerminarEdicion","modalTerminarEdicion","modal-content");

    // INSTANCIANDO EL CKEDITOR
    @if($check_formCarta == 1)
        var editorLetter = CKEDITOR.replace('&carta&|body', {
            uiColor: '#f4f6fa',
            height: 400,
            removePlugins: ['scayt','about','image','anchor','links','specialchar','stylescombo','horizontalrule','table','tabletools','tableselection'],
            removeButtons: 'Anchor,Image,Links,Subscript,Superscript',
            disableNativeSpellChecker: false,
            extraPlugins: ['justify']
        });
    @endif

    // ********************************************************************************************
    // COPIANDO EL CUERPO AL CKEDITOR
    $('#selectlletters').change(function () {
        var bodyletter = $(this).find(':selected');
        editorLetter.setData(bodyletter.attr('data-description'));
    });
    // ********************************************************************************************
    // SELECT2 PARA EL ENVIO DEL INFORME
    $('select.selector-modal-send:not(.normal)').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent().parent(),
            tags:true,
            width:'100%',
            placeholder:"Seleccione o ingrese nuevos correos electrónicos."
        });
    });
</script>

<script>
    // modal Imagen
    modalAjax("modalImagen","modalImagen","modal-content");
</script>


@endsection