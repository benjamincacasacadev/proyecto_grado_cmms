@extends ('layouts.admin', ['title_template' => "Solicitud de materiales"])
@section('extracss')
    <style>
        table#tableOutcomes th{
            font-size:12px;
        }
        table#tableOutcomes td{
            font-size: 12px !important;
        }
        .just{
            text-align: justify !important;
            font-size: 11px !important;
        }
        .left{
            text-align: left !important;
        }
        .icon-tabler {
            width: 25px;
            height: 25px;
            stroke-width: 1.25;
            margin-bottom: 2px;
        }
    </style>
@endsection

@section ('contenidoHeader')
    <div class="col">
        <div class="page-pretitle">
            {{ nameEmpresa() }}
        </div>
        <h1 class="titulomod">
            <svg class="icon icon-tabler" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"></path>
                <path d="M7 12h14l-3 -3m0 6l3 -3"></path>
            </svg>
            Solicitudes
        </h1>
    </div>

    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="/outcomes/createmodal/" rel="modalCreate" type="button" class="btn btn-pill btn-yellow">
                <i class="fa fa-plus" ></i> &nbsp;Solicitud
            </a>
        </div>
    </div>
@endsection

@section ('contenido')

<div class="row">
    {!! Form::open(['route'=>'outcomes.index','method'=>'GET', 'id' => 'formFilterIncome']) !!}
        <div class="pull-right">
            <div class="form-inline"  >
                <div class="form-group">
                    <div class="divEstado pull-right" >
                        <select class="form-control selectEstado" name="selectEstado" style=" margin-right:-10px; width:192px;">
                            <option @if ($selectEstado=="" ) {{ 'selected' }} @endif value="">Todos</option>
                            <option @if ($selectEstado=="0" ) {{ 'selected' }} @endif value="0">Anulado</option>
                            <option @if ($selectEstado=="1" ) {{ 'selected' }} @endif value="1">Pendiente</option>
                            <option @if ($selectEstado=="2" ) {{ 'selected' }} @endif value="2">Validado</option>
                        </select>
                    </div>
                    <span class="pull-right font-weight-bold" style="margin-top: 8px;">Estado:&nbsp;&nbsp; </span>
                </div>
            </div>
        </div>
    {{ Form::close() }}
</div>

<div class="table-responsive">
    <table class="table table-vcenter table-center table-sm table-hover" id="tableOutcomes">
        <thead>
            <tr>
                <th width="8%">Código</th>
                <th width="15%">Fecha</th>
                <th width="15%">Motivo</th>
                <th width="10%">OT asociada</th>
                <th width="15%">Fecha de entrega</th>
                <th width="15%">Estado</th>
                @if(permisoAdminJefe())
                    <th width="8%">Op.</th>
                @endif
            </tr>
        </thead>
        <thead role="row">
            <tr class="filters">
                <td><input style="width: 100%;font-size:10px" id="income0" class="form-control font-weight-bold nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="codb"/></td>
                <td><input style="width: 100%;font-size:10px" id="income1" class="form-control font-weight-bold nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="fechab"/></td>
                <td><input style="width: 100%;font-size:10px" id="income2" class="form-control font-weight-bold nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="motivob"/></td>
                <td><input style="width: 100%;font-size:10px" id="income2" class="form-control font-weight-bold nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="otb"/></td>
                <td><input style="width: 100%;font-size:10px" id="income3" class="form-control font-weight-bold nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="fechab"/></td>
                <td></td>
                @if(permisoAdminJefe())
                    <td></td>
                @endif
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

    {{-- Modal Crear --}}
    <div class="modal modalPrimary fade modal-slide-in-right" aria-hidden="true" role="dialog"  id="modalCreate" data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-plus"></i> Nueva solicitud</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>

    {{-- modal Editar --}}
    <div class="modal modalCyan fade modal-slide-in-right" aria-hidden="true" role="dialog"  id="modalEdit" data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            </div>
        </div>
    </div>

    {{-- modal Eliminar --}}
    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog"  id="modalDelete" data-backdrop="static">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
            </div>
        </div>
    </div>

    {{-- modal Cambio de estado --}}
    <div class="modal modalPrimary fade modal-slide-in-right" aria-hidden="true" role="dialog" id="modalState" data-backdrop="static">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    modalAjax("modalCreate","modalCreate","modal-body");
    modalAjax("modalState","modalState","modal-content");
    modalAjax("modalEdit","modalEdit","modal-content");
    modalAjax("modalDelete","modalDelete","modal-content");
    $(document).ready(function () {
        $(".selectEstado").val("{{$selectEstado}}");
    });
    // modal Create
    var campos = ['asociado','origen','observacion'];
    $( "#add_income" ).click(function() {
        $('#modalCreate').modal('show');
        $.each(campos, function( indice, valor ) {
            $("#"+valor+"-error").html( "" );
            $("[name="+valor+"]").removeClass('is-invalid').removeClass('is-valid');
            $("select[name="+valor+"]").removeClass('is-invalid-select').removeClass('is-valid-select');
            $("#formCreateAreas #"+valor+"-sel2 .select2-selection").removeClass('is-invalid-select').removeClass('is-valid-select');
        });
    });

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

    // Funciones DATATABLE
    $(function () {
        var state = "{{ $selectEstado }}";
        var table = $('#tableOutcomes').DataTable({
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': false,
            'info': true,
            'autoWidth': false,
            "order": [['0', 'desc']],
            'mark': "true",
            'dom': 'lrtip',
            "stateSave": false,
            "pageLength": 25,
            processing: true,
            serverSide: true,
            "columnDefs": [{
                "orderable": false,
                "targets": ["_all"]
            }],
            "ajax": {
                "url": "{{ route('outcomes.table') }}",
                'dataType': 'json',
                'type': 'post',
                'data': {
                    "_token": "{{ csrf_token() }}",
                    state: state,
                },
            },
            "columns": [
                {"data": "cod"},
                {"data": "date"},
                {"data": "reason"},
                {"data": "workorders"},
                {"data": "delivery_date"},
                {"data": "state"},
                @if (permisoAdminJefe())
                    {"data": "operations"},
                @endif
            ],
            "drawCallback": function () {
                restartActionsDT();
                $(function () {
                    $('[data-toggle="popover"]').popover({
                        html: true,
                        "trigger" : "hover",
                        "placement": "top",
                        "container": "body",
                    })

                    $('[data-toggle="popoverOper"]').popover({
                        html: true,
                        "trigger" : "focus",
                        "placement": "left",
                        "container": "body",
                        delay: {
                            "hide": 200
                        }
                    });
                    $('[data-toggle="tooltip"]').tooltip({
                        html: true,
                        "trigger" : "hover",
                        "placement": "top",
                        "container": "body",
                    });
                });
            }
        });

        // BUSCAR Filtros de DataTable
        filterInputDT(table);
    });

    $('.selectEstado').on('change', function () {
        $('#formFilterIncome').submit();
    });
</script>
@endsection