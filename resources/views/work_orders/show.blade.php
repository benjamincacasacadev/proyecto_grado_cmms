@extends ('layouts.admin', ['title_template' => "Orden de trabajo $workorder->cod"])
@section('extracss')
    <style>
        .icon-tabler {
            width: 28px;
            height: 28px;
            stroke-width: 1.25;
        }
        table#tableReports th{
            font-size:10px;
        }
        table#tableReports td{
            font-size: 11px;
        }
        .blink_me {
            animation: blinker 1s linear infinite;
        }
        .blink_me2 {
            animation: blinker 2s linear infinite;
        }
        @keyframes  blinker {
            50% {
                opacity: 0;
            }
        }
        #div_info{
            margin: 0;
            padding: 50px;
        }

        #div_info_center{
            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }

        #divNoImage{
            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }

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
            left: 30%;
        }
        @media  (max-width: 1400px){
            #div_info{
                margin: 0;
                padding: 10px;
            }
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
        .icon-edit {
            width: 25px;
            height: 25px;
            stroke-width: 1.5;
        }
        .select2-container--open {
            z-index: 999999;
        }

        .btn-small{
            padding: .3rem .8rem;

        }
    </style>
    <link href="{{asset('/plugins/fileinput/css/fileinput.min.css')}}" media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{asset('/plugins/iCheck/all.css')}}">
    <link rel="stylesheet" href="{{asset('/plugins/animate.min.css')}}">
@endsection

