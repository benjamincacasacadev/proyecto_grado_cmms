<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
class WorkOrders extends Model
{
    //    JSON
    protected $casts = [
        'info_general' => 'array',
    ];
    // ======================================================================================================
    //                                                   RELACIONES
    // ======================================================================================================

    public function asset(){
        return $this->belongsTo(StAssets::class, 'asset_id');
    }

    public function attachesReport() {
        return $this->hasMany(StAttach::class,'work_order_id');
    }

    public function usuarios(){
        return $this->belongsToMany('App\User')->withPivot('responsable');
    }

    public function forms(){
        return $this->belongsTo(StForms::class, 'form_id');
    }

    public function pivotWO(){
        return $this->belongsToMany(User::class,'user_work_orders','work_orders_id','user_id')->orderBy('responsable','desc');
    }
    // RELACION DE MUCHOS A MUCHOS CON USUARIOS RESPONSABLES
    public function pivotUserResponsable(){
        return $this->belongsToMany(User::class,'user_work_orders','work_orders_id','user_id')->withPivot('responsable')->wherePivot('responsable','1');
    }
    // RELACION DE MUCHOS A MUCHOS CON USUARIOS RESPONSABLES
    public function pivotUsersNoResponsables(){
        return $this->belongsToMany(User::class,'user_work_orders','work_orders_id','user_id')->withPivot('responsable')->wherePivot('responsable','0');
    }

    public function workTimes() {
        return $this->hasMany(WoTime::class,'work_order_id');
    }
    public function lastWorkTimes() {
        return $this->hasOne(WoTime::class,'work_order_id')->latest();
    }
    public function firstWorkTimes() {
        return $this->hasOne(WoTime::class,'work_order_id')->oldest();
    }
    public function outcomes() {
        return $this->hasMany(InvOutcomes::class,'wo_id')->where('state','2');
    }

    // ======================================================================================================
    //                                                   FUNCIONES
    // ======================================================================================================
    public function getCod(){
        return '<a href=\'/work_orders/show/'.code($this->id).'\'>'.$this->cod.'</a>';
    }

    public function getAppendCod(){
        return $this->cod." - ".$this->titulo;
    }

    public function getEstado($estilos = 0){

        switch ($this->estado){
            case 'P':
                $classColor = 'text-yellow';
                $estado0 = "Pendiente";
                $estado1 = '<span class="status-icon '.$classColor.'"></span> <b class="'.$classColor.'">'.$estado0.'</b>';
                $estado2 = '#edb66a';
                $estado6 = '<span class="text-yellow" style="font-weight: 500"><i class="fas fa-exclamation-circle fa-lg"></i>&nbsp;'.$estado0.'</span>';
            break;
            case 'E':
                $classColor = 'text-azure';
                $estado0 = "En progreso";
                $estado1 = '<span class="status-icon '.$classColor.'"></span> <b class="blink_me2 '.$classColor.'">'.$estado0.'</b>';
                $estado2 = '#337AB7';
                $estado6 = '<span class="text-primary"><i class="fa fa-spinner fa-lg fa-spin"></i></span> <span class="text-primary"> &nbsp;<b>'.$estado0.'</b></span>';
            break;
            case 'R':
                $classColor = 'text-cyan';
                $estado0 = "En revisión";
                $estado1 = '<span class="status-icon '.$classColor.'"></span> <b class="'.$classColor.'">'.$estado0.'</b>';
                $estado2 = '#8ebde2';
                $estado6 = '<span class="text-primary" style="font-weight:500;"><i class="fas fa-paper-plane fa-lg"></i> &nbsp;'.$estado0.'</span>';
            break;
            case 'T':
                $classColor = 'text-teal';
                $estado0 = "Terminado";
                $estado1 = '<span class="status-icon '.$classColor.'"></span> <b class="'.$classColor.'">'.$estado0.'</b>';
                $estado2 = '#66c474';
                $estado6 = '<span class="text-green" style="font-weight:500;"><i class="fa fa-check fa-lg"></i>&nbsp;&nbsp;'.$estado0.'</span>';
            break;
            case 'S':
                $classColor = 'text-orange';
                $estado0 = "En pausa";
                $estado1 = '<span class="status-icon '.$classColor.'"></span> <b class="'.$classColor.'">'.$estado0.'</b>';
                $estado2 = '#f76707';
                $estado6 = '<span class="text-yellow" style="font-weight: 500"><i class="fas fa-exclamation-circle fa-lg"></i>&nbsp;'.$estado0.'</span>';
                break;
            case 'C':
                $classColor = 'text-purple';
                $estado0 = "En corrección";
                $estado1 = '<i class="status-icon '.$classColor.'"></i> <b class="'.$classColor.'">'.$estado0.'</b>';
                $estado2 = '#ae3ec9';
                $estado6 = '<span class="text-purple" style="font-weight: 500"><i class="fas fa-cog fa-lg"></i>&nbsp;'.$estado0.'</span>';
            break;
            case 'X':
                $classColor = 'text-red';
                $estado0 = "Anulado";
                $estado1 = '<span class="status-icon '.$classColor.'"></span> <b class="'.$classColor.'">'.$estado0.'</b>';
                $estado2 = '#d63939';
                $estado6 = '<span class="text-red" style="font-weight:500;"><i class="fa fa-ban fa-lg"></i> &nbsp;'.$estado0.'</span>';
            break;
            default:
                $classColor = 'text-muted';
                $estado0 = "Desconocido";
                $estado1 = '<span class="status-icon text-muted"></span><span class="text-muted">Desconocido</span>';
                $estado2 = '#888f96';
                $estado6 = $estado1;
            break;
        }

        // Incluir indicadores de tiempo de recorrido y emergencia
        $estado4 = $estado1;
        $estado1 = $estado1.$this->getEmergencySpan($classColor);
        $estado3 = '<b class="'.$classColor.'">'.$estado0.'</b>';

        // Salida
        switch ($estilos) {
            case '0':   return $estado0;    break;
            case '1':   return $estado1;    break;
            case '2':   return $estado2;    break;
            case '3':   return $estado3;    break;
            case '4':   return $estado4;    break;
            case '6':   return $estado6;    break;
        }
    }

