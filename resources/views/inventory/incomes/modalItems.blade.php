<style>
    .modal-aux{
        position: relative;
        flex: 1 1 auto;
        padding: 1.5rem;
        font-size: 12px;
    }
    .modal { overflow-y: auto; }

    .modal-footer-aux{
        display: flex;
        flex-wrap: wrap;
        flex-shrink: 0;
        align-items: center;
        justify-content: flex-end;
        padding: 1.125rem;
        padding-top: 1.125rem;
        padding-bottom: 1.125rem;
        border-top: 0 solid #e6e8e9;
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
        padding-top: 0;
        padding-bottom: .75rem;
    }

</style>
<div class="modal-header">
    <h5 class="modal-title">
        Materiales en inventario
    </h5>
    <button type="button" class="btn-close cerrarm" aria-label="Close"></button>
</div>
<div class="modal-status bg-primary"></div>
<div class="modal-aux">
    <div class="table-responsive" style="display:none" id="divtable">
        <table class="table table-vcenter  table-sm table-hover" id="tableListItemsEdit" >
            <thead hidden>
                <th width="50%">Imagen</th>
                <th width="10%">Datos</th>
                <th width="40%">Seleccionar</th>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script>

    $(function () {
        var table = $('#tableListItemsEdit').DataTable({
            'mark'        : true,
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : false,
            'info'        : true,
            'retrieve'    : true,
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
                "url": "{{ route('items.incomes.table') }}",
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
                restartActionsDT();
                $("#divtable").fadeIn("slow");
                $('.nopegar').on('paste', function(e) {
                    var valor = e.originalEvent.clipboardData.getData('Text');
                    var id = $(this).attr('id');
                    if ( noPegar(valor,id) == 1) e.preventDefault();
                });
                function noPegar(valor,id){
                    var regex = /^[a-zA-Z0-9ñáéíóú_ÁÉÍÓÚ!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/? ]*$/;
                    if(regex.test(valor) == false) {
                        $('#'+id).attr('data-toggle','popover');
                        $('#'+id).attr('data-content','<span class="text-red font-weight-bold"><center><i class="fa fa-ban"></i> El texto no puede ser copiado.<br>Por favor escríbalo</center></span>');
                        $('#'+id).attr('data-placement','top');
                        $('#'+id).attr('data-html','true');
                        $('#'+id).popover('show');
                        setTimeout(function(){
                            $('#'+id).popover('destroy');
                            $('#'+id).removeAttr('data-toggle');
                        }, 2000)
                        return 1;
                    }else return 0;
                }
                $(".modalbtn").click(function () {
                    var cod = $(this).attr("data-cod");
                    var id = $(this).attr("id");

                    $("#itemcodedit").val(id);
                    $("#inputCoditemedit").val(cod);
                    $("#inputCoditemedit").attr("title","Clic para cambiar de item");
                    $('#modalItemsEdit').modal('hide');
                });
            }
        });
    });

    $(".cerrarm").click(function () {
        $('#modalItemsEdit').modal('hide');
    });

</script>
