@extends ('layouts.admin', ['title_template' => "Contenedores $forms->name"])
@section('extracss')
<style>
    #spantitulo {
        text-align: center;
    }
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
    <div class="steps steps-counter steps-yellow" style="margin:0 !important">
        <a href="/forms/container/{{ code($forms->id) }}" class="step-item">
            <span class="steplabel">Paso 1<br> Registrar contenedores</span>
            <span class="stepMax" hidden>contenedores</span>
        </a>
        <a href="/forms/maintenance/{{ code($forms->id) }}" class="step-item">
            <span class="steplabel">Paso 2<br> Registrar campos</span>
            <span class="stepMax" hidden>Campos</span>
        </a>
        @if ($forms->check_letter == 1)
            <a class="step-item active">
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

{{Form::Open(array('action'=>array('StFormController@storeLetter',code($forms->id)),'method'=>'post','id'=>'formLetter'))}}
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
                </div>
            </div>
        @endif
    @endif

    <div class="row mt-3">
        @if ($forms->state == 2)
            <div class="text-center text-yellow" style="font-size:20px"><b> PROCEDIMIENTO:</b> {{$forms->name}} </div> <br>
            @php $col_ = "offset-lg-2 col-lg-8"; @endphp
            <div class="{{$col_}} col-md-12 col-sm-12 col-xs-12">
                <div class="accordion" id="accordion-vistaprevia">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button text-center" type="button" id="btnvista" data-toggle="collapse" data-target="#vistaprevia" aria-expanded="true">
                                <span class="text-yellow" id="spantitulo" style="font-size:17px">Vista previa del cuerpo de la carta </span>
                            </button>
                        </h2>
                        <div id="vistaprevia" class="accordion-collapse collapse " data-parent="#accordion-vistaprevia">
                            <div class="accordion-body pt-0" id="letter_body--label">
                                    <div class="form-group">
                                        <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label id="letter_body--label"></label>
                                            <textarea class="form-control" rows="3" name="letter_body" placeholder="aas" style="width:100%; resize: none" >{!! purify($forms->letter_body) !!}</textarea>
                                            <span id="letter_body-error" class="text-red"></span>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            @php $col_ = (Gate::check('forms.admin')) ? "col-lg-6" : "col-lg-12" ; @endphp
            @if (Gate::check('forms.admin'))
                <div class="col-lg-6" style="margin-bottom:20px">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Área</label> <br>
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <svg class="icon" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M12 3l-4 7h8z" />
                                            <circle cx="17" cy="17" r="3" />
                                            <rect x="4" y="14" width="6" height="6" rx="1" />
                                        </svg>
                                    </span>
                                    <input class="form-control input-incon" value="@if ( isset($forms->area) ) {{$forms->area->name}} @else No asignada @endif" disabled>
                                </div>
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
                                    <input class="form-control input-incon" type="text" value="@if ( isset($forms->categories) ) {{$forms->categories->category}} @else Asociado a servicios @endif" disabled >
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Nombre del Procedimiento</label>
                                <br><input class="form-control" name="nombreform" type="text" value="{{$forms->name}}" disabled>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label> Tipo</label><br>
                                <input class="form-control" name="nombreform" type="text" value="{{$forms->types->name}}" disabled>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group text-center">
                                <label style="font-size:16px" class="text-yellow"> Seleccionar Modelo de Cuerpo de la Carta</label>

                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 " >
                            <div class="form-group">
                                @if (count($letters) > 0)
                                <label id="modeloDeCarta--label">Modelos de cartas:</label> <br>
                                <select name="modeloDeCarta" id="selectlletters" class="form-control selector-letter" style="width: 100%" data-placeholder="Seleccione un modelo de carta">
                                    <option value="">Seleccione un modelo de carta</option>
                                    <option value="0"  data-description="">Ninguno</option>
                                    @foreach ($letters as $letter)
                                        <option value="{{ code($letter->id) }}" data-description="{{$letter->descripcion}}">{{ $letter->sub_grupo }}</option>
                                    @endforeach
                                </select>
                                <span id="modeloDeCarta-error" class="text-red"></span>
                                @else
                                <div class="card">
                                    <div class="card-body">
                                        <p> No se tienen modelos de cartas registradas en los parámetros generales. <br>
                                            @if (Gate::check('parameters.st'))
                                            Si desea registrarlas haga clic <a href="/parameters?contid=carta"><b>AQUÍ</b></a>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>

                    </div>
                </div>
            @endif

            {{-- VISTA PREVIA --}}

            <div class="{{$col_}} col-md-12 col-sm-12 col-xs-12">
                <div class="accordion" id="accordion-vistaprevia">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button text-center" type="button" id="btnvista" data-toggle="collapse" data-target="#vistaprevia" aria-expanded="true">
                                <span class="text-yellow" id="spantitulo" style="font-size:17px">Vista previa del cuerpo de la carta </span>
                            </button>
                        </h2>
                        <div id="vistaprevia" class="accordion-collapse collapse " data-parent="#accordion-vistaprevia">
                            <div class="accordion-body pt-0" id="letter_body--label">
                                    <div class="form-group">
                                        <div class="col col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <label id="letter_body--label"></label>
                                            <textarea class="form-control" rows="3" name="letter_body" placeholder="aas" style="width:100%; resize: none" >{!! purify($forms->letter_body) !!}</textarea>
                                            <span id="letter_body-error" class="text-red"></span>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                <br> <button type="submit" class="btn btn-yellow btn-lg" name="btnSubmit">{{ ($forms->letter_body != '' && $forms->letter_body != null)? 'Modificar Carta' : 'Registrar Carta'}}</button>
            </div>


        @endif
    </div>
{{ Form::close() }}

