<div class="modal modalPrimary fade modal-slide-in-right" aria-hidden="true" role="dialog"  id="modalCreate" data-backdrop="static">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">
                    <i class="fa fa-plus"></i>
                    Registro de nota de ingreso
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {!! Form::open( array('route' =>'incomes.store','method'=>'POST','autocomplete'=>'off','files'=>'true','id'=>'formCreateIncomes'))!!}
                    <div class="row">
                        {!! datosRegistro('create') !!}

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label id="origen--label">* Origen</label>
                            <input type="text" class="form-control" name="origen" style="width:100%" placeholder="Origen de ingreso">
                            <span id="origen-error" class="text-red"></span>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label id="observacion--label">Observación</label><br>
                                <textarea name="observacion" class="form-control" style="width:100%;resize:none" rows="3"></textarea>
                                <span id="observacion-error" class="text-red"></span>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
                            <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary pull-right" name="btnSubmit">Registrar</button>
                        </div>
                    </div>
                {{Form::Close()}}
            </div>
        </div>
    </div>
</div>