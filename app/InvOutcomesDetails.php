<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvOutcomesDetails extends Model
{
    protected $fillable = ['location'];

    public function outcomes()  {
        return $this->belongsTo(InvOutcomes::class,'outcome_id');
    }
    public function items()  {
        return $this->belongsTo(Inventory::class,'item_id');
    }

    // POLIMORFISMO CON INVSTOCKS
    public function stocks(){
        return $this->morphMany(InvStocks::class, 'origen');
    }

    public function getAlmacenLiteralAttribute(){
        switch ($this->location) {
            case "1": return 'Edificio Arcadia';    break;
            case "2": return 'El Alto'; break;
            case "3": return 'Gramadal';    break;
            case "4": return 'Edifico técnico'; break;
        }
        return '';
    }

    public function getOperations(){
        if($this->outcomes->state != 1){
            return '';
        }
        $operaciones =
        '<span class="form-operations" data-toggle="popoverOper" tabindex="0"
            data-content=
            "<a rel=\'modalEdit\' href=\'/outcomes/details/editmodal/'. code($this->id).' \' >
                <svg class=\'icon text-muted iconhover\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' stroke-width=\'2\' stroke=\'currentColor\' fill=\'none\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><path stroke=\'none\' d=\'M0 0h24v24H0z\' fill=\'none\'/><path d=\'M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3\' /><path d=\'M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3\' /><line x1=\'16\' y1=\'5\' x2=\'19\' y2=\'8\' /></svg>
                &nbsp;&nbsp;<span class=\'text-muted\'>Editar</span>
            </a><br>
            <a rel=\'modalDelete\' href=\'/outcomes/details/deletemodal/'. code($this->id).' \'>
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

    public function getLocationEditable(){
        $location = isset($this->location) ? $this->almacenLiteral : "-";
        if($this->outcomes->state == 1){
            if ($this->quantity <= $this->items->quantity){
                return "<div class='text-center selectedit' data-type='select' data-pk='".$this->id."' data-name='location' data-value='".$this->location."' data-source='/outcomes/location/".code($this->id)."'>"
                            .$location.
                        "</div>";
            }
            return '<i class="text-sm">No existe la cantidad solicitada</i>';
        }
        return $location;
    }

    public function getLocations(){
        if ($this->quantity <= $this->items->quantity){
            return isset($this->location) ? $this->almacenLiteral : '-';
        }
        return '<i class="text-sm">No existe la cantidad solicitada</i>';
    }

    public function checkQuantity(){
        if($this->outcomes->state != 1){
            return $this->quantity;
        }
        if($this->outcomes->state == 1){
            $orange = '<svg class="icon icon-alert blink_me" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>';
            return $this->quantity > $this->items->quantity ? '<span class="text-orange" data-toggle="tooltip" title="Solicitud mayor a lo disponible <b>'.$this->items->quantity.'</b>">'.$orange.$this->quantity.'</span>' : $this->quantity;
        }
    }

    public function getUnitCost(){
        // Costo total de items
        $valor = InvStocks::where('item_id',$this->item_id)->get();
        $monto = 0;
        foreach ($valor as $val) {
            if ($val->incomes != 0 && $val->outcomes == 0)      $monto += ($val->unit_cost * $val->incomes);
            elseif ($val->outcomes != 0 && $val->incomes == 0)  $monto -= ($val->unit_cost * $val->outcomes);
        }

        // Cantidad de items
        $cantidad = InvStocks::selectRaw("SUM(incomes) - SUM(outcomes) as cantidad")->where('item_id',$this->item_id)->first();
        $cantidad = isset($cantidad->cantidad) ? $cantidad->cantidad : '0.00';
        $unitCost = 0;
        if($cantidad>0)
            $unitCost = $monto/$cantidad;
        else{
            $costo_id = InvStocks::where('item_id',$this->item_id)->max('id');
            if( isset($costo_id)){
                $costo = InvStocks::findOrFail($costo_id);
                $unitCost = $costo->unit_cost;
            }
        }
        return $unitCost;
    }

    //  Accessor para saber el costo de los materiales de la OT
    public function getSubTotalAttribute(){
        $subtotal = 0;
        if (count($this->stocks) > 0) {
            $unitCost = $this->stocks->sum('unit_cost');
            $subtotal = ($unitCost > 0)? $unitCost * $this->quantity : 0 ;
        }
        return $subtotal;
    }
}
