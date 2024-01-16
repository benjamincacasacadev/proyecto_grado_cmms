{!! Form::open(['url'=>['reports/deletefile',$attach->id,$cod],'method'=>'delete', 'onsubmit'=>'deleteboton.disabled = true; return true;' ]) !!}
    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    <div class="modal-status bg-red "></div>
    <div class="modal-body text-center py-4">
        {!! iconoArchivos($attach->path)!!}
        <h3>¿Está seguro?</h3>
        <div class="text-muted">
            ¿Está seguro de eliminar el archivo <b>{{$attach->nombre}}</b>?
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
