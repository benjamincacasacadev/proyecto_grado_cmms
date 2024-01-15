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
                Para modificar el orden de los sub contenedores haga clic prolongadamente en los iconos <i class="fa fa-th-list"></i> y mueva el campo a la posición deseada.
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="text-center text-primary" style="font-size:18px">
            <b>CONTENEDOR:</b> {{ $nombreCont }}
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <ul class="todo-list ui-sortable" id="listaSubContainers">
            @foreach ($subcontainers as $subcont)
                <li id="{{$subcont['val']}}" style="margin-top:10px;">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 " style="margin-top: 15px;">
                        <span class="handle ui-sortable-handle" style="width: 100%; background-color:rgba(144, 187,255, 0)">
                            <div class="input-group ">
                                <span class="input-group-addon"><i class="fa fa-th-list"></i></span>
                        </span>
                        <input type="text" class="form-control font-weight-bold" value="{{$subcont['mostrar']}}" readonly style="background-color: transparent">
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 " style="margin-bottom:-10px; margin-top:20px">
        <button type="button" class="btn btn-default pull-left salirbtnContainer" >Cerrar</button>
    </div>
</div>
<script>
    $('.salirbtnContainer').click(function () {
        var cont_a = "{{$idcont}}";
        var pageURL = $(location).attr("href").split('?')[0];
        var url = pageURL+"?contid="+cont_a;
        setTimeout('window.location.href = "'+url+'"',500);
    });

    $(function () {
        var id_form = "{{code($form->id)}}";
        var cont = "{{$idcont}}";
        var sw = 0;
        $('#listaSubContainers').sortable({
            placeholder:'dndPlaceHolder',
            distance:20,
            stop: function () {
                $.map($(this).find('li'), function (el) {
                    var itemID = el.id;
                    var itemIndex = $(el).index();
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('forms.orderSubContainer') }}",
                        method: 'POST',
                        data: {
                            id_form : id_form,
                            cont : cont,
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