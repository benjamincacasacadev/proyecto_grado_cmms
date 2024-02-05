@extends ('layouts.admin', ['title_template' => "Kardex - $item->title"])
@section('extracss')
    <style>
        table#table_requests th{
            font-size:12px;
        }
        table#table_requests td{
            font-size: 12px !important;
        }
        .just{
            text-align: justify !important;
            font-size: 11px !important;
        }
        .left{
            text-align: left !important;
        }
        .icon-tabler {
            width: 25px;
            height: 25px;
            stroke-width: 1.25;
            margin-bottom: 2px;
        }
        .blink_me {
            animation: blinker 1s linear infinite;
        }
        @keyframes  blinker {
            50% {
                opacity: 0;
            }
        }
        .icon-alert{
            padding-top:3px;
        }
        .datos {
            font-weight: 600;
        }
        .accordion-button::after{
            display: none;
        }

        /** FILEINPUT **/
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
        @media  (max-width: 1280px){
            .krajee-default.file-preview-frame {
                left: 20%;
            }
        }
        @media  (max-width: 1100px){
            .krajee-default.file-preview-frame {
                left: 0%;
            }
        }
        .file-caption-main, .kv-error-close{
            display: none !important;
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
            <img class="icon icon-tabler" src="{{asset('imagenes/screw.svg')}}" width="20" height="20" >
            Kardex - {{ $item->title }}
        </h1>
    </div>

    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="/inventory" title="Ver todas las ordenes de trabajo" type="button" class="btn btn-outline-secondary border border-secondary font-weight-bold">
                <i class="fa fa-list-ul"></i> &nbsp;
                <span class="d-none d-sm-inline-block">
                    Ver materiales
                </span>
            </a>
        </div>
    </div>
@endsection

@section ('contenido')
<div class="row">
    {{-- ========================================================================================================== --}}
    {{--                                                CABECERA                                                    --}}
    {{-- ========================================================================================================== --}}
    <div class="col-12 animated zoomIn mb-3">
        <div class="card">
            <div class="card-status-top bg-yellow"></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 invoice-col">
                        <address>
                            <span style="font-size:20px;">
                                <strong>Código: {{ $item->cod}} </strong>
                            </span><br>
                            Unidad de medida: <span class="datos">{{ $item->unit }}</span><br>
                            Cantidad mínima: <span class="datos">{{ $item->min_cant }}</span><br>
                            Fecha de Registro: <span class="datos">{{date("d/m/Y H:i:s", strtotime($item->created_at))}}</span><br>
                            Descripción: <span class="datos">{!! purify(nl2br($item->description)) !!}</span><br>
                        </address>
                    </div>
                    <div class="col-lg-6">
                        <address style="text-align: right">
                            <img src="data:image/png;base64, {!!
                                base64_encode(
                                    QrCode::format('png')
                                    ->color(46, 46, 44)
                                    ->size(185)
                                    ->margin(0)
                                    ->errorCorrection('H')
                                    ->generate($qrcode)
                                ) !!}"
                            >
                        </address>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================================================================================== --}}
    {{--                                                TABLA KARDEX                                                --}}
    {{-- ========================================================================================================== --}}
    <div class="col-12 animated zoomIn mb-3">
        <div class="card">
            <div class="card-status-top bg-yellow"></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter table-center table-sm table-hover" id="table_details">
                        <thead>
                            <tr>
                                <th width="3%"  rowspan="2">Nº</th>
                                <th width="10%" rowspan="2">Fecha</th>
                                <th width="15%" rowspan="2">Origen</th>
                                <th width="15%" rowspan="2">Ubicación</th>
                                <th colspan="3">CANTIDADES</th>
                            </tr>

                            <tr>
                                <th width="8%">Entradas</th>
                                <th width="8%">Salidas</th>
                                <th width="8%" style="color: #656d77 !important">Saldo</th>
                            </tr>
                        </thead>
                        <tbody >
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================================================================================== --}}
    {{--                                                IMAGEN ADJUNTA                                              --}}
    {{-- ========================================================================================================== --}}
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 animated fadeInLeft">
        <div class="card">
            <div class="card-status-top bg-yellow"></div>
            <div class="card-header">
                <h3 class="card-title pull-left text-yellow ">
                    <b>Imagen adjunta&nbsp;</b>
                </h3>
            </div>
            <div class="card-body">
                @php $routeAttach = storage_path('app/public/inventory/'.$item->attach); @endphp
                @if (permisoAdminJefe())
                    {{Form::Open(array('action'=>array('InventoryController@updateImage',code($item->id)),'method'=>'POST','autocomplete'=>'off','id'=>'formAttachItems'))}}
                        <div class="row">
                            <div class="text-center" id="divImageAttach">
                                @if (isset($item->attach) && file_exists($routeAttach))
                                    @php    $spanCheck = 'Cambiar imagen';   @endphp
                                    <a href="/storage/inventory/{{$item->attach."?".rand()}}." target="_blank">
                                        <img src="/storage/inventory/{{$item->attach."?".rand()}}." style="max-height: 250px;margin-bottom:10px" alt="Sin imagen para mostrar" >
                                    </a>
                                @else
                                    @php    $spanCheck = 'Adjuntar imagen';   @endphp
                                    <img src="/storage/noimage.png?{{rand()}}" style="max-height: 250px;margin-bottom:10px" >
                                @endif
                            </div>
                            <div class="text-center" id="divFileInput" style="display:none">
                                <div style="text-align: left;">
                                    <b>Adjunte una imagen o fotografía del material</b><br>
                                    <div style="padding-left: 25px;">
                                        <b><svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M4 9h8v-3.586a1 1 0 0 1 1.707 -.707l6.586 6.586a1 1 0 0 1 0 1.414l-6.586 6.586a1 1 0 0 1 -1.707 -.707v-3.586h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1z" />
                                            </svg>Tipos de archivos soportados:</b>&nbsp;&nbsp;.gif, .jpg, .jpeg, .png<br>
                                        <b><svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M4 9h8v-3.586a1 1 0 0 1 1.707 -.707l6.586 6.586a1 1 0 0 1 0 1.414l-6.586 6.586a1 1 0 0 1 -1.707 -.707v-3.586h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1z" />
                                            </svg>Tamaño Máximo admitido: </b> 5 MB (5192 KB) <br>
                                        <b><svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M4 9h8v-3.586a1 1 0 0 1 1.707 -.707l6.586 6.586a1 1 0 0 1 0 1.414l-6.586 6.586a1 1 0 0 1 -1.707 -.707v-3.586h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1z" />
                                            </svg></b> Las imágenes subidas serán redimensionadas a un tamaño máximo de 1024*1024 píxeles.
                                    </div>
                                </div>
                                <div id="fileItems_fg" class="form-group" style="font-size:12px !important" >
                                    <input type="file" class="input-sm" id="fileItems" name="fileItems" data-max-size="5192" data-browse-on-zone-click="true" accept=".gif, .jpg, .jpeg, .png, .mp4">
                                    <span id="fileItems-error" class="text-red font-weight-bold"></span>
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
                        @if (isset($item->attach) && file_exists($routeAttach))
                            @php    $spanCheck = 'Cambiar imagen';   @endphp
                            <a href="/storage/inventory/{{$item->attach."?".rand()}}." target="_blank">
                                <img src="/storage/inventory/{{$item->attach."?".rand()}}." style="max-height: 250px;margin-bottom:10px" alt="Sin imagen para mostrar" >
                            </a>
                        @else
                            @php    $spanCheck = 'Adjuntar imagen';   @endphp
                            <img src="/storage/noimage.png?{{rand()}}" style="max-height: 250px;margin-bottom:10px" >
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ========================================================================================================== --}}
    {{--                                           CANTIDADES POR Ubicación                                         --}}
    {{-- ========================================================================================================== --}}
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 animated fadeInRight">
        <div class="card">
            <div class="card-status-top bg-yellow"></div>
            <div class="card-header">
                <h3 class="card-title pull-left text-yellow ">
                    <b>Cantidades por almacen&nbsp;</b>
                </h3>
            </div>
            <div class="card-body">

                <table class="table table-vcenter table-center">
                    <thead>
                        <th>Almacen</th>
                        <th>Cantidad</th>
                    </thead>
                    @foreach ($details as $detail)
                        @php
                            $cantidadDet = $detail->ingresos - $detail->egresos;
                        @endphp
                        @if ($cantidadDet > 0)
                            <tr>
                                <td>{{ $detail->almacenLiteral }}</td>
                                <td>{{ number_format($cantidadDet,2,".","") }}</td>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td class="font-weight-bold btn-lg">Total:</td>
                        <td class="btn-lg"><i>{{ number_format($item->TotalItem,2,".","") }}</i> </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
    {{-- Modal Crear --}}
    <div class="modal modalPrimary fade modal-slide-in-right" aria-hidden="true" role="dialog"  id="modalCreate" data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fa fa-plus"></i>
                        Nuevo material
                    </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>

    {{-- modal Show de traspaso --}}
    <div class="modal modalPrimary fade modal-slide-in-right" aria-hidden="true" role="dialog" id="modalShow" data-backdrop="static">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script src="{{asset('/plugins/fileinput/js/fileinput.min.js')}}"></script>
