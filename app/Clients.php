<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    // ==========================================================================
    // FUNCIONES
    // ==========================================================================
    public function getTipoLiteralAttribute(){
        switch ($this->tipo) {
            case 'I':   return 'Integrador';        break;
            case 'F':   return 'Cliente final';     break;
            case 'D':   return 'Distribuidor';      break;
        }
    }
    public function getDatosContactoAttribute(){
        $salida = "<span style='color:#A6ACAF;'>Nombre: </span><b> ".$this->nombre_contacto.'</b><br>';
        $salida .= "<span style='color:#A6ACAF;'>Cargo: </span><b> ".$this->cargo_contacto.'</b><br>';
        $salida .= "<span style='color:#A6ACAF;'>Celular: </span><b> ".$this->celular_contacto.'</b><br>';
        $salida .= "<span style='color:#A6ACAF;'>Email: </span><b> ".$this->email_contacto.'</b>';

        return $salida;
    }

    public function getEstadoShowAttribute(){
        $estado = '';
        if ($this->estado == 1) {
            $estado =
            '<a style="padding: .375rem .75rem; font-size: .9rem; line-height: 1.6;">
                <span class="text-teal" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="<span class=\'text-success\' style=\'font-size: 12px;\'><b>CLIENTE ACTIVO</b></span>" >
                    <i class="fas fa-check-circle fa-lg"></i>
                </span>
            </a>';
            if (permisoAdminJefe()){
                $estado =
                '<a href="/clients/estado/'.code($this->id).'/1" style="padding: .375rem .75rem; font-size: .9rem; line-height: 1.6;">
                    <span class="text-yellow" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="<span style=\'font-size: 12px;\'> Si desea desactivar el cliente haga clic. </span>" data-original-title="<span style=\'font-size: 12px;\'><b>CLIENTE ACTIVO</b></span>">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </span>
                </a>';
            }

        }else{
            $estado =
            '<a style="padding: .375rem .75rem; font-size: .9rem; line-height: 1.6;">
                <span class="text-pink" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="<span style=\'font-size: 12px;\' class=\'text-pink\'><b>CLIENTE INACTIV0</b></span>">
                    <i class="fas fa-exclamation-circle fa-lg"></i>
                </span>
            </a>';
            if (permisoAdminJefe()){
                $estado =
                '<a href="/clients/estado/'.code($this->id).'/0" style="padding: .375rem .75rem; font-size: .9rem; line-height: 1.6;">
                    <span class="text-pink" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="<span style=\'font-size: 12px; \' class=\'text-pink\'> Si desea activar el cliente haga clic. </span>" data-original-title="<span style=\'font-size: 12px;\' class=\'text-pink\'><b>CLIENTE INACTIV0</b></span>">
                        <i class="fas fa-exclamation-circle fa-lg"></i>
                    </span>
                </a>';
            }
        }
        return $estado;
    }

    public function getOperations(){
        $operaciones =
        '<span class="form-operations" data-toggle="popoverOper" tabindex="0"
            data-content=
                "<a style=\'cursor:pointer\' rel=\'modalEdit\' href=\'/clients/editmodal/'. code($this->id).' \' >
                    <svg class=\'icon text-muted iconhover\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' stroke-width=\'2\' stroke=\'currentColor\' fill=\'none\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><path stroke=\'none\' d=\'M0 0h24v24H0z\' fill=\'none\'/><path d=\'M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3\' /><path d=\'M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3\' /><line x1=\'16\' y1=\'5\' x2=\'19\' y2=\'8\' /></svg>&nbsp;<span class=\'text-muted\'>Editar</span>
                </a><br>
                <a  style=\'cursor:pointer\' rel=\'modalDelete\' href=\'/clients/deletemodal/'. code($this->id).' \'>
                    <svg class=\'icon text-muted iconhover\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' stroke-width=\'2\' stroke=\'currentColor\' fill=\'none\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><path stroke=\'none\' d=\'M0 0h24v24H0z\' fill=\'none\'/><line x1=\'4\' y1=\'7\' x2=\'20\' y2=\'7\' /><line x1=\'10\' y1=\'11\' x2=\'10\' y2=\'17\' /><line x1=\'14\' y1=\'11\' x2=\'14\' y2=\'17\' /><path d=\'M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12\' /><path d=\'M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3\' /></svg>&nbsp;<span class=\'text-muted\'>Eliminar</span>
                </a>">
            <svg class="icon text-muted btnoper" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <circle cx="12" cy="12" r="1" /><circle cx="12" cy="19" r="1" /><circle cx="12" cy="5" r="1" />
            </svg>
        </span>';
        return $operaciones;
    }

    // ==========================================================================
    // SCOPES
    // ==========================================================================

    public function scopeNombre($query, $val){
        if ($val != ''){
            $query->where('nombre', 'like', "%{$val}%");
        }
    }

    public function scopeNit($query, $val){
        if ($val != ''){
            $query->where('nit', 'like', "%{$val}%");
        }
    }

    public function scopeTipo($query, $val){
        if ($val != ''){
            $val = substr($val, 0, 1);
            $query->where('tipo', $val);
        }
    }

    public function scopeCaracteristicas($query, $val){
        if ($val != ''){
            $query->where('caracteristicas', 'like', "%{$val}%");
        }
    }

    public function scopeDireccion($query, $val){
        if ($val != ''){
            $query->where('direccion', 'like', "%{$val}%");
        }
    }

    public function scopeContacto($query, $val){
        if ($val != ''){
            $query->where(function ($q1) use ($val) {
                $q1->where('nombre_contacto', 'LIKE', "%$val%")
                    ->orWhere('cargo_contacto', 'LIKE', "%$val%")
                    ->orWhere('celular_contacto', 'LIKE', "%$val%")
                    ->orWhere('email_contacto', 'LIKE', "%$val%");
            });
        }
    }

    public function scopeEstado($query, $val){
        if ($val != ''){
            $query->where('estado', $val);
        }
    }
}
