
@extends ('layouts.admin', ['title_template' => "Crear usuario"])
@section('extracss')
<link rel="stylesheet" href="{{asset('/plugins/iCheck/all.css')}}">

@endsection
@section ('contenidoHeader')
<div class="col">
    <div class="page-pretitle">
        Samaritan's Purse
    </div>
    <h2 class="page-title">
        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="7" r="4" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 11h6m-3 -3v6" /></svg>
        &nbsp;Registrar nuevo usuario
    </h2>
</div>

<div class="col-auto ms-auto d-print-none">
    <div class="btn-list">
        @if (Gate::check('roles.index'))
            <a href="/roles" class="btn btn-outline-secondary">
                <b><svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="15" cy="15" r="4" /><path d="M18.5 18.5l2.5 2.5" /><path d="M4 6h16" /><path d="M4 12h4" /><path d="M4 18h4" /></svg>
                Ver roles</b>
            </a>
        @endif
        @if (Gate::check('users.index'))
            <a href="/users" class="btn btn-outline-secondary">
                <b><svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="7" r="4" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                Ver usuarios</b>
            </a>
        @endif
    </div>
</div>
@endsection
@section ('contenido')
<div class="col-12">
    <div class="card">
        <div class="card-body">
            {!! Form::open(array('route' => 'users.store','method'=>'POST', 'onsubmit'=>'btnSubmit.disabled = true; return true;','id'=>'formCreateUsuarios')) !!}
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <label id="username--label">* Nombre de usuario:</label> &ensp;<small class="form-text text-yellow font-weight-bold">** Con el cual accederá al sistema</small>
                        <input class="form-control" placeholder="Nombre de usuario" id="name_usr" name="username">
                        <span id="validar" class="font-weight-bold" style="text-align:right"></span> <br>
                        <span id="username-error" class="text-red"></span>

                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <label id="password--label">* Contraseña:</label> &ensp; <small class="form-text text-yellow font-weight-bold"></small>
                        <input class="form-control" placeholder="Número de cédula de identidad" name="password">
                        <span id="password-error" class="text-red"></span> <br>
                        <span class="label label-light"></span>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <label id="name--label">* Nombre(s):</label>
                        <input class="form-control" placeholder="Nombre(s)" name="name">
                        <span id="name-error" class="text-red"></span>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <label id="ap_paterno--label">* Apellido Paterno:</label>
                        <input class="form-control" placeholder="Apellido Paterno" name="ap_paterno">
                        <span id="ap_paterno-error" class="text-red"></span>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <label id="ap_materno--label">Apellido Materno:</label>
                        <input class="form-control" placeholder="Apellido Materno" name="ap_materno">
                        <span id="ap_materno-error" class="text-red"></span>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <label id="celular--label">Celular:</label>
                        <input class="form-control" placeholder="Celular" name="celular" >
                        <span id="celular-error" class="text-red"></span>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label id="roles--label">* Asignar rol: </label>
                        <div class="form-group">
                            <select class="form-control form-select" id="tipoRol" name="roles">
                                <option value="" hidden>Seleccionar una opción</option>
                                @foreach($roles as $role)
                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                @endforeach
                            </select>
                            <span id="roles-error" class="text-red"></span>
                        </div>
                    </div>
                </div>

                <div class="pull-right" id="registrar">
                    <button type="submit" class="btn btn-yellow" name="btnSubmit">Registrar</button>
                </div>
                <div class="pull-right" id="msgerror" style="display: none">
                    <span class="help-block">
                        <strong>El nombre de usuario debe ser único</strong>
                    </span>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection
@section('scripts')
    <script>
        var dtTimer;
        $(document).ready(function () {
            $('#tipoRol').children("option:selected").val("");
            clearTimeout(dtTimer);
            $('#name_usr').keyup(function(){
                var query = $('#name_usr').val();
                if (query != '') {
                    clearTimeout(dtTimer);
                    var _token = $('input[name="_token"]').val();
                    dtTimer = setTimeout(function(){
                        $.ajax({
                            url: "{{ route('users.validar') }}",
                            method: "POST",
                            data: { query: query, _token: _token },
                            success: function (data) {
                                $('#validar').fadeIn();
                                $('#validar').html(data.msg);
                                setTimeout(function() {
                                    $('#validar').fadeOut(1500);
                                },5000);
                                //$('#validar').fadeIn();
                                var sw=$('#sw').val();
                                if(sw==0){
                                    $('#msgerror').hide();
                                    $('#registrar').show();
                                    $('#name_usr').removeClass('is-invalid').addClass('is-valid');
                                }else{
                                    $('#name_usr').removeClass('is-valid').addClass('is-invalid');
                                    $('#msgerror').show();
                                    $('#registrar').hide();
                                }
                            }
                        });
                    }, 1000);
                }
            })
        });
    </script>

    {{-- ===========================================================================================
                                    VALIDACION DE CREATE USERS
    =========================================================================================== --}}
    <script>
        var campos = [ 'username', 'password', 'name', 'ap_paterno', 'ap_materno','email','celular', 'roles'];
        ValidateAjax("formCreateUsuarios",campos,"btnSubmit","{{route('users.store')}}","POST","/users");
    </script>
@endsection
