@extends ('layouts.admin', ['title_template' => "Solicitud $outcome->cod"])
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
    <link rel="stylesheet" href="{{asset('dist/css/bootstrap-editable.min.css')}}">
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
            Solicitud de materiales
        </h1>
    </div>

    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="/outcomes" class="btn btn-outline-secondary border border-secondary font-weight-bold">
                <b><i class="fa fa-list fa-lg" ></i> &nbsp;
                Ver todos las solicitudes</b>
            </a>
        </div>
    </div>
@endsection

@section ('contenido')

    <div class="row">
        <div class="col-lg-6 invoice-col">
            <address>
                <span style="font-size:20px;">
                    <strong>Código: {{ $outcome->cod}} </strong>
                </span><br>
                Solicitado por: <span class="datos"><i class="fa fa-user"></i> &nbsp;{{ userFullname($outcome->solicitado_id) }}</span><br>
                Orden de trabajo asociada: <span class="datos">{!! $outcome->workorders->getCod() !!}</span><br>
                Motivo: <span class="datos">{{ $outcome->reason }}</span><br>
                Fecha de entrega: <span class="datos">{{date("d/m/Y", strtotime($outcome->delivery_date))}}</span><br>
                Observación: <br><span class="datos">{!! purify(nl2br($outcome->observation)) !!}</span>
            </address>
        </div>

        <div class="col-lg-6">
            <address style="font-size: 10px; text-align: right">
                @if ($outcome->state == 1)
                    @if (permisoAdminJefe())
                        <a rel="modalState" href="/outcomes/statemodal/{{ code($outcome->id) }}" class="btn btn-outline-orange border border-orange font-weight-bold" title="Cambiar estado (Autorizar ó anular)">
                            <svg class="icon icon-tabler icon-tabler-replace" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <rect x="3" y="3" width="6" height="6" rx="1"></rect>
                                <rect x="15" y="15" width="6" height="6" rx="1"></rect>
                                <path d="M21 11v-3a2 2 0 0 0 -2 -2h-6l3 3m0 -6l-3 3"></path>
                                <path d="M3 13v3a2 2 0 0 0 2 2h6l-3 -3m0 6l3 -3"></path>
                            </svg>
                            Pendiente de retiro
                        </a>
                    @else
                        <button type="button" class="btn btn-outline-orange font-weight-bold cursor-zoom-in" title="Para validar la solicitud, comuniquese con el personal a cargo">
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
                    @if ($outcome->state == 0)
                        <button type="button" class="btn btn-outline-danger font-weight-bold cursor-zoom-in btn-lg" >
                            <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="5.7" y1="5.7" x2="18.3" y2="18.3" />
                            </svg>
                            Anulado
                        </button>
                    @elseif($outcome->state == 2)
                        <button type="button" class="btn btn-outline-success font-weight-bold cursor-zoom-in btn-lg">
                            <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" />
                            </svg>
                            Validado
                        </button>
                    @endif
                @endif

            </address>
        </div>
    </div>

    <div class="offset-lg-1 col-lg-10" style="margin-top:30px">
        @if ($outcome->getCantDetails() >0)
            <div id="_token" class="hidden" data-token="{{ csrf_token() }}"></div>
            <h2>Lista de materiales</h2>
            <div class="table-responsive">
                <table class="table table-vcenter table-center table-sm table-hover" id="table_details">
                    <thead>
                        <tr>
                            <th width="3%">Nº</th>
                            <th width="25%">Material</th>
                            <th width="8%">Cantidad Solicitada</th>
                            <th width="15%">Origen</th>
                            <th width="15%">
                                Almacen
                                @if ($outcome->state == '1')
                                    <span class="form-help edithover" id="ubicacionclass" data-toggle="popover" data-content="<span style=font-size:11px >Para editar un valor de esta columna haga doble clic en el campo o celda deseado para modificarlo.<br>Escoja el almacen deseado y se confirmarà el cambio. </span>" data-title="<b>Columna Editable</b>">?</span>
                                @endif
                            </th>
                            <th width="17%">Destino</th>
                            @if($outcome->state == 1)
                                    <th width="5%">Op.</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="trDetalles">
                    </tbody>
                </table>
            </div>
        @elseif($outcome->state != 1)
            <div class="text-center btn-lg text-orange font-weight-bold">
                <svg class="icon icon-tabler" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><line x1="12" y1="8" x2="12.01" y2="8" /><polyline points="11 12 12 12 12 16 13 16" />
                </svg>
                La solicitud de materiales no tiene detalles asociados
            </div>
        @endif

        @if($outcome->state == 1)
            {{Form::Open(array('action'=>array('InvOutcomesDetailsController@storeDetails',code($outcome->id)),'method'=>'POST','autocomplete'=>'off','id'=>'formSalidas'))}}
                <h2 >Solicitar materiales</h2>
                <div class="table-responsive">
                    <table class="table table-vcenter table-center table-sm " id="tableDetalle">
                        <thead>
                            <tr>
                                <th style="width: 25%" id="item--label">MATERIAL</th>
                                <th style="width: 15%" id="cantidad--label">CANTIDAD SOLICITADA</th>
                                <th style="width: 10%" id="ubicacion--label">ORIGEN</th>
                                <th style="width: 20%" id="destino--label">DESTINO</th>
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
                                    <span class="text-sm text-yellow spancantidad" style="display: none">
                                        Cantidad disponible: <span class="cantdisp font-weight-bold"></span>
                                        <span class="form-help" data-toggle="popover" data-content="<b style='font-size:10px'>Si solicita una cantidad mayor a la cantidad disponible la aprobación de la solicitud puede demorar más.</b>">?</span>
                                    </span>
                                    <input type="text" class="form-control numero" name="cantidad" placeholder="Cantidad solicitada" style="width:100%">
                                    <span id="cantidad-error" class="text-red text-sm"></span>
                                </td>

                                <td id="ubicacion-sel2">
                                    {!! $outcome->workorders->getCod() !!}
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="destino" value="Solicitud desde {!! $outcome->workorders->cod !!}">
                                    <span id="destino-error" class="text-red text-sm"></span>
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-yellow" name="btnSubmitOutcome">
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
<script src="{{asset('/dist/js/bootstrap-editable.min.js')}}"></script>
<script>
    $.fn.editable.defaults.mode = 'inline';
    $.fn.editable.defaults.toggle = 'dblclick';
    $.fn.editable.defaults.emptytext = '-';
    modalAjax("modalItems","modalItems","modal-body");
    modalAjax("modalState","modalState","modal-content");
    modalAjax("modalEditEncabezado","modalEditEncabezado","modal-content");
    AutoNumeric.multiple('.numero',{
        modifyValueOnWheel: false,
        digitGroupSeparator : '',
        minimumValue: 0
    });
    $(".selector").select2();

    $('[data-toggle="popover"]').popover({
        html: true,
        "trigger" : "hover",
        "placement": "left",
        "container": "body",
    });

    var AnchoTr = function(e, width) {
        width.children().each(function() {
            $(this).width($(this).width());
        });
        return width;
    };

    @if ($outcome->getCantDetails() >0)
            $(function () {
                var contador = 0;
                var outcome = "{{ code($outcome->id) }}";

                $( ".edithover" ).hover(function() {
                    var id = $(this).attr('id');
                    $("."+id+"").addClass( 'text-back' );
                });
                $( ".edithover" ).mouseleave(function() {
                    var id = $(this).attr('id');
                    $("."+id+"").removeClass( 'text-back' );
                });
                var table = $('#table_details').DataTable({
                    'paging': true,
                    'lengthChange': true,
                    'searching': true,
                    'ordering': false,
                    'info': true,
                    'autoWidth': false,
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
                        {"data": "orden" },
                        {"data": "item"},
                        {"data": "cant"},
                        {"data": "report"},
                        {"data": "location",'className':'ubicacionclass'},
                        {"data": "observation"},
                        @if($outcome->state == 1)
                            {"data": "operations"},
                        @endif
                    ],
                    "drawCallback": function () {
                        $(function () {
                            $('.selectedit').editable({
                                url:'/outcomes/detail_location/update',
                                tpl:'<select class="form-select form-control" style="width:100%"></select>',
                                validate: function(value) {
                                    console.log(value)
                                    if(value=='Seleccione una opción') return "Debe escoger un almacen válido";
                                },
                                params: function(params) {
                                    // add additional params from data-attributes of trigger element
                                    params._token = $("#_token").data("token");
                                    return params;
                                },
                                success:function(data) {
                                    if(data.error){
                                        toastr.warning(data.error, "Advertencia");
                                    }
                                    if(data.success){
                                        toastr.success('Datos actualizados con éxito', "Correcto");
                                    }
                                },
                                error: function(response, newValue) {
                                    if(response.status === 500) {
                                        return 'Server error. Check entered data.';
                                    } else {
                                        return response.responseText;
                                    }
                                },
                                showbuttons: false,
                            });
                            $('.selectedit').on('save',function(e,params){
                                table.ajax.reload();
                            });

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


        var campos = ['item','cantidad','destino'];
        ValidateAjax("formSalidas",campos,"btnSubmitOutcome","{{ route('outcomes.details.store',code($outcome->id) )}}","POST","/outcomes/show/{{ code($outcome->id) }}");
</script>
@endsection