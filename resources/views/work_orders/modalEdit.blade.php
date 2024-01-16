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
        Editar orden de trabajo: {{ $workorder->cod }}
    </h5>
    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    {!! Form::open( array('route' =>'assets.store','method'=>'POST','autocomplete'=>'off','files'=>'true','id'=>'formCreateOT', 'onsubmit'=>'btnSubmitEdit.disabled = true; return true;'))!!}
    <div class="row">
        {!! datosRegistro('edit') !!}
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group" id="activo-sel2">
                <label id="activo--label">* Activo</label>
                <select name="activo" class="form-control selector-activo" style="width:100%">
                    <option value="">Seleccionar</option>
                    <option value="{{ code($workorder->asset_id) }}" selected>{{ $workorder->asset->cod.' - '.$workorder->asset->nombre }}</option>
                </select>
                <span id="activo-error" class="text-red"></span>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divInfoAsset">{!! $workorder->asset->getInfoAssets('img') !!}</div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <div class="d-flex">
                    <div class="mr-auto">
                        <label id="titulo--label">* Título</label> <br>
                    </div>
                    <div>
                        <label>
                            <span class="cursor-pointer text-red">Emergencia</span>&nbsp;
                            <input type="checkbox" id="checkEmerg" name="emergency" value="E" @if($workorder->emergencia == 'E') checked @endif>
                        </label>
                    </div>
                </div>
                <input class="form-control" name="titulo" type="text" placeholder="Titulo de orden de trabajo" value="{{ $workorder->titulo }}">
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
                        <option value="{!! code($form->id) !!}" @if($form->id == $workorder->form_id) selected @endif> {{ $form->name }} </option>
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
                    <input type='text' class="form-control" name="fecha_ven" placeholder="dd/mm/YYYY HH:mm" id="fechaMantenimiento" value="{{ date('d/m/Y H:i', strtotime($workorder->fecha)) }}"/>
                </div>
                <span id="fecha_ven-error" class="text-red"></span>
                <div><b id="fechaFin-label" class="text-yellowdark" style="display:none"></b></div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group" id="tecresponsable-sel2">
                <label id="tecresponsable--label">* Técnico responsable</label> <br>
                <select name="tecresponsable" class="form-control selector-usuario" style="width: 100%">
                    <option value="" data-mail="Sin usuario">Seleccionar</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @if($workorder->responsableId == $user->id) selected @endif> {{ $user->fullName }} </option>
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
                        @if (in_array($user->id, $workorder->techAdds->pluck('user_id')->toArray() ))
                            <option value="{{ $user->id }}" selected> {{ $user->fullName }} </option>
                        @else
                            <option value="{{ $user->id }}"> {{ $user->fullName }} </option>
                        @endif
                    @endforeach
                </select>
                <span id="asignados-error" class="text-red"></span>
            </div>
        </div>

        {{-- =========================================================================================================== --}}
        {{--                                                PRIORIDAD                                                    --}}
        {{-- =========================================================================================================== --}}
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
            <div class="form-group">
                <label id="prioridad--label" class="col-form-label">* Prioridad</label>
                <div class="checkbox  text-center">
                    <label><input type="radio" id="pri_none" name="prioridad" @if($workorder->prioridad == '0') checked @endif value="0"> <b class="text-primary">Ninguna</b>  </label>
                    <label><input type="radio" id="pri_low"  name="prioridad" @if($workorder->prioridad == '1') checked @endif value="1"> <b class="text-green">Baja</b>  </label>
                    <label><input type="radio" id="pri_med"  name="prioridad" @if($workorder->prioridad == '2') checked @endif value="2"> <b class="text-yellow">Media</b>  </label>
                    <label><input type="radio" id="pri_high" name="prioridad" @if($workorder->prioridad == '3') checked @endif value="3"> <b class="text-orange">Alta</b>  </label>
                </div>
                <center><span id="prioridad-error" class="text-red font-weight-bold"></span></center>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label id="descripcion--label" class="col-form-label">* Descripción</label> <br>
                <textarea name="descripcion" class="form-control" style="width:100%; resize:none;" rows="4" placeholder="Descripción de la orden de trabajo">{!! $workorder->descripcion !!}</textarea>
                <span id="descripcion-error" class="text-red"></span>
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
    $('#checkEmerg').iCheck({
        checkboxClass: 'icheckbox_square-red',
        increaseArea: '20%'
    });

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

    var prior = ["pri_none","pri_low","pri_med","pri_high"];
    var color = ["blue","green","yellow","red"]
    $.each(prior, function( indice, valor ) {
        $('#'+valor).iCheck({
            radioClass: 'iradio_square-'+color[indice],
        });
    })


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

    var campos = ['activo','emergency','titulo','formulario','fecha_ven','prioridad','descripcion','tecresponsable','asignados'];
    ValidateAjax("formCreateOT",campos,"btnSubmitEdit","{{ route('workorders.update',code($workorder->id) )}}","POST","/work_orders");
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