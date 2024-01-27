<div class="modal-header">
    <h5 class="modal-title font-weight-bold">
        Cambio de estado solicitud {{ $outcome->cod }}
    </h5>
    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    {{ Form::Open(['action' => ['InvOutcomesController@updateState', code($outcome->id)], 'method' => 'POST', 'autocomplete' => 'off', 'id' => 'formStateOutcomes']) }}
    <div class="row">
        <div class="form-selectgroup-boxes row mb-3" id="checkstate--label">

            @if($swcant == 1 || $swloc == 1)
                <b class="text-orange">No puede validar la solicitud </b> <br>
            @endif

            @if($swcant == 1)
                <span class="text-muted" style="font-size:12px" >
                    - Hay detalles en las que la <b>cantidad solicitada</b> excede la <b>cantidad disponible</b> de materiales.
                </span>
            @endif

            @if($swloc == 1)
                <span class="text-muted" style="font-size:12px" >
                    - Debe asignar una ubicación en todos los detalles.
                </span>
            @endif

            @if($swcant == 0 && $swloc == 0)
                <div class="col-lg-6 text-center">
                    <label class="form-selectgroup-item text-center">
                        <input type="radio" name="checkstate" value="2" class="form-selectgroup-input" id="radiovali">
                        <span class="form-selectgroup-label d-flex align-items-center p-3 ">
                            <span class="form-selectgroup-label-content">
                                <span class="me-3">
                                    <span class="form-selectgroup-check"></span>
                                </span>
                                <span class="form-selectgroup-title font-weight-bold mb-1 text-success">
                                    Validar solicitud
                                </span>
                            </span>
                        </span>
                    </label>
                </div>
            @endif
            <div class="@if($swcant == 0 && $swloc == 0) col-lg-6 @else col-lg-12 @endif">
                <label class="form-selectgroup-item">
                    <input type="radio" name="checkstate" value="0" class="form-selectgroup-input" id="radioanul">
                    <span class="form-selectgroup-label d-flex align-items-center p-3">
                        <span class="form-selectgroup-label-content">
                            <span class="me-3">
                                <span class="form-selectgroup-check"></span>
                            </span>
                            <span class="form-selectgroup-title font-weight-bold mb-1 text-red">
                                Anular solicitud
                            </span>
                        </span>
                    </span>
                </label>
            </div>
            <center><span id="checkstate-error" class="text-red font-weight-bold"></span></center>
        </div>

        <label class="validdiv">Lista de materiales (Resumen)</label>
        <div class="table-responsive validdiv">
            <table class="table table-vcenter table-center table-sm table-hover text-sm" id="table_detailsmodal">
                <thead>
                    <tr>
                        <th width="3%">Nº</th>
                        <th width="20%">Código de material</th>
                        <th width="8%">Cantidad</th>
                        <th width="20%">Almacen</th>
                    </tr>
                </thead>

                <tbody >
                </tbody>
            </table>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 anulardiv" style="display:none">
            <div class="form-group">
                <label id="motivo--label">Motivo de anulación</label><br>
                <textarea name="motivo" class="form-control" style="width:100%;resize:none" rows="3"></textarea>
                <span id="motivo-error" class="text-red"></span>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
            <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-yellow pull-right" name="btnSubmitStateOutcome">Actualizar estado</button>
        </div>
    </div>
    {{ Form::Close() }}
</div>

<script>
    $("#radiovali").change(function() {
        if($(this).is(":checked")) {
            $(".validdiv").slideDown();
            $(".anulardiv").slideUp();
        }
    });
    $("#radioanul").change(function() {
        if($(this).is(":checked")) {
            $(".validdiv").slideUp();
            $(".anulardiv").slideDown();
        }
    });
    $(function () {
        var outcome = "{{ code($outcome->id) }}";
        var table = $('#table_detailsmodal').DataTable({
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': false,
            'info': true,
            'autoWidth': false,
            "order": [['0', 'desc']],
            'mark': "true",
            'dom': 'rt',
            "pageLength": 25,
            processing: true,
            serverSide: true,
            "columnDefs": [{
                "orderable": false,
                "targets": ["_all"]
            }],
            "ajax": {
                "url": "{{ route('outcomes.details.table') }}",
                'dataType': 'json',
                'type': 'post',
                'data': {
                    "_token": "{{ csrf_token() }}",
                    outcome: outcome
                },
                "error": function(reason) {
                    errorsDatatable(reason.status);
                },
            },
            "columns": [
                { "data": "nro" },
                {"data": "itemmodal"},
                {"data": "cant"},
                {"data": "locationmodal"},
            ],
            "drawCallback": function () {
                $('[data-toggle="tooltip"]').tooltip({
                    html: true
                });
            }
        });
    });

    var statefields = ["checkstate","motivo"];
    ValidateAjax("formStateOutcomes",statefields,"btnSubmitStateOutcome","{{ route('outcomes.state',code($outcome->id) )}}","POST","/outcomes/show/{{ code($outcome->id) }}");

</script>
