<style>
    .input-icon .form-control{
        height: 36px;
    }
</style>
{!! Form::open( array('route' =>'outcomes.store','method'=>'POST','autocomplete'=>'off','files'=>'true','id'=>'formCreateOutcomes', 'onsubmit'=>'btnSubmit.disabled = true; return true;'))!!}
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group" id="ordentrabajo-sel2">
                <label id="ordentrabajo--label">* Orden de trabajo asociada</label>
                <i class="text-sm">(Pendientes, en progreso y en pausa)</i><br>
                <select name="ordentrabajo" class="form-control selector-workorders" id="report_id" style="width:100%">
                    <option value="">Seleccionar</option>
                </select>
                <span id="ordentrabajo-error" class="text-red"></span>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <label>Cliente</label>
                <input type="text" class="form-control text-center cursor-not-allowed cliente_id" id="cliente_id" disabled value="-">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <label>Fecha de mantenimiento</label>
                <input type="text" class="form-control text-center cursor-not-allowed fecha_rep_id" id="fecha_rep_id" disabled value="-">
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <label id="motivo--label">* Motivo</label><br>
            <input type="text" class="form-control" name="motivo" style="width:100%" placeholder="Motivo de la solicitud de materiales">
            <span id="motivo-error" class="text-red"></span>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="form-group">
                <label id="fecha--label">* Fecha de entrega</label><br>
                <div class="input-icon">
                    <span class="input-icon-addon">
                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="5" width="16" height="16" rx="2" /><line x1="16" y1="3" x2="16" y2="7" /><line x1="8" y1="3" x2="8" y2="7" /><line x1="4" y1="11" x2="20" y2="11" /><rect x="8" y="15" width="2" height="2" />
                        </svg>
                    </span>
                    <input class="form-control datepicker" style="width:100%" type="text" id="fecha" name="fecha" placeholder="dd/mm/YYYY">
                </div>
                <span id="fecha-error" class="text-red"></span>
            </div>
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

<script>
    $(document).ready(function () {
        $(".select2-selection").addClass('form-select2').css('border-color','#ccc');
        $(".select2-selection--single").addClass('form-selectcont');
    });
    $('.selector-modal').select2({
        dropdownParent: $('#modalCreate')
    });
    $('select.selector-modal:not(.normal)').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent().parent()
        });
    });
    $('.datepicker').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        todayHighlight: true
    });

    $('#report_id').change(function () {
        assetClient();
    });
    function assetClient(){
        var query = $("#report_id").val();
        if (query != '') {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('outcomes.workorderclientajax') }}",
                method: "POST",
                data: { query: query, _token: _token},
                success: function (salida) {
                    $('#cliente_id').val(salida.cliente);
                    $('#fecha_rep_id').val(salida.fecha);
                }
            });
        }
    }
</script>
{{-- ===========================================================================================
                                            VALIDACION
=========================================================================================== --}}
<script>
    var campos = ['ordentrabajo','motivo','fecha','observacion'];

    ValidateAjax("formCreateOutcomes",campos,"btnSubmit","{{route('outcomes.store')}}","POST","/outcomes");

    // **************************************************************************************************
    // SELECT2 AJAX
    $('select.selector-workorders:not(.normal)').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent().parent(),
            placeholder:'Busque y seleccione una orden de trabajo',
            ajax: {
                url: "{{ route('workorders.listWorkOrdersAjax') }}",
                dataType: 'json',
                method: "GET",
                delay: 250,
                data: function (params) {
                    var query = {
                        estados: ['P','E','S'],
                        search: params.term,
                        page: params.page || 5
                    }
                    return query;
                },
            }
        });
    });
</script>
