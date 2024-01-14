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
<link href="{{asset('/plugins/fileinput/css/fileinput.min.css')}}" media="all" rel="stylesheet" type="text/css" />
{!! Form::open( array('route' =>'assets.store','method'=>'POST','autocomplete'=>'off','files'=>'true','id'=>'formCreateAssets', 'onsubmit'=>'btnSubmit.disabled = true; return true;'))!!}
<div class="row">
    {!! datosRegistro('create') !!}

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group" id="cliente-sel2">
            <label id="cliente--label">* Cliente</label>
            <select class="form-control selector-clients" name="cliente" data-placeholder="Busque y seleccione un cliente" style="width:100%">
                <option value=""></option>
            </select>
            <span id="cliente-error" class="text-red"></span>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divInfoClients text-muted" style="display:none; font-style: italic; font-size: 12px;"></div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="col-form-label" id="nombre--label">* Nombre de activo</label> <br>
            <input class="form-control" name="nombre" type="text" placeholder="Nombre de activo">
            <span id="nombre-error" class="text-red"></span>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
        <div class="form-group" id="ciudad-sel2">
            <label id="ciudad--label">* Ciudad</label>
            <select name="ciudad" class="form-control form-select selector-modal" style="width: 100%">
                <option value="" hidden>Seleccionar</option>
                <option value="0">Beni</option>
                <option value="1">Chuquisaca</option>
                <option value="2">Cochabamba</option>
                <option value="3">La Paz</option>
                <option value="4">Oruro</option>
                <option value="5">Pando</option>
                <option value="6">Potosi</option>
                <option value="7">Santa Cruz</option>
                <option value="8">Tarija</option>
            </select>
            <span id="ciudad-error" class="text-red"></span>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="col-form-label" id="ubicacion--label">* Ubicación del activo</label> <br>
            <input class="form-control" name="ubicacion" type="text" placeholder="Ubicación del activo">
            <span id="ubicacion-error" class="text-red"></span>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
        <div class="form-group" id="categoria-sel2">
            <label id="categoria--label">* Categoria</label>
            <select name="categoria" class="form-control form-select selector-modal" style="width: 100%">
                <option value="" hidden>Seleccionar</option>
                <option value="0">Aire Acondicionado de confort</option>
                <option value="1">Aires de precision</option>
                <option value="2">Banco de baterias de litio</option>
                <option value="3">Equipo inversor</option>
                <option value="4">Equipo rectificador</option>
                <option value="5">Equipo UPS</option>
                <option value="6">Estabilizador</option>
                <option value="7">Grupos Electrógenos</option>
                <option value="8">Reconectador de media tension</option>
                <option value="9">Tablero Banco de capacitores</option>
                <option value="10">Tablero de transferencia ATS</option>
            </select>
            <span id="categoria-error" class="text-red"></span>
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
                        <input class="form-control" type="text" name="nro_serie" placeholder="Número de serie">
                        <span id="nro_serie-error" class="text-red"></span>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label id="marca--label">* Marca</label>
                        <input class="form-control" type="text" name="marca" placeholder="Marca">
                        <span id="marca-error" class="text-red"></span>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label id="modelo--label">* Modelo</label>
                        <input class="form-control" type="text" name="modelo" placeholder="Modelo">
                        <span id="modelo-error" class="text-red"></span>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label id="capacidad--label">* Capacidad/Potencia</label>
                        <input class="form-control" type="text" name="capacidad" placeholder="Capacidad o potencia">
                        <span id="capacidad-error" class="text-red"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 archivodiv" id="fileAssets--label" style="margin-top:10px">
        <b>Adjunte una imagen o fotografía del activo registrado <i>(Opcional)</i> </b><br>
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
            <input type="file" class="input-sm classfileAssets" id="fileAssets" name="fileAssets" data-max-size="2048" data-browse-on-zone-click="true" accept=".jpg, .jpeg, .png, .mp4">
            <span id="fileAssets-error" class="text-red"></span>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-3">
        <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary pull-right" name="btnSubmit">Registrar</button>
    </div>
</div>
{{Form::Close()}}

<script src="{{asset('/plugins/fileinput/js/fileinput.min.js')}}"></script>
<script>

    $(document).ready(function () {
        $(".select2-selection").addClass('form-select2').css('border-color','#ccc');
        $(".select2-selection--single").addClass('form-selectcont');


        $(".classfileAssets").fileinput({
            showUpload: false,
            allowedFileExtensions: ["jpg","jpeg","png"],
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
        $('#fileAssets_fg .file-caption').click(function(){
            $('#fileAssets').trigger('click');
        });
    });

    $('select.selector-modal:not(.normal)').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent().parent()
        });
    });

    // *******************************************************************************************
    // SELECT2 AJAX CLIENTES
    $('select.selector-clients:not(.normal)').each(function () {
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

    var campos = ['cliente','nombre','ciudad','ubicacion','categoria','nro_serie','marca','modelo','capacidad','fileAssets'];
    ValidateAjax("formCreateAssets",campos,"btnSubmit","{{route('assets.store')}}","POST","/assets");
</script>