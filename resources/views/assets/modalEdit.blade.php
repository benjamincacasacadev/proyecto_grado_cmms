<style>
    .divFormControl{
        border: 1px solid #dadcde;
        padding: 10px;
        background-color: #f4f6fa;
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
    .modal-body{
        font-size: 12px;
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
</style>
<link href="{{asset('/plugins/fileinput/css/fileinput.min.css')}}" media="all" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="{{asset('/plugins/iCheck/all.css')}}">
<div class="modal-header">
    <h5 class="modal-title font-weight-bold">
        <i class="fa fa-plus"></i>
        Editar activo: {{ $asset->cod }}
    </h5>
    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    {!! Form::open( array('route' =>'assets.store','method'=>'POST','autocomplete'=>'off','files'=>'true','id'=>'formCreateAssets', 'onsubmit'=>'btnSubmitEdit.disabled = true; return true;'))!!}
    <div class="row">
        {!! datosRegistro('edit') !!}

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group" id="cliente-sel2">
                <label id="clienteedit--label">* Cliente</label>
                <select class="form-control selector-clientsedit" name="clienteedit" data-placeholder="Busque y seleccione un cliente" style="width:100%">
                    <option value=""></option>
                    <option value="{{ code($asset->client_id) }}" selected>{{ $asset->cliente->nombre }}</option>
                </select>
                <span id="clienteedit-error" class="text-red"></span>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divInfoClients text-muted" style="display:none; font-style: italic; font-size: 12px;"></div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <label class="col-form-label" id="nombreedit--label">* Nombre de activo</label> <br>
                <input class="form-control" name="nombreedit" type="text" placeholder="Nombre de activo" value="{{ $asset->nombre }}">
                <span id="nombreedit-error" class="text-red"></span>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
            <div class="form-group" id="ciudad-sel2">
                <label id="ciudadedit--label">* Ciudad</label>
                <select name="ciudadedit" class="form-control form-select selector-modaledit" style="width: 100%">
                    <option value="" hidden>Seleccionar</option>
                    <option @if($asset->ciudad == '0') selected @endif value="0">Beni</option>
                    <option @if($asset->ciudad == '1') selected @endif value="1">Chuquisaca</option>
                    <option @if($asset->ciudad == '2') selected @endif value="2">Cochabamba</option>
                    <option @if($asset->ciudad == '3') selected @endif value="3">La Paz</option>
                    <option @if($asset->ciudad == '4') selected @endif value="4">Oruro</option>
                    <option @if($asset->ciudad == '5') selected @endif value="5">Pando</option>
                    <option @if($asset->ciudad == '6') selected @endif value="6">Potosi</option>
                    <option @if($asset->ciudad == '7') selected @endif value="7">Santa Cruz</option>
                    <option @if($asset->ciudad == '8') selected @endif value="8">Tarija</option>
                </select>
                <span id="ciudadedit-error" class="text-red"></span>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label class="col-form-label" id="ubicacionedit--label">* Ubicación del activo</label> <br>
                <input class="form-control" name="ubicacionedit" type="text" placeholder="Ubicación del activo" value="{{ $asset->ubicacion }}">
                <span id="ubicacionedit-error" class="text-red"></span>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
            <div class="form-group" id="categoria-sel2">
                <label id="categoriaedit--label">* Categoria</label>
                <select name="categoriaedit" class="form-control form-select selector-modaledit" style="width: 100%">
                    <option value="" hidden>Seleccionar</option>
                    <option @if($asset->categoria == '0') selected @endif value="0">Aire Acondicionado de confort</option>
                    <option @if($asset->categoria == '1') selected @endif value="1">Aires de precision</option>
                    <option @if($asset->categoria == '2') selected @endif value="2">Banco de baterias de litio</option>
                    <option @if($asset->categoria == '3') selected @endif value="3">Equipo inversor</option>
                    <option @if($asset->categoria == '4') selected @endif value="4">Equipo rectificador</option>
                    <option @if($asset->categoria == '5') selected @endif value="5">Equipo UPS</option>
                    <option @if($asset->categoria == '6') selected @endif value="6">Estabilizador</option>
                    <option @if($asset->categoria == '7') selected @endif value="7">Grupos Electrógenos</option>
                    <option @if($asset->categoria == '8') selected @endif value="8">Reconectador de media tension</option>
                    <option @if($asset->categoria == '9') selected @endif value="9">Tablero Banco de capacitores</option>
                    <option @if($asset->categoria == '10') selected @endif value="10">Tablero de transferencia ATS</option>
                </select>
                <span id="categoriaedit-error" class="text-red"></span>
            </div>
        </div>

        <div class="card mb-2 mt-1">
            <div class="card-body">
                <div class="row p-0">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <h3>DATOS DE ACTIVO</h3>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label id="nro_serie--label">* Número de serie</label>
                            <input class="form-control" type="text" name="nro_serie" placeholder="Número de serie" value="{{ $asset->nro_serie }}">
                            <span id="nro_serie-error" class="text-red"></span>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label id="marcaedit--label">* Marca</label>
                            <input class="form-control" type="text" name="marcaedit" placeholder="Marca" value="{{ $asset->marca }}">
                            <span id="marca-error" class="text-red"></span>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label id="modeloedit--label">* Modelo</label>
                            <input class="form-control" type="text" name="modeloedit" placeholder="Modelo" value="{{ $asset->modelo }}">
                            <span id="modeloedit-error" class="text-red"></span>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label id="capacidadedit--label">* Capacidad/Potencia</label>
                            <input class="form-control" type="text" name="capacidadedit" placeholder="Capacidad o potencia" value="{{ $asset->capacidad }}">
                            <span id="capacidadedit-error" class="text-red"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CAMBIAR ARCHIVO --}}
        @php
            $rutaarchivo = storage_path('app/public/assets/'.$asset->attach);
        @endphp
        {{-- CAMBIAR IMAGEN ADJUNTA DE ASSETS --}}
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="{{($asset->attach == '' || $asset->attach == null || !file_exists($rutaarchivo)) ? 'display:none' : ''}}">
            <b>Ver imagen adjunta</b>
            <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" title="Ver Imagen">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M4 9h8v-3.586a1 1 0 0 1 1.707 -.707l6.586 6.586a1 1 0 0 1 0 1.414l-6.586 6.586a1 1 0 0 1 -1.707 -.707v-3.586h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1z" />
            </svg>
            <a href="/storage/assets/{{ $asset->attach }}" target="_blank">
                <svg class="icon text-yellow" width="24" height="24" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <line x1="15" y1="8" x2="15.01" y2="8" />
                    <rect x="4" y="4" width="16" height="16" rx="3" />
                    <path d="M4 15l4 -4a3 5 0 0 1 3 0l5 5" />
                    <path d="M14 14l1 -1a3 5 0 0 1 3 0l2 2" />
                </svg>
            </a><br>
            <label class="text-yellow">
                Cambiar imagen
                @php
                    $textpopover ='data-toggle="popover" data-trigger="hover" data-content="<span style=\'font-size:11px\'>Al cambiar de iamgen adjunta se <b>ELIMINARÁ</b> el que se guardó previamente para este registro </span>" data-title="<b>Información Importante</b>"';
                @endphp
                <span class="edithover form-help" {!! $textpopover !!}>?</span>
            </label>
            &nbsp;&nbsp; &nbsp; <input type="checkbox" class="cambioarchivo" name="cambioarchivo" value="1">
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 archivodiv" id="fileAssetsedit--label" style="{{($asset->attach != '' && $asset->attach != null && file_exists($rutaarchivo))? 'display:none' : ''}}">
            <span class="titleFile font-weight-bold">Adjunte una imagen o fotografía del activo registrado <i>(Opcional)</i></span>
            <span id="fileRequired" class="font-weight-bold" style="display: none">* Adjunte una imagen o fotografía del activo registrado</span>
            <br>
            <div style="padding-left: 25px;">
                <b><svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path
                            d="M4 9h8v-3.586a1 1 0 0 1 1.707 -.707l6.586 6.586a1 1 0 0 1 0 1.414l-6.586 6.586a1 1 0 0 1 -1.707 -.707v-3.586h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1z" />
                    </svg>Tipos de archivos soportados:</b>&nbsp;&nbsp;.jpg, .jpeg, .png<br>
                <b><svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path
                            d="M4 9h8v-3.586a1 1 0 0 1 1.707 -.707l6.586 6.586a1 1 0 0 1 0 1.414l-6.586 6.586a1 1 0 0 1 -1.707 -.707v-3.586h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1z" />
                    </svg>Tamaño Máximo admitido: </b>2 MB (2048 KB)<br>
                <b><svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path
                            d="M4 9h8v-3.586a1 1 0 0 1 1.707 -.707l6.586 6.586a1 1 0 0 1 0 1.414l-6.586 6.586a1 1 0 0 1 -1.707 -.707v-3.586h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1z" />
                    </svg></b> Las imágenes subidas serán redimensionadas a un tamaño máximo de 1024*1024 píxeles.
            </div>
            <div id="fileAssets_fg" class="form-group" style="margin:0; padding:0" >
                <input type="file" class="input-sm" id="fileAssetsedit" name="fileAssetsedit" data-max-size="2048" data-browse-on-zone-click="true" accept=".jpg, .jpeg, .png, .mp4">
                <span id="fileAssetsedit-error" class="text-red fileAssetsedit-error"></span>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-3">
            <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary pull-right" name="btnSubmitEdit">Modificar</button>
        </div>
    </div>
    {{Form::Close()}}
