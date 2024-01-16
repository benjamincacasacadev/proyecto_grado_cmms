<div class="modal modal-danger fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalValidar" data-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            {{Form::Open(array('action'=>array('WorkOrdersController@validateRevision',code($workorder->id)),'method'=>'POST','autocomplete'=>'off','onsubmit'=>'btnValidar.disabled = true; return true;'))}}
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-success"></div>
                <div class="modal-body text-center">
                    <svg class="icon mb-2 text-green icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <circle cx="12" cy="12" r="9" />
                        <path d="M9 12l2 2l4 -4" />
                    </svg>
                    <h3>¿Está seguro?</h3>
                    <div class="text-muted" style="margin-bottom:15px">
                        ¿Está Seguro de validar el informe {{$workorder->cod}}??
                    </div>
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
                                <button type="submit" class="btn btn-success w-100" name="btnValidar">Confirmar</button>
                            </div>
                        </div>
                    </div>
                </div>
            {{Form::Close()}}
        </div>
    </div>
</div>