@section ('contenidoHeader')
    <div class="col">
        <div class="page-pretitle">
            {{ nameEmpresa() }}
        </div>
        <h1 class="titulomod">
            <i class="fas fa-clipboard-list fa-md"></i>&nbsp;
            {{ $workorder->titulo }} - {{ $workorder->cod }}
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
@php
$routeAttach = storage_path('app/public/workorders/'.$workorder->attach);
@endphp
<div class="row py-3">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 animated fadeInLeft"  id="colForms">
        <div class="row row-cards">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-status-top bg-yellow"></div>
                    <div class="card-body">
                        <h3 class="card-title pull-left">
                            <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="4" width="18" height="4" rx="2" /><path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-10" /><line x1="10" y1="12" x2="14" y2="12" /></svg>
                            Datos generales
                        </h3>
                        <table class="table table-condensed table-vcenter table-hover">
                            @if ($workorder->emergency == 'E')
                                <tr>
                                    <td class="font-weight-bold text-center" colspan="2">
                                        <span class="text-orange">Orden de trabajo de emergencia</span>
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td class="font-weight-bold">Cliente</td>
                                <td>{!! $workorder->asset->cliente->nombre !!}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Título:</td>
                                <td>{{ $workorder->titulo }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Fecha programada</td>
                                <td>{!! $workorder->getFecha() !!}</td>
                            </tr>

                            <tr>
                                <td class="font-weight-bold">Activo</td>
                                <td>
                                    {!! $workorder->asset->getCod() !!} - <i> {!! $workorder->asset->nombre !!}</i>
                                </td>
                            </tr>

                            <tr>
                                <td class="font-weight-bold">Ubicación</td>
                                <td>{!! $workorder->asset->ubicacion !!}</td>
                            </tr>

                            <tr>
                                <td class="font-weight-bold">Estado</td>
                                <td>{!! $workorder->getEstado(3) !!}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Prioridad</td>
                                <td>{!! $workorder->getPrioridad() !!}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Técnicos asignados</td>
                                <td>{!! $workorder->getAvatars(5) !!}</td>
                            </tr>
                            @if ($workorder->outcomes->count() > 0)
                            <tr>
                                <td class="font-weight-bold">Solicitud de materiales</td>

                                <td>
                                    @php
                                        $codOutcome = '';
                                        $codOutcomeArray = [];
                                        foreach ($workorder->outcomes as $outcome){
                                            $codOutcomeArray[] = $outcome->getCod();
                                        }
                                        $codOutcome = implode(', ',$codOutcomeArray);
                                    @endphp
                                    <b>{!! $codOutcome !!}</b>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td class="font-weight-bold">Descripción</td>
                                <td style="max-height: 200px; overflow-y: auto; max-width: 100px; overflow-x: auto;">
                                    <div style="max-height: 200px; overflow-y: auto;  text-align:justify;word-wrap: break-word;">
                                        {!! purify(nl2br($workorder->descripcion)); !!}
                                    </div>
                                </td>
                            </tr>

                            @if (isset($workorder->historial))
                                <tr>
                                    <td class="font-weight-bold">Historial</td>
                                    <td style="max-height: 200px; overflow-y: auto;">
                                        <div style="max-height: 200px; overflow-y: auto; text-align:justify; font-size:12px">
                                            @php
                                                $historial = substr($workorder->historial,4)
                                            @endphp
                                            {!! purify(nl2br($historial)) !!}
                                        </div>
                                    </td>
                                </tr>
                            @endif

                                <tr class="text-center">
                                    <td colspan="2">
                                            <a href="/workorders/report/{{ code($workorder->id)}}" target="_blank" class="btn btn-outline-yellow font-weight-bold border border-yellow">
                                                <i class="fe fe-file-text " id="icon"> </i> &nbsp;
                                                {{ ($workorder->estado == 'T' || $workorder->estado == 'X') ? 'Ver' : 'Llenar' }} informe
                                            </a>

                                        @if ( $workorder->estado != "P" || isset($workorder->firstTripTimes) )
                                        <a rel="modalDuration" href="/work_orders/time/modalDuration/{{code($workorder->id)}}">
                                            <button class="btn btn-outline-yellow font-weight-bold border border-yellow" type="button">
                                                <i class="far fa-clock"></i> &nbsp;
                                                Duración&nbsp;

                                                @if($workorder->state != '1' && $workorder->state != 'F' && $workorder->state != 'T' && $workorder->state != '0' && isset($workorder->timeElapsed['h'])  && isset($workorder->timeElapsed['m'])  && isset($workorder->timeElapsed['s']) )
                                                    <span id="h_modal" class="clockfont hours" style="display:inline-block !important;" >{{str_pad($workorder->timeElapsed['h'], 2, "0", STR_PAD_LEFT)}}</span>
                                                    <span class="clockfont dots" style="display:inline-block !important;" >:</span>
                                                    <span id="m_modal" class="clockfont minutes" style="display:inline-block !important;" >{{str_pad($workorder->timeElapsed['m'], 2, "0", STR_PAD_LEFT)}}</span>
                                                    <span class="clockfont dots" style="display:inline-block !important;" >:</span>
                                                    <span id="s_modal" class="clockfont seconds" style="display:inline-block !important;" >{{str_pad($workorder->timeElapsed['s'], 2, "0", STR_PAD_LEFT)}}</span>
                                                @else
                                                    del trabajo
                                                @endif
                                            </button>
                                        </a>
                                        @endif
                                    </td>
                                </tr>

                            {{-- ANULAR OT --}}
                            @if (Gate::check('workorders.anular'))
                                @if ($workorder->state != '0' && $workorder->state != 'N' && $workorder->state != 'X' && $workorder->state != '3' && $workorder->state != 'F')
                                    <tr class="text-center">
                                        <td colspan="2">
                                            <a rel='modalCancel' class="btn btn-outline-red font-weight-bold border border-danger"  href='/work_orders/cancelmodal/{{code($workorder->id)}}'>
                                                <svg class='icon' width='24' height='24' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' fill='none' stroke-linecap='round' stroke-linejoin='round'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><circle cx='12' cy='12' r='9' /><line x1='5.7' y1='5.7' x2='18.3' y2='18.3' /></svg>
                                                Anular &nbsp; <b> {{$workorder->cod}}</b>
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 animated fadeInRight align-self-center">
        <div class="row row-cards">
            @if (isset($workorder->attach) && file_exists($routeAttach))
                @php $swfile = 1; @endphp
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divadjunto">
                    <div class="card">
                        <div class="card-status-top bg-yellow"></div>
                        <div class="card-body">
                            <h3 class="card-title">
                                <svg class="icon icon-tabler" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="15" y1="8" x2="15.01" y2="8" /><rect x="4" y="4" width="16" height="16" rx="3" /><path d="M4 15l4 -4a3 5 0 0 1 3 0l5 5" /><path d="M14 14l1 -1a3 5 0 0 1 3 0l2 2" />
                                </svg>
                                Archivo adjunto
                            </h3>
                            <div class="text-center" id="div_info_centers">
                                @php
                                    $extension = explode('.',$workorder->attach);
                                    $ext = end($extension);
                                    $ext = strtolower($ext);
                                    if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'svg') $type = 'img';
                                    elseif($ext == 'mp4') $type = 'vid';
                                    elseif($ext == 'pdf') $type = 'pdf';
                                @endphp

                                @if ($type == 'img')
                                    <a href="/storage/workorders/{{$workorder->attach."?".rand()}}." target="_blank" >
                                        <img src="/storage/workorders/{{$workorder->attach."?".rand()}}."  alt="Sin imagen para mostrar" id="imgWork">
                                    </a>
                                @elseif ($type == 'vid')
                                    <div class="embed-responsive embed-responsive-21by9">
                                        <iframe class="embed-responsive-item" src="/storage/workorders/{{$workorder->attach."?".rand()}}." allowfullscreen></iframe>
                                    </div>
                                @elseif ($type == 'pdf')
                                    <div class="card">
                                        <iframe src="{{URL::to('storage/empresas_archivos/'.codEmpresa().'/workorders/'.$workorder->attach)}}" width="100%" height="300"></iframe>
                                    </div>
                                @endif
                                {{-- SECCION PARA CAMBIAR ADJUNTO --}}
                                @if (permisoAdminJefe())
                                    <div>
                                        <label class="text-yellow">
                                            Cambiar adjunto
                                            @php
                                                $textpopover ='data-toggle="popover" data-trigger="hover" data-content="<span style=\'font-size:11px\'>Al cambiar el archivo adjunto se <b>ELIMINARÁ</b> el que se guardó anteriormente para este registro </span>" data-title="<b>Información Importante</b>"';
                                            @endphp
                                            <span class="edithover form-help" {!! $textpopover !!}>?</span>
                                        </label>
                                        &nbsp;&nbsp; &nbsp; <input type="checkbox" class="cambioarchivo"value="1">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                @php $swfile = 0; @endphp
            @endif

            @if (permisoAdminJefe())
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divnewarchivo" style="{{($swfile != '0') ? 'display:none;' : ''}}">
                    <div class="card" id="{{(!isset($workorder->requests))? 'colPreview' : ''}}">
                        <div class="card-status-top bg-yellow"></div>
                        <div class="card-body">
                            @if ($swfile != '0')
                            <h3 class="card-title">
                                <svg class="icon icon-tabler" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="15" y1="8" x2="15.01" y2="8" /><rect x="4" y="4" width="16" height="16" rx="3" /><path d="M4 15l4 -4a3 5 0 0 1 3 0l5 5" /><path d="M14 14l1 -1a3 5 0 0 1 3 0l2 2" />
                                </svg>
                                Cambiar Adjunto
                                &nbsp;&nbsp; &nbsp; <input type="checkbox" class="cambioarchivo2" name="cambioarchivo" value="1" checked>
                            </h3>
                            @endif
                            @if($workorder->estado != 'T' && $workorder->estado != 'X')
                                {{Form::Open(array('action'=>array('WorkOrdersController@updateImage',code($workorder->id)),'method'=>'POST','autocomplete'=>'off','id'=>'formAttachOrdenTrabajo'))}}
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                                        <div class="text-center font-wight-bold" style="font-size:20px;" id="{{(!isset($workorder->requests))? 'div_info' : ''}}">
                                            @if ($swfile == '0')
                                                <svg class="icon mb-2 icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" />
                                                </svg>

                                                <svg class="icon mb-2 icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="15" y1="8" x2="15.01" y2="8" /><rect x="4" y="4" width="16" height="16" rx="3" /><path d="M4 15l4 -4a3 5 0 0 1 3 0l5 5" /><path d="M14 14l1 -1a3 5 0 0 1 3 0l2 2" />
                                                </svg>
                                                <br> Puede agregar un archivo adjunto para visualizar mejor el trabajo a realizar <br><br>
                                            @else
                                                <span style="font-size: 17px;">Al cambiar el archivo adjunto se <b>eliminará</b> el que se guardó anteriormente.</span><br>
                                            @endif

                                            <div style="padding-left: 25px; text-align: left; font-size: 14px;">
                                                <b><svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path
                                                            d="M4 9h8v-3.586a1 1 0 0 1 1.707 -.707l6.586 6.586a1 1 0 0 1 0 1.414l-6.586 6.586a1 1 0 0 1 -1.707 -.707v-3.586h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1z" />
                                                    </svg>Tipos de archivos soportados:</b>&nbsp;&nbsp;.gif, .jpg, .jpeg, .png, .pdf, .mp4<br>
                                                <b><svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path
                                                            d="M4 9h8v-3.586a1 1 0 0 1 1.707 -.707l6.586 6.586a1 1 0 0 1 0 1.414l-6.586 6.586a1 1 0 0 1 -1.707 -.707v-3.586h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1z" />
                                                    </svg>Tamaño Máximo admitido: </b>5 MB (5192 KB)<br>
                                                <b><svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path
                                                            d="M4 9h8v-3.586a1 1 0 0 1 1.707 -.707l6.586 6.586a1 1 0 0 1 0 1.414l-6.586 6.586a1 1 0 0 1 -1.707 -.707v-3.586h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1z" />
                                                    </svg></b> Las imágenes subidas serán redimensionadas a un tamaño máximo de 1024*1024 píxeles.
                                            </div>
                                            <div id="fileWO_fg" class="form-group" style="font-size:12px !important" >
                                                <input type="file" class="input-sm" id="fileWO" name="fileWO" data-max-size="5192" data-browse-on-zone-click="true" accept=".gif, .jpg, .jpeg, .png, .pdf, .mp4">
                                                @if ($swfile = '1')
                                                    <input type="text" name="changefile" value="1" hidden>
                                                @endif
                                                <span id="fileWO-error" class="text-red font-weight-bold"></span>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="font-size:12px !important">
                                                <button type="submit" class="btn btn-yellow" name="btnSubmitAttach">Guardar</button>
                                            </div>
                                        </div>
                                    </div>
                                {{Form::Close()}}
                            @else
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="text-center text-yellow font-wight-bold" style="font-size:20px;" id="{{(!isset($workorder->requests))? 'divNoImage' : ''}}"  >
                                        <img src="/storage/noimage.png?{{rand()}}" style="margin-bottom:10px" >
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 animated fadeInRight">
                    <div class="card" id="{{(!isset($workorder->requests))? 'colPreview' : ''}}" >
                        <div class="card-status-top bg-yellow"></div>
                        <div class="card-body">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="text-center text-yellow font-wight-bold" style="font-size:20px;" id="{{(!isset($workorder->requests))? 'divNoImage' : ''}}"  >
                                    <img src="/storage/noimage.png?{{rand()}}" style="margin-bottom:10px" >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- modal duration --}}
