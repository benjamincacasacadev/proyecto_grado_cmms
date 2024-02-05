<div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
    <address>
        <span style="font-size:17px;">
            <strong>Orden de trabajo: {!! $workorder->getCod() !!} </strong>
        </span><br>
        Cliente: <span style="font-size:17px">{{ $workorder->asset->cliente->nombre }}</span><br>
        Fecha de mantenimiento: <b>{{ isset($workorder) && $workorder->end_date != '' ? date("d/m/Y", strtotime($workorder->end_date)) : 'No definido'}}</b><br>
        Técnico responsable: <b>{{ $getResponsable != null ? userFullName($getResponsable) : "Sin asignar" }}</b><br>
        <span class="div-description"> Descripción: <b> {!! purify(nl2br($workorder->descripcion)) !!} </b></span>
    </address>
</div>
{{-- ==================================================================================================================== --}}
{{--                                            Tiempo de trabajo                                                         --}}
{{-- ==================================================================================================================== --}}
<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
        @if($workorder->estado == "P" && count($workorder->workTimes) == 0)
            <div class="text-center">
                <button class="btn btn-outline-teal btn-lg border border-teal border-2" type="button" id="init_work">
                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><polyline points="12 7 12 12 15 15" /></svg>
                    <b> Iniciar trabajo</b>
                </button>
            </div>
        @endif

        <div id="timer" class="col-12 text-center">
            @if ( $workorder->estado == 'E' || $workorder->estado == "S")
                <b class="text-center text-teal " style="font-size:20px">
                    <span class="text-teal" style="{{($workorder->estado == 'S') ? 'display:none' : ''}}">Tiempo de trabajo</span>
                    <span class="text-yellow" style="{{($workorder->estado != 'S') ? 'display:none' : ''}}">Tiempo de trabajo en pausa</span>
                </b>
            @endif

            @if ($workorder->estado == 'E' || $workorder->estado=='S')
                <div class="text-center" style="font-size:60px">
                    <span class="relojNumeros hours">{{str_pad($horas, 2, "0", STR_PAD_LEFT)}}</span>
                    <span class="relojNumeros dots">:</span>
                    <span class="relojNumeros minutes">{{str_pad($mins, 2, "0", STR_PAD_LEFT)}}</span>
                    <span class="relojNumeros dots">:</span>
                    <span class="relojNumeros seconds">{{str_pad($segs, 2, "0", STR_PAD_LEFT)}}</span>
                </div>
            @endif
            @if ( !($workorder->estado == "T" || $workorder->estado == "R" || $workorder->estado == "C"))
                <button class="btn btn-outline-yellow btn-lg font-weight-bold border-2 border-yellow" id="btnplay" style="display: {{($workorder->estado != 'S') ? 'none' : ''}}">
                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 4v16l13 -8z" /></svg>
                    Continuar
                </button>
                @if ($workorder->estado == 'E')
                    <button class="btn btn-outline-yellow btn-lg font-weight-bold border-2 border-yellow" id="btnPause">
                        <svg  class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="6" y="5" width="4" height="14" rx="1" /><rect x="14" y="5" width="4" height="14" rx="1" /></svg>
                        Pausa
                    </button>
                @endif
            @endif
        </div>

        {{-- Datos cuando el cronometro de tiempo ya concluyo --}}
        @if ( ( count($workorder->workTimes) > 0 && ($workorder->estado == "T" || $workorder->estado == "R" || $workorder->estado == "C")) )
            <div class="col-12 text-center">
                <label class="text-center" style="font-size:20px">Tiempo total de trabajo</label>
                <div class="text-center font-weight-bold text-cyan" style="font-size:20px" >
                    @php
                        $labhor = $horas > 1 ? $horas." horas " : $horas." hora ";
                        $labmin = $mins > 1  ? $mins." minutos " : $mins." minuto ";
                        $labseg = $segs > 1  ? $segs." segundos." : $segs." segundo.";
                        $labhor = $horas > 0 ? $labhor : "";
                        $labmin = $mins > 0  ? $labmin : "";
                        $labseg = $segs > 0  ? $labseg : "";
                    @endphp
                    {{ $labhor.$labmin.$labseg }}
                </div>
            </div>
        @endif
        {{-- Datos de estado --}}
        <div class="col-12 text-center font-weight-bold mt-3 mb-2" style="font-size:22px;">
            {!! $workorder->getEstado(6) !!}
        </div>
</div>

{{-- ==================================================================================================================== --}}
{{--                                            Datos del activo                                                          --}}
{{-- ==================================================================================================================== --}}
<div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
    <address class="pull-right" style="text-align: right">
        <span style="font-size:20px;">
            <strong>Datos del activo: {!! $asset->getCod() !!} </strong>
        </span><br>
        Nombre: <b>{{ $asset->nombre }}</b><br>
        Categoría: <b>{{ $asset->categoriaLiteral }}</b><br>
        Ubicación: <b>{{ $asset->ubicacion }}</b><br>
        Nro de serie: <b>{{ $asset->nro_serie }}</b><br>
    </address>
</div>


