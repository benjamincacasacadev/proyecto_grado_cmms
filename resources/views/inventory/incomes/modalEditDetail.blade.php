<style>
    .modal-content-aux {
        position: relative;
        display: flex;
        flex-direction: column;
        width: 100%;
        pointer-events: auto;
        background-color: #fbfbfb;
        background-clip: padding-box;
        border: 1px solid transparent;
        border-radius: 4px;
        outline: 0;
    }

    #inputCoditemedit, #inputCodpartedit {
        cursor : pointer;
        background-color: transparent;
    }
</style>
<div class="modal-header">
    <h5 class="modal-title">
        Editar material
    </h5>
    <button type="button" class="btn-close" aria-label="Close" data-dismiss="modal"></button>
</div>
{{Form::Open(array('action'=>array('InvIncomesDetailsController@updateDetails',code($detail->id)),'method'=>'POST','autocomplete'=>'off','id'=>'formDetailEdit'))}}
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label id="itemedit--label">* Código de material</label>
                <a href="/incomes/items/modal/" rel="modalItemsEdit">
                    <input type="text" class="form-select2 form-control text-center font-weight-bold text-yellow" placeholder="Seleccionar" id="inputCoditemedit" readonly value="{{ $detail->items->cod." - ".$detail->items->title }}" title="Clic para cambiar el material">
                    <input type="text" id="itemcodedit" hidden name="itemedit" value="{{ code($detail->item_id) }}">
                </a>
                <span id="itemedit-error" class="text-red text-sm"></span>
            </div>


            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <label id="cantidadedit--label">* Cantidad</label>
                <input type="text" class="form-control numeroedit" name="cantidadedit" placeholder="Cantidad" value="{{ $detail->quantity }}">
                <span id="cantidadedit-error" class="text-red text-sm"></span>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label id="ubicacionedit--label">* Almacen</label>
                    <select name="ubicacionedit" class="form-control selector-modal" style="width:100%">
                        <option value="" hidden>Seleccionar</option>
                        <option value="1" @if($detail->location == '1') selected @endif>Edificio Arcadia</option>
                        <option value="2" @if($detail->location == '2') selected @endif>El Alto</option>
                        <option value="3" @if($detail->location == '3') selected @endif>Gramadal</option>
                        <option value="4" @if($detail->location == '4') selected @endif>Edifico técnico</option>
                    </select>
                    <span id="ubicacionedit-error" class="text-red text-sm"></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label id="observacionedit--label">Observación</label>
                <input type="text" class="form-control" name="observacionedit" value="{{ $detail->observation }}">
                <span id="observacionedit-error" class="text-red text-sm"></span>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
                <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-yellow pull-right" name="btnSubmitEdit">Modificar</button>
            </div>
        </div>
    </div>
{{Form::Close()}}


{{-- modal Items de inventario --}}
<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalItemsEdit" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content-aux">
        </div>
    </div>
</div>

<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalEditParts" data-backdrop="static" >
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content-aux">
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $(".select2-selection").addClass('form-select2').css('border-color','#ccc');
        $(".select2-selection--single").addClass('form-selectcont');
    });
    $('.selector-modal').select2({
        dropdownParent: $('#modalEdit')
    });
    $('select.selector-modal:not(.normal)').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent().parent()
        });
    });
    AutoNumeric.multiple('.numeroedit',{
        modifyValueOnWheel: false,
        digitGroupSeparator : '',
        minimumValue: 0
    });
    modalAjax("modalItemsEdit","modalItemsEdit","modal-content-aux");
    modalAjax("modalEditParts","modalEditParts","modal-content-aux");

    var campos = ['itemedit','cantidadedit','ubicacionedit','observacionedit'];
    ValidateAjax("formDetailEdit",campos,"btnSubmitEdit","{{ route('incomes.details.update',code($detail->id) )}}","POST","/incomes/{{ code($detail->income_id) }}");
</script>
