<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Informe {{$workorder->cod}}</title>
    <link rel="stylesheet" href="{{public_path('/dist/css/bootstrap.min.css')}}">
    <script type="text/javascript" src="{{public_path('dist/js/jquery.min.js')}}"></script>
    <style>

        table.detalle td.conb,th{
            border: solid 0.1px gray;
            padding: 5px;
        }
        #header {
            position: fixed;
            left: 0px;
            top: -80;
            right: px;
            height: 150px;
        }

        footer {
            position: fixed;
            bottom: -105px;
            left: 0px;
            right: 0px;
            height: 90px;
            text-align: right;
            font-size: 7;
            font-family: arial;
        }

        footer .page:after {
            content: counter(page);
        }

        table.detalle td.conb, th {
            border: solid 0.4px black;
            padding: 5px;
        }
        span.marker{
            background-color: yellow;
            padding-left: 8px;
            padding-right: 8px;
        }
        hr.hhrr {
            page-break-after: always;
            border: none;
            margin: 0;
            padding: 0;
        }

        .series_sep{
            font-size:16px;
            line-height:30px;
            margin-left:-15px;
            margin-bottom:20px;
        }
        @page {
            margin-top: 120px;
            margin-bottom: 80px;
        }
        .text-uppercase{
            text-transform: uppercase;
        }
        .tr.noBorder td {
            border: 0;
        }
        .mt {
            margin-top: 4rem !important
        }
    </style>