@push('scripts')

    {{-- ============================================================ --}}
    {{--                FUNCIONES PARA EL CRONOMETRO                  --}}
    {{-- ============================================================ --}}

    <script>
        $( "#btnInitTrip" ).click(function() {
            var stateWo = "{{$workorder->estado}}";
            if(stateWo == '0'){
                $('#modalInitTrip').modal('show');
            }
        });

        $( "#btnEndTrip" ).click(function() {
            var stateWo = "{{$workorder->estado}}";
            if(stateWo == 'T'){
                console.log(stateWo);
                var idTime = $("#woTimeRegister").val();
                $('#tripTimeRegister').val(idTime);
                $('#modalEndTrip').modal('show');
            }
        });
    </script>

    <script>
        $( "#init_work" ).click(function() {
            $('#modalTime').modal('show');
        });
        var seconds = 0
        var minutes = 0
        var hours = 0
        var interval = null;
        const s = $(timer).find('.seconds')
        const m = $(timer).find('.minutes')
        const h = $(timer).find('.hours')

        function aumentarCeros(d) {
            return (d < 10) ? '0' + d.toString() : d.toString()
        }
        function reiniciar() {
            $(s).text(aumentarCeros(seconds))
            $(m).text(aumentarCeros(minutes))
            $('#s_modal').text(aumentarCeros(seconds))
            $('#m_modal').text(aumentarCeros(minutes))
            $('#s_modalSent').text(aumentarCeros(seconds))
            $('#m_modalSent').text(aumentarCeros(minutes))
            $('#s_modalTrip').text(aumentarCeros(seconds))
            $('#m_modalTrip').text(aumentarCeros(minutes))
            $('#s_modalTerm').text(aumentarCeros(seconds))
            $('#m_modalTerm').text(aumentarCeros(minutes))
            if (hours < 0) {
                $(s).text('00')
                $(m).text('00')
                $(h).text('00')
                $('#s_modal').text('00');
                $('#m_modal').text('00');
                $('#h_modal').text('00');

                $('#s_modalTerm').text('00');
                $('#m_modalTerm').text('00');
                $('#h_modalTerm').text('00');

                $('#s_modalSent').text('00');
                $('#m_modalSent').text('00');
                $('#h_modalSent').text('00');

                $('#s_modalTrip').text('00');
                $('#m_modalTrip').text('00');
                $('#h_modalTrip').text('00');
            } else {
                $(h).text(aumentarCeros(hours))
                $('#h_modal').text(aumentarCeros(hours))
                $('#h_modalTerm').text(aumentarCeros(hours))
                $('#h_modalSent').text(aumentarCeros(hours))
                $('#h_modalTrip').text(aumentarCeros(hours))
            }
        }
    </script>
    @if ($workorder->estado=='E')
        <script>
            $(document).ready(function(){
                var s = "{{ $segs }}";
                var m = "{{ $mins }}";
                var h = "{{ $horas }}";
                activeTimer(h,m,s);
            });
        </script>
    @endif
    <script>
        function activeTimer(h,m,s) {
            seconds = s;
            minutes = m;
            hours = h;
            interval = setInterval(() => {
                if (seconds < 59) {
                    seconds++
                    reiniciar()
                }else if (seconds == 59) {
                    minutes++
                    seconds = 0
                    reiniciar()
                }
                if (minutes == 60) {
                    hours++
                    minutes = 0
                    seconds = 0
                    reiniciar()
                }
            }, 1000)
        }
    </script>
    <script>
        // BUTTON PAUSE
        $('#btnPause').click(function () {
            $('#reasonPauseTime').removeClass('is-valid').removeClass('is-invalid').val('');
            $('#reasonPauseTime-error').html('');
            $('#modalPauseTime').modal('show');
        });
        // BUTTON CONTINUAR
        $('#btnplay').click(function () {
            workDateRanges(h.html(),m.html(),s.html(),2,null);
        });
        // BUTTON CONFIRMAR PAUSE
        $('#btnConfPauseTime').click(function () {
            $(this).attr('disabled',true);
            var motivoPause = $('#reasonPauseTime').val();
            $('#reasonPauseTime').removeClass('is-valid').removeClass('is-invalid');
            $('#reasonPauseTime-error').html('');
            if (motivoPause != '' && motivoPause !== null) {
                workDateRanges(h.html(),m.html(),s.html(),1,motivoPause);
            } else {
                $('#reasonPauseTime').removeClass('is-valid').addClass('is-invalid');
                $('#reasonPauseTime-error').html('&nbsp;<i class="fa fa-ban"></i> Debe ingresar el motivo de la pausa.');
                $(this).attr('disabled',false);
            }
        });

        // FUNCION POR AJAX PARA GUARDAR LOS INTERVALOS DE FECHAS
        function workDateRanges(h, m, s, sw, motivo) {
            $('#btnplay').attr('disabled',true);
            $('#btnConfPauseTime').attr('disabled',true);
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('workorders.timeRangeStore',code($workorder->id)) }}",
                method: "POST",
                data: {
                    _token: _token,
                    time: h+':'+m+':'+s,
                    sw: sw,
                    motivo: motivo,
                },
                success: function (data) {
                    if(data.alerta) {
                        toastr.error(data.mensaje, 'Algo salió mal');
                    }
                    if (data.success == '2' || data.success == '1') {
                        window.location.reload();
                    }
                },
                error: function (data) {
                }
            });
        }
    </script>
@endpush