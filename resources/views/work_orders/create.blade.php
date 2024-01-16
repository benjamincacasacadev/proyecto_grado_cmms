@extends ('layouts.admin', ['title_template' => "Crear orden de trabajo"])
@section('extracss')
    <style>
        @media (max-width:1560px) {
            .col-mdx-12 {
                flex: 0 0 auto !important;
                width: 100% !important
            }
            .mbr-aux{
                margin-top: 0.6rem !important;
            }
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
        .blink_me {
            animation: blinker 3s linear infinite;
        }
        .blink_me2 {
            animation: blinker 5s linear infinite;
        }
        @keyframes  blinker {
            50% {
                opacity: 0;
            }
        }
        .icon{
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

        table#tableAccessUser th{
            font-size:12px;
        }
        table#tableAccessUser td{
            font-size: 12px !important;
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
            <i class="fas fa-clipboard-list fa-md"></i>&nbsp;Crear orden de trabajo
        </h1>
    </div>

    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="/work_orders" title="Ver todas las ordenes de trabajo" type="button" class="btn btn-outline-secondary border border-secondary font-weight-bold">
                <i class="fa fa-list-ul"></i> &nbsp;
                <span class="d-none d-sm-inline-block">
                    Ver órdenes de trabajo
                </span>
            </a>
        </div>
    </div>
@endsection

@section ('contenido')
{!! Form::open( array('route' =>'workorders.store','method'=>'POST','autocomplete'=>'off','files'=>'true','id'=>'formCreateOrdenTrabajo', 'onsubmit'=>'btnSubmit.disabled = true; return true;'))!!}
<div class="row">
    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-1 animated fadeInLeft" >
        <div class="row row-cards">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card" >
                    <div class="card-status-top bg-yellow"></div>
                    <div class="card-body">
                        <h3 class="card-title pull-left mb-0">
                            <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="4" width="18" height="4" rx="2" /><path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-10" /><line x1="10" y1="12" x2="14" y2="12" /></svg>
                            Datos generales
                        </h3> <br><br>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group" id="activo-sel2">
                                    <label id="activo--label">* Activo</label>
                                    <select name="activo" class="form-control selector-activo" style="width:100%">
                                        <option value="">Seleccionar</option>
                                    </select>
                                    <span id="activo-error" class="text-red"></span>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divInfoAsset" style="display:none"></div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="d-flex">
                                        <div class="mr-auto">
                                            <label id="titulo--label">* Título</label> <br>
                                        </div>
                                        <div>
                                            <label>
                                                <span class="cursor-pointer text-red">Emergencia</span>&nbsp;
                                                <input type="checkbox" id="checkEmerg" name="emergency" value="E">
                                            </label>
                                        </div>
                                    </div>
                                    <input class="form-control" name="titulo" type="text" placeholder="Titulo de orden de trabajo">
                                    <span id="titulo-error" class="text-red"></span>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group" id="formulario-sel2">
                                    @php
                                        $textPopoverHoraTrabajo = 'data-toggle="popover" data-trigger="hover" data-content="<span style=\'font-size:10px\'>Puede registrar una orden de trabajo sin asignar el formulario, pero debe asignarlo antes de iniciar el trabajo </span>" data-title="<b style=\'font-size:13px\'>Información</b>"';
                                    @endphp
                                    <label id="formulario--label">
                                        Formulario <a {!! $textPopoverHoraTrabajo !!}> <i class="fa fa-info-circle"></i></a>
                                    </label> <br>
                                    <select name="formulario" class="form-control selector-formulario" style="width: 100%">
                                        <option value="" data-mail="Sin usuario">Seleccionar</option>
                                        @foreach($forms as $form)
                                            <option value="{!! code($form->id) !!}"> {{ $form->name }} </option>
                                        @endforeach
                                    </select>
                                    <span id="formulario-error" class="text-red"></span>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label id="fecha_ven--label">
                                        <span>* Fecha programada de mantenimiento</span>
                                    </label> <br>
                                    <div class='input-group date datetimepicker p-0'>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                        <input type='text' class="form-control" name="fecha_ven" placeholder="dd/mm/YYYY HH:mm" id="fechaMantenimiento" />
                                    </div>
                                    <span id="fecha_ven-error" class="text-red"></span>
                                    <div><b id="fechaFin-label" class="text-yellowdark" style="display:none"></b></div>
                                </div>
                            </div>

                            {{-- =========================================================================================================== --}}
                            {{--                                                PRIORIDAD                                                    --}}
                            {{-- =========================================================================================================== --}}
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                <div class="form-group">
                                    <label id="prioridad--label" class="col-form-label">* Prioridad</label>
                                    <div class="checkbox  text-center">
                                        <label><input type="radio" id="pri_none" name="prioridad" value="0"> <b class="text-primary">Ninguna</b>  </label>
                                        <label><input type="radio" id="pri_low"  name="prioridad" value="1"> <b class="text-green">Baja</b>  </label>
                                        <label><input type="radio" id="pri_med"  name="prioridad" value="2"> <b class="text-yellow">Media</b>  </label>
                                        <label><input type="radio" id="pri_high" name="prioridad" value="3"> <b class="text-orange">Alta</b>  </label>
                                    </div>
                                    <center><span id="prioridad-error" class="text-red font-weight-bold"></span></center>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label id="descripcion--label" class="col-form-label">* Descripción</label> <br>
                                    <textarea name="descripcion" class="form-control" style="width:100%; resize:none;" rows="4" placeholder="Descripción de la orden de trabajo"></textarea>
                                    <span id="descripcion-error" class="text-red"></span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 animated fadeInUp">
        <div class="row row-cards">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-status-top bg-yellow"></div>
                    <div class="card-body">
                        <h3 class="card-title">
                            <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="7" r="4" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                            Técnicos a cargo
                        </h3>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group" id="tecresponsable-sel2">
                                    <label id="tecresponsable--label">* Técnico responsable</label> <br>
                                    <select name="tecresponsable" class="form-control selector-usuario" style="width: 100%">
                                        <option value="" data-mail="Sin usuario">Seleccionar</option>
                                        @foreach($users as $user)
                                            <option value="{!! $user->id !!}"> {{ $user->fullName }} </option>
                                        @endforeach
                                    </select>
                                    <span id="tecresponsable-error" class="text-red"></span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group" id="asignados-sel2">
                                    <label id="asignados--label">Técnicos adicionales</label> <br>
                                    <select class="form-control selector-usuario" name="asignados[]" multiple="multiple" data-placeholder="Seleccione uno o más" style="width:100%">
                                        @foreach($users as $user)
                                            <option value="{!! $user->id !!}"> {{ $user->fullName }} </option>
                                        @endforeach
                                    </select>
                                    <span id="asignados-error" class="text-red"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-status-top bg-yellow"></div>
                    <div class="card-body">
                        <h3 class="card-title">
                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="12" y1="11" x2="12" y2="17" /><polyline points="9 14 12 11 15 14" /></svg>
                            Archivo Adjunto
                        </h3>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divArchivo" id="fileWO--label">
                                <label>Adjunte una imagen o pdf de la orden de trabajo (Opcional)</label><br>
                                <div style="padding-left: 25px;" class="mb-3">
                                    <b><svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M4 9h8v-3.586a1 1 0 0 1 1.707 -.707l6.586 6.586a1 1 0 0 1 0 1.414l-6.586 6.586a1 1 0 0 1 -1.707 -.707v-3.586h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1z" />
                                        </svg>Tipos de archivos soportados:</b>&nbsp;&nbsp;.jpg, .jpeg, .png, .pdf<br>
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

                                <div id="fileWO_fg" class="form-group" style="margin:0; padding:0" >
                                    <input type="file" class="input-sm" id="fileWO" name="fileWO" data-max-size="5192" data-browse-on-zone-click="true" accept=".jpg, .jpeg, .png, .pdf">
                                    <span id="fileWO-error" class="text-red"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center mt-3 mb-3">
        <button type="submit" class="btn btn-yellow btn-lg" name="btnSubmit">Registrar</button>
    </div>
</div>
{{Form::Close()}}

@endsection

@section('scripts')
<script src="{{asset('/plugins/fileinput/js/fileinput.min.js')}}"></script>
<script src="{{asset('/plugins/iCheck/icheck.min.js')}}"></script>
<script>
    $('#checkEmerg').iCheck({
        checkboxClass: 'icheckbox_square-red',
        increaseArea: '20%'
    });

    var prior = ["pri_none","pri_low","pri_med","pri_high"];
    var color = ["blue","green","yellow","red"]
    $.each(prior, function( indice, valor ) {
        $('#'+valor).iCheck({
            radioClass: 'iradio_square-'+color[indice],
        });
    })

    $('.datetimepicker').datetimepicker({
        format: 'dd/mm/yyyy hh:ii',
        autoclose: true,
        startDate: '{{now()}}',
    });

    $(function () {
        $('[data-toggle="popover"]').popover({
            html: true,
            "trigger" : "hover",
            "placement": "top",
            "container": "body",
        })
    });

    $('select.selector-usuario:not(.normal)').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent().parent(),
            placeholder: 'Seleccione un usuario',
            width: '100%',
        });
    });

    $('select.selector-formulario:not(.normal)').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent().parent(),
            placeholder: 'Seleccione un formulario',
            width: '100%',
        });
    });

    $("#fileWO").fileinput({
        showUpload: false,
        allowedFileExtensions: ["jpg","jpeg","png","pdf"],
        maxFileSize: 5192,
        maxFilePreviewSize: 5192,
        previewClass: "bg-fileinput",
        preferIconicPreview: true,
        previewFileIconSettings: {
            'docx': '<i class="fas fa-file-word text-yellow"></i>',
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
</script>

<script>
    var campos = ['activo','emergency','titulo','formulario','fecha_ven','prioridad','descripcion','tecresponsable','asignados','fileWO'];
    ValidateAjax("formCreateOrdenTrabajo",campos,"btnSubmit","{{route('workorders.store')}}","POST","/work_orders");
</script>

{{-- SELECT 2 ACTIVOS --}}
<script>
    function formatState (state) {
        var info;
        if (typeof state.info === 'undefined') {
            info = "";
        }else{
            info = state.info;
        }

        var $state = $(
            '<span><b>' + state.text + '</b>' + info + '</span>'
        );
        return $state;
    };

    $('select.selector-activo:not(.normal)').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent().parent(),
            placeholder:'Busque y seleccione un activo por código o nombre',
            templateResult: formatState,
            ajax: {
                url: "{{ route('assets.listAssets.details') }}",
                dataType: 'json',
                method: "GET",
                delay: 500,
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 5
                    }
                    return query;
                },
                processResults: function(data, params){
                    data = data.results.map(function (item) {
                        return {
                            id: item.id,
                            text: item.text,
                            info: item.info,
                        };
                    });
                    return { results: data };
                },
            }
        });
    });

    $('.selector-activo').change(function () {
        var val = $(this).val();
        var data = $('.selector-activo').select2('data')[0];
        var info;
        if (typeof data.info === 'undefined') {
            info = "";
            $(".divInfoAsset").hide();
            $(".divInfoAsset").removeClass('mb-2');
        }else{
            info = data.info;
            $(".divInfoAsset").show();
            $(".divInfoAsset").addClass('mb-2');
        }
        $(".divInfoAsset").html(info);
    });
</script>
@endsection