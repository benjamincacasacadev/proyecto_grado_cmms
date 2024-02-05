<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    //
    protected $table = 'inventory';

    // RELACION A DETALLES
    public function details() {
        return $this->hasMany(InvIncomesDetails::class,'item_id')->orderBy('order');
    }
    // RELACION A STOCKS
    public function stocks() {
        return $this->hasMany(InvStocks::class,'item_id');
    }

    // RELACION A DETALLES  DE SALIDA
    public function detailsOutcomes()  {
        return $this->hasMany(InvOutcomesDetails::class,'item_id');
    }

    // Cantidad de items asociados
    public function getCantDetails(){
        return isset($this->stocks) ? $this->stocks->count() : 0;
    }

    // DEVUELVE EL TOTAL DE LOS DETALLES DEL ITEM
    public function getTotalItemAttribute(){
        return $this->stocks->sum('incomes') - $this->stocks->sum('outcomes');
    }

    public function getCodAppendAttribute(){
        if(permisoAdminJefe()){
            return '<a href="/inventory/kardex/'.code($this->id).'" target="_blank" class="text-yellow">'.$this->codAppendLiteral.'</a>';
        }
        return $this->codAppendLiteral;
    }

    public function getCodAppendLiteralAttribute(){
        return $this->cod." - ".$this->title;
    }

    public function getCod(){
        if(permisoAdminJefe()){
            return '<a href="/inventory/kardex/'.code($this->id).'" target="_blank">'.$this->cod.'</a>';
        }
        return $this->cod;
    }

    public function getQuantity($export = false){
        $orange = '<svg class="icon icon-alert text-orange blink_me" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>';
        $red = '<i class="fas fa-exclamation-circle text-red"></i>&nbsp;';

        if($export == 'export'){
            return $this->quantity;
        }

        if($this->quantity == 0){
            return "<span class='text-red'>&nbsp;".$red.$this->quantity."</span>";
        }elseif($this->quantity < $this->min_cant){
            return "<span title='Cantidad menor a ".$this->min_cant." (cantidad mínima)' data-toggle='tooltip'>".$orange.'&nbsp;'.$this->quantity."</span>";
        }else{
            return $this->quantity;
        }
    }

    public function getEstadoAttribute(){
        if ($this->active == 1) {
            $estado =
            '<span class="text-teal" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="<span style=\'font-size: 12px;\' class=\'text-teal\'> <b>MATERIAL ACTIVO</b> </span>">
                <i class="fas fa-check-circle fa-lg"></i>
            </span>';
            if(permisoAdminJefe()){
                $estado =
                '<a href="/items/state/'.code($this->id).'/1">
                    <span class="text-teal" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="<span style=\'font-size: 12px;\'> Si desea desactivar el material haga clic. </span>" data-original-title="<span style=\'font-size: 12px;\'><b>MATERIAL ACTIVO</b></span>">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </span>
                </a>';
            }
        } else {
            $estado =
            '<span class="text-pink" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="<span style=\'font-size: 12px; \' class=\'text-pink\'> <b>MATERIAL INACTIV0</b> </span>" >
                <i class="fas fa-exclamation-circle fa-lg"></i>
            </span>';
            if(permisoAdminJefe()){
                $estado =
                '<a href="/items/state/'.code($this->id).'/0">
                    <span class="text-pink" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="<span style=\'font-size: 12px; \' class=\'text-pink\'> Si desea activar el material haga clic. </span>" data-original-title="<span style=\'font-size: 12px;\' class=\'text-pink\'><b>MATERIAL INACTIV0</b></span>">
                        <i class="fas fa-exclamation-circle fa-lg"></i>
                    </span>
                </a>';
            }

        }
        return $estado;
    }

    public function getOperations(){

        $operaciones = $options =  "";
        if(permisoAdminJefe()){
            $options =
            '<a rel=\'modalEditInventory\' href=\'/inventory/editmodal/'. code($this->id).' \' >
                <svg class=\'icon text-muted iconhover\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' stroke-width=\'2\' stroke=\'currentColor\' fill=\'none\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><path stroke=\'none\' d=\'M0 0h24v24H0z\' fill=\'none\'/><path d=\'M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3\' /><path d=\'M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3\' /><line x1=\'16\' y1=\'5\' x2=\'19\' y2=\'8\' /></svg>
                &nbsp;&nbsp;<span class=\'text-muted\'>Editar</span>
            </a><br>
            <a rel=\'modalDelete\' href=\'/inventory/deletemodal/'. code($this->id).' \'>
                <svg class=\'icon text-muted iconhover\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' stroke-width=\'2\' stroke=\'currentColor\' fill=\'none\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><path stroke=\'none\' d=\'M0 0h24v24H0z\' fill=\'none\'/><line x1=\'4\' y1=\'7\' x2=\'20\' y2=\'7\' /><line x1=\'10\' y1=\'11\' x2=\'10\' y2=\'17\' /><line x1=\'14\' y1=\'11\' x2=\'14\' y2=\'17\' /><path d=\'M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12\' /><path d=\'M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3\' /></svg>
                &nbsp;&nbsp;<span class=\'text-muted\'>Eliminar</span>
            </a>';

            $operaciones=
            '<span class="form-operations" data-toggle="popoverOper" tabindex="0" data-content= "'.$options.'">
                <svg class="icon text-muted btnoper" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <circle cx="12" cy="12" r="1" /><circle cx="12" cy="19" r="1" /><circle cx="12" cy="5" r="1" />
                </svg>
            </span>';
        }

        return $operaciones;
    }

    // =============================================================================================
    //                                         SCOPES
    // =============================================================================================
    public function scopeCod($query,$val){
        if ($val!="")   $query->where('cod', 'like', "%{$val}%");
    }
    public function scopeTitle($query,$val){
        if ($val!="")   $query->where('title', 'like', "%{$val}%");
    }
    public function scopeDescription($query,$val){
        if ($val!="")   $query->where('description', 'like', "%{$val}%");
    }
    public function scopeQuantity($query,$val){
        if ($val!="")   $query->where('quantity', 'like', "%{$val}%");
    }
    public function scopeMinCant($query,$val){
        if ($val!="")   $query->where('min_cant', 'like', "%{$val}%");
    }
    public function scopeUnit($query,$val){
        if ($val!="")   $query->where('unit', 'like', "%{$val}%");
    }
    public function scopeState($query, $state){
        if ($state != "" && $state != "all")    $query->where('active', $state);
    }
    public function scopeCategory($query, $val){
        if ($val != ""){
            $query->whereHas('categories', function($q) use ($val){
                $q->where('nombre', 'like', "%{$val}%");
            });
        }
    }
}
