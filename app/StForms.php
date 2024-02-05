<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StForms extends Model
{
    //    JSON
    protected $casts = [
        'maintenance' => 'array',
        'containers' => 'array',
        'letter' => 'array',
    ];

    //    RELACIONES
    public function types(){
        return $this->belongsTo(StFormType::class, 'type_id');
    }

    // =========================================================================================
    //                                          FUNCIONES
    // =========================================================================================
    public function getCategoriaLiteralAttribute(){
        switch ($this->category_id) {
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

    public function getOperations(){
        $operaciones = '';
        // if($this->state != "1"){
        //     return '';
        // }
        if(!permisoAdminJefe()){
            return '';
        }

        $editar =
        '<a style=\'cursor:pointer\' rel=\'modalEdit\' href=\'/forms/editmodal/'. code($this->id).' \' >
            <svg class=\'icon text-muted iconhover\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' stroke-width=\'2\' stroke=\'currentColor\' fill=\'none\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><path stroke=\'none\' d=\'M0 0h24v24H0z\' fill=\'none\'/><path d=\'M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3\' /><path d=\'M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3\' /><line x1=\'16\' y1=\'5\' x2=\'19\' y2=\'8\' /></svg>&nbsp;<span class=\'text-muted\'>Editar</span>
        </a><br>';
        $eliminar =
        '<a style=\'cursor:pointer\' rel=\'modalDelete\' href=\'/forms/deletemodal/'. code($this->id).' \'>
            <svg class=\'icon text-muted iconhover\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' stroke-width=\'2\' stroke=\'currentColor\' fill=\'none\' stroke-linecap=\'round\' stroke-linejoin=\'round\'><path stroke=\'none\' d=\'M0 0h24v24H0z\' fill=\'none\'/><line x1=\'4\' y1=\'7\' x2=\'20\' y2=\'7\' /><line x1=\'10\' y1=\'11\' x2=\'10\' y2=\'17\' /><line x1=\'14\' y1=\'11\' x2=\'14\' y2=\'17\' /><path d=\'M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12\' /><path d=\'M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3\' /></svg>&nbsp;<span class=\'text-muted\'>Eliminar</span>
        </a>';

        $editar = $this->state == '1' ? $editar : '';
        $eliminar = $this->state == '1' ? $eliminar : '';

        $operaciones=
        '<span class="form-operations" data-toggle="popoverOper" tabindex="0" data-content="'.$editar.$eliminar.'">
            <svg class="icon text-muted btnoper" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <circle cx="12" cy="12" r="1" /><circle cx="12" cy="19" r="1" /><circle cx="12" cy="5" r="1" />
            </svg>
        </span>';

        return $operaciones;
    }

    public function getLinks($type){
        switch ($type) {
            case 'container':
                $output =
                '<div class="p-2 text-center form-control-sm">
                    <a href="/forms/container/'.code($this->id).'" class="text-yellowdark">
                    <i class="fa fa-plus"></i>&nbsp;&nbsp;  <b>Ver más</b><br><span class="textcont">(Contenedores)</span>  </a>
                </div>';
            break;
            case 'report':
                $output =
                '<div class="p-2 text-center form-control-sm">
                    <a href="/forms/maintenance/'.code($this->id).'" class="text-yellowdark">
                    <i class="fa fa-plus"></i>&nbsp;&nbsp; <b>Ver más</b><br><span class="textcont">(Informes de Mantenimiento)</span> </a>
                </div>';
            break;
            case 'letter':
                if($this->check_letter == 1){
                    $output =
                    '<div class="p-2 text-center form-control-sm">
                        <a href="/forms/letter/'.code($this->id).'" class="text-yellowdark">
                        <i class="fa fa-plus"></i>&nbsp;&nbsp; <b>Ver más</b><br><span class="textcont">(Carta de Presentación)</span></a>
                    </div>';
                }
                else{
                    $output = '';
                }
            break;
            default:  return "";  break;
        }
        return $output;
    }

    public function getState($flag){
        switch ($this->state) {
            case '0':
                $val = "Inactivo";
                $fin =
                '<div class="p-2 text-center form-control-sm vermas">
                    <a style="cursor:pointer" rel="modalDelete" href="/forms/activateDeactivate_modal/'. code($this->id).' " title="Activar procedimiento">
                        <span class="text-red"><i class="fa fa-ban text-danger"></i><br>'.$val.'</span>
                    </a>
                </div>';
            break;
            case '1':
                $val = "En proceso";
                $fin =
                '<div class="p-2 text-center form-control-sm vermas">
                    <a href="forms/statemodal/'.code($this->id).'" class="text-orange" title="Cambiar Estado" rel="modalEstado">
                        <i class="fa fa-refresh fa-spin"></i><br><b>'.$val.'</b>
                    </a>
                </div>';
            break;
            case '2':
                $val = "Terminado";
                $fin =
                '<div class="p-2 text-center input-sm" >
                    <span class="text-green"><i class="fa fa-check"></i><br><b>'.$val.'</b></span>
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

        // =========================================================================================
    //                                          SCOPES
    // =========================================================================================
    public function scopeName($query,$val){
        if ($val != ''){
            $query->where('name', 'like', "%{$val}%");
        }
    }
    public function scopeType($query, $type){
        if ($type != ''){
            $query->whereHas('types', function($q) use ($type){
                $q->where('name', 'like', "%{$type}%");
            });
        }
    }

    public function scopeCategoria($query, $val){
        if ($val != ''){
            $query->where('categoria', $val);
        }
    }

    public function scopeState($query,$val){
        if ($val != '' && $val != 'all'){
            if($val == 'act'){
                $query->where('state','!=','0');
            }else{
                $query->where('state',$val);
            }

        }
    }
}
