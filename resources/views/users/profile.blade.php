@section('extracss')
    <style>
        @media  (max-width: 767px){
            #imgAvatar, #imgNone{
                width:300px;
                height:300px;
            }
        }
        .iti--separate-dial-code{
            width: 100% !important;
        }

    </style>
    <link rel="stylesheet" href="{{asset('/plugins/iCheck/all.css')}}">
    <link rel="stylesheet" href="{{asset('/plugins/cropperjs/cropper.css')}}">
    <link rel="stylesheet" href="{{asset('/plugins/fileinput/css/fileinput.min.css')}}"/>
@endsection
@extends ('layouts.admin', ['title_template' => "Perfil de Usuario"])

@section('contenidoHeader')
    <div class="col-auto">
        <div class="page-pretitle">
            {{nameEmpresa()}}
        </div>
        <h1 class="titulomod">
            <b>Perfil de usuario:</b> {{ userFullName(userId()) }}
        </h1>
    </div>
@endsection

@section('contenido')
@php  $swfirma = isset($_GET['swfirma']) ? $_GET['swfirma'] : ""; @endphp
    {!!Form::model(auth()->user(),['route'=>['updateprofile', auth()->user()->id],'method'=>'POST','files'=>true,'id'=>'formProfile' ]) !!}
        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <div class="container d-flex h-100">
                    <div class="row justify-content-center align-self-center">
                        <img id="imgAvatar" class="avatar-rounded cambiar_avatar" src="{{ imageRouteAvatar(auth()->user()->avatar,0) }}" title="Presione para cambiar su imagen de avatar<br><i>Antes de seguir asegúrese de guardar los datos que fueron modificados presionando <b>Modificar perfil</b></i>" data-toggle="tooltip"/>
                        <img id="imgNone" class="avatar-rounded cambiar_avatar" src="{{ '/storage/avatar0.png?'.rand() }}" style="display:none" title="Presione para cambiar su imagen de avatar<br><i>Antes de seguir asegúrese de guardar los datos que fueron modificados presionando <b>Modificar perfil</b></i>" data-toggle="tooltip" />
                        @if (auth()->user()->avatar!='avatar0.png')
                            <div class="text-center" style="margin-top:10px">
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="checkAvatar" class="checkAvatar" value="1"> <b><i> Quitar imagen </i></b>
                                </label>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-status-top bg-primary"></div>

                    <div class="card-body">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Nombre de usuario:</label>
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                    <i class="fe fe-user"></i>
                                    </span>
                                    <input type="text" class="form-control" readonly value="{{auth()->user()->username}}">
                                </div>
                            </div>
                        </div>
                        {{-- Cambiar contraseña --}}
                        <div style="margin-bottom:15px">
                            <a href='#' id="showps" class="text-primary"> <i class="fas fa-key" id="iconpass"></i> <b id="titlepass">Clic para cambiar contraseña</b></a>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-2" id="changeps" style="display: none">
                            <div class="card">
                                <div class="card-status-top bg-primary"></div>
                                <div class="card-body">
                                    <div class="row form-group" id="current_password--label">
                                        <label class="col-md-4">* Contraseña Actual: </label>
                                        <div class="col-md-8">
                                            <div class='input-group'>
                                                <input id="current_password" type='password' class="pass_quit form-control" name="current_password" style="border-right: 0px" placeholder="Ingrese la contraseña que recibió mediante correo electronico"/>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-eye-open" id="pass_current" title="Mostrar Contraseña"></span>
                                                </span>
                                            </div>
                                            <span id="current_password-error" class="text-red"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group" id="password_first--label">
                                        <label class="col-md-4">* Contraseña Nueva:</label>
                                        <div class="col-md-8">
                                            <div class='input-group'>
                                                <input id="password1" type='password' class="pass_quit form-control" name="password_first" style="border-right: 0px" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-eye-open" id="pass_first" title="Mostrar Contraseña"></span>
                                                </span>
                                            </div>
                                            <span id="password_first-error" class="text-red"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group" id="new_password--label">
                                        <label class="col-md-4">* Confirmar Contraseña Nueva:</label>
                                        <div class="col-md-8">
                                            <div class='input-group'>
                                                <input id="password2" type='password' class="pass_quit form-control" name="new_password" style="border-right: 0px" />
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-eye-open" id="pass_confirm" title="Mostrar Contraseña"></span>
                                                </span>
                                            </div>
                                            <span id="new_password-error" class="text-red"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <b>La Contraseña nueva debe cumplir los siguientes requerimientos:</b><br>
                                    </div>
                                    <input type="text" name="auxpass" id="auxpass" value="0" hidden>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <span id="8char" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Al menos 8 caracteres de longitud.<br>
                                            <span id="ucase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Al menos una letra mayúscula.
                                        </div>
                                        <div class="col-sm-6">
                                            <span id="lcase" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Al menos una letra minúscula.<br>
                                            <span id="num" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Al menos un número.
                                        </div>
                                        <div class="col-sm-12">
                                            <span id="pwmatch" class="glyphicon glyphicon-remove" style="color:#FF0004;"></span> Los campos <b><i>"Contraseña Nueva"</i></b> y <b><i>"Confirmar Contraseña Nueva"</i></b> deben coincidir.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label id="name--label">* Nombre(s): </label>
                                    <input type="text" class="form-control" name="name" value="{{auth()->user()->name}}">
                                    <span id="name-error" class="text-red"></span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label id="ap_paterno--label">* Apellido paterno: </label>
                                    <input type="text" class="form-control" name="ap_paterno" value="{{auth()->user()->ap_paterno}}">
                                    <span id="ap_paterno-error" class="text-red"></span>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label id="ap_materno--label">Apellido materno: </label>
                                    <input type="text" class="form-control" name="ap_materno" value="{{auth()->user()->ap_materno}}">
                                    <span id="ap_materno-error" class="text-red"></span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label id="email--label">* Email:</label>
                                    <div class="input-icon">
                                        <span class="input-icon-addon">
                                        <i class="fas fa-at fa-sm"></i>
                                        </span>
                                        <input type="text" class="form-control" value="{{auth()->user()->email}}" name="email">
                                    </div>
                                    <span id="email-error" class="text-red"></span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label id="celular--label">Celular: </label><br>
                                    <input type="text" id="phonex" class="form-control" name="celular" value="{{auth()->user()->celular}}">
                                    <span id="celular-error" class="text-red"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            <button type="submit" class="btn btn-primary text-center btn-pill btn-lg" name="btnSubmit">Modificar perfil</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {!!Form::Close()!!}

    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12">
        </div>
        <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-status-top bg-yellow"></div>
                <div class="card-header">
                    <h3 class="card-title pull-left text-yellow ">
                        <b>IMAGEN Ó CAPTURA DE FIRMA </b>
                    </h3>
                </div>

                <div class="row mx-4 mt-2">
                    <div class="col-xl-6 col-lg-12 col-sm-12 col-xs-12 col-md-6">
                        <div class="form-group">
                            <label>
                                Cambiar firma
                                <span class="form-help" data-toggle='popover' data-trigger='hover' data-content='<span style="font-size: 11px;" >Se recomienda que la imagen tenga un ancho máximo de 150 píxeles (px) así no sufrirá distorsión al momento de reducirlo. </span>' data-original-title='<span style="font-size: 12px; font-weight: bold ">Información</span>'>
                                    ?
                                </span>
                            </label>
                            <input type="file" name="firma" id="input-file" accept="image/*" data-show-preview="false"/>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-12 col-sm-12 col-xs-12 text-center col-md-6 pb-3">
                        @if (auth()->user()->firma != null)
                            <div class="container bg-white" style="max-width: 170px; max-height: 100px;border-radius:10%">
                                <div class="row justify-content-center align-items-center">
                                    <img id="imgfirma" src="/storage/general/firmas/{{auth()->user()->firma}}" style="max-width: 150px; max-height: 100px;" />
                                </div>
                            </div>
                        @else
                            <br><b class="text-yellow">No cuenta con imagen de firma... </b>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Imagen de Firma --}}
    <div class="modal modalPrimary fade" id="modalImagenFirma" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Cambiar imagen de firma
                    </h5>
                    <button type="button" class="btn-close btnCancFirma" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <img id="imageFirma" style="max-height: 600px">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btnCancFirma" data-dismiss="modal">Cancelar</button>
                    <input type="button" class="btn btn-yellow pull-right" id="guardarfirma" value="Guardar firma">
                </div>
            </div>
        </div>
    </div>

    {{-- Modal AVATAR --}}
    <input type="file" name="image" class="image" accept="image/*" id="inputImageAvatar" style="display:none">
    <div class="modal modalPrimary fade modal-slide-in-right" aria-hidden="true" role="dialog"  id="modalAvatar" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">
                        Cambiar avatar
                    </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>

                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <img id="imagAv" style="max-height: 600px">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
                        <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cancelar</button>
                        <input type="button" class="btn btn-primary pull-right" id="btnGuardarAvatar" value="Guardar imagen" >
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{asset('/plugins/fileinput/js/fileinput.min.js')}}"></script>
    <script src="{{asset('/plugins/iCheck/icheck.min.js')}}"></script>
    <script src="{{asset('/plugins/cropperjs/cropper.js')}}"></script>
    <script>
        // MOSTRAR U OCULTAR CAMBIO DE PASSWORD
        $( "#showps" ).click(function() {
            var aux = $("#auxpass").val();
            if(aux == 1){
                $("#auxpass").val(0);
                $("#changeps" ).fadeOut();
                $("#iconpass").removeClass('fa-times').addClass("fa-key");
                $("#titlepass").text("Clic para cambiar contraseña");
            }
            if(aux == 0){
                $("#auxpass").val(1);
                $("#changeps" ).fadeIn();
                $("#titlepass").text("Cancelar cambio de contraseña");
                $("#iconpass").removeClass('fa-key').addClass("fa-times");
            }
        });

        $(document).ready(function(){
            var sw_msg = "{{$swfirma}}";
            if(sw_msg == '1'){
                toastr.success('Imagen de firma Modificada con éxito', 'Correcto');
            }
            var pageURL = $(location).attr("href").split('?')[0];
            window.history.pushState("profile", "Title", pageURL);

            $("#auxpass").val(0);
            $(".btn-file").addClass('w-100');
        });

        $('.datepicker').datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy'
        });

        //popover
        $(function () {
            $('[data-toggle="popover"]').popover({
                html: true,
                "trigger": "hover",
                "placement": "right",
                "container": "body",
            })
            $('[data-toggle="tooltip"]').tooltip({
                html: true,
                "placement": "top",
                "container": "body",
            })
        });

        // ICHECK QUITAR IMAGEN
        $('.checkAvatar').iCheck({
            checkboxClass: 'icheckbox_square-blue',
        }).on('ifChecked', function (event) {
            $('#imgAvatar').hide();
            $('#imgNone').show();
        }).on('ifUnchecked', function (event) {
            $('#imgAvatar').show();
            $('#imgNone').hide();
        });

        function iconValidation(cond, object){
            if(cond){
                object.removeClass("glyphicon-remove");
                object.addClass("glyphicon-ok");
                object.css("color","#00A41E");
            }else{
                object.removeClass("glyphicon-ok");
                object.addClass("glyphicon-remove");
                object.css("color","#FF0004");
            }
        }
        $("#password1,#password2").keyup(function(){
            var ucase = new RegExp("[A-Z]+");
            var lcase = new RegExp("[a-z]+");
            var num = new RegExp("[0-9]+");

            // Condiciones
            var long8 = $("#password1").val().length >= 8;
            var mayus = ucase.test($("#password1").val());
            var minus = lcase.test($("#password1").val());
            var numero = num.test($("#password1").val());
            var iguales = ($("#password1").val() == $("#password2").val()) && $("#password1").val() != '' && $("#password2").val() != '';

            // 8 caracteres de longitud
            iconValidation(long8, $("#8char"));
            // Mayusculas
            iconValidation(mayus, $("#ucase"));
            // minusculas
            iconValidation(minus, $("#lcase"));
            // numeros
            iconValidation(numero, $("#num"));
            // iguales
            iconValidation(iguales, $("#pwmatch"));
        });

        $( "#pass_current" ).click(function() {
            if($(this).hasClass('glyphicon-eye-open')){
                $('#current_password').removeAttr('type');
                $('#pass_current').attr('title', 'Ocultar Contraseña');
                $('#pass_current').addClass('glyphicon-eye-close').removeClass('glyphicon-eye-open');
            }else{
                $('#current_password').attr('type','password');
                $('#pass_current').attr('title', 'Mostrar Contraseña');
                $('#pass_current').addClass('glyphicon-eye-open').removeClass('glyphicon-eye-close');
            }
        });

        $( "#pass_first" ).click(function() {
            if($(this).hasClass('glyphicon-eye-open')){
                $('#password1').removeAttr('type');
                $('#pass_first').attr('title', 'Ocultar Contraseña');
                $('#pass_first').addClass('glyphicon-eye-close').removeClass('glyphicon-eye-open');
            }else{
                $('#password1').attr('type','password');
                $('#pass_first').attr('title', 'Mostrar Contraseña');
                $('#pass_first').addClass('glyphicon-eye-open').removeClass('glyphicon-eye-close');
            }
        });

        $( "#pass_confirm" ).click(function() {
            if($(this).hasClass('glyphicon-eye-open')){
                $('#password2').removeAttr('type');
                $('#pass_confirm').attr('title', 'Ocultar Contraseña');
                $('#pass_confirm').addClass('glyphicon-eye-close').removeClass('glyphicon-eye-open');
            }else{
                $('#password2').attr('type','password');
                $('#pass_confirm').attr('title', 'Mostrar Contraseña');
                $('#pass_confirm').addClass('glyphicon-eye-open').removeClass('glyphicon-eye-close');
            }
        });

    </script>

    {{-- Recortar imagen avatar con CROPPER --}}
    <script>
        $('.cambiar_avatar').click(function (event) {
            $("#inputImageAvatar").click();
        });
        var $modalavatar = $('#modalAvatar');
        var imageAvatar = document.getElementById('imagAv');
        var cropperAvatar;
        $("#inputImageAvatar").on("change", function (e) {
            var files = e.target.files;
            var done = function (url) {
                imageAvatar.src = url;
                $modalavatar.modal('show');
            };
            var reader;
            var file;
            var url;
            if ( files.length > 0) {
                file = files[0];
                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function (e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        $modalavatar.on('shown.bs.modal', function () {
            cropperAvatar = new Cropper(imageAvatar, {
                aspectRatio: 700/700,
                viewMode: 0,
                crop(event) {
                    console.log(event.detail.width);
                    console.log(event.detail.height);
                },
                preview: '.preview'
            });
        }).on('hidden.bs.modal', function () {
            cropperAvatar.destroy();
            cropperAvatar = null;
        });

        $("#btnGuardarAvatar").click(function () {
            $(this).attr("disabled","disabled");
            canvas = cropperAvatar.getCroppedCanvas({
                width: 700,
                height: 700,
            });
            var btnEnviarEnc = $("#btnGuardarAvatar");
            var userid = "{{code(auth()->user()->id)}}";
            canvas.toBlob(function (blob) {
                url = URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function () {
                    var base64data = reader.result;
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "{{ route('users.avatar') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'image': base64data,
                            userid : userid
                        },
                        beforeSend: function(){
                            btnEnviarEnc.val("Guardando Imagen..."); // Para input de tipo button
                            btnEnviarEnc.attr("disabled","disabled");
                        },
                        complete:function(data){
                            btnEnviarEnc.val("Guardar Imagen");
                            btnEnviarEnc.attr("disabled","disabled");
                        },
                        success: function (data) {
                            $modalavatar.modal('hide');
                            btnEnviarEnc.removeAttr("disabled");
                            window.location.reload();
                        },
                        error: function(data){
                            toastr.error('Hubo un problema al actualizar los datos');
                        }

                    });
                }
            });
        })
    </script>

    {{-- CROPPER DE FIRMA --}}
<script>
    $(".btnCancFirma").click(function () {
        $("#input-file").fileinput('clear');
    })
    $("#input-file").fileinput({
        previewFileType: "image",
        showUpload: false,
        showCancel: false,
        dropZoneEnabled: true,
        showCaption: false,
    });

    $('#input-file').change(function(){
        $(".fileinput-remove-button").hide();
    })

    var $modal = $('#modalImagenFirma');
    var image = document.getElementById('imageFirma');
    var cropper;
    $("#input-file").on("change", function (e) {
        var files = e.target.files;
        var done = function (url) {
            image.src = url;
            $modal.modal('show');
        };
        var reader;
        var file;
        var url;
        if ( files.length > 0) {
            file = files[0];
            if (URL) {
                done(URL.createObjectURL(file));
            } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function (e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    $modal.on('shown.bs.modal', function () {
        cropper = new Cropper(image, {
            aspectRatio: 160/100,
            viewMode: 0,
            crop(event) {
                // console.log(event.detail.width);
                // console.log(event.detail.height);
            },
            preview: '.preview'
        });
    }).on('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
    });

    $("#guardarfirma").click(function () {
        $(this).attr("disabled","disabled");
        canvas = cropper.getCroppedCanvas({
            width: 160,
            height: 100,
        });
        var btnEnviarEnc = $("#guardarfirma");
        var userid = "{{code(auth()->user()->id)}}";
        canvas.toBlob(function (blob) {
            url = URL.createObjectURL(blob);
            var reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = function () {
                var base64data = reader.result;
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "{{ route('users.firma') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        'image': base64data,
                        userid : userid
                    },
                    beforeSend: function(){
                        btnEnviarEnc.val("Guardando firma..."); // Para input de tipo button
                        btnEnviarEnc.attr("disabled","disabled");
                    },
                    complete:function(data){
                        btnEnviarEnc.val("Guardar firma");
                        btnEnviarEnc.attr("disabled","disabled");
                    },
                    success: function (data) {
                        btnEnviarEnc.removeAttr("disabled");
                        $modal.modal('hide');
                        window.location.reload();
                        window.history.pushState("users", "Title", "/perfil_usuario?swfirma=1");
                        window.location.reload();
                    },
                    error: function(data){
                        toastr.error('Hubo un problema al actualizar los datos');
                    }

                });
            }
        });
    })
</script>

    <script>
        var camposprofile = ['current_password','password_first','new_password','name','ap_paterno','ap_materno','email','celular'];
        ValidateAjax("formProfile",camposprofile,"btnSubmit","{{route('updateprofile',code(userId()))}}","POST");
    </script>
@endsection
