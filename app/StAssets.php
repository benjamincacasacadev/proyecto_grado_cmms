<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class StAssets extends Model
{

    // ==========================================================================
    // RELACIONES
    // ==========================================================================
    public function cliente(){
        return $this->belongsTo(Clients::class, 'client_id');
    }
    public function workorders() {
        return $this->hasMany(WorkOrders::class,'asset_id');
    }

    // ==========================================================================
    // FUNCIONES
    // ==========================================================================
    public function getCod(){
        return '<a href=\'/assets/show/'.code($this->id).'\'>'.$this->cod.'</a>';
    }

    public function getCiudadLiteralAttribute(){
        switch ($this->ciudad) {
            case '0': return 'Beni';            break;
            case '1': return 'Chuquisaca';      break;
            case '2': return 'Cochabamba';      break;
            case '3': return 'La Paz';          break;
            case '4': return 'Oruro';           break;
            case '5': return 'Pando';           break;
            case '6': return 'Potosi';          break;
            case '7': return 'Santa Cruz';      break;
            case '8': return 'Tarija';          break;
        }
    }

    public function getCategoriaLiteralAttribute(){
        switch ($this->categoria) {
            case '0': return 'Aire Acondicionado de confort';   break;
            case '1': return 'Aires de precisión';              break;
            case '2': return 'Banco de baterias de litio';      break;
            case '3': return 'Equipo inversor';                 break;
            case '4': return 'Equipo rectificador';             break;
            case '5': return 'Equipo UPS';                      break;
            case '6': return 'Estabilizador';                   break;
            case '7': return 'Grupos Electrógenos';             break;
            case '8': return 'Reconectador de media tension';   break;
            case '9': return 'Tablero Banco de capacitores';    break;
            case '10': return 'Tablero de transferencia ATS';   break;
        }
    }

    public function getDatosActivoAttribute(){
        $salida = "<span style='color:#A6ACAF;'>Marca: </span><b> ".$this->marca.'</b><br>';
        $salida .= "<span style='color:#A6ACAF;'>Modelo: </span><b> ".$this->modelo.'</b><br>';
        $salida .= "<span style='color:#A6ACAF;'>Capacidad/Potencia: </span><b> ".$this->capacidad.'</b><br>';

        return $salida;
    }

    public function getEstadoShowAttribute(){
        $estado = '';
        if ($this->estado == 1) {
            $estado =
            '<a style="padding: .375rem .75rem; font-size: .9rem; line-height: 1.6;">
                <span class="text-teal" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="<span class=\'text-success\' style=\'font-size: 12px;\'><b>ACTIVO HABILITADO</b></span>" >
                    <i class="fas fa-check-circle fa-lg"></i>
                </span>
            </a>';
            if (permisoAdminJefe()){
                $estado =
                '<a href="/assets/estado/'.code($this->id).'/1" style="padding: .375rem .75rem; font-size: .9rem; line-height: 1.6;">
                    <span class="text-yellow" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="<span style=\'font-size: 12px;\'> Si desea inhabilitar el activo haga clic. </span>" data-original-title="<span style=\'font-size: 12px;\'><b>ACTIVO HABILITADO</b></span>">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </span>
                </a>';
            }

        }else{
            $estado =
            '<a style="padding: .375rem .75rem; font-size: .9rem; line-height: 1.6;">
                <span class="text-pink" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="<span style=\'font-size: 12px;\' class=\'text-pink\'><b>ACTIVO INHABILITADO</b></span>">
                    <i class="fas fa-exclamation-circle fa-lg"></i>
                </span>
            </a>';
            if (permisoAdminJefe()){
                $estado =
                '<a href="/assets/estado/'.code($this->id).'/0" style="padding: .375rem .75rem; font-size: .9rem; line-height: 1.6;">
                    <span class="text-pink" data-toggle="popover" data-placement="left" data-trigger="hover" data-content="<span style=\'font-size: 12px; \' class=\'text-pink\'> Si desea habilitar el activo haga clic. </span>" data-original-title="<span style=\'font-size: 12px;\' class=\'text-pink\'><b>ACTIVO INHABILITADO</b></span>">
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
                "<a style=\'cursor:pointer\' rel=\'modalEdit\' href=\'/assets/editmodal/'. code($this->id).' \' >
                    <svg class=\'icon text-muted iconhover\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' stroke-width=\'2\' stroke=\'currentColor\' fill=\'none\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><path stroke=\'none\' d=\'M0 0h24v24H0z\' fill=\'none\'/><path d=\'M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3\' /><path d=\'M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3\' /><line x1=\'16\' y1=\'5\' x2=\'19\' y2=\'8\' /></svg>&nbsp;<span class=\'text-muted\'>Editar</span>
                </a><br>
                <a  style=\'cursor:pointer\' rel=\'modalDelete\' href=\'/assets/deletemodal/'. code($this->id).' \'>
                    <svg class=\'icon text-muted iconhover\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' stroke-width=\'2\' stroke=\'currentColor\' fill=\'none\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><path stroke=\'none\' d=\'M0 0h24v24H0z\' fill=\'none\'/><line x1=\'4\' y1=\'7\' x2=\'20\' y2=\'7\' /><line x1=\'10\' y1=\'11\' x2=\'10\' y2=\'17\' /><line x1=\'14\' y1=\'11\' x2=\'14\' y2=\'17\' /><path d=\'M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12\' /><path d=\'M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3\' /></svg>&nbsp;<span class=\'text-muted\'>Eliminar</span>
                </a>">
            <svg class="icon text-muted btnoper" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <circle cx="12" cy="12" r="1" /><circle cx="12" cy="19" r="1" /><circle cx="12" cy="5" r="1" />
            </svg>
        </span>';
        return $operaciones;
    }

    public function getInfoAssets($swAdjunto = ''){
        $attach = isset($this->attach) ? $this->attach : 'X';
        $imagen =  '<img src="/imagenes/noImageBlack.png">';
        if(themeMode() != 'D'){
            $imagen =  '<img src="/storage/noimage.png">';
        }
        if(Storage::exists('/public/assets/'.$attach)){
            $ruta = '/storage/assets/'.$attach;
            $imagen = '<a href="'.$ruta.'" target="_blank"><img src="'.$ruta.'"></a>';
        }

        $serie = "<b>Nro de serie: </b>".$this->nro_serie.'<br>';
        $cliente = "<b>Cliente: </b>".$this->cliente->nombre.'<br>';
        $categoria = "<b>Categoria: </b>".$this->categoriaLiteral.'<br>';
        $ubicacion = "<b>Ubicación: </b>".$this->ubicacion.'<br>';
        $ciudad = "<b>Ciudad: </b>".$this->ciudadLiteral;


        if($swAdjunto == ""){
            return
            '<div class="text-sm mt-2">'
                .$serie.$cliente.$categoria.$ubicacion.$ciudad.
            '</div>';
        }

        if($swAdjunto == 'img'){
            return
            '<div class="row">
                <div class="col-2 divImageSelect2" >
                    <div class="container d-flex h-100">
                        <div class=" justify-content-center align-self-center">'.
                            $imagen
                        .'</div>
                    </div>
                </div>
                <div class="col-10 text-sm">'
                    .$serie.$cliente.$categoria.$ubicacion.$ciudad.
                '</div>
            </div>';
        }
    }

    // ==========================================================================
    // SCOPES
    // ==========================================================================

    public function scopeCod($query, $val){
        if ($val != ''){
            $query->where('cod', 'like', "%{$val}%");
        }
    }

    public function scopeCliente($query, $val)
    {
        if ($val != '') {
            $query->whereHas('cliente', function ($q1) use ($val) {
                $q1->where('nombre', 'like', "%{$val}%");
            });
        }
    }

    public function scopeNombre($query, $val){
        if ($val != ''){
            $query->where('nombre', 'like', "%{$val}%");
        }
    }

    public function scopeCategoria($query, $val){
        if ($val != ''){
            $query->where('categoria', $val);
        }
    }

    public function scopeUbicacion($query, $val){
        if ($val != ''){
            $query->where('ubicacion', 'like', "%{$val}%");
        }
    }

    public function scopeCiudad($query, $val){
        if ($val != ''){
            $query->where('ciudad', $val);
        }
    }

    public function scopeSerie($query, $val){
        if ($val != ''){
            $query->where('nro_serie', 'like', "%{$val}%");
        }
    }

    public function scopeDatosActivo($query, $val){
        if ($val != ''){
            $query->where(function ($q1) use ($val) {
                $q1->where('marca', 'LIKE', "%$val%")
                    ->orWhere('modelo', 'LIKE', "%$val%")
                    ->orWhere('capacidad', 'LIKE', "%$val%");
            });
        }
    }

    public function scopeEstado($query, $val){
        if ($val != ''){
            $query->where('estado', $val);
        }
    }

    public function scopeCodName($query, $search){
        if($search != ''){
            $query->where('cod','LIKE','%'.$search.'%')
            ->orwhere('nombre','LIKE','%'.$search.'%');
        }
    }
}