{{-- modal de Eliminar --}}
<div class="modal modal-danger fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalDeleteLetter" data-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script src="{{asset('plugins/ckeditor/ckeditor.js?2')}}"></script>

<script>

    // ********************************************************************************************
    // SELECT CARTAS
    $('select.selector-letter:not(.normal)').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent().parent(),
        });
    });

    // ********************************************************************************************
    // INSTANCIANDO EL CKEDITOR
    var editorLetter = CKEDITOR.replace('letter_body', {
        uiColor: '#f4f6fa',
        height: 400,
        removePlugins: ['scayt','about','image','anchor','links','specialchar','stylescombo','horizontalrule','table','tabletools','tableselection'],
        removeButtons: 'Anchor,Image,Links,Subscript,Superscript',
        extraPlugins: ['justify'],
        disableNativeSpellChecker: false,
    });

    // ********************************************************************************************
    // COPIANDO EL CUERPO AL CKEDITOR
    $('#selectlletters').change(function () {
        var bodyletter = $(this).find(':selected');
        editorLetter.setData(bodyletter.attr('data-description'));
    });

    $(document).ready(function() {
        // $('.selector').select2();
        $("#btnvista").click();
    });
    modalAjax("modalDeleteLetter","modalDeleteLetter","modal-content");
</script>

    {{-- ===========================================================================================
                                                VALIDACION
    =========================================================================================== --}}
    <script>
        var campos = ['letter_body'];

        $("#formLetter").on('submit', function(e) {
            e.preventDefault();
            for ( instance in CKEDITOR.instances ) {
                CKEDITOR.instances[instance].updateElement();
            }
            $('#formLetter .divMensajeDeEsperaEdit').slideDown();
            var registerForm = $("#formLetter");
            var formData = new FormData($("#formLetter")[0]);
            $.each(campos, function( indice, valor ) {
                $("#"+valor+"-error").html( "" );
                var inputtype = $("[name="+valor+"]").attr("type");
                $("[name="+valor+"]").removeClass('is-invalid').addClass('is-valid');
                $("#formLetter select[name="+valor+"]").removeClass('is-invalid-select').addClass('is-valid-select').removeClass('select2-selection');
                $("#formLetter #"+valor+"-sel2 .select2-selection").removeClass('is-invalid-select').addClass('is-valid-select').css('border','1px solid #5eba00');
                $("#formLetter #cke_letter_body").css('border','1px solid #5eba00');
            });
            $('input[name^=cliente]').map(function(idx, elem) {
                $(elem).removeClass('is-invalid').addClass('is-valid');
            }).get();

            $.ajax({
                url: "{{route('letter.store',code($forms->id) )}}",
                type: "POST",
                data:formData,
                contentType: false,
                processData: false,
                success:function(data) {
                    $('.divMensajeDeEsperaEdit').hide();
                    $("[name=btnSubmit]").attr('disabled',false)
                    if(data.alerta) {
                        toastr.error(data.mensaje);
                    }
                    if(data.success) {
                        window.location.reload();
                    }
                },
                error: function(data){
                    $('.divMensajeDeEsperaEdit').hide();
                    if(data.responseJSON.errors) {
                        $.each(data.responseJSON.errors, function( index, value ) {
                            $('#'+index+'-error' ).html( '&nbsp;<i class="fa fa-ban"></i> '+value );
                            $("[name="+index+"]").removeClass('is-valid').addClass('is-invalid');
                            $("#formLetter select[name="+index+"]").removeClass('is-valid-select').addClass('is-invalid-select').removeClass('select2-selection');
                            $("#formLetter #"+index+"-sel2 .select2-selection").removeClass('is-valid-select').addClass('is-invalid-select').css('border','1px solid #cd201f');
                            if (index == 'letter_body') {
                                $("#formLetter #cke_letter_body").css('border','1px solid #cd201f');
                            }
                        });
                        var indexaux = []; var camposaux =[]; var i=0;
                        $.each(campos, function( indice, valor ) {
                            if(data.responseJSON.errors[valor]){
                                indexaux[i] = indice;  i++;
                            }
                            var j = indice;
                            camposaux[j] = valor;
                        });
                        var menor = Math.min.apply(null, indexaux);
                        if($('#'+camposaux[menor]+'--label')[0]){
                            $('#'+camposaux[menor]+'--label')[0].scrollIntoView({behavior: 'smooth'});
                        }
                        $("[name=btnSubmit]").attr('disabled',false);

                    }
                    if(typeof(data.status) != "undefined" && data.status != null && data.status == '401'){
                        window.location.reload();
                    }
                }
            });
        });
    </script>

@endsection