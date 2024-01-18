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
</style>

{!! Form::open( array('route' =>'inventory.store','method'=>'POST','autocomplete'=>'off','files'=>'true','id'=>'formCreateInventory'))!!}
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <label id="nombre--label">* Nombre</label>
            <input type="text" class="form-control" name="nombre" style="width:100%" placeholder="Nombre del material">
            <span id="nombre-error" class="text-red"></span>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <label id="unidad--label">* Unidad de medida</label>
            <input type="text" class="form-control" name="unidad" style="width:100%" placeholder="Ej. metros...">
            <span id="unidad-error" class="text-red"></span>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <label id="cantidadmin--label">* Cantidad mínima</label>
            <input type="text" class="form-control numero" name="cantidadmin" id="cantidadmin" style="width:100%" placeholder="Introduzca la cantidad mínima de items">
            <span id="cantidadmin-error" class="text-red"></span>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label id="descripcion--label">Descripción</label><br>
                <textarea name="descripcion" class="form-control" style="width:100%;resize:none" rows="3"></textarea>
                <span id="descripcion-error" class="text-red"></span>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 archivodiv" id="fileInventory--label">
            <b>Adjunte una imagen o fotografía del material (Opcional)</b><br>
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

            <div id="fileAssets_fg" class="form-group" style="margin:0; padding:0" >
                <input type="file" class="input-sm" id="fileInventory" name="fileInventory" data-max-size="5192" data-browse-on-zone-click="true" accept=".gif, .jpg, .jpeg, .png">
                <span id="fileInventory-error" class="text-red"></span>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="margin-top:20px">
            <label class="text-yellow cursor-pointer">
                <b>Inventario inicial</b>
                &nbsp;&nbsp; &nbsp; <input type="checkbox" class="inicial" name="inicial" value="1">
            </label>
        </div>
        <div class="row divInicial" style="margin:0;display:none">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" >
                <div class="form-group" id="ubicacion-sel2">
                    <label id="ubicacion--label">* Almacen</label>
                    <select name="ubicacion" id="ubicacion" class="form-control selector-modal" disabled style="width:100%">
                        <option value="">Seleccionar</option>
                        <option value="1">Edificio Arcadia</option>
                        <option value="2">El Alto</option>
                        <option value="3">Gramadal</option>
                        <option value="4">Edifico técnico</option>
                    </select>
                    <span id="ubicacion-error" class="text-red"></span>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label id="cantidad--label">* Cantidad</label>
                <input type="text" class="form-control numero" name="cantidad" id="cantidad" style="width:100%" placeholder="Cantidad de materiales" disabled>
                <span id="cantidad-error" class="text-red"></span>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
            <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-yellow pull-right" name="btnSubmit">Registrar</button>
        </div>
    </div>
{{Form::Close()}}

<script src="{{asset('/plugins/fileinput/js/fileinput.min.js')}}"></script>
<script src="{{asset('/plugins/iCheck/icheck.min.js')}}"></script>
<script>
    $(document).ready(function () {
        $(".select2-selection").addClass('form-select2').css('border-color','#ccc');
        $(".select2-selection--single").addClass('form-selectcont');
        $("#fileInventory").fileinput({
            showUpload: false,
            allowedFileExtensions: ["gif","jpg","jpeg","png"],
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
        $('#fileAssets_fg .file-caption').click(function(){
            $('#fileInventory').trigger('click');
        });
    });
    $('.selector-modal').select2({
        dropdownParent: $('#modalCreate')
    });
    $('select.selector-modal:not(.normal)').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent().parent()
        });
    });
    $('.inicial').iCheck({
        checkboxClass: 'icheckbox_square-yellow',
    }).on('ifChecked', function (event) {
        $("#cantidad,#costo, #ubicacion").prop("disabled", false);
        $(".divInicial").slideDown();
    }).on('ifUnchecked', function (event){
        $("#cantidad,#costo ,#ubicacion").prop("disabled", true);
        $(".divInicial").slideUp();
    });
    AutoNumeric.multiple('.numero',{
        modifyValueOnWheel: false,
        digitGroupSeparator : '',
        minimumValue: 0
    });
    var campos = ['nombre','unidad','cantidadmin','descripcion','fileInventory','ubicacion','cantidad'];
    ValidateAjax("formCreateInventory",campos,"btnSubmit","{{route('inventory.store')}}","POST","/inventory");
</script>
