
{{Form::open(array('action'=>array('StFormController@destroyMaintenance',$idcampo,$id),'method'=>'delete', 'onsubmit'=>'deleteboton.disabled = true; return true;')) }}
    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    <div class="modal-status bg-red"></div>
    <div class="modal-body text-center py-4">
        <svg class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
        <br>
        <b>¿Está Seguro de eliminar el campo</b> <b class="text-red">"{{$nombre_campo}}"</b> <b>?</b>
        <div class="text-muted">
            <i class="text-sm pull-left mt-2">
                @if (count($campos_dep)>0)
                    El campo tiene asociados los siguientes campos dependientes que también se eliminarán: <br>
                    <div class="mt-3" style="max-height: 200px; overflow-y: auto; text-align:justify;">
                        @foreach ($campos_dep as $item)
                        <b> - {{$item}}.</b> <br>
                        @endforeach
                    </div>
                @endif
            </i>
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
                    <button type="submit" class="btn btn-danger w-100" name="deleteboton">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
{{Form::Close()}}