    public function getEmergencySpan($color){
        $emergency = $this->emergencia == 'E' ? '<br><b class="'.$color.' cursor-zoom-in"> <i class="fas fa-info-circle fa-md"></i>&nbsp;Emergencia</b>' : '';
        return $emergency;
    }

    public function getAvatars($limit){
        $avatar = $avatarmore = "";
        $responsable = $this->pivotUserResponsable->first();
        $workorderuser = $this->pivotUsersNoResponsables;

        if (isset($responsable->id)){
            $urlavatar = '/storage/general/avatar/thumbnail/'.$responsable->avatar;
            $imgExists = false;
            if (\Storage::exists('public/general/avatar/thumbnail/'.$responsable->avatar)){
                $imgExists = $responsable->avatar != 'avatar0.png';
            }

            $tooltip = "<b>Responsable</b><br>".($responsable->name.' '.$responsable->ap_paterno.' '.$responsable->ap_materno);
            $rand = $imgExists ? '' : randAvatar();
            $backImage = $imgExists ? 'background-image: url('.$urlavatar.');' : '';
            $iniciales = $imgExists ? '' : '<b>'.$responsable->name[0].$responsable->ap_paterno[0].'</b>';

            $avatar .= '<span class="avatar avatar-sm avatar-rounded '.$rand.'" data-toggle="tooltip" title="'.$tooltip.'" style="'.$backImage.'margin-right:10px">'.$iniciales.'</span>';

        }else{
            $avatar = '<i class="text-muted text-sm font-weight-bold">Sin asignar</i>';
        }

        foreach($workorderuser as $t => $tuser){
            $nameUser = ($tuser->name.' '.$tuser->ap_paterno.' '.$tuser->ap_materno);
            $urlavatar = '/storage/general/avatar/thumbnail/'.$tuser->avatar;

            $imgExists = false;
            if (\Storage::exists('public/general/avatar/thumbnail/'.$tuser->avatar)){
                $imgExists = $tuser->avatar != 'avatar0.png';
            }

            $tooltip = $nameUser;
            $rand = $imgExists ? '' : randAvatar();
            $backImage = $imgExists ? 'background-image: url('.$urlavatar.');' : '';
            $iniciales = $imgExists ? '' : '<b>'.$tuser->name[0].$tuser->ap_paterno[0].'</b>';

            $avatarSpan = '<span class="avatar avatar-sm avatar-rounded '.$rand.'" data-toggle="tooltip" title="'.$tooltip.'" style="'.$backImage.'margin-right:10px">'.$iniciales.'</span>';
            $popAvatarSpan = '<span class=\'avatar avatar-sm avatar-rounded '.$rand.' \' style=\' '.$backImage.' \'>'.$iniciales.'</span>&nbsp;&nbsp;'.$nameUser.'<br>';

            if($t < $limit){
                if(count($workorderuser) > $limit){
                    if($t < ($limit-1)){
                        $avatar .= $avatarSpan;
                    }else{
                        $avatarmore .= $popAvatarSpan;
                    }
                }else{
                    $avatar .= $avatarSpan;
                }
            }else{
                $avatarmore .= $popAvatarSpan;
            }
        }
        if($avatarmore != ""){
            $avatar .= '<span style="margin:0px 0px 5px 5px;padding:13px" class="form-help" data-toggle="popover" data-content="'.$avatarmore.'" data-title="<span style=\'font-size=10px; font-weight:bold\'>Más técnicos</span>">...</span>';
        }

        return $avatar;
    }

