@extends ('layouts.admin', ['title_template' => "Contenedores $forms->name"])
@section('extracss')
<style>
    .step-item.active {
        color: #f7a600;
        font-size: 15px;
        font-weight: bold;
    }

    @media  (max-width: 550px){
        .steplabel{
            display:none;
        }
        .stepMax{
            display:inline !important;
        }
    }
</style>
<link rel="stylesheet" href="{{asset('/plugins/iCheck/all.css')}}">
@endsection

@section ('contenidoHeader')
    @php    $contid = isset($_GET['contid']) ? $_GET['contid'] : "";    @endphp
    <div class="steps steps-counter steps-yellow" style="margin:0 !important">
        <a  class="step-item active">
            <span class="steplabel">Paso 1<br> Registrar contenedores</span>
            <span class="stepMax" hidden>Contenedores</span>
        </a>
        <a href="/forms/maintenance/{{ code($forms->id) }}" class="step-item">
            <span class="steplabel">Paso 2<br> Registrar campos</span>
            <span class="stepMax" hidden>Campos</span>
        </a>
        @if ($forms->check_letter == 1)
            <a href="/forms/letter/{{ code($forms->id) }}" class="step-item">
                <span class="steplabel">Paso 3<br> Registrar carta</span>
                <span class="stepMax" hidden>Carta</span>
            </a>
        @endif
    </div>
@endsection

@section ('contenido')
<div class="row" style="margin-bottom:20px">
    <div class="col-auto">
        <div class="page-pretitle">
            {{ nameEmpresa() }}
        </div>
        <h1 class="titulomod">
            {{$forms->name}} &nbsp;
            @if ($forms->state == 2)
                <i class="fa fa-check-circle text-center" style="font-size: 18px; color:green;" title="Finalizado"> </i>
            @endif
        </h1>
    </div>

    <div class="col-auto ms-auto">
        <div class="btn-list">
            <a href="/forms" class="btn btn-outline-secondary border border-secondary font-weight-bold" title="Ver todos los formularios">
                <i class="fa fa-list-ul fa-lg"></i>&nbsp;&nbsp;
                <span class="d-none d-sm-inline-block">
                    Ver todos los formularios
                </span>
            </a>
        </div>
    </div>
</div>

