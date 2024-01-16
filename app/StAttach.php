<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StAttach extends Model
{

    protected $table = 'st_attaches';
    protected $fillable = ['path', 'nombre', 'orden','flag'];

    //  POLIMORPH ENTRE TODOS LOS QUE LO NECESITEN
    public function imageable(){
        return $this->morphTo();
    }

    public function getFileSize($cod, $modulo){
        $arch = $this->path;
        $carpeta = storage_path().'/app/public/st/'.$modulo.'/'.$cod.'/';
        $url = $carpeta.$arch;
        $fileSize = is_file($url) ? round(filesize($url)/1000,2) : 0;
        $fileSize = $fileSize >= 1024 ? round($fileSize/1024, 2)." <b>MB</b>" : $fileSize." <b>Kb</b>";
        return $fileSize;
    }

    public function getOperations($cod, $perm, $modulo){
        $mostrar = mostrarArchivosST($this->path,  '/st/'.$modulo.'/'.$cod.'/', code($this->id), $cod, $modulo);

        $descargar =
        '<a href="/'.$modulo.'/downloadfile/'.$this->path.'/'.$cod.'" title="Descargar">
            <svg class="icon text-muted iconhover" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19 18a3.5 3.5 0 0 0 0 -7h-1a5 4.5 0 0 0 -11 -2a4.6 4.4 0 0 0 -2.1 8.4" /><line x1="12" y1="13" x2="12" y2="22" /><polyline points="9 19 12 22 15 19" /></svg>
        </a>';

        $eliminar = '';
        if($perm){
            $eliminar =
            '<a rel="modalEliminarArchivo" href="/'.$modulo.'/deleteModalFile/'. code($this->id) .'/'.$cod.'">
                <svg class="icon text-muted iconhover" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
            </a>';
        }

        return $mostrar.$descargar.$eliminar;
    }
}
