<!doctype html>
<html lang="es" dir="ltr">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="U.E. Rep. Federal de Alemania" />
    <meta name="description" content="Unidad Educativa República Federal de Alemania" />
    <meta name="author" content="Benjamin Cacasaca" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Inicio</title>
    {{-- <title>U.E. República Federal de Alemania</title> --}}
    {{-- <link rel="shortcut icon" href="{{asset('favicon.ico?1')}}" /> --}}
    <link rel="stylesheet" href="{{asset('/templates/tabler/dist/css/tabler.min.css')}}" />
    <link rel="stylesheet" href="{{asset('/plugins/iCheck/all.css')}}">
    <link rel="stylesheet" href="{{asset('/plugins/font-awesome1/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('/plugins/font-awesome/css/all.css')}}">
    <style>
        html, body {
            height: 100%;
        }
        .main {
            height: 100%;
            width: 100%;
            display: table;
        }
        .wrapper {
            display: table-cell;
            height: 100%;
            vertical-align: middle;
        }

        body {
            font-family: 'nunito', sans-serif;
            background-image: url("{{ asset('/fondo.png')}}");
            background-color: #2e2e2c;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
        }
        @font-face {
            font-family: 'nunito';
            src:  url({{asset('fonts/nunito.ttf') }});
        }
        .icon-tabler {
            width: 23px;
            height: 23px;
            stroke-width: 2.5;
            cursor: pointer !important;
        }
        .text-5xl {
            font-size: 3rem;
            line-height: 1;
        }
        .text-4xl {
            font-size: 2rem;
            line-height: 1;
        }
        .text-xl {
            font-size: 1.25rem;
            line-height: 1.75rem;
        }
        .text-indigo-200 {
            color: rgba(199, 210, 254, 1);
        }


        @media (max-width: 1278px) {
            #loginsubmit {
                padding: 50px 0px;
            }
            #formEnviarPass {
                padding: 50px 0px;
            }
        }
        .container {
            max-width: 1536px;
        }
        .font-bold{
            font-weight: 700 !important
        }
        .big{
            font-size:1.35rem;
        }
        .bigText{
            font-size:16px !important;
        }
        @media (min-width:900px) {
            .d-md-inline-title {
                display: inline !important
            }
        }
    </style>