{{Form::Open(array('action'=>array('StFormController@storeContainer',code($forms->id)),'method'=>'POST','id'=>'formStoreContainer'))}}
    @if ($forms->state == 2)
        <div class="text-center text-yellow" style="font-size:20px"><b> FORMULARIO:</b> {{$forms->state}}</div> <br>
    @else
        @if (permisoAdminJefe())
            <div class="offset-lg-1 col-lg-10 offset-md-0">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label> Nombre del formulario</label>
                            <br><input class="form-control" name="nombreform" type="text" value="{{$forms->name}}" disabled>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label>Categoría</label> <br>
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                    <svg class="icon" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="5" cy="5" r="1" />
                                        <circle cx="12" cy="5" r="1" />
                                        <circle cx="19" cy="5" r="1" />
                                        <circle cx="5" cy="12" r="1" />
                                        <circle cx="12" cy="12" r="1" />
                                        <circle cx="19" cy="12" r="1" />
                                        <circle cx="5" cy="19" r="1" />
                                        <circle cx="12" cy="19" r="1" />
                                        <circle cx="19" cy="19" r="1" />
                                    </svg>
                                </span>
                                <input class="form-control input-incon" type="text" value="{{ $forms->categoriaLiteral }}" disabled >
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label> Tipo</label><br>
                            <input class="form-control" name="nombreform" type="text" value="{{$forms->types->name}}" disabled>
                        </div>
                    </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <div class="checkbox text-center" id="contsw--label">
                            <label><input type="radio" name="contsw" id="newcont" value="new_cont"> <b>Crear nuevo contenedor</b>  </label>
                            <label><input type="radio" name="contsw" id="addcont" value="add_cont"> <b>Añadir Sub contenedor </b>  </label>
                        </div>
                        <center><span id="contsw-error" class="text-red font-weight-bold"></span></center>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 inputcontnuevo" style="display:none">
                    <div class="form-group">
                        <label id="contnuevo--label"> * Nombre del contenedor nuevo</label><br>
                        <input class="form-control" name="contnuevo" id="input_cont_nuevo" style="width:100%" type="text" placeholder="Nombre del Contenedor que se mostrará">
                        <span id="contnuevo-error" class="text-red"></span>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 selectcontadd" style="display:none">
                    <div class="form-group" id="contenedorid-sel2">
                        <label id="contenedorid--label">* Contenedor</label><br>
                        <select name="contenedorid" id="contenedorid" class="form-control selector" style="width: 100%">
                            <option value="" hidden>Seleccionar</option>
                            @foreach ($containers as $cont)
                                <option value="{{$cont['id']}}">{{$cont['mostrar']}}</option>
                            @endforeach
                        </select>
                        <span id="contenedorid-error" class="text-red"></span>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 opciones_subcontainer" id="divsubcont" style="display:none; padding-bottom:20px">
                    <label>Sub Contenedores</label> <br>
                    <button type="button" class="avatar avatar-upload rounded add_input_button_radio pull-right" style="font-weight:bold">
                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                        <span class="avatar-upload-text">Agregar</span>
                    </button>
                    <div style="padding-bottom:10px">
                        <span id="myOptions-error" class="text-red"></span><br>
                        <input type="text" name="myOptions[]" class="form-control-append" placeholder="Nombre del Subcontenedor" style="width:50%">
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="divboton"  style="padding:20px 0px 50px 0px">
                    <button type="submit" class="btn btn-yellow" name="btnSubmitContainer">Confirmar registro</button>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 msgmaxsubc text-right text-red font-weight-bold" style="display:none; padding-top:20px; padding-bottom:30px">
                    El Contenedor elegido ya tiene el máximo de Sub Contenedores agregados.
                </div>

                </div>
            </div>
        @endif
    @endif

    {{-- ==========================================================================================================================================
                                                                    VISTA PREVIA
    ========================================================================================================================================== --}}
    <div class="row">
        @if (isset($containers) && count($containers)>0)
            <div class="text-center font-weight-bold text-yellow" style="font-size:17px"> Vista previa de los contenedores </div> <br>
            <ul class="nav nav-tabs" data-toggle="tabs">
                @php
                    $containers_array = $containers->toArray();
                    $contfirst = array_shift($containers_array);
                    $contid = ($contid!="") ? $contid : $contfirst['id'];
                @endphp

                @foreach ($containers as $cont)
                    @if ($cont['id']==$contid)
                        <li class="active">
                            <a class="nav-link active" href="#tab_{{$cont['id']}}" data-toggle="tab">
                                {{$cont['mostrar']}}
                            </a>
                        </li>
                    @else
                        <li>
                            <a class="nav-link" href="#tab_{{$cont['id']}}" data-toggle="tab">
                                {{$cont['mostrar']}}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
            {{-- CONTENIDO TABS --}}
            <div class="card-body">
                <div class="tab-content">
                    @foreach ($containers as $cont)
                        <div class="tab-pane  @if($cont['id']==$contid){{'active'}}@endif show" id="tab_{{$cont['id']}}">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-bottom:20px;">
                                @if (isset($cont['subcontainer']) && $cont['subcontainer']!="")
                                    @php    $subcontain = collect($cont['subcontainer'])->sortBy('orden');  @endphp
                                    <div class="accordion" id="accordion_principal">
                                        @foreach ($subcontain as $item)
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button" type="button" data-toggle="collapse" data-target="#{{$item['val']}}---collapse" data-pk="subc_{{$item['val']}}" aria-expanded="true">
                                                        <span class="text-yellow spantitulo" style="font-size:17px">
                                                            <svg class="icon icon-tabler" style="margin-bottom:2px" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="5" width="18" height="14" rx="3" /><line x1="3" y1="10" x2="21" y2="10" /><line x1="7" y1="15" x2="7.01" y2="15" /><line x1="11" y1="15" x2="13" y2="15" /></svg>
                                                            {{$item['mostrar']}}
                                                        </span>
                                                    </button>
                                                </h2>
                                                <div id="{{delete_charspecial($item['val'])}}---collapse" class="accordion-collapse collapse show" data-parent="#accordion_principal">
                                                    <div style="padding:20px 0px 30px 50px"> Aquí irá el contenido del sub contenedor de <b>{{$item['mostrar']}}</b>&nbsp;&nbsp;
                                                        @if (permisoAdminJefe())
                                                            @if ($forms->state != 2)
                                                                <a rel="modalEditSubContainer" href="/forms/subcontainer/editmodal/{{$cont['id']}}/{{$item['val']}}/{{code($forms->id)}}" title="Editar subcontenedor" class="text-yellow">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                        <path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3"></path>
                                                                        <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3"></path>
                                                                        <line x1="16" y1="5" x2="19" y2="8"></line>
                                                                    </svg>
                                                                </a>
                                                                <a rel="modalDeleteContainer" href="/forms/container/deletemodal/{{$cont['id']}}/{{$item['val']}}/{{code($forms->id)}}/subc" title="Eliminar sub contenedor">
                                                                    <svg  class="icon text-red iconhover" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            @if (permisoAdminJefe())
                                @if (count($containers)>0 && $forms->state != 2)
                                    <div class="row">
                                        <div style="font-size: 10px; text-align: right; padding-top:20px">
                                            <a data-toggle="dropdown" href="#" >
                                                <button class="btn btn-outline-secondary font-weight-bold border border-secondary" style="font-size: 15px;" type="button">
                                                    <svg class="icon icon-tabler icon-tabler-sort-descending" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.25" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <line x1="4" y1="6" x2="13" y2="6"></line>
                                                        <line x1="4" y1="12" x2="11" y2="12"></line>
                                                        <line x1="4" y1="18" x2="11" y2="18"></line>
                                                        <polyline points="15 15 18 18 21 15"></polyline>
                                                        <line x1="18" y1="6" x2="18" y2="18"></line>
                                                        </svg> Cambiar orden
                                                </button>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right text-right" style=" border: 1px solid rgb(121, 119, 119); box-shadow:1px 1px 2px 3px #ccc; margin:10px; font-size: 13px;">
                                                <div class="form-label m-3" >Cambiar orden de:</div>
                                                <div class="m-3">
                                                    <label class="form-check">
                                                    <input class="form-check-input" type="radio" rel="modalOrdenContainer" data-href="/forms/container/ordermodal/{{code($forms->id)}}">
                                                    <span class="form-check-label">Contenedores</span>
                                                    </label>
                                                    @if (isset($cont['subcontainer']) && $cont['subcontainer']!="")
                                                        @php    $countSubc = count($cont['subcontainer']);  @endphp
                                                        @if($countSubc>1)
                                                            <label class="form-check">
                                                            <input class="form-check-input" type="radio" rel="modalOrdenSubContainer" data-href="/forms/subcontainer/ordermodal/{{$cont['id']}}/{{code($forms->id)}}">
                                                            <span class="form-check-label">Subcontenedores asociados a: <br><b class="text-uppercase">{{$cont['mostrar']}} </b></span>
                                                            </label>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                            <a rel="modalEditContainer" href="/forms/container/editmodal/{{$cont['id']}}/{{code($forms->id)}}" class="btn btn-outline-yellow font-weight-bold border border-yellow">
                                                <i class="fa fa-edit" ></i> &nbsp; Editar contenedor
                                            </a>
                                            <a rel="modalDeleteContainer" href="/forms/container/deletemodal/{{$cont['id']}}/subc/{{code($forms->id)}}/cont" class="btn btn-outline-danger font-weight-bold border border-danger">
                                                <i class="fa fa-trash-alt" ></i> &nbsp; Eliminar contenedor
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
{{ Form::close() }}

