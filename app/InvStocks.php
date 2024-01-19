<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvStocks extends Model
{
    public function items() {
        return $this->belongsTo(Inventory::class,'item_id');
    }
    public function parts() {
        return $this->belongsTo(InvParts::class,'part_id');
    }

    public function incomesRel() {
        return $this->belongsTo(InvIncomesDetails::class,'origen_id');
    }
    public function outcomesRel() {
        return $this->belongsTo(InvOutcomesDetails::class,'origen_id');
    }
    public function transfers(){
        return $this->belongsTo(InvTransfers::class,'origen_id');
    }
    public function removals() {
        return $this->belongsTo(InvPartsRemoval::class,'origen_id');
    }

    public function origen(){
        return $this->morphTo();
    }

    public function getIn(){
        return $this->incomes>0 ? '<span class="text-yellow">'.$this->incomes.'</span>' : $this->incomes;
    }
    public function getOut(){
        return $this->outcomes>0 ? '<span class="text-orange">'.$this->outcomes.'</span>' : $this->outcomes;
    }

    public function getTotalIn($item){
        return $this->sum('incomes')->where('item_id',$item);
    }
    public function getTotalOut($item){
        return $this->sum('outcomes')->where('item_id',$item);
    }
    public function getTotalLocation($item){
        return $this->getTotalIn($item) - $this->getTotalOut($item);
    }


    public function getOrigenLink(){
        switch($this->origen_type){
            case 'A0':
                $ruta = '<b class="text-yellow">Inventario inicial</b>';
            break;
            case 'A1':
                $icono =
                '<span data-toggle="tooltip" title="Notas de ingreso">
                    <svg class="icon text-yellow" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" /><path d="M20 12h-13l3 -3m0 6l-3 -3" />
                    </svg>
                </span>&nbsp;';
                $ruta = isset($this->incomesRel->incomes->cod) ? $icono.$this->incomesRel->incomes->getCod() : "";
            break;
            case 'A2':
                $icono =
                '<span data-toggle="tooltip" title="Pedidos">
                    <svg class="icon text-orange" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" /><path d="M7 12h14l-3 -3m0 6l3 -3" />
                    </svg>
                </span>&nbsp;';
                $ruta = isset($this->outcomesRel->outcomes->cod) ? $icono.$this->outcomesRel->outcomes->getCod() : "";
            break;
            case 'A3':
                $icono =
                '<span data-toggle="tooltip" title="Traspasos">
                    <svg class="icon " width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M14 21l-11 -11"></path><path d="M3 14v-4h4"></path><path d="M17 14h4v-4"></path><line x1="10" y1="3" x2="21" y2="14"></line>
                    </svg>
                </span>&nbsp;';
                $ruta = isset($this->transfers->cod) ? $icono.$this->transfers->getCod() : "";
            break;
            case 'A4':
                $ruta = '<b class="text-yellow">Registro inicial</b>';
            break;
            case 'A5':
                $icono =
                '<span data-toggle="tooltip" title="Retiro">
                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M7 13v-8a1 1 0 0 0 -1 -1h-2a1 1 0 0 0 -1 1v7a1 1 0 0 0 1 1h3a4 4 0 0 1 4 4v1a2 2 0 0 0 4 0v-5h3a2 2 0 0 0 2 -2l-1 -5a2 3 0 0 0 -2 -2h-7a3 3 0 0 0 -3 3"></path>
                    </svg>
                </span>&nbsp;';
                $ruta = isset($this->removals->cod) ? $icono.$this->removals->getCod() : "";
            break;
            default: $ruta = ""; break;
        }
        return $ruta;
    }

    public function getIndicatorKardex($last){
        if(round($this->unit_cost,2) == $last || $last==""){
            return '<svg class="icon text-green" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9" /><path d="M9 12l2 2l4 -4" />
            </svg>';
        }else{
            if(round($this->unit_cost,2)>$last)
                return
                '<svg class="icon text-orange" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <circle cx="12" cy="12" r="9"></circle>
                    <line x1="12" y1="8" x2="8" y2="12"></line>
                    <line x1="12" y1="8" x2="12" y2="16"></line>
                    <line x1="16" y1="12" x2="12" y2="8"></line>
                </svg>';
            elseif(round($this->unit_cost,2)<$last)
                return
                '<svg class="icon text-red" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <circle cx="12" cy="12" r="9"></circle>
                    <line x1="8" y1="12" x2="12" y2="16"></line>
                    <line x1="12" y1="8" x2="12" y2="16"></line>
                    <line x1="16" y1="12" x2="12" y2="16"></line>
                </svg>';
        }
    }

    public function getAlmacenLiteralAttribute(){
        switch ($this->location) {
            case '1': return 'Edificio Arcadia';    break;
            case '2': return 'El Alto'; break;
            case '3': return 'Gramadal';    break;
            case '4': return 'Edifico técnico'; break;
        }
        return '';
    }

    public function scopeInvRangeDate($query,$start,$final, $type){
        if($start != '' && $final != '' && $type == 'R'){
            $query->where('date','>=', $start)->where('date','<=', $final);
        }
    }

    public function scopeInvDate($query, $mes, $trimestre, $semestre, $anio, $type){
        if($type != 'R'){
            switch ($type) {
                case 'A':
                    if($anio != '')
                        $query->whereYear('date',$anio);
                break;
                case 'M':
                    if($anio != '' && $mes != '' && $mes != 't'){
                        $query->whereMonth('date',$mes)->whereYear('date',$anio);
                    }else{
                        $query->whereYear('date',$anio);
                    }

                break;
                case 'T':
                    if($anio != '' && $trimestre != '' ){
                        if($trimestre != 't'){
                            $mesesArray = array('1' => ['01','03'],'2' => ['04','06'], '3' => ['07','09'], '4' => ['10','12']);
                            $dateS = new Carbon($anio.'-'.$mesesArray[$trimestre][0].'-01');
                            $dateE = new Carbon($anio.'-'.$mesesArray[$trimestre][1].'-01');
                            $query->whereBetween('date',[$dateS->startOfMonth(),$dateE->endOfMonth()]);
                        }else{
                            $query->whereYear('date',$anio);
                        }
                    }
                break;
                case 'S':
                    if($anio != '' && $semestre != ''){
                        if($semestre != 't'){
                            $mesesArray = array('1' => ['01','06'],'2' => ['07','12']);
                            $dateS = new Carbon($anio.'-'.$mesesArray[$semestre][0].'-01');
                            $dateE = new Carbon($anio.'-'.$mesesArray[$semestre][1].'-01');
                            $query->whereBetween('date',[$dateS->startOfMonth(),$dateE->endOfMonth()]);
                        }else{
                            $query->whereYear('date',$anio);
                        }
                    }
                break;
            }
        }
    }
}