</head>
<body >
    @php
        $op = isset($_GET['show']) ? $_GET['show'] : 0;
        $error = isset($_GET['error']) ? $_GET['error'] : 0;
        $msg = isset($_GET['msg']) ? $_GET['msg'] : 0;
        $reset = isset($_GET['reset']) ? $_GET['reset'] : 0;

        $classUser = $errors->has('username') ? 'is-invalid' : '';
        $classPass = $errors->has('password') ? 'is-invalid' : '';
        $borderPass = $errors->has('password') ? 'border-danger' : '';
        $showError = $errors->has('username') || $errors->has('password');
        $classUserEnviar = $error==1 ? 'is-invalid' : '';
    @endphp
    <div class = "main">
        <div class = "wrapper">
            <div class="d-flex justify-content-center mt-1" id="allContent">
                <div class="row container align-items-center justify-content-center">
                    <div class="col-lg-5 col-md-7 col-sm-12 card mt-3" style="border-radius: 30px" id="divPrincipal">
                        <div class="mx-2" id="contentLogin">
                            <div id="login" @if($op==1) hidden @endif>
                                <form id="loginsubmit" method="POST" action="{{ route('login') }}" style="border-radius: 30px" onsubmit="btnLogin.disabled = true; return true;">
                                    @csrf
                                    <div class="text-center" style="margin-top: 2rem !important">
                                        {{-- <img src="{{asset('logo.png')}}" style="height: 11rem !important;" > <br>
                                        <b style="font-size:25px; font-weight-bolder">Unidad Educativa República Federal de Alemania</b> --}}
                                        <img src="{{asset('logo.png')}}" style="height: 6rem !important;"> <br><br>
                                        <b style="font-size:25px; font-weight-bolder">Sistema de gestión de mantenimiento computarizado</b>
                                    </div>
                                    <div class="card-body">
                                        @if($reset==1)
                                            <div class="item-action mt-2 font-weight-bold text-center">
                                                <span class="bg-green-lt badge-pill" style="padding:2px 15px 2px 15px;font-size:19px">
                                                    Su contraseña fue modificada con éxito
                                                    <i class="fas fa-check-circle"></i>
                                                </span>
                                            </div>
                                        @endif
                                        @if ($showError)
                                            <div class="item-action mt-2 font-weight-bold text-center msgError">
                                                <span class="bg-red-lt badge-pill" style="padding:2px 15px 2px 15px;font-size:17px">
                                                    <i class="fas fa-times"></i> &nbsp;
                                                    Nombre de usuario y/ó contraseña incorrectos
                                                </span>
                                            </div>
                                        @endif
                                        <div class="form-group mt-4">
                                            <b class="form-label font-bold bigText">Nombre de usuario</b>
                                            <div class="input-icon mb-1">
                                                <span class="input-icon-addon">
                                                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <circle cx="12" cy="7" r="4"></circle>
                                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                                    </svg>
                                                </span>
                                                <input type="text" class="pass_quit form-control form-control-rounded {{$classUser}}" id="usernameInput" aria-describedby="emailHelp" name="username" placeholder="Nombre de usuario" value="{{$data1}}">
                                            </div>
                                            <span class="text-red font-bold" id="user-error" style="display: none"><i class="fa fa-ban"></i>&nbsp;El campo nombre de usuario es obligatorio</span>
                                        </div>

                                        <div class="mt-3">
                                            <label class="form-label font-bold bigText">
                                                Contraseña
                                            </label>
                                            <div class="input-group input-group-flat mb-1">
                                                <span class="input-group-text {{$borderPass}} borderPassword" style="border-bottom-left-radius: 10rem !important; border-top-left-radius: 10rem !important;">
                                                    <svg class="icon " width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                                                        <circle cx="12" cy="16" r="1"></circle>
                                                        <path d="M8 11v-4a4 4 0 0 1 8 0v4"></path>
                                                    </svg>
                                                </span>
                                                <input type="password" class="pass_quit form-control form-control-rounded {{ $classPass }}" name="password" placeholder="Contraseña" value="{{$data2}}"  id="passwordInput">
                                                <span class="input-group-text cursor-pointer {{$borderPass}} borderPassword" style="border-bottom-right-radius: 10rem !important; border-top-right-radius: 10rem !important">
                                                    <a class="link-secondary ms-2" title="Mostrar contraseña" id="passShow">
                                                        <svg class="icon icon-tabler" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <circle cx="12" cy="12" r="2"></circle>
                                                            <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"></path>
                                                        </svg>
                                                    </a>
                                                    <a class="link-secondary ms-2" title="Ocultar contraseña" id="passHide" style="display: none" >
                                                        <svg class="icon icon-tabler icon-tabler-eye-off" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <line x1="3" y1="3" x2="21" y2="21"></line>
                                                            <path d="M10.584 10.587a2 2 0 0 0 2.828 2.83"></path>
                                                            <path d="M9.363 5.365a9.466 9.466 0 0 1 2.637 -.365c4 0 7.333 2.333 10 7c-.778 1.361 -1.612 2.524 -2.503 3.488m-2.14 1.861c-1.631 1.1 -3.415 1.651 -5.357 1.651c-4 0 -7.333 -2.333 -10 -7c1.369 -2.395 2.913 -4.175 4.632 -5.341"></path>
                                                        </svg>
                                                    </a>
                                                </span>
                                            </div>
                                            <span class="text-red font-bold" id="pass-error" style="display: none"><i class="fa fa-ban"></i>&nbsp;El campo contraseña es obligatorio</span>
                                        </div>

                                        <div class="form-group mt-3">
                                            <label>
                                                <input type="checkbox" name="remember" id="remember" @if($data1!=null && $data2!=null) {{ 'checked'}} @endif style="padding-bottom:10px !important"/>
                                                <span class="font-bold cursor-pointer" style="font-size:16px; ">Recordar</span>
                                            </label>
                                        </div>
                                        <div class="form-footer text-center mt-4">
                                            <button type="button" class="btn btn-danger w-100 btn-pill text-center font-bold btn-lg" id="BtnLogin" name="btnLogin">Iniciar sesión</button>
                                        </div>
                                        {{-- <div class="d-flex align-items-end flex-column mt-4 mb-0" >
                                            <a id="olvido" class="float-right font-bold text-yellow">Olvidé mi contraseña</a>
                                        </div> --}}
                                    </div>
                                </form>
                            </div>

                            <div id="enviar" @if($op==0) style="display:none" @endif>
                                <form id="formEnviarPass" method="POST" action="{{ route('password.email') }}" onsubmit="enviar.disabled = true; return true;">
                                    @csrf
                                    <div class="text-center mt-3 mb-3">
                                        <img src="{{asset('logo.png')}}" style="height: 8rem !important;" >
                                    </div>
                                    @if ($msg==0)
                                        <div class="card-body">
                                            <div class=" bigText font-bold text-yellow">¿Olvidó su contraseña?</div>
                                            <p class="text-muted bigText">Ingrese el nombre de usuario con el que ingresa al sistema.</p>

                                            <div class="form-group mt-2">
                                                <b class="form-label font-bold" style="font-size:16px">* Nombre de usuario</b>
                                                <div class="input-icon mb-2">
                                                    <span class="input-icon-addon">
                                                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <circle cx="12" cy="7" r="4"></circle>
                                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                                        </svg>
                                                    </span>
                                                    <input type="text" class="form-control form-control-rounded {{$classUserEnviar}}" id="usernameMail" name="username" placeholder="Nombre de usuario" value="{{ $data1 }}">
                                                </div>

                                                @if ($error==1)
                                                    <span class="text-red font-weight-bold msgEnviar">El nombre de usuario es inexistente</span>
                                                @elseif($error==2)
                                                    <span class="text-red font-weight-bold msgEnviar">La nueva contraseña no pudo enviarse. Intente nuevamente</span>
                                                @endif
                                                <span class="text-red font-bold" id="userEnviar-error" style="display: none"><i class="fa fa-ban"></i>&nbsp;El campo nombre de usuario es obligatorio</span>
                                            </div>
                                            <div class="form-footer text-center mt-4">
                                                <button type="button" class="btn btn-yellow w-100 btn-pill text-center font-bold btn-lg" id="btnEnviar" name="enviar">Enviar nueva contraseña</button>
                                            </div>
                                            <div class="d-flex align-items-end flex-column mt-4 mb-0" >
                                                <a href="/login" class="float-right font-bold text-yellow">Volver a pantalla de inicio de sesión</a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="card-body p-6 text-center mt-2">
                                            <img src="{{asset('email.png')}}" style="height: 6rem !important;" class="mb-3" >
                                            <p class="text-yellow font-bold bigText">Correcto!</p>
                                            <span style="font-size:18px">Le enviamos instrucciones para realizar el cambio de contraseña a su dirección de correo electrónico</span>
                                            <p class="text-muted bigText">Revise la bandeja de entrada o la carpeta de spam.</p>
                                        </div>
                                        <div class="text-center text-muted bigText" >
                                            <a href="/login" class="font-bold text-yellow">Volver</a> a pantalla de Inicio de Sesión.
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="{{asset('/dist/js/jquery.min.js')}}"></script>
<script src="{{asset('/templates/tabler/dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('/templates/tabler/dist/js/tabler.min.js')}}"></script>
<script src="{{asset('/templates/tabler/dist/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('/plugins/iCheck/icheck.min.js')}}"></script>
<script>
    $('#olvido').click(function(){
        $('#login').hide();
        $('#enviar').fadeIn(1000);
    });
    $( "#passShow" ).click(function() {
        $("#passHide").show();
        $("#passShow").hide();
        $('#passwordInput').removeAttr('type');
    });
    $("#passHide").click(function() {
        $("#passShow").show();
        $("#passHide").hide();
        $('#passwordInput').attr('type','password');
    });

    $(document).ready(function() {
        $('#olvido').css('cursor', 'pointer');
        @if($showError)
            setTimeout(function() {
                $(".pass_quit").removeClass('is-invalid');
                $(".borderPassword").removeClass('border-danger')
                $('.spanmsg').hide();
                $('.msgError').slideUp();
            },5000);
        @endif

        @if($error == 1 || $error == 2)
            setTimeout(function() {
                $("#usernameMail").removeClass('is-invalid');
                $('.msgEnviar').hide();
            },5000);
        @endif

        if ($(window).width() <= 1222){
            $("#titleCmms").removeClass('text-5xl').addClass('text-4xl');
        }else{
            $("#titleCmms").removeClass('text-4xl').addClass('text-5xl');
        }
        if ($(window).width() <= 1278){
            $("#contentLogin").removeClass('py-3').addClass('py-0');
        }else{
            $("#contentLogin").removeClass('py-1').addClass('py-3');
        }
        if ($(window).width() <= 900){
            $("#topTitle").show();
        }else{
            $("#topTitle").hide();
        }

        if ($(window).width() <= 768){
            $("#allContent").removeClass('mt-1').addClass('mt-5');
            $("#topTitle").hide();
        }else{
            $("#allContent").removeClass('mt-5').addClass('mt-1');
        }
    });

    $(window).resize(function(){
        if ($(window).width() <= 1222){
            $("#titleCmms").removeClass('text-5xl').addClass('text-4xl');
        }else{
            $("#titleCmms").removeClass('text-4xl').addClass('text-5xl');
        }
        if ($(window).width() <= 1278){
            $("#contentLogin").removeClass('py-3').addClass('py-0');
        }else{
            $("#contentLogin").removeClass('py-1').addClass('py-3');
        }
        if ($(window).width() <= 900){
            $("#topTitle").show();
        }else{
            $("#topTitle").hide();
        }

        if ($(window).width() <= 768){
            $("#allContent").removeClass('mt-1').addClass('mt-5');
            $("#topTitle").hide();
        }else{
            $("#allContent").removeClass('mt-5').addClass('mt-1');
        }
    })

    function requiredLogin(){
        var most = 0;
        var user = $("#usernameInput");
        var pass = $("#passwordInput");
        if( !user.val() ){
            most++;
            user.addClass('is-invalid');
            $('#user-error').show();
        }else{
            user.removeClass('is-invalid');
            $('#user-error').hide();
        }

        if( !pass.val() ){
            most++;
            pass.addClass('is-invalid');
            $('.borderPassword').addClass('border-danger');
            $('#pass-error').show();

        }else{
            pass.removeClass('is-invalid');
            $('.borderPassword').removeClass('border-danger');
            $('#pass-error').hide();
        }
        return most;
    }

    $( "#BtnLogin" ).click(function(e) {
        requiredLogin();
        var most = requiredLogin();
        if(most == 0){
            $("#BtnLogin").text('Iniciando...');
            var pass = $('#passwordInput').val();
            $('#passwordInput').val( $.trim(pass) );
            $( "#loginsubmit" ).submit();
        }
    });

    function requiredEnviar(){
        var enviar = 0;
        var userEnviar = $("#usernameMail");

        if( !userEnviar.val() ){
            enviar++;
            userEnviar.addClass('is-invalid');
            $('#userEnviar-error').show();

        }else{
            userEnviar.removeClass('is-invalid');
            $('#userEnviar-error').hide();
        }
        return enviar;
    }

    $( "#btnEnviar" ).click(function(e) {
        requiredEnviar();
        var enviar = requiredEnviar();
        $(".msgEnviar").hide();
        if(enviar == 0){
            $("#btnEnviar").text('Enviando...');
            $( "#formEnviarPass" ).submit();
        }
    });

    $('#usernameInput, #passwordInput').keypress(function (e) {
        if (e.which == 13) {
            requiredLogin();
            var enviar = requiredLogin();
            $(".msgEnviar").hide();
            console.log(enviar);
            if(enviar == 0){
                $("#BtnLogin").text('Iniciando...');
                $( "#loginsubmit" ).submit();
            }
            return false;    //<---- Add this line
        }
    });
    $('#usernameMail').keypress(function (e) {
        if (e.which == 13) {
            e.preventDefault();
            requiredEnviar();
            var enviar = requiredEnviar();
            $(".msgEnviar").hide();
            if(enviar == 0){
                $("#btnEnviar").text('Enviando...');
                $( "#formEnviarPass" ).submit();
            }
        }
    });

    $(function () {
        $('#remember').iCheck({
            checkboxClass: 'icheckbox_square-red',
            increaseArea: '20%'
        });
    });
</script>
</html>