    public function getFecha(){
        if(isset($this->fecha)){
            $endDate = date("Y-m-d H:i",strtotime($this->fecha));
            $dateAux = date("d/m/Y",strtotime($this->fecha));
            $hourAux = date("H:i",strtotime($this->fecha));

            if( strtotime(now()) > strtotime($endDate.':59') && $this->estado == 'P' ){
                $date = '&nbsp;
                <svg class="icon text-orange blink_me" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <circle cx="12" cy="12" r="9" />
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                <span class="text-orange font-weight-bold" title="Orden de trabajo vencida" data-toggle="tooltip">'.$dateAux.'<br>'.$hourAux.'</span>';
            }else{
                $date = $dateAux.'<br>'.$hourAux;
            }
            return $date;
        }
        return "-";
    }

    public function getPrioridad($clean = false){
        switch ($this->prioridad) {
            case '1':
                $classColorBack = 'bg-green-lt';
                $colorText = '#2aa13d';
                $prio = 'Baja';
                break;
            case '2':
                $classColorBack = 'bg-yellow-lt';
                $colorText = '#dd8f00';
                $prio = 'Media';

            break;
            case '3':
                $classColorBack = 'bg-red-lt';
                $colorText = '#c13333';
                $prio = 'Alta';
                break;
            default:
                $classColorBack = 'bg-blue-lt';
                $colorText = '#1d60b0';
                $prio = 'Ninguna';
                break;
        }
        $fin = '<b style="color:'.$colorText.'; font-size:13.5px" >'.$prio.'</b>';

        return $clean ? $prio : $fin;
    }

    public function getOperations(){
        $operaciones = '';
        if ($this->estado == 'P' && permisoAdminJefe()){
            $operaciones=
            '<span class="form-operations" data-toggle="popoverOper" tabindex="0"
                data-content=
                    "<a rel=\'modalEdit\' href=\'/work_orders/editmodal/'. code($this->id).' \' >
                        <svg class=\'icon text-muted iconhover\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' stroke-width=\'2\' stroke=\'currentColor\' fill=\'none\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><path stroke=\'none\' d=\'M0 0h24v24H0z\' fill=\'none\'/><path d=\'M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3\' /><path d=\'M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3\' /><line x1=\'16\' y1=\'5\' x2=\'19\' y2=\'8\' /></svg>&nbsp;<span class=\'text-muted\'>Editar</span>
                    </a><br>
                    <a rel=\'modalDelete\' href=\'/work_orders/deletemodal/'. code($this->id).' \'>
                        <svg class=\'icon text-muted iconhover\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' stroke-width=\'2\' stroke=\'currentColor\' fill=\'none\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><path stroke=\'none\' d=\'M0 0h24v24H0z\' fill=\'none\'/><line x1=\'4\' y1=\'7\' x2=\'20\' y2=\'7\' /><line x1=\'10\' y1=\'11\' x2=\'10\' y2=\'17\' /><line x1=\'14\' y1=\'11\' x2=\'14\' y2=\'17\' /><path d=\'M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12\' /><path d=\'M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3\' /></svg>&nbsp;<span class=\'text-muted\'>Eliminar</span>
                    </a>">
                <svg class="icon text-muted btnoper" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <circle cx="12" cy="12" r="1" /><circle cx="12" cy="19" r="1" /><circle cx="12" cy="5" r="1" />
                </svg>
            </span>';
        }
        return $operaciones;
    }

    public function getResponsableIdAttribute(){
        $responsable = DB::table('user_work_orders')->where('responsable','1')->where('work_orders_id',$this->id)->first();
        return isset($responsable->user_id) ? $responsable->user_id : '';
    }

    public function getTechAddsAttribute(){
        return DB::table('user_work_orders')->where('responsable','0')->where('work_orders_id',$this->id)->get();
    }

