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

        {{--  =)0============================== --}}


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
