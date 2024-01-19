<style>
    #inputCoditemedit, #inputCodpartedit {
        cursor : pointer;
        background-color: transparent;
    }
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
</style>
{!! Form::open( array('route' =>'transfers.store','method'=>'POST','autocomplete'=>'off','files'=>'true','id'=>'formCreateTransfer'))!!}
    <div class="row">
        {{-- ====================================================================================================================== --}}
        {{--                                                TRASPASO DE ITEMS DE INVENTARIO                                         --}}
        {{-- ====================================================================================================================== --}}
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divitem">
            <label id="itemedit--label">* Material</label>
            <a href="/incomes/items/modal/" rel="modalItemsEdit">
                <input type="text" class="form-select2 form-control text-center font-weight-bold text-yellow" placeholder="Seleccionar" id="inputCoditemedit" readonly >
                <input type="text" id="itemcodedit" hidden name="itemedit" value="">
            </a>
            <span id="itemedit-error" class="text-red text-sm"></span>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 divitem2">
            <div class="form-group" id="origentransf-sel2">
                <label id="origentransf--label">* Ubicación origen</label>
                <select name="origentransf" class="form-control selector-modal" id="ubi_origen" data-placeholder="Ubicaciones" title="Primero debe seleccionar un material">
                </select>
                <span id="origentransf-error" class="text-red text-sm"></span>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 divitem2">
            <div class="form-group" id="destinotransf-sel2">
                <label id="destinotransf--label">* Ubicación destino</label>
                <select name="destinotransf" class="form-control selector-modal" id="ubi_destino" data-placeholder="Ubicaciones" title="Primero debe seleccionar un material">
                </select>
                <span id="destinotransf-error" class="text-red text-sm"></span>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divGral">
            <div class="form-group">
                <label id="cantidadtransf--label">* Cantidad a traspasar</label>
                <input type="text" class="form-control w-100 numero" name="cantidadtransf" placeholder="Cantidad de materiales a traspasar">
                <span id="cantidadtransf-error" class="text-red text-sm"></span>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 divGral">
            <div class="form-group">
                <label id="observaciontransf--label">Observación</label><br>
                <textarea name="observaciontransf" style="resize:none" rows="3" class="form-control w-100"></textarea>
                <span id="observaciontransf-error" class="text-red text-sm"></span>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:20px">
            <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-yellow pull-right" name="btnSubmit">Registrar</button>
        </div>
    </div>
{{Form::Close()}}


{{-- modal Items de inventario --}}
<div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalItemsEdit" data-backdrop="static" >
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
        $('[data-toggle="popover"]').popover();
    });

    $('.selector-modal').select2({
        dropdownParent: $('#modalTransferItems')
    });
    $('[data-toggle="popoveredit"]').popover({
        html: true,
        "trigger" : "hover",
        "placement": "top",
        "container": "body",
    });
    $('select.selector-modal:not(.normal)').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent().parent(),
            width:'100%'
        });
    });
    AutoNumeric.multiple('.numero',{
        modifyValueOnWheel: false,
        digitGroupSeparator : '',
        minimumValue: 1
    });
    modalAjax("modalItemsEdit","modalItemsEdit","modal-content-aux");
    $('#modalItemsEdit').on('hidden.bs.modal', function (e) {
        selectLocations();
        function selectLocations(){
            var query = $("#itemcodedit").val();
            if (query != '') {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('transfers.locations.ajax') }}",
                    method: "POST",
                    data: { query: query, _token: _token},
                    success: function (salida) {
                        $('#ubi_origen').html(salida.origen);
                        $('#ubi_destino').html(salida.destino);
                        $(".divitem2").show();
                        $(".divGral").show();
                    }
                });
            }
        }
    })

    var campos = ['check','itemedit','origentransf','destinotransf','partedit','origentransfparts','destinotransfparts','cantidadtransf','observaciontransf'];
    ValidateAjax("formCreateTransfer",campos,"btnSubmit","{{ route('transfers.store') }}","POST");
</script>
