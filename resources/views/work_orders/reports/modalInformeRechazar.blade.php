<div class="modal modal-danger fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalRechazar" data-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            {{Form::Open(array('action'=>array('WorkOrdersController@rejectRevision',code($workorder->id)),'method'=>'put','autocomplete'=>'off','onsubmit'=>'anular.disabled = true; return true;'))}}
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-red"></div>
                <div class="modal-body text-center">
                    <svg class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
                    <h3>¿Está seguro?</h3>
                    <div class="text-muted" style="margin-bottom:15px">
                        ¿Está Seguro de rechazar el informe {{$workorder->cod}}??
                    </div>

                    <textarea name="textrechazo" rows="3" class="form-control" style="width:100%; resize:none" placeholder="Motivo de rechazo del informe" required></textarea>
                    <input type="text" name="url" hidden value="{{ config('app.url')."reports/show/".code($workorder->id)  }}">
                </div>

                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col">
                                <a class="btn @if(themeMode() == 'D') btn-secondary @endif w-100" data-dismiss="modal">
                                    Cancelar
                                </a>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-danger w-100" name="anular">Rechazar</button>
                            </div>
                        </div>
                    </div>
                </div>
            {{Form::Close()}}
        </div>
    </div>
</div>
