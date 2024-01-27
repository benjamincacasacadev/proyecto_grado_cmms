<link href="{{asset('/plugins/fileinput/css/fileinput.min.css')}}" media="all" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="{{asset('/plugins/iCheck/all.css')}}">
<style>
    .input-icon .form-control{
        height: 36px;
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

    .modal-content-aux {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 100%;
        pointer-events: auto;
        background-color: #fbfbfb;
        background-clip: padding-box;
        border: 1px solid transparent;
        border-radius: 4px;
        outline: 0;
    }
</style>

<div class="modal-header">
    <h5 class="modal-title"><i class="fa fa-edit"></i> Editar material {{ $item->cod }}</h5>
    <button type="button" class="btn-close text-yellow" data-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    {{Form::Open(array('action'=>array('InventoryController@update',code($item->id)),'method'=>'POST','autocomplete'=>'off','id'=>'formEditInventory'))}}
        <div class="row">
            <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                <label id="nombreedit--label">* Nombre</label>
                <input type="text" class="form-control" name="nombreedit" style="width:100%" placeholder="Nombre del material" value="{{ $item->title }}">
                <span id="nombreedit-error" class="text-red"></span>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label id="unidadedit--label">* Unidad de medida</label>
                <input type="text" class="form-control" name="unidadedit" style="width:100%" placeholder="Ej. metros..." value="{{ $item->unit }}">
                <span id="unidadedit-error" class="text-red"></span>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label id="cantidadminedit--label">* Cantidad mínima</label>
                <input type="text" class="form-control numero" name="cantidadminedit" style="width:100%" placeholder="Introduzca la cantidad mínima de items" value="{{ $item->min_cant }}">
                <span id="cantidadminedit-error" class="text-red"></span>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label id="descripcionedit--label">Descripción</label><br>
                    <textarea name="descripcionedit" class="form-control" style="width:100%;resize:none" rows="3">{!! purify($item->description) !!}</textarea>
                </div>
            </div>


            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                @if($swImg)
                    <b>Ver imagen adjunta</b>
                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" title="Ver Imagen">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 9h8v-3.586a1 1 0 0 1 1.707 -.707l6.586 6.586a1 1 0 0 1 0 1.414l-6.586 6.586a1 1 0 0 1 -1.707 -.707v-3.586h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1z" />
                    </svg>
                    <a href="/storage/inventory/{{ $item->attach }}" target="_blank">
                        <svg class="icon text-yellow" width="24" height="24" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <line x1="15" y1="8" x2="15.01" y2="8" />
                            <rect x="4" y="4" width="16" height="16" rx="3" />
                            <path d="M4 15l4 -4a3 5 0 0 1 3 0l5 5" />
                            <path d="M14 14l1 -1a3 5 0 0 1 3 0l2 2" />
                        </svg>
                    </a> <br>
                @endif
                <label class="text-yellow">
                    @if($swImg) Cambiar @else Adjuntar @endif imagen
                    @php
                        $textpopover ='data-toggle="popover" data-trigger="hover" data-content="<span style=\'font-size:11px\'>Al cambiar de imagen adjunta se <b>ELIMINARÁ</b> el que se guardó previamente para este registro </span>" data-title="<b>Información Importante</b>"';
                    @endphp
                    @if($swImg)
                        <span class="edithover form-help" {!! $textpopover !!}>?</span>
                    @endif
                </label>
                &nbsp;&nbsp; &nbsp; <input type="checkbox" class="cambioarchivo" name="cambioarchivo" value="1">
            </div>


            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 archivodiv" id="fileInventoryedit--label"style="display:none">
                <b>* Adjunte una imagen o fotografía del material registrado</b><br>
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
                <div id="fileInventoryEdit_fg" class="form-group" style="margin:0; padding:0" >
                    <input type="file" class="input-sm" id="fileInventoryedit" name="fileInventoryedit" data-max-size="5192" data-browse-on-zone-click="true" accept=".gif, .jpg, .jpeg, .png, .mp4">
                    <span id="fileInventoryedit-error" class="text-red"></span>
                </div>
            </div>


            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
                <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-yellow pull-right" name="btnSubmitEdit">Modificar</button>
            </div>
        </div>
    {{Form::Close()}}

        {{-- modal de Ver Imagenes --}}
        <div class="modal modalPrimary fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalImageEdit" data-backdrop="static">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content-aux">
                </div>
            </div>
        </div>
</div>

<script src="{{asset('/plugins/fileinput/js/fileinput.min.js')}}"></script>
<script src="{{asset('/plugins/iCheck/icheck.min.js')}}"></script>
<script>
    $(document).ready(function () {
        $(".select2-selection").addClass('form-select2').css('border-color','#ccc');
        $(".select2-selection--single").addClass('form-selectcont');
        $("#fileInventoryedit").fileinput({
            showUpload: false,
            allowedFileExtensions: ["gif","jpg","jpeg","png","mp4"],
            maxFileSize: 5192,
            // Máximo tamaño a previsualizar
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
        $('#fileInventoryEdit_fg .file-caption').click(function(){
            $('#fileInventoryedit').trigger('click');
        });
    });
    $('.selector-modal').select2({
        dropdownParent: $('#modalEditInventory')
    });
    $('select.selector-modal:not(.normal)').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent().parent()
        });
    });
    $('.inicial').iCheck({
        checkboxClass: 'icheckbox_square-yellow',
    }).on('ifChecked', function (event) {
        $("#cantidad,#fecha,#ubicacion").prop("disabled", false);
    }).on('ifUnchecked', function (event){
        $("#cantidad,#fecha,#ubicacion").prop("disabled", true);
    });
    $('.datepicker').datepicker({
        autoclose: true,
        width: '100%',
        format: 'dd/mm/yyyy',
        ignoreReadonly: true,
        todayHighlight: true
    });
    AutoNumeric.multiple('.numero',{
        modifyValueOnWheel: false,
        digitGroupSeparator : '',
        minimumValue: 0
    });

    // Funciones para cambiar imagen de activo
    modalAjax("modalImageEdit","modalImageEdit","modal-content-aux");
    $('.cambioarchivo').iCheck({
        checkboxClass: 'icheckbox_square-yellow',
    }).on('ifChecked', function (event) {
        $('.archivodiv').slideDown();
    }).on('ifUnchecked', function (event){
        $('.archivodiv').slideUp();
    });
    if ($(".cambioarchivo").is(':checked')) {
        $('.archivodiv').slideDown();
    }

    $('[data-toggle="popover"]').popover({
        html: true,
        "trigger" : "hover",
        "placement": "top",
        "container": "body",
    })

    var camposedit = ['nombreedit','unidadedit','cantidadminedit','descripcionedit','fileInventoryedit']
    ValidateAjax("formEditInventory",camposedit,"btnSubmitEdit","{{ route('inventory.update',code($item->id) )}}","POST","/inventory");
</script>
