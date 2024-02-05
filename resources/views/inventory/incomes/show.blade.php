@extends ('layouts.admin', ['title_template' => "Ingresos de materiales"])
@section('extracss')
    <style>
        table#table_details th{
            font-size:12px;
        }
        table#table_details td{
            font-size: 12px !important;
        }
        .icon-tabler {
            width: 25px;
            height: 25px;
            stroke-width: 1.25;
            margin-bottom: 2px;
        }
        .blink_me {
            animation: blinker 1s linear infinite;
        }
        @keyframes  blinker {
            50% {
                opacity: 0;
            }
        }
        .icon-alert{
            padding-top:3px;
        }
        .datos {
            font-weight: 600;
        }
        #inputCoditem{
            cursor : pointer;
            background-color: transparent;
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
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 13.5v-7.5a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-6m-8 -10h16m-10 -6v11.5m-8 3.5h7m-3 -3l3 3l-3 3" />
            </svg>
            Ingreso de materiales
        </h1>
    </div>

    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="/incomes" class="btn btn-outline-secondary">
                <b><i class="fa fa-list fa-lg" ></i> &nbsp;
                Ver todos las notas de ingresos</b>
            </a>
        </div>
    </div>
@endsection

@section ('contenido')

<div class="row">
    <div class="col-lg-6 invoice-col">
        <address>
            <span style="font-size:20px;">
                <strong>Código: {{ $income->cod}} </strong>
            </span><br>
            Origen: <span class="datos">{{ $income->origin }}</span><br>
            Fecha de registro: <span class="datos">{{date("d/m/Y H:i:s", strtotime($income->created_at))}}</span><br>
            Observación: <br><span class="datos">{!! purify(nl2br($income->observation)) !!}</span>
        </address>
    </div>

    <div class="col-lg-6">
        <address style="font-size: 10px; text-align: right">
            @if ($income->state == 1)
                @if (permisoAdminJefe())
                    <a rel="modalState" href="/incomes/statemodal/{{ code($income->id) }}" class="btn btn-outline-orange border border-orange font-weight-bold" title="Cambiar estado (Autorizar ó anular)">
                        <svg class="icon icon-tabler icon-tabler-replace" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <rect x="3" y="3" width="6" height="6" rx="1"></rect>
                            <rect x="15" y="15" width="6" height="6" rx="1"></rect>
                            <path d="M21 11v-3a2 2 0 0 0 -2 -2h-6l3 3m0 -6l-3 3"></path>
                            <path d="M3 13v3a2 2 0 0 0 2 2h6l-3 -3m0 6l3 -3"></path>
                        </svg>
                        Pendiente de ingreso
                    </a>
                @else
                    <button type="button" class="btn btn-outline-orange font-weight-bold cursor-zoom-in " >
                        <svg class="icon icon-tabler icon-tabler-replace" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <rect x="3" y="3" width="6" height="6" rx="1"></rect>
                            <rect x="15" y="15" width="6" height="6" rx="1"></rect>
                            <path d="M21 11v-3a2 2 0 0 0 -2 -2h-6l3 3m0 -6l-3 3"></path>
                            <path d="M3 13v3a2 2 0 0 0 2 2h6l-3 -3m0 6l3 -3"></path>
                        </svg>
                        Pendiente de ingreso
                    </button>
                @endif
            @else
                @if ($income->state == 0)
                    <button type="button" class="btn btn-outline-danger font-weight-bold cursor-zoom-in btn-lg" >
                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="5.7" y1="5.7" x2="18.3" y2="18.3" />
                        </svg>
                        Anulado
                    </button>
                @elseif($income->state == 2)
                    <button type="button" class="btn btn-outline-success font-weight-bold cursor-zoom-in btn-lg">
                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" />
                        </svg>
                        Ingresado
                    </button>
                @endif
            @endif

        </address>
    </div>
</div>