</head>
<body>
    {{--========================================================================================
    *                                   CARTA                                                    *
    //========================================================================================--}}
    @if ($checkcarta == 1)
        <div class="row" style="padding-left:25px; padding-right:25px; font-family: roboto,sans-serif; font-size:16px;">
            <div style="text-align: right;" >
                {{ now() }}
            </div>
            <div style="text-align: left; padding-bottom:50px">
                @if (isset($paracarta))
                    <div class="row">
                        <div class="col-xs-1">Para: </div>
                        <div class="col-xs-11"> <strong> {!!nl2br($paracarta)!!} </strong> </div>
                    </div>
                @endif
                @if (isset($copiacarta))
                    <div class="row">
                        <div class="col-xs-1">CC: </div>
                        <div class="col-xs-11"> <strong> {!!nl2br($copiacarta)!!}</strong> </div>
                    </div>
                @endif
                @if (isset($user->id))
                    <div class="row">
                        <div class="col-xs-1">De: </div>
                        <div class="col-xs-11"> <strong> {{ isset($user->id) ? userFullName($user->id) : "" }}</strong> </div>
                    </div>
                @endif
            </div>

            <div style="text-align: right;" >
                @if (isset($refcarta))
                    REF.: <b>{!!nl2br($refcarta)!!} </b>
                @endif
            </div> <br><br>

            @if (isset($cuerpocarta))
                <div style="padding-bottom:50px">
                    {!! purify($cuerpocarta) !!}
                </div>
            @endif

            @if (isset($user))
                <div class="row" style="line-height:15px; page-break-inside: avoid !important;">
                    <div class="col-xs-9"> <br>
                        {{--========================================================================================
                        *                               FIRMA DEL USUARIO                                          *
                        //========================================================================================--}}
                        @php
                            $firmausuario = isset($user->firma) ? $user->firma : "";
                            $rutafirma = storage_path('app/public/general/firmas/'.$firmausuario);
                        @endphp
                        @if ($firmausuario!='')
                            @if (file_exists( $rutafirma ) )
                                <img src="{{ $rutafirma }}" style="display: block; width: 150px; height: 100px; margin-top: 0px;" alt="" >
                            @else
                                <br><br><br><br><br>
                            @endif
                        @else
                            <br><br><br><br><br>
                        @endif
                        {{--  NOMBRE - CARGO  Y  EMPRESA --}}
                        {{ isset($user->id) ? userFullName($user->id) : "" }}<br>
                        <b>{{ isset($user->cargo) ? $user->cargo : "" }}</b><br>
                        <b>{{nameEmpresa()}}</b><br>
                    </div>
                </div>
            @endif
        </div>
        <hr class="hhrr"> {{-- SEPARACION QUE REALIZA SALTO DE LINEA --}}
    @endif

    @php
        $salidaqr  = "Orden de trabajo: ".$workorder->cod."\r\nCliente: ";
        $salidaqr .= $workorder->asset->cliente->nombre;
        $salidaqr .= "\r\nActivo: ".$workorder->asset->cod.' - '.$workorder->asset->nombre;
        $camposinput = collect($campos_array)->sortBy('orden');
    @endphp

    <table style=" border:2px solid; width:100%; font-size:14px" id="cabeceratable">
        <tr>
            <td colspan="2" class="text-center" >
                <h4 style="padding-top:2px;font-size:20px"><b><i>GERENCIA DEPARTAMENTO TÉCNICO</i></b></h4>
            </td>
        </tr>
        <tr>
            <td style="border:2px solid" width="80%" >
                <h4 class="text-center text-uppercase" style="padding-top:5px;line-height : 30px;font-size:23px">
                    <b>INFORME: {{$formulario->name}}</b> <br>
                    <b>ORDEN DE TRABAJO: {{$workorder->cod}} </b>
                </h4>
            </td>
            <td style="border:0.5px solid" width="20%" class="text-center">
                <img src="data:image/png;base64, {!!
                    base64_encode(
                        QrCode::format('png')
                        ->color(46, 46, 44)
                        ->size(130)
                        ->margin(0)
                        ->errorCorrection('H')
                        ->generate($salidaqr)
                    ) !!}"
                style="padding-top:5px">
                <br>
                {{$workorder->cod}}
            </td>
        </tr>
    </table><br>

    <div class="col-xs-12 text-center" style="border: solid 1px black; background-color: lightgray; font-size:15px;margin-bottom:10px;margin-top:10px;padding-top:5px">
        <b>Datos de cliente</b>
    </div>

    <div class="col-xs-6" style="font-size:14px;line-height:30px;">
        <b>Cliente: </b>
        <span style="padding-left:5px; padding-right:5px; color:#0074d9; text-align:justify;">
            <b> {{ $workorder->asset->cliente->nombre }}</b>
        </span>
    </div>
    <div class="col-xs-6" style="font-size:14px;line-height:30px;">
        <b>Nro de identificación: </b>
        <span style="padding-left:5px; padding-right:5px; color:#0074d9; text-align:justify;">
            <b> {{ $workorder->asset->cliente->nit }}</b>
        </span>
    </div>
    <div class="col-xs-12" style="font-size:14px;line-height:30px;text-align:justify">
        <b>Dirección: </b>
        <span style="padding-left:5px; padding-right:5px; color:#0074d9; text-align:justify;">
            <b> {{ $workorder->asset->cliente->direccion }}</b>
        </span>
    </div>

        <div class="col-xs-12 text-center" style="border: solid 1px black; background-color: lightgray; font-size:15px;margin-bottom:10px;margin-top:10px;padding-top:5px">
            <b>Datos del activo</b>
        </div>

        <div class="col-xs-6" style="font-size:14px;line-height:30px;">
            <b>Código: </b>
            <span style="padding-left:5px; padding-right:5px; color:#0074d9; text-align:justify;">
                <b> {{ $workorder->asset->cod }}</b>
            </span>
        </div>
        <div class="col-xs-6" style="font-size:14px;line-height:30px;">
            <b>Nombre: </b>
            <span style="padding-left:5px; padding-right:5px; color:#0074d9; text-align:justify;">
                <b> {{ $workorder->asset->nombre }}</b>
            </span>
        </div>
        <div class="col-xs-6" style="font-size:14px;line-height:30px;">
            <b>Categoría: </b>
            <span style="padding-left:5px; padding-right:5px; color:#0074d9; text-align:justify;">
                <b> {{ $workorder->asset->categoriaLiteral }}</b>
            </span>
        </div>
        <div class="col-xs-6" style="font-size:14px;line-height:30px;">
            <b>Ubicación: </b>
            <span style="padding-left:5px; padding-right:5px; color:#0074d9; text-align:justify;">
                <b> {{ $workorder->asset->ubicacion }}</b>
            </span>
        </div>
        <div class="col-xs-6" style="font-size:14px;line-height:30px;">
            <b>Ciudad: </b>
            <span style="padding-left:5px; padding-right:5px; color:#0074d9; text-align:justify;">
                <b> {{ $workorder->asset->ciudadLiteral }}</b>
            </span>
        </div>
        <div class="col-xs-6" style="font-size:14px;line-height:30px;">
            <b>Nro de serie: </b>
            <span style="padding-left:5px; padding-right:5px; color:#0074d9; text-align:justify;">
                <b> {{ $workorder->asset->nro_serie }}</b>
            </span>
        </div>
        <div class="col-xs-6" style="font-size:14px;line-height:30px;">
            <b>Marca: </b>
            <span style="padding-left:5px; padding-right:5px; color:#0074d9; text-align:justify;">
                <b> {{ $workorder->asset->nro_serie }}</b>
            </span>
        </div>
        <div class="col-xs-6" style="font-size:14px;line-height:30px;">
            <b>Modelo: </b>
            <span style="padding-left:5px; padding-right:5px; color:#0074d9; text-align:justify;">
                <b> {{ $workorder->asset->nro_serie }}</b>
            </span>
        </div>
        <div class="col-xs-6" style="font-size:14px;line-height:30px;">
            <b>Capacidad: </b>
            <span style="padding-left:5px; padding-right:5px; color:#0074d9; text-align:justify;">
                <b> {{ $workorder->asset->nro_serie }}</b>
            </span>
        </div>

    <div class="col-xs-12 text-center" style="border: solid 1px black; background-color: lightgray; font-size:15px;margin-bottom:10px;margin-top:10px;padding-top:5px">
        <b>Técnico(s) a cargo</b>
    </div>

    <div class="col-xs-6" style="font-size:14px;line-height:30px;">
        <b>Técnico responsable: </b>
        <span style="padding-left:5px; padding-right:5px; color:#0074d9; text-align:justify;">
            <b> {{ isset($responsable->id) ? userFullName($responsable->id) : "Sin asignar" }} </b>
        </span>
    </div>
    @if (count($asociados) > 0)
        <div class="col-xs-6" style="font-size:14px;line-height:30px;">
            <b>Técnicos adicionales: </b>
            <span style="padding-left:5px; padding-right:5px; color:#0074d9; text-align:justify;">
                <b> {{ (implode(", ",$asociados)) }}.</b>
            </span>
        </div>
    @endif
    <div class="col-xs-6" style="font-size:14px;line-height:30px;">
        <b>Conclusión de trabajo: </b>
        <span style="padding-left:5px; padding-right:5px; color:#0074d9; text-align:justify;">
            @if(isset($workorder->lastWorkTimes->end_work_date))
                <b> {{ $workorder->lastWorkTimes->end_work_date->format('d/m/Y H:i') }}</b>
            @else
                <i>Pendiente</i>
            @endif
        </span>
    </div>

    {{-- ======================================================================================================= --}}
    {{-- ======================================================================================================= --}}
    {{-- ======================================================================================================= --}}
    @foreach ($containers as $i=>$cont)
        @if (isset($cont['subcontainer']) && $cont['subcontainer']!="")
            @foreach (collect($cont['subcontainer'])->sortBy('orden') as $item)
                @php    $auxdep = ""; /* SW para saber si el campo es dependiente */  @endphp
                <div class="row" style="margin: 0px;page-break-inside: avoid !important">
                    <div class="col-xs-12" style="border: solid 1px black; background-color: lightgray; font-size:14px;margin:10px 0px 10px 0px;;padding-top:5px">
                        <b>{{$item['mostrar']}}</b>
                    </div>
                    @foreach($camposinput as $key=>$campo)
                        @if ($campo['container'] == $cont['id'] &&  $item['val'] == $campo['subcontainer'] )
                            @php
                                $datosmostrar = isset($datosguardados[$campo['id']]['valor']) ? $datosguardados[$campo['id']]['valor'] : "";
                                $clasepadre = isset($campo['clase_padre']) ? $campo['clase_padre'] : "";
                                $radiopadreid = isset($campo['radiopadre_id']) ? $campo['radiopadre_id'] : "";
                                $radiodepclass = delete_charspecial($radiopadreid);
                                $campodepid =  explode("___",$radiopadreid);
                                $campodepid = isset($campodepid[1]) ? $campodepid[1] : "";
                                $datosseriesalida = $datosseriesalidamult = $generadoseriesalida_xy = $generadoseriesalida_simp = "";
                            @endphp
                            <div class=" subc_{{$item['val']}} {{$radiodepclass}} {{$clasepadre}}" id="{{$radiodepclass}}"  data-campo = "{{$key}}" style="@if ( $radiopadreid != "" ) display:none; @endif ">
                                @switch($campo['type'])
                                    @case('radio')
                                        @if ($datosmostrar != "")
                                            @php
                                                $datosradio = explode("___",$datosmostrar);
                                                $optionsr = collect($campo['options'])->sortBy('orden');
                                                $tChars = Str::length($campo['mostrar'].": ".$datosradio[0]);
                                                $col = ($tChars >= 60) ? 'col-xs-12' : 'col-xs-6';
                                            @endphp
                                            <div class="{{ $col }}" style="font-size:15px;line-height:30px;">
                                                <b>{{ $campo['mostrar'] }}: </b>
                                                @foreach($optionsr as $opcion)
                                                    @php
                                                        $nameradio = isset($campo['subcontainer']) ? $key.'|'.$campo['container'].'|'.$campo['subcontainer'] : $key.'|'.$campo['container'];
                                                        $hexradio = isset($opcion['hex']) ? $opcion['hex'] : "";
                                                        $valueradio = isset($opcion['hex']) ? $opcion['val'].'___'.$opcion['hex'] : $opcion['val'];
                                                    @endphp
                                                    @if ($datosradio[0]==$opcion['val'])
                                                        @if($hexradio == '#367FA9')
                                                            <b style="color: #0074d9; padding:6px 5px 2px 5px;" >  {{$opcion['mostraropt']}} </b>
                                                        @else
                                                            <b style="background-color: {{$hexradio}}; padding:6px 5px 2px 5px; color:white; border-radius:5%" >  {{$opcion['mostraropt']}} </b>
                                                        @endif
                                                        @php $auxdep = $opcion['val']; @endphp
                                                    @endif
                                                @endforeach
                                            </div>
                                            <div class="checkbox text-center" style="display: none;" >
                                                @foreach($optionsr as $opcion)
                                                    @php
                                                        $nameradio = $key;
                                                        $colorradio = isset($opcion['color']) ? $opcion['color'] : "blue";
                                                        $hexradio = isset($opcion['hex']) ? $opcion['hex'] : "";
                                                        $valueradio = isset($opcion['hex']) ? $opcion['val'].'___'.$opcion['hex'] : $opcion['val'];
                                                    @endphp
                                                    <label>
                                                        <input class="{{$colorradio}} {{$campo["id"]}}" data-radioclass ="{{delete_charspecial($campo["id"])}}" data-radioval = "{{delete_charspecial($opcion['val'])}}"
                                                        type="radio" name="{!! $nameradio !!}"
                                                        value="{{ $valueradio}}" id="{!! $opcion["val"] !!}" {{($datosradio[0]==$opcion["val"]) ? "checked" : ""}}>
                                                        <b style="color: {{$hexradio}}">{!! $opcion["mostraropt"] !!}</b>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @endif
                                    @break
                                    @case('serie')
                                        @php $tipo_grafico = isset($campo['tipografico']) ? $campo['tipografico'] : ""; @endphp
                                        @if ( $tipo_grafico == "xvsy_graf" )
                                            @php
                                                $nombreejex = str_replace(" ","_",$campo["nombre_eje_x"]);
                                                $nombreejey = str_replace(" ","_",$campo["nombre_eje_y"]);
                                                $datos_ejey = isset($datosguardados [$campo["id"]] [$nombreejey]['valor']) ? $datosguardados [$campo["id"]] [$nombreejey]['valor'] : [];
                                                $datos_ejex = isset($datosguardados [$campo["id"]] [$nombreejex]['valor']) ? $datosguardados [$campo["id"]] [$nombreejex]['valor'] : [];
                                                $seriegen_x = "&grafXY&|".$campo["id"]."|".$nombreejex."[]";
                                                $seriegen_y = "&grafXY&|".$campo["id"]."|".$nombreejey."[]";
                                                $campos_sort = isset($campo['campos']) ? collect($campo['campos'])->sortBy('orden_serie') : [];
                                                $generadoseriesalida_xy = "";
                                                // CAMPOS ADICIONALES DE LOS GRAFICOS DE SERIE
                                                $generadoseriesalida_xy .=
                                                '<div class="col-xs-12" style="font-size:14px;line-height:30px; margin-left:-15px;margin-bottom:20px; margin-top:20px">
                                                    <center><b style="font-size:20px">'.$campo['mostrar'].'</b></center>';
                                                    foreach ($campos_sort as $keyser =>$serie){
                                                        $nombreserieInputs_xy = "&serie_xy&|".$campo["id"]."|".$serie['id'];
                                                        $datosserie_xy = isset($datosguardados [$campo["id"]] [$serie['id']] ['valor']) ? $datosguardados [$campo["id"]] [$serie['id']] ['valor']: "";
                                                        if($datosserie_xy != ""){
                                                            if($serie['type'] == 'radio'){
                                                                $options = isset($serie['options']) ? $serie['options'] : [];
                                                                $datosradio = explode("___",$datosserie_xy);
                                                                $mostrarradio = "";
                                                                foreach($options as $keyopt=>$opcion){
                                                                    if (mb_strtolower($datosradio[0]) == $opcion['val']){
                                                                        $mostrarradio = $opcion['mostraropt'] ;
                                                                        $hex = isset($opcion['hex']) ? $opcion['hex'] : "#0074d9";
                                                                    }
                                                                }
                                                                $tChars = Str::length($serie['mostrar'].": ".substr($mostrarradio,0,-1));
                                                                $col = ($tChars > 60) ? 'col-xs-12' : 'col-xs-6';
                                                                    $generadoseriesalida_xy .=
                                                                "<div class='".$col."' style='font-size:14px;line-height:30px;'>
                                                                    <b>".$serie['mostrar'].": </b>
                                                                    <span style='background-color: ".$hex."; padding:6px 5px 2px 5px; color:white'>"
                                                                        .$mostrarradio.
                                                                    "</span>
                                                                </div>";
                                                            }elseif($serie['type'] == 'checkbox'){
                                                                $options = isset($serie['options']) ? collect($serie['options'])->sortBy('orden') : [];
                                                                $salidaCheckSerie = '';
                                                                foreach(collect($options)->sortBy('orden') as $opcionSerie){
                                                                    $datosselSerie = isset($datosserie_xy) ? $datosserie_xy : [];
                                                                    if(in_array($opcionSerie['val'],$datosselSerie))
                                                                        $salidaCheckSerie .=" ".$opcionSerie['mostraropt'].",";
                                                                }
                                                                $tChars = Str::length($serie['mostrar'].": ".substr($salidaCheckSerie,0,-1));
                                                                $col = ($tChars > 60) ? 'col-xs-12' : 'col-xs-6';
                                                                $generadoseriesalida_xy .=
                                                                "<div class='".$col."' style='font-size:14px;line-height:30px;'>
                                                                    <b>".$serie['mostrar'].": </b>
                                                                    <span style='color:#0074d9; padding-left:5px; padding-right:5px;'>
                                                                        <b>".substr($salidaCheckSerie,0,-1).".</b>
                                                                    </span>
                                                                </div>";
                                                            }elseif($serie['type'] == 'select2'){
                                                                $sel2mult = isset($serie['multiple']) ? $serie['multiple'] : "";
                                                                $salidaSelect2Serie = '';
                                                                if($sel2mult == 'multiple'){
                                                                    $options = isset($serie['options']) ? collect($serie['options'])->sortBy('orden') : [];
                                                                    foreach($options as $opcionSerie){
                                                                        $datosselSerie = isset($datosserie_xy) ? $datosserie_xy : [];
                                                                        if(in_array($opcionSerie['val'],$datosselSerie))
                                                                            $salidaSelect2Serie .=" ".$opcionSerie['mostraropt'].",";
                                                                    }
                                                                    $salidaSelect2Serie = substr($salidaSelect2Serie,0,-1);
                                                                }else{
                                                                    foreach($options as $opcionSerie){
                                                                        $datosselSerie = isset($datosserie_xy) ? $datosserie_xy : "";
                                                                        if($opcionSerie['val'] == $datosselSerie){
                                                                            $salidaSelect2Serie = $opcionSerie['mostraropt'];
                                                                            break;
                                                                        }
                                                                    }
                                                                }
                                                                $tChars = Str::length($serie['mostrar'].": ".substr($salidaSelect2Serie,0,-1));
                                                                $col = ($tChars > 60) ? 'col-xs-12' : 'col-xs-6';
                                                                $generadoseriesalida_xy .=
                                                                    "<div class='".$col."' style='font-size:14px;line-height:30px;'>
                                                                        <b>".$serie['mostrar'].": </b>
                                                                        <span style='color:#0074d9; padding-left:5px; padding-right:5px;'>
                                                                            <b>".$salidaSelect2Serie.".</b>
                                                                        </span>
                                                                    </div>";
                                                            }elseif($serie['type'] == 'select'){
                                                                $options = isset($serie['options']) ? collect($serie['options'])->sortBy('orden') : [];
                                                                foreach($options as $opcionSerie){
                                                                    $datosselSerie = isset($datosserie_xy) ? $datosserie_xy : "";
                                                                    if($opcionSerie['val'] == $datosselSerie){
                                                                        $salidaSelect2Serie = $opcionSerie['mostraropt'];
                                                                        break;
                                                                    }
                                                                }
                                                                $tChars = Str::length($serie['mostrar'].": ".substr($salidaSelect2Serie,0,-1));
                                                                $col = ($tChars > 60) ? 'col-xs-12' : 'col-xs-6';
                                                                $generadoseriesalida_simp .=
                                                                    "<div class='".$col."'  style='font-size:14px;line-height:30px;'>
                                                                        <b>".$serie['mostrar'].": </b>
                                                                        <span style='color:#0074d9; padding-left:5px; padding-right:5px;'>
                                                                            <b>".$salidaSelect2Serie.".</b>
                                                                        </span>
                                                                    </div>";
                                                            }else{
                                                                $tChars = Str::length($serie['mostrar'].": ".substr($datosserie_xy,0,-1));
                                                                $col = ($tChars > 60) ? 'col-xs-12' : 'col-xs-6';
                                                                $generadoseriesalida_xy .=
                                                                "<div class='".$col."' style='font-size:14px;line-height:30px;'>
                                                                        <b>".$serie['mostrar'].": </b>";
                                                                    if (is_numeric($datosserie_xy))
                                                                            $generadoseriesalida_xy .=
                                                                        "<span style='background-color: #0074d9; padding:6px 5px 2px 5px; color:white'>"
                                                                            .$datosserie_xy.
                                                                        "</span>";
                                                                    else
                                                                            $generadoseriesalida_xy .=
                                                                        "<span style='padding-left:5px; padding-right:5px; color:#0074d9'>
                                                                            <b>".$datosserie_xy."</b>
                                                                        </span>";
                                                                    $generadoseriesalida_xy .=
                                                                "</div>";
                                                            }
                                                        }
                                                    }
                                                    $generadoseriesalida_xy .=
                                                '</div>';

                                                    $generadoseriesalida_xy .='
                                                <div class="col-xs-12 series_sep" >
                                                    <div class="col-xs-12"><b>'.$campo["nombre_eje_x"].'</b><i> (Eje X)</i></div>';
                                                    foreach ($datos_ejex as $keje=>$_ejex) {
                                                        if($_ejex != "") $generadoseriesalida_xy .='<div class="col-xs-2" style="overflow-wrap:break-word;word-wrap: break-word;">'.$_ejex.'</div>';
                                                    }

                                                $generadoseriesalida_xy .='</div>
                                                <div class="col-xs-12 series_sep" >
                                                    <div class="col-xs-12"><b>'.$campo["nombre_eje_y"].'</b> <i> (Eje Y)</i></div>';
                                                    foreach ($datos_ejey as $keje=>$_ejey) {
                                                        if($_ejey != ""){
                                                            $generadoseriesalida_xy .= '<div class="col-xs-2" style="overflow-wrap:break-word;word-wrap: break-word;">'.$_ejey.'</div>';
                                                        }
                                                    }

                                                    if(isset($campo["nro_eje_y"])){
                                                        for ($ejey = 0; $ejey < $campo["nro_eje_y"]; $ejey++){
                                                            $nombreejey = str_replace(" ","_",$campo["nombre_eje_y"]);
                                                            $datos_ejey = isset($datosguardados [$campo["id"]] [$nombreejey] ["&seriegenY&_".$ejey]) ? $datosguardados [$campo["id"]] [$nombreejey] ["&seriegenY&_".$ejey] : "";
                                                            if($datos_ejey != "") $generadoseriesalida_xy .='<div class="col-xs-2" style="overflow-wrap:break-word;word-wrap: break-word;">'.$datos_ejey.'</div>';
                                                        }
                                                    }
                                                    if(isset($datosguardados [$campo["id"]])){
                                                        $generadoseriesalida_xy .=
                                                        '</div>';
                                                    }

                                                // Ejes Extra
                                                if(isset($campo["eje_more"])){
                                                    for ($ejemore = 0; $ejemore < count($campo["eje_more"]["nombre_eje_more"]); $ejemore++){
                                                        if(isset($datosguardados [$campo["id"]])){
                                                            $generadoseriesalida_xy .='
                                                            <div class="col-xs-12 series_sep">
                                                            <div class="col-xs-12"><b>'.$campo["eje_more"]["nombre_eje_more"][$ejemore].'</b> </div>';
                                                        }
                                                        if(isset($campo["eje_more"]["nro_eje_more"])){
                                                            for ($nromore = 0; $nromore < $campo["eje_more"]["nro_eje_more"][$ejemore]; $nromore++){
                                                                $nombreejemore = str_replace(" ","_",$campo["eje_more"]["nombre_eje_more"][$ejemore]);
                                                                $datos_ejemore = isset($datosguardados[ $campo["id"] ][$nombreejemore]['valor'][$nromore]) ? $datosguardados[ $campo["id"] ][$nombreejemore]['valor'][$nromore] : "";
                                                                if($datos_ejemore != "")
                                                                    $generadoseriesalida_xy .='<div class="col-xs-2" style="background-color:yellow: border: 1px solid red;">'.$datos_ejemore.'</div>';
                                                            }
                                                        }
                                                        if(isset($datosguardados [$campo["id"]])){ $generadoseriesalida_xy .='</div>'; }
                                                    }
                                                }

                                                $generadoseriesalida_xy .= '<div class="col-xs-12 text-center" > ';
                                                    $camponame = delete_charspecial($campo['id']);
                                                    $nombrecont_chart = '1__'.$workorder->id.'__'.$camponame."__".$campo['type'];
                                                    $ruta2 = storage_path('app/public/chartreports/'.$nombrecont_chart.'.png');
                                                    $imgchart = (file_exists($ruta2)) ? '<img  src="'.$ruta2.'">' : "";
                                                    $generadoseriesalida_xy .=$imgchart;
                                                $generadoseriesalida_xy .="</div>";
                                            @endphp
                                            {!! $generadoseriesalida_xy !!}
                                        @elseif($tipo_grafico == "serie_graf")
                                            @php
                                                $nroXserie = isset($datosguardados[$campo['id']]['nro_x_serie']) ? $datosguardados[$campo['id']]['nro_x_serie'] : 0;
                                                $campoXserie = isset($datosguardados[$campo['id']]['campos_x_serie']) ? $datosguardados[$campo['id']]['campos_x_serie'] : 0;
                                                $campos_sort = isset($campo['campos']) ? collect($campo['campos'])->sortBy('orden_serie') : [];
                                                $datosseriesalida = "";
                                                    $datosseriesalida .=
                                                '<div class="col-xs-12" style="font-size:14px;line-height:30px; margin-left:-15px; margin-bottom:20px;margin-top:10px">
                                                    <center><b style="font-size:20px">'.mb_strtoupper($campo['mostrar']).'</b></center>';
                                                    foreach ($campos_sort as $keyser =>$serie){
                                                        if ($keyser == 'nro_x_serie'){
                                                            $datosseriesalida .="
                                                            <div class='col-xs-6' style='font-size:15px;line-height:30px;'>
                                                                <b>".$serie['mostrar'].": </b><b style='background-color: #0074d9; padding:6px 5px 2px 5px; color:white'> ".$nroXserie."</b>
                                                            </div>";
                                                        }
                                                        if($keyser == 'campos_x_serie'){
                                                            $datosseriesalida .="
                                                            <div class='col-xs-6' style='font-size:15px;line-height:30px;'>
                                                                <b>".$serie['mostrar'].": </b><b style='background-color: #0074d9; padding:6px 5px 2px 5px; color:white'> ".$campoXserie."</b>
                                                            </div>";
                                                        }
                                                    }
                                                    for ($ca = 1; $ca <= $nroXserie; $ca++){
                                                        $stiloMxN = $ca != $nroXserie ? 'style=" border-bottom:1px solid black; padding-top:10px;padding-bottom:10px"' : '';
                                                        $datosseriesalida .=
                                                        '<div class="col-xs-12" '.$stiloMxN.'>
                                                            <div class="col-xs-12">
                                                                <b style="font-size:16px">SERIE '.$ca.'</b>
                                                            </div>';
                                                            $datosserie = isset( $datosguardados[$campo['id']."|".$ca] ) ? $datosguardados[$campo['id']."|".$ca]  : null;
                                                            foreach ($campos_sort as $keyser =>$serie){
                                                                if ($keyser != 'nro_x_serie' && $keyser != 'campos_x_serie'){
                                                                    $mostrardatoserie = isset($datosserie[$serie['id']]['valor']) ? $datosserie[$serie['id']]['valor']: "";
                                                                    if($mostrardatoserie != ""){

                                                                        if($serie['type'] == 'radio'){
                                                                            $options = isset($serie['options']) ? $serie['options'] : [];
                                                                            $datosradio = explode("___",$mostrardatoserie);
                                                                            $hex = isset($datosradio[1]) ? $datosradio[1] : "#0074d9";
                                                                            $mostrarradio = "";
                                                                            foreach($options as $keyopt=>$opcion){
                                                                                if (mb_strtolower($datosradio[0]) == $opcion['val']){
                                                                                    $mostrarradio = $opcion['mostraropt'];
                                                                                    $hex = $opcion['hex'] ? $opcion['hex'] :  "#0074d9";
                                                                                }
                                                                            }
                                                                            $tChars = Str::length($serie['mostrar'].": ".substr($mostrarradio,0,-1));
                                                                            $col = ($tChars > 60) ? 'col-xs-12' : 'col-xs-6';
                                                                                $datosseriesalida .=
                                                                            "<div class='".$col."' style='font-size:14px;line-height:30px;'>
                                                                                <b>".$serie['mostrar'].": </b>
                                                                                <span style='background-color: ".$hex."; padding:6px 5px 2px 5px; color:white'>"
                                                                                    .$mostrarradio.
                                                                                "</span>
                                                                            </div>";
                                                                        }elseif($serie['type'] == 'checkbox'){
                                                                            $options = isset($serie['options']) ? collect($serie['options'])->sortBy('orden') : [];
                                                                            $datosselSerie = isset($mostrardatoserie) ? $mostrardatoserie : [];
                                                                            $salidaCheckSerie = '';
                                                                            foreach(collect($options)->sortBy('orden') as $opcionSerie){
                                                                                if(in_array($opcionSerie['val'],$datosselSerie))
                                                                                    $salidaCheckSerie .=" ".$opcionSerie['mostraropt'].",";
                                                                            }
                                                                            $tChars = Str::length($serie['mostrar'].": ".substr($salidaCheckSerie,0,-1));
                                                                            $col = ($tChars > 60) ? 'col-xs-12' : 'col-xs-6';
                                                                            $datosseriesalida .=
                                                                            "<div class='".$col."' style='font-size:14px;line-height:30px;'>
                                                                                <b>".$serie['mostrar'].": </b>
                                                                                <span style='color:#0074d9; padding-left:5px; padding-right:5px;'>
                                                                                    <b>".substr($salidaCheckSerie,0,-1).".</b>
                                                                                </span>
                                                                            </div>";
                                                                        }elseif($serie['type'] == 'select2'){
                                                                            $options = isset($serie['options']) ? collect($serie['options'])->sortBy('orden') : [];
                                                                            $sel2mult = isset($serie['multiple']) ? $serie['multiple'] : "";
                                                                            $salidaSelect2Serie = '';
                                                                            if($sel2mult == 'multiple'){
                                                                                foreach($options as $opcionSerie){
                                                                                    $datosselSerie = isset($mostrardatoserie) ? $mostrardatoserie : [];
                                                                                    if(in_array($opcionSerie['val'],$datosselSerie))
                                                                                        $salidaSelect2Serie .=" ".$opcionSerie['mostraropt'].",";
                                                                                }
                                                                                $salidaSelect2Serie = substr($salidaSelect2Serie,0,-1);
                                                                            }else{
                                                                                foreach($options as $opcionSerie){
                                                                                    $datosselSerie = isset($mostrardatoserie) ? $mostrardatoserie : "";
                                                                                    if($opcionSerie['val'] == $datosselSerie){
                                                                                        $salidaSelect2Serie = $opcionSerie['mostraropt'];
                                                                                        break;
                                                                                    }
                                                                                }
                                                                            }
                                                                            $tChars = Str::length($serie['mostrar'].": ".substr($salidaSelect2Serie,0,-1));
                                                                            $col = ($tChars > 60) ? 'col-xs-12' : 'col-xs-6';
                                                                            $datosseriesalida .=
                                                                                "<div class='".$col."' style='font-size:14px;line-height:30px;'>
                                                                                    <b>".$serie['mostrar'].": </b>
                                                                                    <span style='color:#0074d9; padding-left:5px; padding-right:5px;'>
                                                                                        <b>".$salidaSelect2Serie.".</b>
                                                                                    </span>
                                                                                </div>";
                                                                        }elseif($serie['type'] == 'select'){
                                                                            $options = isset($serie['options']) ? collect($serie['options'])->sortBy('orden') : [];
                                                                            $salidaSelect2Serie = '';
                                                                            foreach($options as $opcionSerie){
                                                                                $datosselSerie = isset($mostrardatoserie) ? $mostrardatoserie : "";
                                                                                if($opcionSerie['val'] == $datosselSerie){
                                                                                    $salidaSelect2Serie = $opcionSerie['mostraropt'];
                                                                                    break;
                                                                                }
                                                                            }
                                                                            $tChars = Str::length($serie['mostrar'].": ".substr($salidaSelect2Serie,0,-1));
                                                                            $col = ($tChars > 60) ? 'col-xs-12' : 'col-xs-6';
                                                                            $datosseriesalida .=
                                                                                "<div class='".$col."' style='font-size:14px;line-height:30px;'>
                                                                                    <b>".$serie['mostrar'].": </b>
                                                                                    <span style='color:#0074d9; padding-left:5px; padding-right:5px;'>
                                                                                        <b>".$salidaSelect2Serie.".</b>
                                                                                    </span>
                                                                                </div>";
                                                                        }else{
                                                                            $tChars = Str::length($serie['mostrar'].": ".substr($mostrardatoserie,0,-1));
                                                                            $col = ($tChars > 60) ? 'col-xs-12' : 'col-xs-6';
                                                                                $datosseriesalida .=
                                                                            "<div class='".$col."' style='font-size:14px;line-height:30px;'>";
                                                                            if (is_numeric($mostrardatoserie))
                                                                                    $datosseriesalida .=
                                                                                "<b>".$serie['mostrar'].": </b>
                                                                                <b style='background-color: #0074d9; padding:6px 5px 2px 5px; color:white'> ".$mostrardatoserie."</b>";
                                                                            else {
                                                                                if(is_array($mostrardatoserie)) $mostrardatoserie = implode(", ",$mostrardatoserie).".";
                                                                                    $datosseriesalida .=
                                                                                "<b>".$serie['mostrar'].": </b>
                                                                                <b style='color: #0074d9;  padding-left:5px; padding-right:5px;'> ".$mostrardatoserie."</b>";
                                                                            }
                                                                                $datosseriesalida .=
                                                                            "</div>";
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            $datosseriesalida .=
                                                            '<div class="col-xs-12 text-center" > ';
                                                                $nombrecont_chart = '1__'.$workorder->id.'__'.$campo['id']."__".$campo['type']."__".$ca;
                                                                $ruta2 = storage_path('app/public/chartreports/'.$nombrecont_chart.'.png');
                                                                $imgchart = (file_exists($ruta2)) ? '<img  src="'.$ruta2.'">' : "";
                                                                $datosseriesalida .= $imgchart;
                                                            $datosseriesalida .=
                                                            "</div>
                                                        </div>";
                                                    }
                                                    $datosseriesalida .=
                                                "</div>";
                                            @endphp
                                            {!! $datosseriesalida !!}
                                        @endif
                                    @break
                                    @case('checkbox')
                                        @if($datosmostrar != "")
                                            @php $salidacheck = ""; @endphp
                                            @foreach($campo['options'] as $keyopt=>$opcion)
                                                @php
                                                    $namecheck = $campo["id"].'|'.$campo['container'].'|'.$campo['subcontainer'].'[]';
                                                    $datossel = isset($datosguardados[$campo['id']]['valor']) ? $datosguardados[$campo['id']]['valor'] : [];
                                                    if(in_array($opcion['val'],$datossel)) $salidacheck .=" ".$opcion['mostraropt'].",";
                                                    $tChars = Str::length($campo['mostrar'].": ".substr($salidacheck,0,-1));
                                                    $col = ($tChars > 60) ? 'col-xs-12' : 'col-xs-6';
                                                @endphp
                                            @endforeach
                                            <div class="{{ $col }}" style="font-size:14px;line-height:30px;">
                                                <b>{{ $campo['mostrar'] }}: </b>
                                                <span style="padding-left:5px; padding-right:5px; color:#0074d9">
                                                    <b>{{substr($salidacheck,0,-1)}}.</b>
                                                </span>
                                            </div>
                                        @endif
                                    @break
                                    @case('select')
                                        @if(empty($campo['options'])) Campo incompleto, no se registrará
                                        @else
                                            @if($datosmostrar != "")
                                                @foreach($campo['options'] as $opcion)
                                                    @if ($opcion['val'] == $datosmostrar)
                                                        @php
                                                            $tChars = Str::length($campo['mostrar'].": ".$datosmostrar);
                                                            $col = ($tChars > 60) ? 'col-xs-12' : 'col-xs-6';
                                                        @endphp
                                                        <div class="{{ $col }}" style="font-size:14px;line-height:30px;">
                                                            <b>{{ $campo['mostrar'] }}:
                                                                <span style="padding-left:5px; padding-right:5px; color:#0074d9; text-align:justify;">
                                                                    {{$opcion['mostraropt']}}
                                                                </span>
                                                            </b>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endif
                                    @break
                                    @case('select2')
                                        @if(empty($campo['options'])) Campo incompleto, no se registrará
                                        @else
                                            @if ($datosmostrar != "")
                                                @if ( isset($campo['multiple']) )
                                                    @php
                                                        $salidaselectmultiple = "";
                                                        $datossel = isset($datosguardados[$campo['id']]['valor']) ? $datosguardados[$campo['id']]['valor'] : [];
                                                        $selorden = collect($campo['options'])->sortBy('orden');
                                                        foreach($selorden as $opcion){
                                                            if(in_array($opcion['val'],$datossel))
                                                                $salidaselectmultiple .=" ".$opcion['mostraropt'].",";
                                                        }
                                                        $tChars = Str::length($campo['mostrar'].": ".substr($salidaselectmultiple,0,-1));
                                                        $col = ($tChars > 60) ? 'col-xs-12' : 'col-xs-6';
                                                    @endphp
                                                    <div class="{{ $col }}" style="font-size:14px;line-height:30px;">
                                                        <b>{{ $campo['mostrar'] }}:
                                                            <span style="padding-left:5px; padding-right:5px; color:#0074d9; text-align:justify;">
                                                                {{substr($salidaselectmultiple,0,-1)}}
                                                            </span>
                                                        </b>
                                                    </div>
                                                @else
                                                    @foreach($campo['options'] as $opcion)
                                                        @if ($opcion['val'] == $datosmostrar)
                                                            @php
                                                                $tChars = Str::length($campo['mostrar'].": ".$datosmostrar);
                                                                $col = ($tChars > 60) ? 'col-xs-12' : 'col-xs-6';
                                                            @endphp
                                                            <div class="{{ $col }}" style="font-size:14px;line-height:30px;">
                                                                <b>{{ $campo['mostrar'] }}:
                                                                    <span style="padding-left:5px; padding-right:5px; color:#0074d9; text-align:justify;">
                                                                        {{$opcion['mostraropt']}}
                                                                    </span>
                                                                </b>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endif
                                        @endif
                                    @break

                                    @case('textarea')
                                        @if($datosmostrar != "")
                                            <div class="col-xs-12" style="font-size:14px;line-height:30px;text-align:justify; page-break-inside: avoid !important;">
                                                <b>{{ $campo['mostrar'] }}:</b><br>
                                                <span style="color:#0074d9; text-align:justify;">
                                                    <b> {!!nl2br($datosmostrar)!!} </b>
                                                </span>
                                            </div>
                                        @endif
                                    @break
                                    @default
                                        @if($datosmostrar != "")
                                            <div class="col-xs-6" style="font-size:14px;line-height:30px;text-align:justify">
                                                <b>{{ $campo['mostrar'] }}:</b>
                                                @if (is_numeric($datosmostrar))
                                                    <span style="background-color: #0074d9; padding:6px 5px 2px 5px; color:white; border-radius:5%">
                                                        <b> @if (strpos($datosmostrar, "\r\n") ) <br> @endif {!!nl2br( round($datosmostrar,2))!!} </b>
                                                    </span>
                                                @else
                                                    <span style="padding-left:5px; padding-right:5px; color:#0074d9; text-align:justify;">
                                                        <b> @if (strpos($datosmostrar, "\r\n") ) <br> @endif {!!nl2br($datosmostrar)!!} </b>
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    @break
                                @endswitch
                            </div>
                        @endif
                    @endforeach
                </div>
            @endforeach
        @endif
    @endforeach


<div class="row"></div>
<hr class="hhrr">
<div class="row"></div>
<div class="row" style="margin: 0px">
    @if (count($images)>0)
        <div class="row">
            <h3 class="text-center"><b> REPORTE FOTOGRÁFICO</b></h3> <br><br>
            @foreach ($images as $key=>$attach)
                @php    $ruta = storage_path('app/public/reports/'.$workorder->cod.'/'.$attach->path.'');    @endphp
                <center>
                @if (file_exists( $ruta ) && strpos( $ruta , " ") === false  )
                    <img src={{$ruta}} style="max-width:600px; max-height:800px;margin-bottom:10px">
                    <div style="font-size:18px; @if ($imageslast->id != $attach->id) margin-bottom: 80px @else margin-bottom: 0px @endif" >
                        <b>{{$attach->nombre}}</b>
                    </div>
                @endif
                </center>
            @endforeach
        </div>
    @endif
</div>
</body>
</html>


<script>
    $(document).ready(function(){
        var radiojs = ['blue','red','green','yellow','orange','purple'];
        for(var i=0; i<radiojs.length; i++){
            $('.'+radiojs[i]+'').each( function(i) {
                if($(this).is(':checked')) {
                    var valor = $(this).attr('data-radioval');
                    var clase = $(this).attr('data-radioclass');
                    var dependiente = clase+'___'+valor;
                    $('.'+dependiente+'').show();
                }
            });
        }
    });
</script>
