{{Form::open(array('action'=>array('WorkOrdersController@destroy',code($workorder->id)),'method'=>'delete', 'onsubmit'=>'btnSubmitDelete.disabled = true; return true;'))}}
    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    <div class="modal-status bg-red"></div>
    <div class="modal-body text-center py-4">
        <svg class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
        </svg>
        <h3>¿Está seguro?</h3>
        <div class="text-muted">
            ¿Está seguro de eliminar la orden de trabajo <b>{{ $workorder->cod }}</b>?
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
                    <button type="submit" class="btn btn-danger w-100" name="btnSubmitDelete">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
{{Form::Close()}}
