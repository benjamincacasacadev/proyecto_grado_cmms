{!! Form::open(['url'=>'users_delete/'.code($users->id), 'method'=>'POST', 'id'=>'formDeleteUser','onsubmit'=>'btnEliminar.disabled = true; return true;']) !!}
    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    <div class="modal-status bg-red"></div>
    <div class="modal-body py-4">
        <center>
            <svg class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
            </svg>
            <h3>¿Está seguro?</h3>
        </center>
        <p class="text-justify">
            Esta acción no se puede deshacer, esto eliminará de forma permanente el avatar y al usuario <b>{{userFullName($users->id)}}</b>.
            <br>Esto no afectará a los registros que tiene asociado este usuario.
        </p>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-2" id="userborrar--label">
            <label> Por favor escriba <b class="text-red">{{$users->username}}</b> para confirmar</label>
            <input type="text" class="form-control" name="userborrar" placeholder="Escriba {{$users->username}}" autocomplete="off">
            <span class="text-red" id="userborrar-error"></span>
        </div>
    </div>

    <div class="modal-footer">
        <div class="w-100">
            <div class="row">
                <div class="col-lg-12 mb-3">
                    <button type="submit" class="btn btn-danger w-100 font-weight-bold" name="btnEliminar">Entiendo las consecuencias, eliminar usuario</button>
                </div>
                <div class="col-lg-12 ">
                    <a class="btn @if(themeMode() == 'D') btn-secondary @endif w-100" data-dismiss="modal">
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>
{{Form::Close()}}
{{-- ===========================================================================================
                                            VALIDACION
=========================================================================================== --}}
<script>
    var campos = ['userborrar'];
    ValidateAjax("formDeleteUser",campos,"btnEliminar","{{ route('users.destroy',code($users->id) )}}","POST","/users");
</script>