<div class="offset-lg-1 col-lg-10" style="margin-top:30px">
    @if ($income->getCantDetails() >0)
        <h2>Lista de materiales</h2>
        <div class="table-responsive">
            <table class="table table-vcenter table-center table-sm table-hover" id="table_details">
                <thead>
                    <tr>
                        <th width="3%">Nº</th>
                        <th width="25%">Código de material</th>
                        <th width="8%">Cantidad</th>
                        <th width="15%">Ubicación</th>
                        <th width="17%">Observación</th>
                        @if(permisoAdminJefe() && $income->state == 1)
                            <th width="5%">Op.</th>
                        @endif
                    </tr>
                </thead>
                <tbody id="trDetalles">
                </tbody>
            </table>
        </div>
    @elseif($income->state != 1)
        <div class="text-center btn-lg text-orange font-weight-bold">
            <svg class="icon icon-tabler" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12.01" y2="8" /><polyline points="11 12 12 12 12 16 13 16" />
            </svg>
            La nota de ingreso no tiene detalles asociados
        </div>
    @endif

    @if(permisoAdminJefe() && $income->state == 1)
        {{Form::Open(array('action'=>array('InvIncomesDetailsController@storeDetails',code($income->id)),'method'=>'POST','autocomplete'=>'off','id'=>'formIngreso'))}}
            <h2 >Agregar materiales</h2>
            <div class="table-responsive">
                <table class="table table-vcenter table-center table-sm " id="tableDetalle">
                    <thead>
                        <tr>
                            <th style="width: 25%" id="item--label">CÓDIGO DE MATERIAL</th>
                            <th style="width: 10%" id="cantidad--label">CANTIDAD</th>
                            <th style="width: 15%" id="ubicacion--label">ALMACÉN</th>
                            <th style="width: 20%" id="observacion--label">OBSERVACIÓN</th>
                            <th style="width: 10%" ></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <a href="/items/modal/" rel="modalItems">
                                    <input type="text" class="form-select2 form-control text-center font-weight-bold text-yellow" placeholder="Seleccionar" id="inputCoditem" readonly>
                                    <input type="text" id="itemcod" hidden name="item">
                                </a>
                                <span id="item-error" class="text-red text-sm"></span>
                            </td>
                            <td>
                                <input type="text" class="form-control numero" name="cantidad" placeholder="Cantidad">
                                <span id="cantidad-error" class="text-red text-sm"></span>
                            </td>
                            <td id="ubicacion-sel2">
                                <select name="ubicacion" class="form-control selector" style="width:100%">
                                    <option value="" hidden>Seleccionar</option>
                                    <option value="1">Edificio Arcadia</option>
                                    <option value="2">El Alto</option>
                                    <option value="3">Gramadal</option>
                                    <option value="4">Edifico técnico</option>
                                </select>
                                <span id="ubicacion-error" class="text-red text-sm"></span>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="observacion">
                                <span id="observacion-error" class="text-red text-sm"></span>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-yellow" name="btnSubmit">
                                    Adicionar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        {{Form::Close()}}
    @endif
</div>


    {{-- Modal Editar encabezado --}}
    <div class="modal modalCyan fade modal-slide-in-right" aria-hidden="true" role="dialog"  id="modalEditEncabezado" data-backdrop="static">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
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
                        Nuevo material
                    </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>

    {{-- modal Cambio de estado --}}
    <div class="modal modalPrimary fade modal-slide-in-right" aria-hidden="true" role="dialog" id="modalState" data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            </div>
        </div>
    </div>

    {{-- modal Items de inventario --}}
    <div class="modal modalPrimary fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalItems" data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Materiales en inventario
                    </h5>
                    <button type="button" class="btn-close" aria-label="Close" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div class="modal modalCyan fade modal-slide-in-right" aria-hidden="true" role="dialog"  id="modalEdit" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            </div>
        </div>
    </div>

    {{-- Modal Delete --}}
    <div class="modal fade modal-slide-in-right" aria-hidden="true" role="dialog"  id="modalDelete" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    modalAjax("modalItems","modalItems","modal-body");
    modalAjax("modalState","modalState","modal-content");
    modalAjax("modalEditEncabezado","modalEditEncabezado","modal-content");
    AutoNumeric.multiple('.numero',{
        modifyValueOnWheel: false,
        digitGroupSeparator : '',
        minimumValue: 0
    });
    $(".selector").select2();

    var AnchoTr = function(e, width) {
        width.children().each(function() {
            $(this).width($(this).width());
        });
        return width;
    };

    @if ($income->getCantDetails() >0)
        $(function () {
            var contador = 0;
            var income = "{{ code($income->id) }}";
            var asociado = 'I';
            var table = $('#table_details').DataTable({
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
                    "url": "{{ route('incomes.details.table') }}",
                    'dataType': 'json',
                    'type': 'post',
                    'data': {
                        "_token": "{{ csrf_token() }}",
                        income: income,
                        asociado: asociado
                    },
                    "error": function(reason) {
                        errorsDatatable(reason.status);
                    },
                },
                "columns": [
                    { "data": "orden", "className": "ordericon font-weight-bold " },
                    {"data": "item"},
                    {"data": "cant"},
                    {"data": "location"},
                    {"data": "observation"},
                    @if(permisoAdminJefe() && $income->state == 1)
                        {"data": "operations"},
                    @endif
                ],
                "drawCallback": function () {
                    $(function () {

                        $('[data-toggle="popover"]').popover({
                            html: true,
                            "trigger" : "hover",
                            "placement": "left",
                            "container": "body",
                        });
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
                            html: true
                        });
                    });
                    modalAjax("modalEdit","modalEdit","modal-content");
                    modalAjax("modalDelete","modalDelete","modal-content");
                }
            });
        });
    @endif

    var campos = ['item','cantidad','ubicacion','observacion'];
    ValidateAjax("formIngreso",campos,"btnSubmit","{{ route('incomes.details.store',code($income->id) )}}","POST","/incomes/{{ code($income->id) }}");
</script>
@endsection