@extends ('layouts.admin', ['title_template' => "Clientes"])
@section('extracss')
<style>
    table#tablaClientes th{
        font-size:12px;
    }
    table#tablaClientes td{
        font-size: 13px;
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
            <i class=" fa fa-user-tie  icon-tabler"></i>
            Clientes
        </h1>
    </div>
    @if (permisoAdminJefe())
    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="clients/modalCreate" rel="modalCreate" title="Nuevo cliente">
                <button type="button" class="btn btn-pill btn-primary">
                    <i class="fa fa-plus" ></i> &nbsp;
                    <span class="d-none d-sm-inline-block">
                        Cliente
                    </span>
                </button>
            </a>
        </div>
    </div>
    @endif
@endsection

@section ('contenido')
    <div class="table-responsive">
        <table class="table table-vcenter table-center table-sm table-hover" id="tablaClientes">
            <thead>
                <tr>
                    <th width="15%">Nombre comercial</th>
                    <th width="10%">NIT</th>
                    <th width="10%">Tipo de cliente</th>
                    <th width="15%" style="text-align: center !important">Caracteristicas/Rubro</th>
                    <th width="15%" style="text-align: center !important">Dirección</th>
                    <th width="15%" style="text-align: center !important">Datos de contacto</th>
                    <th width="10%">Estado</th>
                    @if (permisoAdminJefe())
                        <th width="3%">OP.</th>
                    @endif
                </tr>
            </thead>

            <thead role="row">
                <tr class="filters">
                    <td><input style="width: 100%;font-size:10px" id="cliente0" class="form-control nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="nombreb"/></td>
                    <td><input style="width: 100%;font-size:10px" id="cliente0" class="form-control nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="nitb"/></td>
                    <td>
                        <select class="form-control text-center" style="width: 100%" name="tipob">
                            <option selected value="">Todos</option>
                            <option value="II">Integrador</option>
                            <option value="FF">Cliente final</option>
                            <option value="DD">Distribuidor</option>
                        </select>
                    </td>
                    <td><input style="width: 100%;font-size:10px" id="cliente0" class="form-control nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="caracteristicasb"/></td>
                    <td><input style="width: 100%;font-size:10px" id="cliente0" class="form-control nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="direccionb"/></td>
                    <td><input style="width: 100%;font-size:10px" id="cliente0" class="form-control nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="contactob"/></td>
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

    {{-- Modal Crear --}}
    <div class="modal modalPrimary fade modal-slide-in-right" aria-hidden="true" role="dialog"  id="modalCreate" data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fa fa-plus"></i>
                        Nuevo cliente
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

    $(function () {
        var table = $('#tablaClientes').DataTable({
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
                "url": "{{ route('clients.table') }}",
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
                {"data": "nombre"},
                {"data": "nit"},
                {"data": "tipo"},
                {"data": "caracteristicas", "className": "left"},
                {"data": "direccion", "className": "left"},
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