@extends ('layouts.admin', ['title_template' => "Formularios"])
@section('extracss')
<style>
    table#tableForms th{
        font-size:12px;
    }
    table#tableForms td{
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
            Formularios
        </h1>
    </div>

    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="forms/modalCreate" rel="modalCreate" title="Nuevo formulario" class="btn btn-pill btn-primary">
                <i class="fa fa-plus" ></i> &nbsp;
                <span class="d-none d-sm-inline-block">
                    Formulario
                </span>
            </a>
        </div>
    </div>
@endsection

@section ('contenido')
    <div class="row">
        {!! Form::open(['route'=>'forms.index','method'=>'GET']) !!}
            <div class="pull-right">
                <div class="form-inline"  >
                    <div class="form-group">
                        <div class="divEstado pull-right" >
                            <select class="form-control selectEstado" name="stateFilter" style=" margin-right:-10px; width:192px;">
                                <option @if ($stateFilter=="all" ) {{ 'selected' }} @endif value="all">Todos</option>
                                <option @if ($stateFilter=="act" ) {{ 'selected' }} @endif value="act">Activos</option>
                                <option @if ($stateFilter=="0" ) {{ 'selected' }} @endif value="0">Inactivos</option>
                                <option @if ($stateFilter=="1" ) {{ 'selected' }} @endif value="1">En Proceso</option>
                                <option @if ($stateFilter=="2" ) {{ 'selected' }} @endif value="2">Terminados</option>
                            </select>
                        </div>
                        <span class="pull-right texto_menu" style="margin-top: 8px;">Estado:&nbsp;&nbsp; </span>
                        <input class="hidden"  type="submit" id="submiSearch">
                    </div>
                </div>
            </div>
        {{ Form::close() }}
    </div>

    <div class="table-responsive">
        <table class="table table-vcenter table-center table-sm table-hover" id="tableForms">
            <thead>
                <tr>
                    <th style="width:14%">NOMBRE</th>
                    <th style="width:14%">TIPO DE INFORME</th>
                    <th style="width:15%">CONTENEDORES</th>
                    <th style="width:15%">INFORMES DE MANTENIMIENTO</th>
                    <th style="width:15%">FORMULARIO PARA CARTA</th>
                    <th style="width:7%">ESTADO</th>
                    @if (permisoAdminJefe())
                        <th width="3%">OP.</th>
                    @endif
                </tr>
            </thead>

            <thead role="row">
                <tr class="filters">
                    <td><input style="width: 100%;font-size:10px" id="formnombre" class="form-control nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="nombreb"/></td>
                    <td><input style="width: 100%;font-size:10px" id="formtipo" class="form-control nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="tipob"/></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
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
                        Nuevo formulario
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

    {{-- Modal Cambio de Estado --}}
    <div class="modal modal-success fade modal-slide-in-right" aria-hidden="true" role="dialog"  id="modalEstado" data-backdrop="static">
        <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
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
    var state = "{{$stateFilter}}";
    $(function () {
        var table = $('#tableForms').DataTable({
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
                "url": "{{ route('forms.table') }}",
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
                { "data": "name" },
                { "data": "type" },
                { "data": "contenedores" },
                { "data": "informes" },
                { "data": "carta" },
                { "data": "estado" },
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