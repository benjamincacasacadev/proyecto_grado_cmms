@extends ('layouts.admin', ['title_template' => "Traspasos - Inventario"])
@section('extracss')
    <style>
        table#table_transfers th{
            font-size:12px;
        }
        table#table_transfers td{
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
                <path d="M14 21l-11 -11"></path>
                <path d="M3 14v-4h4"></path>
                <path d="M17 14h4v-4"></path>
                <line x1="10" y1="3" x2="21" y2="14"></line>
            </svg>
            Traspasos
        </h1>
    </div>

    @if (permisoAdminJefe())
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="/transfers/createmodal" rel="modalCreate">
                    <button type="button" class="btn btn-pill btn-yellow">
                        <i class="fa fa-plus" ></i> &nbsp;Traspaso
                    </button>
                </a>
            </div>
        </div>
    @endif
@endsection

@section ('contenido')
    @php
        $codTransfers = (isset($_GET['cod'])) ? $_GET['cod'] : '';
    @endphp
    <div class="row">
        {!! Form::open(['route'=>'transfers.index','method'=>'GET', 'id' => 'formFilterTransfers']) !!}
            <div class="pull-right">
                <div class="form-inline"  >
                    <div class="form-group">
                        <div class="divEstado pull-right" >
                            <select class="form-control selectEstado" name="selectEstado" style=" margin-right:-10px; width:192px;">
                                <option @if ($selectEstado=="all" ) {{ 'selected' }} @endif value="all">Todos</option>
                                <option @if ($selectEstado=="0" ) {{ 'selected' }} @endif value="0">Anulados</option>
                                <option @if ($selectEstado=="1" ) {{ 'selected' }} @endif value="1">Pendientes</option>
                                <option @if ($selectEstado=="2" ) {{ 'selected' }} @endif value="2">Autorizados</option>
                            </select>
                        </div>
                        <span class="pull-right font-weight-bold" style="margin-top: 8px;">Estado:&nbsp;&nbsp; </span>
                        <input class="hidden"  type="submit" id="submiSearch">
                    </div>
                </div>
            </div>
        {{ Form::close() }}
    </div>

    <div class="table-responsive">
        <table class="table table-vcenter table-center table-sm table-hover" id="table_transfers">
            <thead>
                <tr>
                    <th width="8%">Código</th>
                    <th width="8%">Fecha</th>
                    <th width="20%">Material</th>
                    <th width="10%">Cantidad</th>
                    <th width="15%">Almacen origen</th>
                    <th width="10%">Almacen destino</th>
                    <th width="15%">Estado</th>
                </tr>
            </thead>
            <thead role="row">
                <tr class="filters">
                    <td><input style="width: 100%;font-size:10px" id="transfer0" value="{{$codTransfers}}" class="form-control font-weight-bold nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="fechab"/></td>
                    <td><input style="width: 100%;font-size:10px" id="transfer0" class="form-control font-weight-bold nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="fechab"/></td>
                    <td><input style="width: 100%;font-size:10px" id="transfer1" class="form-control font-weight-bold nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="itemb"/></td>
                    <td><input style="width: 100%;font-size:10px" id="transfer2" class="form-control font-weight-bold nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="cantidadb"/></td>
                    <td><input style="width: 100%;font-size:10px" id="transfer3" class="form-control font-weight-bold nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="origenb"/></td>
                    <td><input style="width: 100%;font-size:10px" id="transfer4" class="form-control font-weight-bold nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="destinob"/></td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    {{-- modal Crear --}}
    <div class="modal modalPrimary fade modal-slide-in-right" aria-hidden="true" role="dialog"  id="modalCreate" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M14 21l-11 -11"></path>
                            <path d="M3 14v-4h4"></path>
                            <path d="M17 14h4v-4"></path>
                            <line x1="10" y1="3" x2="21" y2="14"></line>
                        </svg>
                        Registrar traspaso
                    </h5>
                    <button type="button" class="btn-close" aria-label="Close" data-dismiss="modal"></button>
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

    {{-- modal Show --}}
    <div class="modal modalPrimary fade modal-slide-in-right" aria-hidden="true" role="dialog" id="modalShow" data-backdrop="static">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $(".selectEstado").val("{{$selectEstado}}");
    });
    modalAjax("modalCreate","modalCreate","modal-body");
    modalAjax("modalState","modalState","modal-content");
    modalAjax("modalShow","modalShow","modal-content");
    modalAjax("modalEdit","modalEdit","modal-content");
    modalAjax("modalDelete","modalDelete","modal-content");
    var campos = ['origen','observacion'];

    //  funcion para el filtrado por etapa
    $('.selectEstado').on('change', function () {
        $('#formFilterTransfers').submit();
    });

    $(function () {
        var state = "{{ $selectEstado }}";
        var table = $('#table_transfers').DataTable({
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
                "url": "{{ route('transfers.table') }}",
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
                {"data": "date"},
                {"data": "item",'className':'font-weight-bold'},
                {"data": "quantity"},
                {"data": "origin"},
                {"data": "destination"},
                {"data": "state"},
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