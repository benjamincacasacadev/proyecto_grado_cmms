
<style>
    .input-icon .form-control{
        height: 36px;
    }
</style>
<div class="modal-header">
    <h5 class="modal-title font-weight-bold">
        <i class="fa fa-edit"></i>
        Editar solicitud {{ $outcome->cod }}
    </h5>
    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
</div>
{{Form::Open(array('action'=>array('InvOutcomesController@update',code($outcome->id)),'method'=>'POST','autocomplete'=>'off','id'=>'formEditOutcomes'))}}
<div class="modal-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="ordentrabajoedit-sel2">
            <label id="ordentrabajoedit--label">* Orden de trabajo asociada</label>
            <select name="ordentrabajoedit" class="form-control selector-workorders-edit" id="report_id_edit" style="width: 100%">
                <option value="">Seleccionar</option>
                <option value="{{ code($outcome->wo_id) }}" selected>{{ $outcome->workorders->getAppendCod() }} </option>
            </select>
            <span id="ordentrabajoedit-error" class="text-red"></span>
        </div>

        @php
            $cliente = isset($outcome->workorders->client) ? $outcome->workorders->client->nombre_comercial : "Sin cliente";
            $fecha = isset($outcome->workorders->end_date) ? date("d/m/Y",strtotime($outcome->workorders->end_date)) : "Sin fecha";
        @endphp
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <label>Cliente</label>
            <input type="text" class="form-control text-center cliente_id" id="cliente_id" placeholder="Cliente" disabled value="{{ $cliente }}">
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <label>Fecha de mantenimiento</label>
            <input type="text" class="form-control text-center fecha_rep_id" id="fecha_rep_id" placeholder="Cliente" disabled value="{{ $fecha }}">
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <label id="motivoedit--label">* Motivo</label> <br>
            <input type="text" class="form-control" name="motivoedit" style="width:100%" value="{{ $outcome->reason }}">
            <span id="motivoedit-error" class="text-red"></span>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <label id="fechaedit--label">* Fecha de entrega</label>
            <div class="input-icon">
                <span class="input-icon-addon">
                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="5" width="16" height="16" rx="2" /><line x1="16" y1="3" x2="16" y2="7" /><line x1="8" y1="3" x2="8" y2="7" /><line x1="4" y1="11" x2="20" y2="11" /><rect x="8" y="15" width="2" height="2" />
                    </svg>
                </span>
                @php
                    $fecha = isset($outcome->date) ? date("d/m/Y",strtotime($outcome->date)) : "";
                @endphp
                <input class="form-control datepicker" style="width:100%" type="text" id="fechaedit" name="fechaedit" placeholder="dd/mm/YYYY" value="{{ $fecha }}">
            </div>
            <span id="fechaedit-error" class="text-red"></span>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label id="observacionedit--label">Observación</label><br>
                <textarea name="observacionedit" class="form-control" style="width:100%;resize:none" rows="3">{{ purify($outcome->observation) }}</textarea>
                <span id="observacionedit-error" class="text-red"></span>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
            <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-yellow pull-right" name="btnSubmitEditOutcome">Modificar</button>
        </div>
    </div>
</div>
{{Form::Close()}}


<script>

    $(document).ready(function () {
        $(".select2-selection").addClass('form-select2').css('border-color','#ccc');
        $(".select2-selection--single").addClass('form-selectcont');
    });
    $('.selector-modal').select2({
        dropdownParent: $('#modalEditEncabezadoOutcome')
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

    $('#report_id_edit').change(function () {
        assetClient();
    });
    function assetClient(){
        var query = $("#report_id_edit").val();
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

    // VALIDAR EDITAR ENCABEZADO
    var camposedit = ['ordentrabajoedit','motivoedit','destinoedit','fechaedit','observacionedit'];
    ValidateAjax("formEditOutcomes",camposedit,"btnSubmitEditOutcome","{{ route('outcomes.update',code($outcome->id) )}}","POST");

    // **************************************************************************************************
    // SELECT2 AJAX
    $('select.selector-workorders-edit:not(.normal)').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent().parent(),
            placeholder:'Busque y seleccione uno o varios clientes',
            ajax: {
                url: "{{ route('workorders.listWorkOrdersAjax') }}",
                dataType: 'json',
                method: "GET",
                delay: 250,
                data: function (params) {
                    var query = {
                        estados: ['P'],
                        search: params.term,
                        page: params.page || 5
                    }
                    return query;
                },
            }
        });
    });
</script>
