<div class="table-responsive" style="display:none" id="tableitems">
    <table class="table table-vcenter table-sm table-hover" id="tableListItems" >
        <thead hidden>
            <th width="50%">Imagen</th>
            <th width="10%">Datos</th>
            <th width="40%">Seleccionar</th>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script>

    $(function () {
        var table = $('#tableListItems').DataTable({
            'mark'        : true,
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : false,
            'info'        : true,
            'autoWidth'   : false,
            "order": [[ 0, "desc" ]],
            "pageLength": 10,
            "columnDefs": [ {
                "orderable": false,
                "targets": ["_all"] ,
            } ],
            "processing": true,
            "serverSide": true,
            "language": {
                "processing": "<i class='fa fa-spinner fa-3x fa-spin' style='color:gray'></i>"
            },
            "ajax":{
                "url": "{{ route('items.table') }}",
                'dataType': 'json',
                'type': 'POST',
                'data': {
                    "_token": "{{ csrf_token() }}",
                },
            },

            "columns": [
                { "data": "imagen", "className":"text-center" },
                { "data": "datos" },
                { "data": "button", "className":"text-center" },
            ],
            "drawCallback": function () {
                $("#tableitems").fadeIn("slow");
                restartActionsDT();
                $(".modalbtn").click(function () {
                    var cod = $(this).attr("data-cod");
                    var id = $(this).attr("id");
                    var cant = $(this).attr("data-cant");

                    $("#itemcod").val(id);
                    $("#inputCoditem").val(cod);
                    $("#inputCoditem").attr("title","Clic para cambiar de item");
                    $(".spancantidad").show();
                    $(".cantdisp").text(cant);
                    $('#modalItems').modal('toggle');
                });

                $('.inputSearchDT').on('paste', function(e) {
                    var valor = e.originalEvent.clipboardData.getData('Text');
                    var id = $(this).attr('id');
                    if ( noPegar(valor,id,'top') == 1) e.preventDefault();
                });
                $('.inputSearchDT').on('drop', function(e) {
                    event.preventDefault();
                    event.stopPropagation();
                    var id = $(this).attr('id');
                    $('#'+id).attr('data-toggle','popover');
                    $('#'+id).attr('data-trigger','manual');
                    $('#'+id).attr('data-content','<span class="text-red font-weight-bold"><center><i class="fa fa-ban"></i> La acción no se puede realizar.<br>Por favor escríba el texto</center></span>');
                    $('#'+id).attr('data-placement','top');
                    $('#'+id).attr('data-html','true');
                    $('#'+id).attr('data-container','body');
                    $('#'+id).popover('show');
                    setTimeout(function(){
                        $('#'+id).popover('hide');
                        $('#'+id).removeAttr('data-toggle');
                        $('#'+id).removeAttr('data-trigger');
                    }, 2000)
                });
            }
        });
    });

    $(".cerrarm").click(function () {
        $('#modalItemsEdit').modal('toggle');
    });

</script>
