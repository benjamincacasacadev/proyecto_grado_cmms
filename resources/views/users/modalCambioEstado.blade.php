    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    <div class="modal-status @if($users->active == 1) bg-yellow @else bg-info @endif"></div>
    {{Form::open(array('action'=>array('UserController@cambiarestado',code($users->id)),'method'=>'post', 'onsubmit'=>'btnCambEstado.disabled = true; return true;'))}}
        <div class="modal-body text-center py-4">
            @if ($users->active == 1)
                <svg class="icon mb-2 text-yellow icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14.274 10.291a4 4 0 1 0 -5.554 -5.58m-.548 3.453a4.01 4.01 0 0 0 2.62 2.65" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 1.147 .167m2.685 2.681a4 4 0 0 1 .168 1.152v2" /><line x1="3" y1="3" x2="21" y2="21" />
                </svg>
                <h3>¿Está seguro?</h3>
                <div class="text-muted">
                    ¿Está seguro de de cambiar a estado INACTIVO al usuario <b>{{userFullName($users->id)}}?</b>?
                </div>
            @else
                <svg class="icon mb-2 text-info icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="7" r="4" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 11l2 2l4 -4" />
                </svg>
                <h3>¿Está seguro?</h3>
                <div class="text-muted">
                    ¿Está seguro de de cambiar a estado ACTIVO al usuario <b>{{userFullName($users->id)}}?</b>?
                </div>
            @endif
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
                        <button type="submit" class="btn @if($users->active == 1) btn-yellow @else btn-info @endif w-100" name="btnCambEstado">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
    {{Form::Close()}}


