{!! Form::open( array('route' =>'clients.store','method'=>'POST','autocomplete'=>'off','files'=>'true','id'=>'formCreateClients', 'onsubmit'=>'btnSubmit.disabled = true; return true;'))!!}
<div class="row">
    {!! datosRegistro('create') !!}
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="col-form-label" id="nombre--label">* Nombre comercial</label> <br>
            <input class="form-control" name="nombre" type="text" placeholder="Nombre comercial">
            <span id="nombre-error" class="text-red"></span>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="col-form-label" id="nit--label">* Número de NIT</label> <br>
            <input class="form-control" name="nit" type="text" placeholder="Número de NIT">
            <span id="nit-error" class="text-red"></span>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
        <div class="form-group">
            <label id="tipo--label">* Tipo de cliente</label>
            <select name="tipo" class="form-control form-select">
                <option value="" hidden>Seleccionar</option>
                <option value="I">Integrador</option>
                <option value="F">Cliente final</option>
                <option value="D">Distribuidor</option>
            </select>
            <span id="tipo-error" class="text-red"></span>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="col-form-label" id="caracteristicas--label">* Caracteristicas/Rubro</label> <br>
            <textarea name="caracteristicas"  rows="2" class="form-control" style="width:100%;resize:none" placeholder="Caracteristicas/Rubro"></textarea>
            <span id="caracteristicas-error" class="text-red"></span>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <label class="col-form-label" id="direccion--label">* Dirección</label> <br>
            <textarea name="direccion"  rows="2" class="form-control" style="width:100%;resize:none" placeholder="Dirección"></textarea>
            <span id="direccion-error" class="text-red"></span>
        </div>
    </div>

    <div class="card mb-2 mt-1">
        <div class="card-body">
            <div class="row p-0">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                    <h3>DATOS DE CONTACTO</h3>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label id="nombreContacto--label">* Nombre completo</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="7" r="4" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                            </span>
                            <input class="form-control" type="text" name="nombreContacto" placeholder="Nombre de contacto">
                        </div>
                        <span id="nombreContacto-error" class="text-red"></span>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label id="cargo--label">* Cargo</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="10" y="4" width="4" height="4" rx="1" /><rect x="3" y="17" width="4" height="4" rx="1" /><rect x="17" y="17" width="4" height="4" rx="1" /><line x1="6.5" y1="17.1" x2="11.5" y2="8" /><line x1="17.5" y1="17.1" x2="12.5" y2="8" /><line x1="7" y1="19" x2="17" y2="19" /></svg>
                            </span>
                            <input class="form-control" type="text" name="cargo" placeholder="Cargo">
                        </div>
                        <span id="cargo-error" class="text-red"></span>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group">
                        <label id="celular--label"> Celular</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="7" y="4" width="10" height="16" rx="1" /><line x1="11" y1="5" x2="13" y2="5" /><line x1="12" y1="17" x2="12" y2="17.01" /></svg>
                            </span>
                            <input class="form-control" type="text" name="celular" placeholder="Celular">
                        </div>
                        <span id="celular-error" class="text-red"></span>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label id="email--label">* Email</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="4" /><path d="M16 12v1.5a2.5 2.5 0 0 0 5 0v-1.5a9 9 0 1 0 -5.5 8.28" /></svg>
                            </span>
                            <input class="form-control" type="text" name="email" placeholder="ejemplo@mail.com">
                        </div>
                        <span id="email-error" class="text-red"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-3">
        <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary pull-right" name="btnSubmit">Registrar</button>
    </div>
</div>
{{Form::Close()}}

<script>

    $(document).ready(function () {
        $(".select2-selection").addClass('form-select2').css('border-color','#ccc');
        $(".select2-selection--single").addClass('form-selectcont');
    });

    var campos = ['nombre', 'nit', 'tipo', 'caracteristicas', 'direccion', 'nombreContacto', 'cargo', 'celular', 'email'];
    ValidateAjax("formCreateClients",campos,"btnSubmit","{{route('clients.store')}}","POST","/clients");
</script>