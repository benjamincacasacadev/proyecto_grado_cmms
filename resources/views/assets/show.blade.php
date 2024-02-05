@extends ('layouts.admin', ['title_template' => "Activo: $asset->cod"])
@section('extracss')
    <style>
        .icon-tabler {
            width: 28px;
            height: 28px;
            stroke-width: 1.25;
        }
        table#tableWorkorders th{
            font-size:10px;
        }
        table#tableWorkorders td{
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
            <svg class="icon icon-tabler" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3"></polyline>
                <line x1="12" y1="12" x2="20" y2="7.5"></line>
                <line x1="12" y1="12" x2="12" y2="21"></line>
                <line x1="12" y1="12" x2="4" y2="7.5"></line>
            </svg>
            Ficha de {{ $asset->nombre }} - {{ $asset->cod }}
        </h1>
    </div>

    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="/assets" class="btn btn-outline-secondary border-secondary font-weight-bold" title="Ver listado de activos">
                <i class="far fa-list-alt fa-lg"></i> &nbsp;
                <span class="d-none d-sm-inline-block">
                    Ver activos
                </span>
            </a>
        </div>
    </div>
@endsection

@section ('contenido')
@php
$routeAttach = storage_path('app/public/assets/'.$asset->attach);
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
                            Datos generales de activo
                        </h3>
                        <table class="table table-condensed table-vcenter table-hover">
                            <tr>
                                <td class="font-weight-bold">Cliente</td>
                                <td>{!! $asset->cliente->nombre !!}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Nombre:</td>
                                <td>{{ $asset->nombre }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Categoria</td>
                                <td>{!! $asset->categoriaLiteral !!}</td>
                            </tr>

                            <tr>
                                <td class="font-weight-bold">Ubicación</td>
                                <td>{!! $asset->ubicacion !!}</td>
                            </tr>

                            <tr>
                                <td class="font-weight-bold">Ciudad</td>
                                <td>{!! $asset->ciudadLiteral !!}</td>
                            </tr>

                            <tr>
                                <td class="font-weight-bold">Número de serie</td>
                                <td>{!! $asset->nro_serie !!}</td>
                            </tr>

                            <tr>
                                <td class="font-weight-bold">Marca</td>
                                <td>{!! $asset->marca !!}</td>
                            </tr>

                            <tr>
                                <td class="font-weight-bold">Modelo</td>
                                <td>{!! $asset->modelo !!}</td>
                            </tr>

                            <tr>
                                <td class="font-weight-bold">Capacidad</td>
                                <td>{!! $asset->capacidad !!}</td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 animated fadeInRight" >
        <div class="card">
            <div class="card-status-top bg-yellow"></div>
            <div class="card-header">
                <h3 class="card-title pull-left" id="fileAsset--label">
                    <svg class="icon icon-tabler" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="15" y1="8" x2="15.01" y2="8" /><rect x="4" y="4" width="16" height="16" rx="3" /><path d="M4 15l4 -4a3 5 0 0 1 3 0l5 5" /><path d="M14 14l1 -1a3 5 0 0 1 3 0l2 2" />
                    </svg>
                    Imagen adjunta
                </h3>
            </div>
            <div class="card-body">
                @php $routeAttach = storage_path('app/public/assets/'.$asset->attach); @endphp
                @if (permisoAdminJefe())
                    {{Form::Open(array('action'=>array('StAssetsController@updateImage',code($asset->id)),'method'=>'POST','autocomplete'=>'off','id'=>'formAttachAsset'))}}
                        <div class="row">
                            <div class="text-center" id="divImageAttach">
                                @if (isset($asset->attach) && file_exists($routeAttach))
                                    @php    $spanCheck = 'Cambiar imagen';   @endphp
                                    <a href="/storage/assets/{{$asset->attach."?".rand()}}." target="_blank">
                                        <img src="/storage/assets/{{$asset->attach."?".rand()}}." style="max-height: 200px;margin-bottom:10px" alt="Sin imagen para mostrar" >
                                    </a>
                                @else
                                    @php    $spanCheck = 'Adjuntar imagen';   @endphp
                                    <img src="/storage/noimage.png?{{rand()}}" style="max-height: 200px;margin-bottom:10px" >
                                @endif
                            </div>
                            <div class="text-center" id="divFileInput" style="display:none">
                                <div style="text-align: left;">
                                    <b>Adjunte una imagen o fotografía del activo</b><br>
                                    <div style="padding-left: 25px;">
                                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4 9h8v-3.586a1 1 0 0 1 1.707 -.707l6.586 6.586a1 1 0 0 1 0 1.414l-6.586 6.586a1 1 0 0 1 -1.707 -.707v-3.586h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1z" />
                                        </svg>
                                        <b>Tipos de archivos soportados: </b>&ensp;.gif, .jpg, .jpeg, .png<br>

                                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4 9h8v-3.586a1 1 0 0 1 1.707 -.707l6.586 6.586a1 1 0 0 1 0 1.414l-6.586 6.586a1 1 0 0 1 -1.707 -.707v-3.586h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1z" />
                                        </svg>
                                        <b>Tamaño Máximo admitido: </b> 2 MB (2048 KB) <br>
                                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4 9h8v-3.586a1 1 0 0 1 1.707 -.707l6.586 6.586a1 1 0 0 1 0 1.414l-6.586 6.586a1 1 0 0 1 -1.707 -.707v-3.586h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1z" />
                                        </svg>
                                        Las imágenes subidas serán redimensionadas a un tamaño máximo de 1024*1024 píxeles.
                                    </div>
                                </div>
                                <div id="fileAsset_fg" class="form-group" style="font-size:12px !important" >
                                    <input type="file" class="input-sm" id="fileAsset" name="fileAsset" data-max-size="2048" data-browse-on-zone-click="true" accept=".gif, .jpg, .jpeg, .png, .mp4">
                                    <span id="fileAsset-error" class="text-red font-weight-bold"></span>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="font-size:12px !important">
                                    <button type="submit" class="btn btn-yellow" name="btnSubmitAttach">Guardar</button>
                                </div>
                            </div>
                            <div class="text-center" style="margin-top:20px">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="checkImageAttach" class="checkImageAttach" value="1" > <b><i> {{ $spanCheck }} </i></b>
                                    </label>
                                </div>
                            </div>
                        </div>
                    {{Form::Close()}}
                @else
                    <div class="text-center" id="divImageAttach">
                        @if (isset($asset->attach) && file_exists($routeAttach))
                            @php    $spanCheck = 'Cambiar imagen';   @endphp
                            <a href="/storage/assets/{{$asset->attach."?".rand()}}." target="_blank">
                                <img src="/storage/assets/{{$asset->attach."?".rand()}}." style="max-height: 200px;margin-bottom:10px" alt="Sin imagen para mostrar" >
                            </a>
                        @else
                            @php    $spanCheck = 'Adjuntar imagen';   @endphp
                            <img src="/storage/noimage.png?{{rand()}}" style="max-height: 200px;margin-bottom:10px" >
                        @endif
                    </div>
                @endif
            </div>
        </div>


    </div>
</div>

<div class="row mt-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 animated fadeInLeft">
        <div class="card mb-3">
            <div class="card-status-top bg-yellow"></div>
            <div class="card-header">
                <h3 class="card-title">
                    <svg class="icon icon-tabler" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5.697" /><path d="M18 14v4h4" /><path d="M18 11v-4a2 2 0 0 0 -2 -2h-2" /><rect x="8" y="3" width="6" height="4" rx="2" /><circle cx="18" cy="18" r="4" /><path d="M8 11h4" /><path d="M8 15h3" /></svg>
                    Órdenes de trabajo asociadas
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter table-center table-sm table-hover" id="tableWorkorders">
                        <thead role="row">
                            <tr>
                                <th class="text-center" width="10%;">ORDEN DE TRABAJO</th>
                                <th class="text-center" width="10%;">ESTADO</th>
                                <th class="text-center" width="10%;">TÍTULO</th>
                                <th class="text-center" width="15%;">TÉCNICOS ASIGNADOS</th>
                                <th class="text-center" width="10%;">DESCRIPCIÓN</th>
                                <th class="text-center" width="12%;">PRIORIDAD</th>
                                <th class="text-center" width="10%;">FECHA PROGRAMADA</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
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
    modalAjax("modalDuration","modalDuration","modal-content");
    modalAjax("modalCancel","modalCancel","modal-content");

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

        $("#fileAsset").fileinput({
            showUpload: false,
            allowedFileExtensions: ["gif","jpg","jpeg","png"],
            maxFileSize: 2048,
            maxFilePreviewSize: 2048,
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
        $('#fileAsset_fg .file-caption').click(function(){
            $('#fileAsset').trigger('click');
        });
    });

    // ICHECK QUITAR IMAGEN
    $('.checkImageAttach').iCheck({
        checkboxClass: 'icheckbox_square-yellow',
    }).on('ifChecked', function (event) {
        $('#divImageAttach').slideUp();
        $('#divFileInput').slideDown();
    }).on('ifUnchecked', function (event) {
        $('#divImageAttach').slideDown();
        $('#divFileInput').slideUp();
    });
</script>

{{-- ===========================================================================================
                                    VALIDACION IMAGEN
=========================================================================================== --}}
<script>
    var campos = ['fileAsset'];
    ValidateAjax("formAttachAsset",campos,"btnSubmitAttach","{{route( 'assets.updateImage',code($asset->id) )}}","POST","/assets/show/{{ code($asset->id) }}");
</script>

<script>
    var table = $('#tableWorkorders').DataTable({
        'paging': true,
        'lengthChange': true,
        'searching': true,
        'ordering': false,
        'info': true,
        'autoWidth': false,
        "order": [['0', 'desc']],
        'mark': "true",
        'dom': 'lrtip',
        "stateSave": false,
        "pageLength": 25,
        processing: true,
        serverSide: true,
        "columnDefs": [{
            "orderable": false,
            "targets": ["_all"]
        }],
        "ajax": {
            "url": "{{ route('workorders.table') }}",
            'dataType': 'json',
            'type': 'post',
            'data': {
                "_token": "{{ csrf_token() }}",
                assetId: "{{ $asset->id }}",
            },
            // "error": function(reason) {
            //     errorsDatatable(reason.status);
            // },
        },
        "columns": [
            {"data": "cod"},
            {"data": "estado"},
            {"data": "titulo"},
            {"data": "tecnicos"},
            {"data": "descripcion", "className": "left"},
            {"data": "prioridad"},
            {"data": "fecha"},
        ],
        "drawCallback": function () {
            restartActionsDT();
            $(document).ready(function () {
                $("[name=table_areas_length]").addClass('form-select');
            });

            $(function () {
                $('[data-toggle="popover"]').popover({
                    html: true,
                    "trigger" : "hover",
                    "placement": "top",
                    "container": "body",
                })

                $('[data-toggle="popoverOper"]').popover({
                    html: true,
                    "trigger" : "focus",
                    "placement": "left",
                    "container": "body",
                    delay: {
                        "hide": 200
                    }
                });
                $('[data-toggle="tooltip"]').tooltip({
                    html: true,
                    "trigger" : "hover",
                    "placement": "top",
                    "container": "body",
                });
            });
        }
    });
</script>

@endsection