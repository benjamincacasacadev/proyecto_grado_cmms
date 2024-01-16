<style>
    .backDark{
        background-color: #494949 !important;
    }
    .backLight{
        background-color: #f7f7f7 !important;
    }
    .headerTable{
        background-color: rgba(247, 166, 0, 0.4) !important;
    }
</style>
<div class="modal-header">
    <h5 class="modal-title"><i class="far fa-clock"></i> Duración del trabajo {{ $workorder->cod }}</h5>
    <button type="button" class="btn-close text-primary" data-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    @if (count($workTimes)> 0)
        <div class="row row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table text-center small">
                                <thead>
                                    <th colspan="3" class="headerTable">
                                        Duración de trabajo
                                    </th>
                                </thead>
                            <thead>
                                <tr>
                                    <th width="30%">Fecha Inicial</th>
                                    <th width="30%">Fecha Final</th>
                                    <th width="40%">Duración</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($workTimes as $key => $wotime)
                                <tr>
                                    @php
                                        $iniTime = isset($wotime->init_work_date) ? $wotime->init_work_date->format('d/m/Y').'<br>'.$wotime->init_work_date->format('H:i:s') : '';
                                        $endTime = isset($wotime->end_work_date) ? $wotime->end_work_date->format('d/m/Y').'<br>'.$wotime->end_work_date->format('H:i:s') : 'Pendiente';
                                        $latitudIni = isset($wotime->geolocation['latitudIni']) ? $wotime->geolocation['latitudIni'] : '';
                                        $longitudIni = isset($wotime->geolocation['longitudIni']) ? $wotime->geolocation['longitudIni'] : '';
                                        $latitudFin = isset($wotime->geolocation['latitudFin']) ? $wotime->geolocation['latitudFin'] : '';
                                        $longitudFin = isset($wotime->geolocation['longitudFin']) ? $wotime->geolocation['longitudFin'] : '';
                                    @endphp
                                    <td class="text-center">
                                        {!! $iniTime !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $endTime !!}
                                    </td>
                                    <td class="text-center text-muted">{{$wotime->durationText}}</td>
                                </tr>
                                @if (isset($wotime->description) ||  ($countWoTimes > 1 && (($key+1) != $countWoTimes) )  )
                                    <tr>
                                        <td class="text-center @if(themeMode() == 'D') backDark @else backLight @endif" colspan="3" style="font-size: 13px;">
                                            <b>Motivo de la pausa:</b>  {!!isset($wotime->description) ? $wotime->description : 'No especificado'!!}
                                        </td>
                                    </tr>
                                @endif
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @if (isset($woInterval['interval']))
                @if ( $workorder->estado  == 'T' || $workorder->estado  == 'R' || $workorder->estado  == 'C' )
                    <div class="col-12 text-center">
                        <label class="text-center" style="font-size:16px">Duración total del trabajo</label>
                        <div class="text-center font-weight-bold text-yellow" style="font-size:16px" >
                            {{$woInterval['interval']->forHumans()}}
                        </div>
                    </div>
                @endif
            @endif
        </div>
    @endif
</div>

<script>
    $('[data-toggle="tooltip"]').tooltip({
        html: true,
        trigger : 'hover',
    });
</script>
