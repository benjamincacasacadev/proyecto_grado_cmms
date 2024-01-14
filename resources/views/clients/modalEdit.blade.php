<div class="modal-header">
    <h5 class="modal-title font-weight-bold">
        Editar cliente: {{ $cliente->nombre }}
    </h5>
    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    {!! Form::open( array('route' =>'clients.store','method'=>'POST','autocomplete'=>'off','files'=>'true','id'=>'formEditClients', 'onsubmit'=>'btnSubmitEdit.disabled = true; return true;'))!!}
    <div class="row">
        {!! datosRegistro('edit') !!}
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label class="col-form-label" id="nombreedit--label">* Nombre comercial</label> <br>
                <input class="form-control" name="nombreedit" type="text" placeholder="Nombre comercial" value="{{ $cliente->nombre }}">
                <span id="nombreedit-error" class="text-red"></span>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <label class="col-form-label" id="nit--label">* Número de NIT</label> <br>
                <input class="form-control" name="nit" type="text" placeholder="Número de NIT" value="{{ $cliente->nit }}">
                <span id="nit-error" class="text-red"></span>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
            <div class="form-group">
                <label id="tipoedit--label">* Tipo de cliente</label>
                <select name="tipoedit" class="form-control form-select">
                    <option value="" hidden>Seleccionar</option>
                    <option @if($cliente->tipo == 'I') selected @endif value="I">Integrador</option>
                    <option @if($cliente->tipo == 'F') selected @endif value="F">Cliente final</option>
                    <option @if($cliente->tipo == 'D') selected @endif value="D">Distribuidor</option>
                </select>
                <span id="tipoedit-error" class="text-red"></span>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label class="col-form-label" id="caracteristicasedit--label">* Caracteristicas/Rubro</label> <br>
                <textarea name="caracteristicasedit"  rows="2" class="form-control" style="width:100%;resize:none" placeholder="Caracteristicas/Rubro">{!! $cliente->caracteristicas !!}</textarea>
                <span id="caracteristicasedit-error" class="text-red"></span>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label class="col-form-label" id="direccionedit--label">* Dirección</label> <br>
                <textarea name="direccionedit"  rows="2" class="form-control" style="width:100%;resize:none" placeholder="Dirección">{!! $cliente->direccion !!}</textarea>
                <span id="direccionedit-error" class="text-red"></span>
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
                            <label id="nombreContactoedit--label">* Nombre completo</label>
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="7" r="4" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                                </span>
                                <input class="form-control" type="text" name="nombreContactoedit" placeholder="Nombre de contacto" value="{{ $cliente->nombre_contacto}}">
                            </div>
                            <span id="nombreContactoedit-error" class="text-red"></span>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label id="cargoedit--label">* Cargo</label>
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="10" y="4" width="4" height="4" rx="1" /><rect x="3" y="17" width="4" height="4" rx="1" /><rect x="17" y="17" width="4" height="4" rx="1" /><line x1="6.5" y1="17.1" x2="11.5" y2="8" /><line x1="17.5" y1="17.1" x2="12.5" y2="8" /><line x1="7" y1="19" x2="17" y2="19" /></svg>
                                </span>
                                <input class="form-control" type="text" name="cargoedit" placeholder="Cargo" value="{{ $cliente->cargo_contacto }}">
                            </div>
                            <span id="cargoedit-error" class="text-red"></span>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <div class="form-group">
                            <label id="celularedit--label"> Celular</label>
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="7" y="4" width="10" height="16" rx="1" /><line x1="11" y1="5" x2="13" y2="5" /><line x1="12" y1="17" x2="12" y2="17.01" /></svg>
                                </span>
                                <input class="form-control" type="text" name="celularedit" placeholder="Celular" value="{{ $cliente->celular_contacto }}">
                            </div>
                            <span id="celularedit-error" class="text-red"></span>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label id="emailedit--label">* Email</label>
                            <div class="input-icon">
                                <span class="input-icon-addon">
                                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="4" /><path d="M16 12v1.5a2.5 2.5 0 0 0 5 0v-1.5a9 9 0 1 0 -5.5 8.28" /></svg>
                                </span>
                                <input class="form-control" type="text" name="emailedit" placeholder="ejemplo@mail.com " maxlength="50" value="{{ $cliente->email_contacto }}">
                            </div>
                            <span id="emailedit-error" class="text-red"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-3">
            <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary pull-right" name="btnSubmitEdit">Modificar</button>
        </div>
    </div>
    {{Form::Close()}}
</div>

<script>

    $(document).ready(function () {
        $(".select2-selection").addClass('form-select2').css('border-color','#ccc');
        $(".select2-selection--single").addClass('form-selectcont');
    });

    var campos = ['nombreedit', 'nit', 'tipoedit', 'caracteristicasedit', 'direccionedit', 'nombreContactoedit', 'cargoedit', 'celularedit', 'emailedit'];
    ValidateAjax("formEditClients",campos,"btnSubmitEdit","{{ route('clients.update',code($cliente->id) )}}","POST","/clients");
</script>