<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvOutcomes extends Model
{
    public function solicitado()  {
        return $this->belongsTo(User::class,'solicitado_id');
    }
    public function workorders()  {
        return $this->belongsTo(WorkOrders::class,'wo_id');
    }
    // RELACION A DETALLES
    public function details() {
        return $this->hasMany(InvOutcomesDetails::class,'outcome_id');
    }

    // =============================================================================================
    //                                         FUNCIONES
    // =============================================================================================
    public function getCod(){
        return '<a href="/outcomes/show/'.code($this->id).'" class="font-weight-bold">'.$this->cod.'</a>';
    }

    public function getCantDetails(){
        return isset($this->details) ? $this->details->count() : 0;
    }

    public function getState($flag){
        switch ($this->state) {
            case '0':
                $val = "Anulado";
                $fin =
                '<div class="p-2 text-center form-control-sm vermas">
                    <span class="text-red"><i class="fa fa-ban text-danger"></i>&nbsp;&nbsp;'.$val.'</span>
                </div>';
            break;
            case '1':
                $val = "Pendiente de aprobación";

                $fin =
                '<div class="p-2 text-center form-control-sm vermas text-orange">
                    <i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;'.$val.'
                </div>';
                if(permisoAdminJefe()){
                    $fin =
                    '<div class="p-2 text-center form-control-sm vermas">
                        <a href="/outcomes/statemodal/'.code($this->id).'" class="text-orange" title="Cambiar Estado" rel="modalState">
                            <i class="fa fa-refresh fa-spin"></i>&nbsp;&nbsp;'.$val.'
                        </a>
                    </div>';
                }
            break;
            case '2':
                $val = "Validado";
                $fin =
                '<div class="p-2 text-center  input-sm" >
                    <span class="text-green"><i class="fa fa-check"></i>&nbsp;&nbsp;'.$val.'</span>
                </div>';
            break;
            default:
                $val = "Desconocido";
                $fin =
                '<div class="text-center bg-info input-sm" style="height: 100%;">
                    <p class="text-primary"> <i class="fas fa-question"></i>&nbsp;&nbsp;'.$val.'</p>
                </div>';
            break;
        }
        return $flag ? $fin : $val;
    }

    public function getOperations(){
        if (!permisoAdminJefe()){
            return '';
        }
        if($this->state != 1){
            return '';
        }
        $operaciones =
        '<span class="form-operations" data-toggle="popoverOper" tabindex="0"
            data-content=
                "<a style=\'cursor:pointer\' rel=\'modalEdit\' href=\'/outcomes/editmodal/'. code($this->id).' \' >
                    <svg class=\'icon text-muted iconhover\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' stroke-width=\'2\' stroke=\'currentColor\' fill=\'none\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><path stroke=\'none\' d=\'M0 0h24v24H0z\' fill=\'none\'/><path d=\'M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3\' /><path d=\'M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3\' /><line x1=\'16\' y1=\'5\' x2=\'19\' y2=\'8\' /></svg>
                    &nbsp;&nbsp;<span class=\'text-muted\'>Editar</span>
                </a><br>
                <a  style=\'cursor:pointer\' rel=\'modalDelete\' href=\'/outcomes/deletemodal/'. code($this->id).' \'>
                    <svg class=\'icon text-muted iconhover\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' stroke-width=\'2\' stroke=\'currentColor\' fill=\'none\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><path stroke=\'none\' d=\'M0 0h24v24H0z\' fill=\'none\'/><line x1=\'4\' y1=\'7\' x2=\'20\' y2=\'7\' /><line x1=\'10\' y1=\'11\' x2=\'10\' y2=\'17\' /><line x1=\'14\' y1=\'11\' x2=\'14\' y2=\'17\' /><path d=\'M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12\' /><path d=\'M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3\' /></svg>
                    &nbsp;&nbsp;<span class=\'text-muted\'>Eliminar</span>
                </a>">
            <svg class="icon text-muted btnoper" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <circle cx="12" cy="12" r="1" /><circle cx="12" cy="19" r="1" /><circle cx="12" cy="5" r="1" />
            </svg>
        </span>';
        return $operaciones;
    }

    // Accessor para saber el costo total de los detalles
    public function getTotalCostAttribute(){
        return isset($this->details) ? $this->details->sum('subtotal') : 0;
    }

    // =============================================================================================
    //                                         SCOPES
    // =============================================================================================
    public function scopePermisoVerOTs($query){
        if(!permisoAdminJefe()){
            $query->whereHas('workorders.pivotWO', function($q) {
                $q->where('user_id', userId());
            });
        }
    }

    public function scopeCod($query,$val){
        if($val != ''){
            $query->where('cod', 'like', "%{$val}%");
        }
    }
    function scopeDate($query, $val){
        if($val != ''){
            $query->where(\DB::raw('DATE_FORMAT(date, "%d/%m/%Y %H:%i")'), 'like', "%{$val}%");
        }
    }
    public function scopeReason($query,$val){
        if($val != ''){
            $query->where('reason', 'like', "%{$val}%");
        }
    }
    public function scopeDestination($query,$val){
        if($val != ''){
            $query->where('destination', 'like', "%{$val}%");
        }
    }
    public function scopeWorkOrders($query, $val){
        if($val != ''){
            $query->whereHas('workorders', function($q) use ($val){
                $q->where('cod', 'like', "%{$val}%");
            });
        }
    }
    public function scopeSolicitado($query, $user){
        if ($user != ''){
            $query->whereHas('solicitado', function($q) use ($user){
                $q->where(\DB::raw("CONCAT(COALESCE(name,''), ' ', COALESCE(ap_paterno,''), ' ', COALESCE(ap_materno,''))"), 'like', "%{$user}%");
            });
        }
    }
    public function scopeAutorizador($query, $user){
        if ($user != ''){
            $query->whereHas('autorizador', function($q) use ($user){
                $q->where(\DB::raw("CONCAT(COALESCE(name,''), ' ', COALESCE(ap_paterno,''), ' ', COALESCE(ap_materno,''))"), 'like', "%{$user}%");
            });
        }
    }
    function scopeDeliveryDate($query, $val){
        if($val != ''){
            $query->where(\DB::raw('DATE_FORMAT(delivery_date, "%d/%m/%Y %H:%i")'), 'like', "%{$val}%");
        }
    }
    public function scopeObservation($query,$val){
        if($val != ''){
            $query->where('observation', 'like', "%{$val}%");
        }
    }
    public function scopeState($query, $state){
        if ($state != '' && $state != 'all'){
            $query->where('state', $state);
        }
    }
}