<script src="{{asset('/plugins/iCheck/icheck.min.js')}}"></script>

<script>
    // modal Create
    modalAjax("modalCreate","modalCreate","modal-body");
    // modal Show de traspaso
    modalAjax("modalShow","modalShow","modal-content");

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

    $("#fileItems").fileinput({
        showUpload: false,
        allowedFileExtensions: ["gif","jpg","jpeg","png"],
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
        },
        "fileActionSettings":{ "showZoom":true }
    });
    $('#fileItems_fg .file-caption').click(function(){
        $('#fileItems').trigger('click');
    });

    $(function () {
        var item = "{{ code($item->id) }}";
        var table = $('#table_details').DataTable({
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': false,
            'info': true,
            'autoWidth': false,
            "order": [['0', 'desc']],
            'mark': "true",
            'dom': 'lrtip',
            "pageLength": 25,
            processing: true,
            serverSide: true,
            "columnDefs": [{
                "orderable": false,
                "targets": ["_all"]
            }],
            "ajax": {
                "url": "{{ route('inventory.details.table') }}",
                'dataType': 'json',
                'type': 'post',
                'data': {
                    "_token": "{{ csrf_token() }}",
                    item: item
                },
                "error": function(reason) {
                    errorsDatatable(reason.status);
                },
            },
            "columns": [
                { "data": "orden"},
                {"data": "date"},
                {"data": "origin"},
                {"data": "location"},
                {"data": "in"},
                {"data": "out"},
                {"data": "balance",'className':'text-yellow font-weight-bold'},
            ],
            "drawCallback": function () {
                $(function () {
                    $('[data-toggle="popover"]').popover({
                        html: true,
                        "trigger" : "hover",
                        "placement": "left",
                        "container": "body",
                    });
                    $('[data-toggle="popoverOper"]').popover({
                        html: true,
                        "trigger" : "hover",
                        "placement": "left",
                        "container": "body",
                        delay: {
                            show: "200",
                            hide: "2000"
                        }
                    });

                    $('[data-toggle="tooltip"]').tooltip({
                        html: true
                    });
                });
                modalAjax("modalEdit","modalEdit","modal-content");
                modalAjax("modalDelete","modalDelete","modal-content");
            }
        });

        table.columns().eq(0).each(function (colIdx) {
            $('input', $('.filters td')[colIdx]).on('keyup', function () {
                table.column(colIdx).search(this.value).draw();
            });
        });
    });

    $('.fileinput-remove').click(function(){
        $('.checkImageAttach').iCheck('uncheck');
    })
</script>

{{-- ===========================================================================================
                                    VALIDACION IMAGEN
=========================================================================================== --}}
<script>
    var campos = ['fileItems'];
    ValidateAjax("formAttachItems",campos,"btnSubmitAttach","{{route( 'inventory.updateImage',code($item->id) )}}","POST","/inventory/kardex/{{ code($item->id) }}");
</script>

@endsection