    public function getCanEditAttribute(){
        return $this->estado == 'P' && permisoAdminJefe();
    }

    //  Accessor para obtener el tiempo de inicio de un ot
    public function getTimeElapsedAttribute(){
        $arrayTime['h'] = $arrayTime['m'] = $arrayTime['s'] = 0;
        $interval = CarbonInterval::createFromFormat('H:i:s', '00:00:00');

        if(count($this->workTimes) > 0){
            $firstTime = $this->StartTime;

            foreach($this->workTimes->where('end_work_date','!=',null) as $wotime){
                if(isset($wotime->init_work_date) && isset($wotime->end_work_date)){
                    $firstTime = $wotime->init_work_date;
                    $lastTime = $wotime->end_work_date;
                    $difSegundos = Carbon::parse($lastTime)->floatDiffInSeconds($firstTime);
                    $interval->addSeconds($difSegundos)->cascade();
                }
            }
            $workTimeInit =  $this->workTimes->where('end_work_date',null)->first();
            if($workTimeInit != null){
                $firstTime = $workTimeInit->init_work_date;
                $lastTime = Carbon::now();
                $difSegundos = Carbon::parse($lastTime)->floatDiffInSeconds($firstTime);
                $interval->addSeconds($difSegundos)->cascade();
            }
        }
        if(isset($interval)){

            $horas = intVal($interval->totalHours);
            $arrayTime['h'] += "".$horas;
            $arrayTime['m'] += $interval->minutes;
            $arrayTime['s'] += $interval->seconds;
            $arrayTime['interval'] = $interval;
        }
        return $arrayTime;
    }

    public function getReportEnabledAttribute(){
        // return $this->state != 'P' && $this->state != 'C' && $this->state != '1' ? false : true ;
        return $this->estado == 'E' || $this->estado == 'C' || $this->state == 'R';
    }

    public function getBodyletterAttribute(){
        if ($this->letter_body != null && $this->letter_body != '') {
            return isset($this->letter_body) ? $this->letter_body : '';
        }

        return isset($this->forms->letter_body) ? $this->forms->letter_body : '' ;
    }

    // ======================================================================================================
    //                                                   SCOPES
    // ======================================================================================================

    public function scopePermisoVer($query){
        if(!permisoAdminJefe()){
            $query->whereHas('pivotWO', function($q) {
                $q->where('user_id', userId());
            });
        }
    }

    public function scopeCod($query, $val){
        if ($val != ''){
            $query->where('cod', 'like', "%{$val}%");
        }
    }

    public function scopeCodTitle($query, $val){
        if($val != ''){
            $query->where('cod','LIKE','%'.$val.'%')
            ->orwhere('title','LIKE','%'.$val.'%');
        }
    }

    public function scopeActivo($query, $val){
        if ($val != '') {
            $query->whereHas('asset', function ($q1) use ($val) {
                $q1->where('cod', 'like', "%{$val}%")
                ->orwhere('nombre', 'like', "%{$val}%");
            });
        }
    }

    public function scopeTitulo($query, $val){
        if ($val != ''){
            $query->where('titulo', 'like', "%{$val}%");
        }
    }

    public function scopeCliente($query, $val){
        if ($val != '') {
            $query->whereHas('asset.cliente', function ($q1) use ($val) {
                $q1->where('nombre', 'like', "%{$val}%");
            });
        }
    }

    public function scopeTecnicoId($query, $val){
        if($val != 't' && $val != ''){
            $query->whereHas('pivotWO', function($q) use ($val){
                $q->where('user_id', $val);
            });
        }
    }

    public function scopeDescripcion($query, $val){
        if ($val != ''){
            $query->where('descripcion', 'like', "%{$val}%");
        }
    }

    public function scopePrioridad($query, $val){
        if ($val != ''){
            $query->where('prioridad', $val);
        }
    }

    public function scopeEstado($query, $val){
        if ($val != ''){
            $val = substr($val, 0, 1);
            $query->where('estado', $val);
        }
    }

    public function scopeFecha($query, $val){
        if ($val != '') {
            $query->where(\DB::raw('DATE_FORMAT(fecha, "%d/%m/%Y %H:%i")'), 'like', "%{$val}%");
        }
    }

    public function scopeSearchEstado($query, $estados){
        if(isset($estados)){
            $query->whereIn('estado',$estados);
        }else{
            $query->where('estado','!=', 'T')
            ->where('estado','!=', 'X');
        }
    }

    public function scopeAssetId($query, $val){
        if($val != ''){
            $query->where('asset_id', $val);
        }
    }
}
