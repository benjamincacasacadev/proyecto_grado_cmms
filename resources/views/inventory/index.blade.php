@extends ('layouts.admin', ['title_template' => "Materiales"])
@section('extracss')
    <style>
        table#table_inventory th{
            font-size:12px;
        }
        table#table_inventory td{
            font-size: 13px;
        }

        .equalcols{
            width:10%;
            font-size: 11px !important;
        }
        .icon-tabler {
            width: 28px;
            height: 28px;
            stroke-width: 1.25;
            margin-bottom: 2px;
        }
        .dt-buttons{
            width: 100% !important;
        }
        .left{
            text-align: left !important;
        }
        .blink_me {
            animation: blinker 1s linear infinite;
        }
        .blink_me2 {
            animation: blinker 2s linear infinite;
        }
        @keyframes  blinker {
            50% {
                opacity: 0;
            }
        }
    </style>
@endsection

@section ('contenidoHeader')
    <div class="col">
        <div class="page-pretitle">
            {{ nameEmpresa() }}
        </div>
        <h1 class="titulomod">
            <img class="icon icon-tabler" src="{{asset('imagenes/screw.svg')}}" width="20" height="20">
            Materiales
        </h1>
    </div>

    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            @if (permisoAdminJefe())
                <a href="/inventory/createmodal" rel="modalCreate" class="btn btn-pill btn-yellow">
                    <i class="fa fa-plus" ></i> &nbsp;
                    <span class="d-none d-sm-inline-block">
                        Material
                    </span>
                </a>
            @endif
        </div>
    </div>
@endsection

@section ('contenido')
<div class="card">
    <div class="card-body">
        {!! Form::open(['route'=>'inventory.index','method'=>'GET', 'role'=>'search', 'id'=>'formFilterOT']) !!}
        <div class="row mb-3 me-1">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="pull-right">
                    <div class="form-inline"  >
                        <div class="form-group">
                            <div class="divEstado pull-right" >
                                <select class="form-control selectEstado" name="selectEstado" style=" margin-right:-10px; width:192px;">
                                    <option @if ($selectEstado=="all" ) {{ 'selected' }} @endif value="all">Todos</option>
                                    <option @if ($selectEstado=="1" ) {{ 'selected' }} @endif value="1">Activos</option>
                                    <option @if ($selectEstado=="0" ) {{ 'selected' }} @endif value="0">Inactivos</option>
                                </select>
                            </div>
                            <span class="pull-right font-weight-bold" style="margin-top: 8px;">Estado:&nbsp;&nbsp; </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}

        <div class="table-responsive">
            <table class="table table-vcenter table-center table-sm table-hover" id="table_inventory">
                <thead>
                    <tr>
                        <th width="8%">Código</th>
                        <th width="15%">Item</th>
                        <th width="25%">Descripción</th>
                        <th width="10%">Cantidad</th>
                        <th width="10%">Cantidad mínima</th>
                        <th width="15%">Unidad</th>
                        <th width="15%">Estado</th>
                        @if (permisoAdminJefe())
                            <th width="8%">Op.</th>
                        @endif
                    </tr>
                </thead>
                <thead role="row">
                    <tr class="filters">
                        <td><input style="width: 100%;font-size:10px" id="inven0" class="form-control font-weight-bold nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="codb"/></td>
                        <td><input style="width: 100%;font-size:10px" id="inven1" class="form-control font-weight-bold nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="itemb"/></td>
                        <td><input style="width: 100%;font-size:10px" id="inven2" class="form-control font-weight-bold nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="descripcionb"/></td>
                        <td><input style="width: 100%;font-size:10px" id="inven3" class="form-control font-weight-bold nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="cantb"/></td>
                        <td><input style="width: 100%;font-size:10px" id="inven4" class="form-control font-weight-bold nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="cantminb"/></td>
                        <td><input style="width: 100%;font-size:10px" id="inven5" class="form-control font-weight-bold nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="unidadb"/></td>
                        <td></td>
                        @if (permisoAdminJefe())
                            <td></td>
                        @endif
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <input class="hidden" type="text" name="tipoExport" id="tipoExport">
            <input class="hidden" type="text" name="estadob" />
        </div>
    </div>
</div>

    {{-- Modal Crear --}}
    <div class="modal modalPrimary fade modal-slide-in-right" aria-hidden="true" role="dialog"  id="modalCreate" data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-plus"></i> Nuevo material</h5>
                    <button type="button" class="btn-close text-yellow" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
    {{-- modal de Ver Imagenes --}}
    <div class="modal modalPrimary fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalImage" data-backdrop="static">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
            </div>
        </div>
    </div>
    {{-- Modal Editar --}}
    <div class="modal modalCyan fade modal-slide-in-right" aria-hidden="true" role="dialog"  id="modalEditInventory" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            </div>
        </div>
    </div>

    {{-- Modal Eliminar --}}
    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog"  id="modalDelete" data-backdrop="static">
        <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    modalAjax("modalCreate","modalCreate","modal-body");
    modalAjax("modalEditInventory","modalEditInventory","modal-content");
    modalAjax("modalDelete","modalDelete","modal-content");
    $('.selector').select2();

    //  funcion para el filtrado por etapa
    $('.selectEstado').on('change', function () {
        $('#formFilterOT').submit();
    });

    $(function () {
        var state = "{{ $selectEstado }}";
        var table = $('#table_inventory').DataTable({
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
                "url": "{{ route('inventory.table') }}",
                'dataType': 'json',
                'type': 'post',
                'data': {
                    "_token": "{{ csrf_token() }}",
                    state: state,
                },
                // "error": function(reason) {
                //     errorsDatatable(reason.status);
                // },
            },
            "columns": [
                {"data": "cod"},
                {"data": "title"},
                {"data": "description"},
                {"data": "quantity"},
                {"data": "min_cant"},
                {"data": "unit"},
                {"data": "state"},
                @if (permisoAdminJefe())
                    {"data": "operations"},
                @endif
            ],
            "drawCallback": function () {
                restartActionsDT();
                $(document).ready(function () {
                    $("[name=table_areas_length]").addClass('form-select');
                });

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
</script>
@endsection