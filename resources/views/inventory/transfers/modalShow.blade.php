<div class="modal-header">
    <h5 class="modal-title font-weight-bold">
        Traspaso {{ $transfer->cod }}
    </h5>
    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row" style="padding-left:10px">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 validdiv">
            <h5 class="card-title pull-left ">
                <b>Resumen&nbsp;</b>
            </h5>
            <table class="table table-sm ">
                <tbody>
                    <tr>
                        <td width="50%" class="font-weight-bold">
                            Material
                        </td>
                        <td>{!! $transfer->getItemsParts() !!}</td>
                    </tr>
                    <tr>
                        <td width="50%" class="font-weight-bold">Almacen de origen:</td>
                        <td>{{ $transfer->almacenOrigenLiteral }}</td>
                    </tr>
                    <tr>
                        <td width="50%" class="font-weight-bold">Almacen de destino:</td>
                        <td>{{ $transfer->almacenDestinoLiteral }}</td>
                    </tr>
                    <tr>
                        <td width="50%" class="font-weight-bold">Cantidad a traspasar:</td>
                        <td>{{ $transfer->quantity }}</td>
                    </tr>
                    <tr>
                        <td width="50%" class="font-weight-bold">Solicitado por:</td>
                        <td>{{ userFullName($transfer->solicitado_id) }}</td>
                    </tr>
                    <tr>
                        <td width="50%" class="font-weight-bold">Estado:</td>
                        <td>{{ $transfer->getState(0) }}</td>
                    </tr>
                    @if (isset($transfer->message) && $transfer->message != '')
                        <tr>
                            <td width="50%" class="font-weight-bold">Mensaje de anulación:</td>
                            <td>{{ $transfer->message }}</td>
                        </tr>
                    @endif

                </tbody>
            </table>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
            <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>

