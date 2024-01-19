<div class="modal-header">
    <h5 class="modal-title font-weight-bold">
        <i class="fa fa-edit"></i>
        Editar nota de ingreso {{ $income->cod }}
    </h5>
    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
</div>
{{Form::Open(array('action'=>array('InvIncomesController@update',code($income->id)),'method'=>'POST','autocomplete'=>'off','id'=>'formEditIncomes'))}}
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <label id="origenedit--label">* Origen</label>
            <input type="text" class="form-control" name="origenedit" style="width:100%" placeholder="Origen de ingreso" value="{{ $income->origin }}">
            <span id="origenedit-error" class="text-red"></span>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label id="observacionedit--label">Observación</label><br>
                <textarea name="observacionedit" class="form-control" style="width:100%;resize:none" rows="3">{!! purify($income->observation) !!}</textarea>
                <span id="observacionedit-error" class="text-red"></span>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
            <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-yellow pull-right" name="btnSubmitEditIncome">Modificar</button>
        </div>
    </div>
</div>
{{Form::Close()}}

<script>
    // VALIDAR EDITAR ENCABEZADO
    var camposedit = ['origenedit','observacionedit'];
    ValidateAjax("formEditIncomes",camposedit,"btnSubmitEditIncome","{{ route('incomes.update',code($income->id) )}}","POST");
</script>
