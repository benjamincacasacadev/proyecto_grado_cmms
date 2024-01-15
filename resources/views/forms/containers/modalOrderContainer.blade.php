<style>
    .todo-list {
        margin: 0;
        padding: 0;
        list-style: none;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="alert alert-info" role="alert">
            <h4 class="alert-title">
                Información
            </h4>
            <div class="text-muted">
                Para modificar el orden de los contenedores haga clic prolongadamente en los iconos <i class="fa fa-th-list"></i> y mueva el campo a la posición deseada.
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <ul class="todo-list ui-sortable" id="listaContainers">
            @foreach ($containers as $cont)
                <li id="{{$cont['id']}}" style="margin-top:10px;">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 " style="margin-top: 15px;">
                        <span class="handle ui-sortable-handle" style="width: 100%; background-color:rgba(144, 187,255, 0)">
                            <div class="input-group ">
                                <span class="input-group-addon"><i class="fa fa-th-list"></i></span>
                        </span>
                        <input type="text" class="form-control font-weight-bold" value="{{$cont['mostrar']}}" readonly style="background-color: transparent">
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 " style="margin-bottom:-10px; margin-top:20px">
        <button type="button" class="btn btn-default pull-left salirbtn" >Cerrar</button>
    </div>
</div>

<script>
    $('.salirbtn').click(function () {
        window.location.reload();
    });

    $(function () {
        var id_form = "{{code($form->id)}}";
        var sw = 0;
        $('#listaContainers').sortable({
            placeholder:'dndPlaceHolder',
            distance:20,
            stop: function () {
                $.map($(this).find('li'), function (el) {
                    var itemID = el.id;
                    var itemIndex = $(el).index();
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('forms.orderContainer') }}",
                        method: 'POST',
                        data: {
                            id_form : id_form,
                            itemID: itemID,
                            itemIndex: itemIndex,
                            _token: _token
                        },
                        success: function (data) {
                            if(sw == 0) toastr.success('Orden modificado correctamente');
                            sw++;
                        }
                    });
                });
                sw = 0;
            }
        });
    });
</script>