<div class="modal modalPrimary fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalDuration" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{asset('/plugins/fileinput/js/fileinput.min.js')}}"></script>
<script src="{{asset('/plugins/iCheck/icheck.min.js')}}"></script>
<script>
    modalAjax("modalDuration","modalDuration","modal-content",'/work_orders/show/{{code($workorder->id)}}');
    modalAjax("modalCancel","modalCancel","modal-content",'/work_orders/show/{{code($workorder->id)}}');

    $( "#btnAsignTech" ).click(function() {
        $('#modalAddTech').modal('show');
    });

    $(document).ready(function () {
        var hT = $( "#colForms" ).height();
        $("#div_info").fadeIn("slow");
        $("#colPreview").height(hT);
        $("#colPreview").fadeIn();

        $(function () {
            $('[data-toggle="popover"]').popover({
                html: true,
                "trigger" : "hover",
                "placement": "top",
                "container": "body",
            });

            $('[data-toggle="tooltip"]').tooltip({
                html: true,
                trigger : 'hover',
            });
            $('[data-toggle="popoverOper"]').popover({
                html: true,
                "trigger" : "focus",
                "placement": "left",
                "container": "body",
                delay: {
                    "hide": 200
                }
            });
        });

        $("#fileWO").fileinput({
            showUpload: false,
            allowedFileExtensions: ["gif","jpg","jpeg","png","pdf","mp4"],
            maxFileSize: 5192,
            maxFilePreviewSize: 5192,
            previewClass: "bg-fileinput",
            preferIconicPreview: true,
            previewFileIconSettings: {
                'docx': '<i class="fas fa-file-word text-primary"></i>',
                'xlsx': '<i class="fas fa-file-excel text-success"></i>',
                'pptx': '<i class="fas fa-file-powerpoint text-danger"></i>',
                'pdf': '<i class="fas fa-file-pdf text-danger"></i>',
                'zip': '<i class="fas fa-file-archive text-muted"></i>',
                'mp4': '<i class="fas fa-file-video text-blue"></i>',
            },
            "fileActionSettings":{ "showZoom":false }
        });
        $('#fileWO_fg .file-caption').click(function(){
            $('#fileWO').trigger('click');
        });
    });

    // ICHECK PARA CAMBIAR DE ADJUNTO
    $('.cambioarchivo').iCheck({
        checkboxClass: 'icheckbox_square-yellow',
    }).on('ifChecked', function (event) {
        $('.divnewarchivo').slideDown();
        $('.divadjunto').slideUp();
        $('.cambioarchivo2').iCheck('check');
    }).on('ifUnchecked', function (event){
    });

    $('.cambioarchivo2').iCheck({
        checkboxClass: 'icheckbox_square-yellow',
    }).on('ifChecked', function (event) {
    }).on('ifUnchecked', function (event){
        $('.divnewarchivo').slideUp();
        $('.divadjunto').slideDown();
        $('.cambioarchivo').iCheck('uncheck');
    });
</script>
{{-- ===========================================================================================
                                        VALIDACION
=========================================================================================== --}}
<script>
    var campos = ['fileWO'];
    ValidateAjax("formAttachOrdenTrabajo",campos,"btnSubmitAttach","{{route( 'workorders.updateImage',code($workorder->id) )}}","POST","/work_orders/show/{{ code($workorder->id) }}");
</script>
@endsection