</div>

<script src="{{asset('/plugins/fileinput/js/fileinput.min.js')}}"></script>
<script src="{{asset('/plugins/iCheck/icheck.min.js')}}"></script>
<script>

    $(document).ready(function () {
        $(".select2-selection").addClass('form-select2').css('border-color','#ccc');
        $(".select2-selection--single").addClass('form-selectcont');

        $("#fileAssetsedit").fileinput({
            // Validación del tipo de archivo (Incluye Drag and Drop)
            showUpload: false,
            allowedFileExtensions: ["jpg","jpeg","png"],
            // Validación del tamaño de archivo máximo a subir (Incluye Drag and Drop)
            maxFileSize: 2048,
            // Máximo tamaño a previsualizar
            maxFilePreviewSize: 2048,
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

        $('[data-toggle="popover"]').popover({
            html: true,
            "trigger" : "hover",
            "placement": "top",
            "container": "body",
        });
    });

    $('select.selector-modaledit:not(.normal)').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent().parent()
        });
    });

    // *******************************************************************************************
    // SELECT2 AJAX CLIENTES
    $('select.selector-clientsedit:not(.normal)').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent().parent(),
            ajax: {
                url: "{{ route('clients.listClients') }}",
                dataType: 'json',
                method: "GET",
                delay: 250,
                data: function (params) {
                    var query = {
                        search: params.term,
                        page: params.page || 5
                    }
                    return query;
                },
            }
        });
    });

    $('.cambioarchivo').iCheck({
        checkboxClass: 'icheckbox_square-yellow',
    }).on('ifChecked', function (event) {
        $('.titleFile').hide();
        $('#fileRequired').show();
        $('.archivodiv').slideDown();
    }).on('ifUnchecked', function (event){
        $('.archivodiv').slideUp();
    });

    var campos = ['clienteedit','nombreedit','ciudadedit','ubicacionedit','categoriaedit','nro_serie','marcaedit','modeloedit','capacidadedit','fileAssetsedit'];
    ValidateAjax("formCreateAssets",campos,"btnSubmitEdit","{{ route('assets.update',code($asset->id) )}}","POST","/assets");
</script>