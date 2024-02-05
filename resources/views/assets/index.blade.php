@extends ('layouts.admin', ['title_template' => "Activos"])
@section('extracss')
    <style>
        table#tablaAssets th{
            font-size:12px;
        }
        table#tablaAssets td{
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
                <polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3"></polyline>
                <line x1="12" y1="12" x2="20" y2="7.5"></line>
                <line x1="12" y1="12" x2="12" y2="21"></line>
                <line x1="12" y1="12" x2="4" y2="7.5"></line>
            </svg>
            Activos
        </h1>
    </div>
    @if (permisoAdminJefe())
    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="assets/modalCreate" rel="modalCreate" title="Nuevo activo" type="button" class="btn btn-pill btn-primary">
                <i class="fa fa-plus" ></i> &nbsp;
                <span class="d-none d-sm-inline-block">
                    Activo
                </span>
            </a>
        </div>
    </div>
    @endif
@endsection

@section ('contenido')

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-vcenter table-center table-sm table-hover" id="tablaAssets">
                <thead>
                    <tr>
                        <th width="7%">Código</th>
                        <th width="8%">Nombre</th>
                        <th width="10%">Cliente</th>
                        <th width="10%">Categoria</th>
                        <th width="10%">Ubicación</th>
                        <th width="7%">Ciudad</th>
                        <th width="8%">Nº de serie</th>
                        <th width="13%" style="text-align: center !important">Datos del activo</th>
                        <th width="5%">Estado</th>
                        @if (permisoAdminJefe())
                            <th width="3%">OP.</th>
                        @endif
                    </tr>
                </thead>

                <thead role="row">
                    <tr class="filters">
                        <td><input style="width: 100%;font-size:10px" id="user0" class="form-control nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="codigob"/></td>
                        <td><input style="width: 100%;font-size:10px" id="user0" class="form-control nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="nombreb"/></td>
                        <td><input style="width: 100%;font-size:10px" id="user0" class="form-control nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="clienteb"/></td>
                        <td>
                            <select class="form-control text-center selector" style="width: 100%" name="ciudadb">
                                <option selected value="">Todas</option>
                                <option value="0">Aire Acondicionado de confort</option>
                                <option value="1">Aires de precision</option>
                                <option value="2">Banco de baterias de litio</option>
                                <option value="3">Equipo inversor</option>
                                <option value="4">Equipo rectificador</option>
                                <option value="5">Equipo UPS</option>
                                <option value="6">Estabilizador</option>
                                <option value="7">Grupos Electrógenos</option>
                                <option value="8">Reconectador de media tension</option>
                                <option value="9">Tablero Banco de capacitores</option>
                                <option value="10">Tablero de transferencia ATS</option>
                            </select>
                        </td>
                        <td><input style="width: 100%;font-size:10px" id="user0" class="form-control nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="ubicacionb"/></td>
                        <td>
                            <select class="form-control text-center selector" style="width: 100%" name="ciudadb">
                                <option selected value="">Todas</option>
                                <option value="0">Beni</option>
                                <option value="1">Chuquisaca</option>
                                <option value="2">Cochabamba</option>
                                <option value="3">La Paz</option>
                                <option value="4">Oruro</option>
                                <option value="5">Pando</option>
                                <option value="6">Potosi</option>
                                <option value="7">Santa Cruz</option>
                                <option value="8">Tarija</option>
                            </select>
                        </td>
                        <td><input style="width: 100%;font-size:10px" id="user0" class="form-control nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="serieb"/></td>
                        <td><input style="width: 100%;font-size:10px" id="user0" class="form-control nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="datosb"/></td>
                        <td>
                            <select class="form-control text-center" style="width: 100%" name="estadob">
                                <option selected value="">Todos</option>
                                <option value="1">Activos</option>
                                <option value="0">Inactivos</option>
                            </select>
                        </td>
                        @if (permisoAdminJefe())
                            <td></td>
                        @endif
                    </tr>
                </thead>

                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
    {{-- Modal Crear --}}
    <div class="modal modalPrimary fade modal-slide-in-right" aria-hidden="true" role="dialog"  id="modalCreate" data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fa fa-plus"></i>
                        Nuevo activo
                    </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Editar --}}
    <div class="modal modalCyan fade modal-slide-in-right" aria-hidden="true" role="dialog"  id="modalEdit" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            </div>
        </div>
    </div>

    {{-- Modal Eliminar --}}
    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog"  id="modalDelete" data-backdrop="static">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    modalAjax("modalCreate","modalCreate","modal-body");
    modalAjax("modalEdit","modalEdit","modal-content");
    modalAjax("modalDelete","modalDelete","modal-content");
    $('.selector').select2();

    $(function () {
        var table = $('#tablaAssets').DataTable({
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
                "url": "{{ route('assets.table') }}",
                'dataType': 'json',
                'type': 'post',
                'data': {
                    "_token": "{{ csrf_token() }}",
                },
                // "error": function(reason) {
                //     errorsDatatable(reason.status);
                // },
            },
            "columns": [
                {"data": "cod"},
                {"data": "nombre"},
                {"data": "cliente"},
                {"data": "categoria"},
                {"data": "ubicacion"},
                {"data": "ciudad"},
                {"data": "serie"},
                {"data": "datosContacto", "className": "left"},
                {"data": "estado"},
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
                });
            }
        });

        // BUSCAR Filtros de DataTable
        filterInputDT(table);
    });
</script>
@endsection