{{-- modal de Eliminar --}}
<div class="modal modal-danger fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalDeleteContainer" data-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
        </div>
    </div>
</div>

<div class="modal modalCyan fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalEditSubContainer" data-backdrop="static">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">
                    <i class="fa fa-edit"></i>&nbsp;Editar sub contenedor
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal modalCyan fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalEditContainer" data-backdrop="static">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">
                    <i class="fa fa-edit"></i>&nbsp;Editar contenedor
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal modalPrimary fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalOrdenContainer" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">
                    <i class="fa fa-th-list"></i>&nbsp;Cambiar orden contenedores
                </h5>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal modalPrimary fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalOrdenSubContainer" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">
                    <i class="fa fa-th-list"></i>&nbsp;Cambiar orden sub contenedores
                </h5>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{asset('/plugins/iCheck/icheck.min.js')}}"></script>
<script>
    $('.selector').select2();
    $(document).ready(function () {
        $(".select2-selection").addClass('form-select2').css('border-color','#ccc');
        $(".select2-selection--single").addClass('form-selectcont');
    });

    var x=-1;
    $('#addcont').iCheck({
        radioClass: 'iradio_square-yellow',
        increaseArea: '5%'
    }).on('ifChecked', function (event) {
        $('.opciones_subcontainer').show(1000);
        $('.selectcontadd').show(1000);
        $('.inputcontnuevo').hide(1000);
    });

    $('#newcont').iCheck({
        radioClass: 'iradio_square-yellow',
        increaseArea: '5%'
    }).on('ifChecked', function (event) {
        $('.opciones_subcontainer').hide();
        $('.opciones_subcontainer').hide(1000);
        $('.selectcontadd').hide(1000);
        $('.inputcontnuevo').show(1000);
        $('#contenedorid').val('');
        $('#contenedorid').trigger('change');
        $('#divboton').show();
        $('.msgmaxsubc').hide();
        x=-1;

    });

    modalAjax("modalDeleteContainer","modalDeleteContainer","modal-content");
    modalAjax("modalEditSubContainer","modalEditSubContainer","modal-body");
    modalAjax("modalEditContainer","modalEditContainer","modal-body");

    $(document).on('click','input[rel=modalOrdenContainer]',function(evt) {
        evt.preventDefault();
        var modal = $('#modalOrdenContainer').modal();
        var modalurl = $(this).attr('data-href');
        modal.show();
        modal.find('.modal-body')
        .load(modalurl, function (responseText, textStatus) {
            if ( textStatus === 'success' ||
                textStatus === 'notmodified')
            {
                modal.show();
            }
        });
    });
    $(document).on('click','input[rel=modalOrdenSubContainer]',function(evt) {
        evt.preventDefault();
        var modal = $('#modalOrdenSubContainer').modal();
        var modalurl = $(this).attr('data-href');
        modal.show();
        modal.find('.modal-body')
        .load(modalurl, function (responseText, textStatus) {
            if ( textStatus === 'success' ||
                textStatus === 'notmodified')
            {
                modal.show();
            }
        });
    });


    function selectsubcontainer(){
        var query = $("#contenedorid").val();
        var idform = "{{code($forms->id)}}";
        // if (query != '') {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('forms.subcontainerajax') }}",
                method: "POST",
                data: { query: query, _token: _token, idform: idform},
                success: function (salida) {'.$requerido.'
                    $('.salidaprueba').html(salida.selectxd1);
                    x = salida.contsub;
                    console.log(x);
                    if(x==10){
                        $('#divboton').hide();
                        $('.msgmaxsubc').show();
                    }else{
                        $('#divboton').show();
                        $('.msgmaxsubc').hide();
                    }
                }
            });
        // }
    }
    var max_fields = 9;
    // ================  Radio Button ================================
    $(".add_input_button_radio").click(function (e) {
        e.preventDefault();
        // if(x!=-1){
            if (max_fields > x) {
            $(".opciones_subcontainer").append(
            '<div class="moresubc" style="padding-bottom:10px">'+
                '<input type="text" name="myOptions[]" class="form-control-append" placeholder="Nombre del Subcontenedor" style="width:50%"/>&nbsp;&nbsp;'+
                '<a href="#" class="remove_input" title="Borrar"><i class="fa fa-trash-alt text-red"></i></a>'+
            '</div>');
            x++;
            }else{  alert("Llegó al máximo de opciones permitidas (máximo: 10 Opciones)") }
        // }else{
        //     alert ("Debe escoger un Contenedor antes de poder agregar Sub Contenedores")
        // }
    });
    $(".opciones_subcontainer").on("click", ".remove_input", function (e) {
        e.preventDefault();
        $(this).parent('div').remove();
        x--;
    })

