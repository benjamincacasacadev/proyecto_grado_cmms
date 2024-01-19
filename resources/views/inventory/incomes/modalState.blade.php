<div class="modal-header">
    <h5 class="modal-title font-weight-bold">
        Cambio de estado ingreso {{ $income->cod }}
    </h5>
    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    {{ Form::Open(['action' => ['InvIncomesController@updateState', code($income->id)], 'method' => 'POST', 'autocomplete' => 'off', 'id' => 'formStateIncomes']) }}
    <div class="row">
        <div class="form-selectgroup-boxes row mb-3">
            <div class="col-lg-6">
                <label class="form-selectgroup-item">
                    <input type="radio" name="checkstate" value="2" class="form-selectgroup-input" id="radiovali">
                    <span class="form-selectgroup-label d-flex align-items-center p-3">
                        <span class="me-3">
                            <span class="form-selectgroup-check"></span>
                        </span>
                        <span class="form-selectgroup-label-content">
                            <span class="form-selectgroup-title font-weight-bold mb-1 text-success">
                                Validar ingreso
                            </span>
                        </span>
                    </span>
                </label>
            </div>
            <div class="col-lg-6">
                <label class="form-selectgroup-item">
                    <input type="radio" name="checkstate" value="0" class="form-selectgroup-input" id="radioanul">
                    <span class="form-selectgroup-label d-flex align-items-center p-3">
                        <span class="me-3">
                            <span class="form-selectgroup-check"></span>
                        </span>
                        <span class="form-selectgroup-label-content">
                            <span class="form-selectgroup-title font-weight-bold mb-1 text-red">
                                Anular ingreso
                            </span>
                        </span>
                    </span>
                </label>
            </div>
            <center><span id="checkstate-error" class="text-red font-weight-bold"></span></center>
        </div>

        <label class="validdiv">Lista de materiales (Resumen)</label>
        <div class="validdiv">
            <table class="table table-vcenter table-center table-sm table-hover text-sm" id="table_detailsmodal">
                <thead>
                    <tr>
                        <th width="5%">Nº</th>
                        <th width="50%">Código materiales</th>
                        <th width="35%">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <center><span class="text-red font-weight-bold" id="preciomsg-error"></span></center>
        </div>


        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 anulardiv" style="display:none">
            <div class="form-group">
                <label id="motivo--label">Motivo de anulación</label><br>
                <textarea name="motivo" class="form-control" style="width:100%;resize:none" rows="3"></textarea>
                <span id="motivo-error" class="text-red"></span>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:10px">
            <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary pull-right" name="btnSubmitStateIncome">Actualizar estado</button>
        </div>
    </div>
    {{ Form::Close() }}
</div>

<script>
    $("#radiovali").change(function() {
        if($(this).is(":checked")) {
            $(".validdiv").slideDown();
            $(".anulardiv").slideUp();
        }
    });
    $("#radioanul").change(function() {
        if($(this).is(":checked")) {
            $(".validdiv").slideUp();
            $(".anulardiv").slideDown();
        }
    });
    $(function () {
        var income = "{{ code($income->id) }}";
        var asociado = "{{ $income->associate }}"
        var table = $('#table_detailsmodal').DataTable({
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'ordering': false,
            'info': true,
            'autoWidth': false,
            "order": [['0', 'desc']],
            'mark': "true",
            'dom': 'rt',
            "pageLength": 25,
            processing: true,
            serverSide: true,
            "language": {
                "processing": "<i class='fa fa-spinner fa-3x fa-spin' style='color:gray'></i>"
            },
            "columnDefs": [{
                "orderable": false,
                "targets": ["_all"]
            }],
            "ajax": {
                "url": "{{ route('incomes.details.table') }}",
                'dataType': 'json',
                'type': 'post',
                'data': {
                    "_token": "{{ csrf_token() }}",
                    income: income,
                    asociado: asociado
                },
                "error": function(reason) {
                    errorsDatatable(reason.status);
                },
            },
            "columns": [
                { "data": "nro" },
                {"data": "itemmodal"},
                {"data": "cant"},
                @if ($income->associate == 'I')
                    {"data": "unit_cost"},
                @endif
            ],
            "drawCallback": function () {
                AutoNumeric.multiple('.moneda',{
                    modifyValueOnWheel: false,
                    minimumValue: 0
                });
            }
        });
    });

    var statefields = ["checkstate","motivo"];

    $("#formStateIncomes").on('submit', function(e) {
        e.preventDefault();
        var registerForm = $("#formStateIncomes");
        var formData = new FormData($("#formStateIncomes")[0]);
        $.each(campos, function( indice, valor ) {
            $("#"+valor+"-error").html( "" );
            var inputtype = $("[name="+valor+"]").attr("type");
            if(inputtype != 'radio')    $("[name="+valor+"]").removeClass('is-invalid').addClass('is-valid');
            $("select[name="+valor+"]").removeClass('is-invalid-select').addClass('is-valid-select').removeClass('select2-selection');
            $("#formStateIncomes #"+valor+"-sel2 .select2-selection").removeClass('is-invalid-select').addClass('is-valid-select');
            $("#formStateIncomes #"+valor+"-sel2 .select2-selection").css('border','1px solid #5eba00');
            $(".programadodiv").css('border','1px solid transparent');
            $("#preciomsg-error").html( '' );
        });
        $.ajax({
            url: "{{ route('incomes.state',code($income->id) )}}",
            type: "POST",
            data:formData,
            contentType: false,
            processData: false,
            success:function(data) {
                if(data.alerta) {
                    toastr.error(data.mensaje);
                    $("[name=btnSubmitStateIncome]").attr('disabled',false)
                }
                if(data.success) {
                    $("[name=btnSubmitStateIncome]").attr('disabled',true)
                    window.location.href = "/incomes/{{ code($income->id) }}";
                }
            },
            error: function(data){
                if(data.responseJSON.errors) {
                    var swprecio = 0; var msgdates = "";
                    $.each(data.responseJSON.errors, function( index, value ) {
                        if (~index.indexOf("precio")){
                            swprecio = 1;
                        }else{
                            $('#'+index+'-error' ).html( '&nbsp;<i class="fa fa-ban"></i> '+value );
                            var inputtype = $("[name="+index+"]").attr("type");
                            if(inputtype != 'radio')    $("[name="+index+"]").removeClass('is-valid').addClass('is-invalid');
                            $("select[name="+index+"]").removeClass('is-valid-select').addClass('is-invalid-select').removeClass('select2-selection');
                            $("#formStateIncomes #"+index+"-sel2 .select2-selection").removeClass('is-valid-select').addClass('is-invalid-select');
                            $("#formStateIncomes #"+index+"-sel2 .select2-selection").css('border','1px solid #cd201f');
                        }
                    });
                    if(swprecio == 1){
                        $("#preciomsg-error").html( '<i class="fa fa-ban"></i>&nbsp;Debe llenar correctamente todos los campos de precio unitario y el valor debe ser mayor a 0' );
                    }

                    var indexaux = []; var camposaux =[]; var i=0;
                    $.each(campos, function( indice, valor ) {
                        if(data.responseJSON.errors[valor]){
                            indexaux[i] = indice;  i++;
                        }
                        var j = indice;
                        camposaux[j] = valor;
                    });
                    var menor = Math.min.apply(null, indexaux);
                    $("[name=btnSubmitStateIncome]").attr('disabled',false);
                }
                if(typeof(data.status) != "undefined" && data.status != null && data.status == '401'){
                    window.location.reload();
                }
            }
        });
    });

</script>
