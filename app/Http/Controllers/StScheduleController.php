<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\WorkOrders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use View;
use PDF;

class StScheduleController extends Controller
{
    public function datesAjax(Request $request){

        $filters = $request->filters;
        $stateF = isset($filters[6]) ? $filters[6] : 'all';
        $stateF = $stateF == 'Ven' ? '' : $stateF;

        $start = date('Y-m-d H:i',strtotime($request->start));
        $end = date('Y-m-d',strtotime($request->end));
        $end = $end.' 23:59';

        $start = Carbon::parse($start)->subDays(5);
        $end = Carbon::parse($end)->addDays(5);

        $workOrders = WorkOrders::
        whereDate('fecha','>=',$start)
        ->whereDate('fecha','<=',$end)
        ->PermisoVer()
        ->orderBy('fecha','asc')
        ->get();
        $salida_cron = [];
        $key = 0;
        foreach ($workOrders as $wo) {

            $duration = is_numeric( $wo->duration ) && floor( $wo->duration ) != $wo->duration ? $wo->duration : round($wo->duration);
            $fechaIni = Carbon::parse($wo->fecha);
            if($wo->type_duration == 'd'){
                $h = $wo->duration * 24;
                $horas = round($h);
                $msjDuration = 'Día';

                $msjHoras = ' Hr';
                $msjHoras = $wo->horas == 1 ? $msjHoras : $msjHoras.'s';
                $msjHoras = ' ('.$horas.$msjHoras.')';
            }else{
                $h = $wo->duration;
                $horas = floor($h);
                $msjDuration = 'Hora';
                $msjHoras = '';
            }
            $mins = ($h - $horas) * 60;
            $fechaFinal = $fechaIni->addHours($horas);
            $fechaFinal = $fechaIni->addMinutes($mins);
            $horaFinAux = $fechaFinal->format('H:i:s');
            $fechaFinal = $fechaFinal->format('Y-m-d H:i');

            // VERIFICAR SI EL TRABAJO INCLUYE FINES DE SEMANA
            $fechaIniMsg = Carbon::parse($wo->fecha);
            $cantDias = $fechaIniMsg->diffInHours($fechaFinal, false);
            $cantDias = isset($cantDias) ? ceil($cantDias/24) : 0;
            $cantDias = $fechaIniMsg->isWeekend() ? $cantDias + 1 : $cantDias;
            // $cantDias = $cantDias - 1;

            $fechaIniAux = Carbon::parse($wo->fecha);
            $fechaFinAux = $fechaIniAux->addWeekdays($cantDias);
            $fechaFinAux = $fechaFinAux->format('Y-m-d').' '.$horaFinAux;

            $fechaFinMsg = $wo->type_duration == 'd' && $wo->work_weekend != "1" ? $fechaFinAux : $fechaFinal;

            $initDate =
            '<div>
                <b><i class="far fa-calendar-alt"></i>&ensp; Fecha programada: </b>'.date("d/m/Y H:i", strtotime($wo->fecha)).
            '</div>';

            $endDate =
            '<div>
                <b><i class="far fa-calendar-alt"></i>&ensp; Fecha final aproximada: </b>'.date("d/m/Y H:i",strtotime($fechaFinMsg)).
            '</div>';

            $msjDuration = $wo->duration == 1 ? $msjDuration : $msjDuration.'s';

            // RESPONSABLE
            $idResp = DB::table('user_work_orders')->select('user_id')->where('responsable','1')->where('work_orders_id',$wo->id)->first();
            $techResp = isset($idResp->user_id) ? userFullName($idResp->user_id) : '';

            $textDescription =
            '<b> <i class="far fa-clock"></i>&ensp; Duración aproximada: </b>'.$duration.' '.$msjDuration.$msjHoras.
            '<div style="color:'.$wo->getEstado(2).';">
                <b><i class="fas fa-circle"></i> '.$wo->getEstado(0).'</b>
            </div>
            <hr class="mt-1 mb-1 ml-0 mr-0">
            <b>Cliente: ' . $wo->asset->cliente->nombre . '</b><br>
            <b>Prioridad:</b> ' . $wo->getPrioridad(true) . '<br>';


            $tituloTechs = '<hr class="mt-1 mb-1 ml-0 mr-0"> <b>Técnicos asignados:</b><br>';

            $textTechResp = $techResp != '' ? '<i class="fas fa-user-check"></i>&nbsp;</b>'.$techResp.'<br>' : '';
            // técnicos ADICIONALES
            $textTechAdds = '';
            $tecnicosAdicionales = DB::table('user_work_orders')->select('user_id')->where('responsable','0')->where('work_orders_id',$wo->id)->get();
            foreach($tecnicosAdicionales as $usr){
                $textTechAdds .='<b><i class="fas fa-user-cog"></i></b>&nbsp;' . userFullName($usr->user_id).'<br>';
            }

            $textTecnicos = $tituloTechs.'<div class="" style="max-height: 120px; overflow-y: auto; text-align:justify;">'.$textTechResp.$textTechAdds.'</div>';
            $btnDetalles = '';

            $date = date("Y-m-d",strtotime($wo->fecha));
            $fechaIni = Carbon::parse($date);
            // $swPast = $fechaIni->gt(Carbon::yesterday());
            // if(Gate::check('schedule.admin') && ($wo->state == '0' || $wo->state == 'T' || $wo->state == 'N') && $swPast){
            //     $btnDetalles .=
            //     '<a class="btn btn-outline-yellow border border-yellow btn-pill btn-sm font-weight-bold w-100 mt-2" href="/schedule/editmodal/'.code($wo->id).'" rel="modalEdit">
            //         Editar
            //     </a>';
            // }
            if(Gate::check('workorders.index') || Gate::check('workorders.myindex')){
                $btnDetalles .=
                '<a class="btn btn-outline-yellow border border-yellow btn-pill btn-sm font-weight-bold w-100 mt-2" href="/work_orders/show/'.code($wo->id).'" target="_blank">
                    Ver más detalles
                </a>';
            }

            $textBody = $initDate.$endDate.$textDescription.$textTecnicos.$btnDetalles;

            $fechaIniCalc = Carbon::parse($wo->fecha);


            if($wo->type_duration == 'd' && $wo->work_weekend != "1"){
                $cantDias = $fechaIniCalc->diffInHours($fechaFinal, false);
                $cantDias = isset($cantDias) ? ceil($cantDias/24) : 0;
                $cantDias = $fechaIniCalc->isWeekend() ? $cantDias + 1 : $cantDias;
                // $cantDias = $cantDias - 1;
                $fechaIniCalcAux = Carbon::parse($wo->fecha);
                $fechaFinCalcAux = $fechaIniCalcAux->addWeekdays($cantDias);
                $period = CarbonPeriod::create($fechaIniCalc, $fechaFinCalcAux)->filter('isWeekday');

                $cantDias = $fechaIniCalc->isWeekend() ? $cantDias - 1 : $cantDias;
            }else{
                $fechaFinCalc = Carbon::parse($fechaFinal);
                $cantDias = $fechaIniCalc->diffInDays($fechaFinCalc, false);
                $cantDias = isset($cantDias) ? $cantDias : 0;
                $period = CarbonPeriod::create($fechaIniCalc, $fechaFinCalc);
            }

            // dump("===========".$wo->cod."==========");
            // dump($fechaIniCalc. " ".$fechaFinCalcAux);
            foreach ($period as $dP=>$datePeriod) {
                $indDias = $cantDias > 0 ? ' ('.$dP.'/'.$cantDias.')' : '';
                $horas = $dP == 0 ? date("H:i", strtotime($wo->fecha)).'-' : '';
                $fechaMostrar = $datePeriod->format('Y-m-d H:i');
                // dump($fechaMostrar);
                $salida_cron[$key]['id'] = code($wo->id);
                $salida_cron[$key]['calendarId'] = '1';
                $salida_cron[$key]['title'] =  $horas.$wo->cod.$indDias;
                $salida_cron[$key]['category'] = 'allday';
                $salida_cron[$key]['body'] = $textBody;
                $salida_cron[$key]['start'] = $fechaMostrar;
                $salida_cron[$key]['end'] = $fechaMostrar;
                $salida_cron[$key]['color'] = '#FFFFFF';
                $salida_cron[$key]['bgColor'] = $wo->getEstado(2);
                $salida_cron[$key]['borderColor'] = $wo->getEstado(2);
                $salida_cron[$key]['isReadOnly'] = $wo->state == '0' || $wo->state == 'T' ? false : true;
                $key++;
            }
        }
        return $salida_cron;
    }
}