</script>

<script>
    var campos = ['contsw','contnuevo','contenedorid','myOptions'];
    $("#formStoreContainer").on('submit', function(e) {
        e.preventDefault();
        var registerForm = $("#formStoreContainer");
        var formData = new FormData($("#formStoreContainer")[0]);
        $.each(campos, function( indice, valor ) {
            $("#"+valor+"-error").html( "" );
            var inputtype = $("[name="+valor+"]").attr("type");
            if(inputtype != 'radio')    $("[name="+valor+"]").removeClass('is-invalid').addClass('is-valid');
            $("select[name="+valor+"]").removeClass('is-invalid-select').addClass('is-valid-select').removeClass('select2-selection');
            $("#formStoreContainer #"+valor+"-sel2 .select2-selection").removeClass('is-invalid-select').addClass('is-valid-select');
            $("#formStoreContainer #"+valor+"-sel2 .select2-selection").css('border','1px solid #5eba00');
        });
        $('input[name^=myOptions]').map(function(idx, elem) {
            $(elem).removeClass('is-invalid').addClass('is-valid');
        }).get();

        $.ajax({
            url: "{{ route('container.store',code($forms->id) )}}",
            type: "POST",
            data:formData,
            contentType: false,
            processData: false,
            success:function(data) {
                if(data.alerta) {
                    toastr.error(data.mensaje);
                    $("[name=btnSubmitContainer]").attr('disabled',false)
                }
                if(data.success) {
                    var contid = (data.contid) ? '?contid='+data.contid : "";
                    $("[name=btnSubmitContainer]").attr('disabled',true)
                    window.location.href = "/forms/container/{{ code($forms->id) }}"+contid;
                }
            },
            error: function(data){
                if(data.responseJSON.errors) {
                    var swsubcont = 0;
                    var contErrors = 0;
                    $.each(data.responseJSON.errors, function( index, value ) {
                        if (~index.indexOf("myOptions")){
                            swsubcont = 1;
                            if (contErrors == 0) {
                                var scrollpos = $("#divsubcont").offset().top - 150;
                                $('html, body').animate({scrollTop: scrollpos }, 600);
                                contErrors++;
                            }
                        }else{
                            $('#'+index+'-error' ).html( '&nbsp;<i class="fa fa-ban"></i> '+value );
                            var inputtype = $("[name="+index+"]").attr("type");
                            if(inputtype != 'radio')    $("[name="+index+"]").removeClass('is-valid').addClass('is-invalid');
                            $("select[name="+index+"]").removeClass('is-valid-select').addClass('is-invalid-select').removeClass('select2-selection');
                            $("#formStoreContainer #"+index+"-sel2 .select2-selection").removeClass('is-valid-select').addClass('is-invalid-select');
                            $("#formStoreContainer #"+index+"-sel2 .select2-selection").css('border','1px solid #cd201f');
                        }

                        if (contErrors == 0) {
                            var divPadre1 = $("#formStoreContainer [name="+index+"]").closest('div.col-xs-12');
                            var divPadre2 = $("#formStoreContainer [name="+index+"]").closest('div');
                            if(divPadre1 != null){
                                var scrollpos = divPadre1.offset().top - 150;
                            }else if(divPadre2 != null){
                                var scrollpos = divPadre1.offset().top - 150;
                            }
                            $('html, body').animate({scrollTop: scrollpos }, 600);
                        }
                        contErrors++;


                    });
                    if(swsubcont == 1){
                        $('#myOptions-error' ).html( '&nbsp;<i class="fa fa-ban"></i> Debe llenar todos los campos correctamente.' );
                        $('input[name^=myOptions]').map(function(idx, elem) {
                            if ( $(elem).val() == "" )  $(elem).removeClass('is-valid').addClass('is-invalid');
                        }).get();
                    }

                    $("[name=btnSubmitContainer]").attr('disabled',false);
                }

                if(typeof(data.status) != "undefined" && data.status != null && data.status == '401'){
                    window.location.reload();
                }
            }
        });
    });
</script>
@endsection