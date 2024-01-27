<div class="modal-header">
    <h5 class="modal-title font-weight-bold">
        Traspaso: {!! $transfer->getItemsParts() !!}
    </h5>
    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    {{ Form::Open(['action' => ['InvTransfersController@updateState', code($transfer->id)], 'method' => 'POST', 'autocomplete' => 'off', 'id' => 'formStateTrasfers']) }}
    <div class="row" style="padding-left:10px">
        <div class="form-selectgroup-boxes row" id="checkstate--label">
            @if ($swdisp == 0)
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <label class="form-selectgroup-item ">
                        <input type="radio" name="checkstate" value="2" class="form-selectgroup-input" id="radiovali">
                        <span class="form-selectgroup-label d-flex align-items-center p-3 ">
                            <span class="form-selectgroup-label-content">
                                <span class="me-3">
                                    <span class="form-selectgroup-check"></span>
                                </span>
                                <span class="form-selectgroup-title font-weight-bold mb-1 text-success">
                                    Validar traspaso
                                </span>
                            </span>
                        </span>
                    </label>
                </div>
            @endif

            @if($swdisp == 1)
                <b class="text-orange" style="font-size:12px">No puede AUTORIZAR el traspaso </b> <br>
                <span class="text-muted" style="font-size:12px" >
                    La cantidad a traspasar <b>{{ $transfer->quantity }}</b> sobrepasa a la cantidad disponible en la ubicación de origen "{{ $transfer->almacenOrigenLiteral }}" <b>{{ number_format($total,2,".","") }}</b>.
                </span>
            @endif

            <div class="@if ($swdisp == 0) col-lg-6 col-md-6 @else col-lg-12 col-md-12 @endif col-sm-12 col-xs-12">
                <label class="form-selectgroup-item">
                    <input type="radio" name="checkstate" value="0" class="form-selectgroup-input" id="radioanul">
                    <span class="form-selectgroup-label d-flex align-items-center p-3">
                        <span class="form-selectgroup-label-content">
                            <span class="me-3">
                                <span class="form-selectgroup-check"></span>
                            </span>
                            <span class="form-selectgroup-title font-weight-bold mb-1 text-red">
                                Anular traspaso
                            </span>
                        </span>
                    </span>
                </label>
            </div>
            <center><span id="checkstate-error" class="text-red font-weight-bold"></span></center>
        </div>
        <div class="row" style="margin-top:20px">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 validdiv">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title pull-left ">
                            <b>Resumen&nbsp;</b>
                        </h5>
                        <table class="table table-sm text-sm">
                            <tbody>
                                <tr>
                                    <td width="50%" class="font-weight-bold">
                                        Material:
                                    </td>
                                    <td>{!! $transfer->getItemsParts() !!}</td>
                                </tr>
                                <tr>
                                    <td width="50%" class="font-weight-bold">Ubicación de origen:</td>
                                    <td>{{ $transfer->almacenOrigenLiteral }}</td>
                                </tr>
                                <tr>
                                    <td width="50%" class="font-weight-bold">Ubicación de destino:</td>
                                    <td>{{ $transfer->almacenDestinoLiteral }}</td>
                                </tr>
                                <tr>
                                    <td width="50%" class="font-weight-bold">Cantidad a traspasar:</td>
                                    <td>{{ $transfer->quantity }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="table-responsive ">
                            <table class="table table-vcenter table-center table-sm table-hover text-sm" id="table_detailsmodal">
                                <thead>
                                    <th>Ubicación</th>
                                    <th>Cantidad</th>
                                </thead>

                                <tbody>
                                    @foreach ($stocks as $stock)
                                        <tr>
                                            <td>
                                                {{ $stock->almacenLiteral }}
                                                @if($stock->location == $transfer->origin_location)
                                                    <i class="font-weight-bold"> (Origen) </i>
                                                @elseif($stock->location == $transfer->destination_location)
                                                    <i class="font-weight-bold">(Destino) </i>
                                                @endif
                                            </td>
                                            <td> {{  number_format($stock->ingresos - $stock->egresos,2,'.','')  }} </td>
                                        </tr>
                                        {{-- almacenDestinoLiteral --}}
                                    @endforeach
                                    <tr>
                                        <td class="font-weight-bold ">Total:</td>
                                        <td>
                                            <i>{{ number_format($transfer->items->TotalItem,2,'.','')  }}</i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 anulardiv" style="display:none">
                <div class="form-group">
                    <label id="motivo--label">Motivo de anulación</label><br>
                    <textarea name="motivo" class="form-control" style="width:100%;resize:none" rows="3"></textarea>
                    <span id="motivo-error" class="text-red"></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
                <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary pull-right" name="btnSubmitState">Actualizar estado</button>
            </div>
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

    var statefields = ["checkstate","motivo"];
    ValidateAjax("formStateTrasfers",statefields,"btnSubmitState","{{ route('transfers.state',code($transfer->id) )}}","POST","/transfers");
</script>
