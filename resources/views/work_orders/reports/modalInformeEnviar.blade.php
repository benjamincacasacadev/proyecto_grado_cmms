
{{Form::Open(array('action'=>array('WorkOrdersController@SendRevision',code($workorder->id), $swC ),'method'=>'put','autocomplete'=>'off','id'=>'formSendInforme','onsubmit' => 'btnSubmitSendRevision.disabled = true; return true;'))}}
    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    <div class="modal-status bg-yellow"></div>
    <div class="modal-body text-center py-4">
        <svg class="icon mb-2 text-yellow icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" /></svg>
        <h3>¿Está seguro?</h3>
        <div class="text-muted">
            ¿Está seguro de enviar el informe de orden de trabajo <b>{{ $workorder->cod }}</b>?
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-2">
            <div class="card">
                <div class="card-body">
                    <p class="text-yellow">El trabajo se encuentra <b>{{mb_strtoupper($workorder->getEstado(0))}}</b> con una duración de&nbsp;
                        <b style="font-size:20px">
                            <span id="h_modalSent" class="relojNumeros hours" style="display:inline-block !important;" >{{str_pad($workorder->timeElapsed['h'], 2, "0", STR_PAD_LEFT)}}</span>
                            <span class="relojNumeros dots" style="display:inline-block !important;" >:</span>
                            <span id="m_modalSent" class="relojNumeros minutes" style="display:inline-block !important;" >{{str_pad($workorder->timeElapsed['m'], 2, "0", STR_PAD_LEFT)}}</span>
                            <span class="relojNumeros dots" style="display:inline-block !important;" >:</span>
                            <span id="s_modalSent" class="relojNumeros seconds" style="display:inline-block !important;" >{{str_pad($workorder->timeElapsed['s'], 2, "0", STR_PAD_LEFT)}}</span>
                        </b>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <div class="w-100">
            <div class="row">
                <div class="col">
                    <a class="btn w-100" data-dismiss="modal">
                        Cancelar
                    </a>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary pull-right" name="btnSubmitSendRevision">Enviar</button>
                </div>
            </div>
        </div>
    </div>
{{Form::Close()}}


