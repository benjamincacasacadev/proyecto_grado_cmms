@extends ('layouts.admin', ['title_template' => "Órdenes de trabajo"])
@section('extracss')
    <style>
        table#tableWorkorders th{
            font-size:12px;
        }
        table#tableWorkorders td{
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
            <i class="fas fa-clipboard-list fa-md"></i>&nbsp;Órdenes de trabajo
        </h1>
    </div>

    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="work_orders/create" title="Nueva orden de trabajo" type="button" class="btn btn-pill btn-primary">
                <i class="fa fa-plus" ></i> &nbsp;
                <span class="d-none d-sm-inline-block">
                    Orden de trabajo
                </span>
            </a>
        </div>
    </div>
@endsection

@section ('contenido')
<div class="card">
    <div class="card-body">
        {!! Form::open(['route'=>'workorders.index','method'=>'GET', 'role'=>'search', 'id'=>'formFilterOT']) !!}
        <div class="row mb-3 me-1">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="pull-right">
                    <div class="form-inline"  >
                        <div class="form-group">
                            <div class="divEstado pull-right" >
                                <select class="form-control selectEstado" name="selectEstado" style=" margin-right:-10px; width:192px;">
                                    <option @if($selectEstado == "") selected @endif value="">Todos</option>
                                    <option @if($selectEstado == "PP") selected @endif value="PP">Pendiente</option>
                                    <option @if($selectEstado == "EE") selected @endif value="EE">En progreso</option>
                                    <option @if($selectEstado == "SS") selected @endif value="SS">En pausa</option>
                                    <option @if($selectEstado == "RR") selected @endif value="RR">En revisión</option>
                                    <option @if($selectEstado == "CC") selected @endif value="CC">En corrección</option>
                                    <option @if($selectEstado == "TT") selected @endif value="TT">Terminados</option>
                                    <option @if($selectEstado == "XX") selected @endif value="XX">Anulados</option>
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
            <table class="table table-vcenter table-center table-sm table-hover" id="tableWorkorders">
                <thead>
                    <tr>
                        <th width="7%">Código</th>
                        <th width="8%">Activo</th>
                        <th width="10%">Estado</th>
                        <th width="10%">Título</th>
                        <th width="10%">Cliente</th>
                        <th width="10%">Técnicos asignados</th>
                        <th width="8%">Descripción</th>
                        <th width="8%">Prioridad</th>
                        <th width="8%">Fecha programada</th>
                        @if (permisoAdminJefe())
                            <th width="3%">OP.</th>
                        @endif
                    </tr>
                </thead>

                <thead role="row">
                    <tr class="filters">
                        <td><input style="width: 100%;font-size:10px" id="user0" class="form-control nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="codigob"/></td>
                        <td><input style="width: 100%;font-size:10px" id="user0" class="form-control nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="activob"/></td>
                        <td></td>
                        <td><input style="width: 100%;font-size:10px" id="user0" class="form-control nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="titulob"/></td>
                        <td><input style="width: 100%;font-size:10px" id="user0" class="form-control nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="clienteb"/></td>
                        <td>
                            <select class="selector" name="usuariob">
                                <option value="" selected>Todos</option>
                                @foreach($users as $usr)
                                    <option value="{{$usr->id}}">{{ $usr->fullName }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><input style="width: 100%;font-size:10px" id="user0" class="form-control nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="descripcionb"/></td>
                        <td>
                            <select class="selector" name="prioridadb">
                                <option value="" selected>Todos</option>
                                <option value="0">Ninguna</option>
                                <option value="1">Baja</option>
                                <option value="2">Media</option>
                                <option value="3">Alta</option>
                            </select>
                        </td>
                        <td><input style="width: 100%;font-size:10px" id="user0" class="form-control nopegar" type="text" placeholder="🔍 &nbsp;Buscar" name="fechab"/></td>
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

    //  funcion para el filtrado por etapa
    $('.selectEstado').on('change', function () {
        $('#formFilterOT').submit();
    });

    $(function () {
        var state = "{{ $selectEstado }}";
        var table = $('#tableWorkorders').DataTable({
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
                "url": "{{ route('workorders.table') }}",
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
                {"data": "activo"},
                {"data": "estado"},
                {"data": "titulo"},
                {"data": "cliente"},
                {"data": "tecnicos"},
                {"data": "descripcion", "className": "left"},
                {"data": "prioridad"},
                {"data": "fecha"},
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