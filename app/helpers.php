<?php

use App\User;
use Flasher\Prime\FlasherInterface;
use Spatie\Permission\Models\Role;
function nameEmpresa(){
    return "AMPER SRL";
}

function userId(){
    return auth()->user()->id;
}

function roleId(){
    return auth()->user()->role_id;
}

function userData($id){
    $user = User::find($id);
    return isset($user) ? $user : null;
}

function roleName($id){
    $rol = Role::find($id);
    return isset($rol) ? $rol->name : "";
}

function userMail($id){
    return userData($id) != null ? userData($id)->email : '';
}

function themeMode(){
    return isset( auth()->user()->layout ) ? auth()->user()->layout[0] : "L";
}

/**
 * Devuelve el id encriptado
* @param int $id es el numero que será encriptado
* @return int $id
*/
function code($id){
    return \Hashids::encode($id);
}

/**
 * Devuelve el id desencriptado
* @param int $id es el numero que se encuentra encriptado
* @return int $id
*/
function decode($id){
    $deco = \Hashids::decode($id);
    return count($deco) == 0 ? 0 : \Hashids::decode($id)[0];
}

/**
 * Devuelve el nombre y el apellido paterno de un usuario
 * @param  int $id ID del usuario del cual que quiere obtener su nombre
 * @return string $user_name
 */
function userFullName($id){
    if(isset($id)){
        $user = User::select('name','ap_paterno','ap_materno')->whereId($id)->first();
        return isset($user) ? $user->name." ".$user->ap_paterno." ".$user->ap_materno : "Usuario desconocido";
    }else
        return "Sin usuario asignado";
}

// Ruta para obtener el avatar
// $name: nombre del avatar en la tabla Users
// $swT: 1 obtener el thumbnail, 0: obtener tamaño grande
function imageRouteAvatar($name,$swT){
    $routeAttach = storage_path('app/public/general/avatar/'.$name);
    $routeAttachThumb = storage_path('app/public/general/avatar/thumbnail/'.$name);
    if($swT == 1){
        if (isset($name) && file_exists($routeAttachThumb))
            $ruta = '/storage/general/avatar/thumbnail/'.$name."?".rand();
        else
            $ruta = '/storage/thumbnail/avatar0.png?'.rand();
    }else{
        if (isset($name) && file_exists($routeAttach))
            $ruta = '/storage/general/avatar/'.$name."?".rand();
        else
            $ruta = '/storage/avatar0.png?'.rand();
    }
    return $ruta;
}

/**
 * Convierte la fecha de entrada dd/mm/YYYY a YYYY-mm-dd
* @param date $fechaEntreda  es la fecha de entrada del dataTable
* @return date $fechaSalida  es la fecha de salida para el filtro
*/
function convFechaDT($fechaInicial){
    $fechaFinal=explode("/",$fechaInicial);
    switch (count($fechaFinal)) {
        case '1':   return $fechaFinal[0];  break;
        case '2':   return $fechaFinal[1]."-".$fechaFinal[0];   break;
        case '3':   return $fechaFinal[2]."-".$fechaFinal[1]."-".$fechaFinal[0];    break;
        default:    return "";  break;
    }
}

/**
 * Devuelve los permisos
 * @return array $permisos el listado de los permisos
 */
function getPermisos($id){
    return Permission::where('parent_id',$id)->where('active','1')->orderBy('orden')->get();
}

function permisoName($id){
    $perm = Permission::find($id);
    return isset($perm) ? $perm->description : "";
}

function purify($val){
    return \Purify::clean($val);
}

function datosRegistro($type){
    $titulo = $type == 'edit' ? 'Modificado por' : 'Registrado por';
    $fecha = $type == 'edit' ? 'Fecha de modificación' : 'Fecha de registro';
    return  '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label>'.$titulo.'</label> <br>
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <i id="iconForm" class="fas fa-user"></i>
                        </span>
                        <input class="form-control input-incon cursor-not-allowed" value="'.userFullName(userId()).'" disabled>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="form-group">
                    <label>'.$fecha.'</label> <br>
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <i id="iconForm" class="far fa-calendar-alt"></i>
                        </span>
                        <input class="form-control input-incon cursor-not-allowed" type="text" value="'.date("d/m/Y").'" disabled>
                    </div>
                </div>
            </div>';

}

function generateCode($maximo,$code,$letter,$cant_letras,$zero){
    if($maximo==null){
        $cod = $code;
    }else{
        $cont = substr($maximo, $cant_letras);
        $cont = $cont+1;
        $codConCeros = str_pad($cont, $zero,"0", STR_PAD_LEFT);
        $cod = $letter.$codConCeros;
    }
    return $cod;
}

function checkVacio($var){
    return isset($var) && $var != "";
}

function delete_char_file($url){
    $url = mb_strtolower($url);
    $array_characters = array('Š'=>'S', 'š'=>'s', 'Ṕ'=>'Z', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y','ñ'=>'n','ç'=>'c','/'=>'_',"\\"=>'_','%'=>'_' );
    $url = strtr( $url, $array_characters );
    $url = preg_replace('([^A-_a-z!-@ ])', '-', $url);
    return $url;
}

function delete_charspecial($url){
    $url = strtolower($url);
    $array_characters = array('Š'=>'S', 'š'=>'s', 'Ṕ'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y','ñ'=>'n','ç'=>'c','/'=>'_',"\\"=>'_','%'=>'_' );
    $url = strtr( $url, $array_characters );
    $find = array(' ', '&', '\r\n', '\n','+');
    $url = str_replace($find, '-', $url);
    $url = preg_replace('([^A-_a-z!-@ ])', '_', $url);
    return $url;
}

function cleanAll($string) {
    $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
    return preg_replace('/[^A-Za-z0-9\-_]/', '-', $string); // Removes special chars.
}

function isBetween($varToCheck, $high, $low) {
    if($varToCheck < $low) return false;
    if($varToCheck > $high) return false;
    return true;
}

function fechaLiteral($fecha = false){
    $dias = array("", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
    $meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

    /* pregunta si ingreso el dato de fecha */
    if (!$fecha) $fecha = date("Y-m-d");
    return $dias[date('N', strtotime($fecha))] . " " . date('j', strtotime($fecha)) . " de " . $meses[date('n', strtotime($fecha))] . " de " . date('Y', strtotime($fecha));
}

function permisoAdmin(){
    return roleId() == 1;
}

function permisoJefe(){
    return roleId() == 2;
}

function permisoTecnico(){
    return roleId() == 4;
}

function permisoVeedor(){
    return roleId() == 3 || roleId() == 4;
}

function permisoAdminJefe(){
    return roleId() == 1 || roleId() == 2;
}

function canPassAdminJefe(){
    if(!permisoAdminJefe()){
        return abort(403);
    }
}

function permisoAdminOTs($workId, $ajax = false){
    if(!permisoAdminJefe()){
        $checkTech = DB::table('user_work_orders')->select('user_id')->where('work_orders_id',$workId)->where('user_id',userId())->count();
        if($checkTech != 1){
            if($ajax){
                return 'ajax';
            }else{
                abort(403);
            }
        }
    }
}

function limpiarTexto($texto,$type){
    if($type == 's2'){
        $find = array('â','ê','î','ô','û','ã','õ','ç');
        $repl = array('a','e','i','o','u','a','o','c');
        $texto = str_replace($find, $repl, $texto);
        $txt = trim(preg_replace('([^A-_a-z!-@ñ ])', '-*', $texto));
    }
    return $txt;
}

function randAvatar(){
    $arr = ["bg-green-lt","bg-red-lt","bg-yellow-lt","bg-blue-lt","bg-purple-lt"];
    shuffle($arr);
    return $arr[0];
}

function highchartHeatMap($idcontainer,$titulo,$datos,$max,$min,$cantd ){
    if($cantd <= 6){
        $heightchart = 160;
        $symbolHeight = 60;
    }elseif($cantd >6 && $cantd< 13 ){
        $heightchart = 200;
        $symbolHeight = 110;
    }elseif($cantd >12 && $cantd< 19 ){
        $heightchart = 250;
        $symbolHeight = 160;
    }elseif($cantd >18 && $cantd< 25 ){
        $heightchart = 300;
        $symbolHeight = 210;
    }elseif($cantd >24 && $cantd< 31 ){
        $heightchart = 350;
        $symbolHeight = 260;
    }elseif($cantd >30 && $cantd< 37 ){
        $heightchart = 400;
        $symbolHeight = 310;
    }else{
        $heightchart = 470;
        $symbolHeight = 360;
    }
    $highchart = "
    Highcharts.chart('".$idcontainer."', {
        chart: {
            type: 'heatmap',
            marginTop: 50,
            plotBorderWidth: 1,
            height: ".$heightchart.",
            width:500
        },
        title: { text: ".$titulo.", },
        yAxis: {
            title: {
                text: null,
                rotation: 270,
                x: 18
            },
            labels: {
                enabled: false
            },
            opposite: true,
        },
        xAxis: {
            opposite: true,
            labels: {
                y: -5
            },
        },
        exporting: { enabled: false },
        credits: false,
        colorAxis: {
            min: ".$min.",
            max: ".$max.",
            stops: [
            [0.0, '#B02418'],
            [0.2, '#B02418'],
            [0.4, '#DF8244'],
            [0.6, '#FFFE55'],
            [0.8, '#7EAB55'],
            [1, '#008F39']
            ],
        },
        legend: {
            align: 'right',
            layout: 'vertical',
            margin: 0,
            verticalAlign: 'top',
            y: 40,
            symbolHeight: ".$symbolHeight."
        },
        series: [{
            name: ".$titulo.",
            borderWidth: 1,
            data: [".$datos." ],
            dataLabels: {
                enabled: true,
                color: '#ffffff',
                style:{
                    fontSize: '14px'
                }
            }
        }],
    });
    ";
    return $highchart;
}

function highchartXY($idcontainer,$titulo,$tituloejex,$tituloejey,$datos, $tipo_graf ){
    $highchart = "
        Highcharts.chart('".$idcontainer."', {
            chart: {
                type: '".$tipo_graf."'
            },
            title: { text: '".$titulo."', },
            yAxis: {
                title: {
                    enabled: true,
                    text: '".$tituloejey."',
                },
            },
            xAxis: {
                title: {
                    enabled: true,
                    text: '".$tituloejex."',
                },
            },
            legend: {
                symbolWidth: 80
            },
            plotOptions: {
                series: {
                    color: '#368BB9',
                    animation: false
                },
            },
            tooltip: {
                headerFormat: '<span>{series.name}</span>: {point.x:,.2f}<br>',
                pointFormat: '<b>".$tituloejey.": {point.name}</b> <b>{point.y:,.2f}</b>'
            },
            exporting: { enabled: false },
            credits: false,
            series: [{
                name: '".$tituloejex."',
                data: [
                    ".$datos."
                ]
            }]
        });
    ";
    return $highchart;
}

function tipoCampoSerie($namecampo,$valor,$label,$tipocampo,$options, $href, $contaux, $multiple){

    $salida = "";
    $cols = ($tipocampo == "radio" || $tipocampo == "checkbox") ? "col-lg-12 col-md-12 col-sm-12 col-xs-12" : "col-lg-4 col-md-4 col-sm-12 col-xs-12";
    $salida .=
    '<div class="'.$cols.'" style="height:90px;margin-top:10px;margin-bottom:10px" >
        <label class="label_serie" title="'.$label.'">';
            if( isset($href) && $contaux>1){
                $salida .=
                '<a rel="modalEditarSerie" href="/forms/series/editmodal/'.$href.'" title="Editar campo" style="cursor:pointer">
                    <svg  class="icon iconhover text-primary" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" /><path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" /><line x1="16" y1="5" x2="19" y2="8" /></svg>
                </a>
                <a rel="modalEliminarSerie" data-modpop="popover" data-trigger="hover" href="/forms/series/deletemodal/'.$href.'" data-content="<span class=\'text-justify\' style=\'font-size: 11px;\'><b>Eliminar Campo</b></span>" style="cursor:pointer">
                    <svg class="icon text-muted iconhover" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" /><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                    </svg>
                </a>';
            }
        $salida .=$label.'</label><br>';
    switch ($tipocampo) {
        case 'text':
            $salida .='<input type="text" class="form-control"  name="'.$namecampo.'" value="'.$valor.'" style="width:100%; ">';
        break;
        case 'number':
            $salida .='<input type="text" class="numero form-control" name="'.$namecampo.'" value="'.$valor.'" style="width:100%">';
        break;
        case 'money':
            $salida .='<input type="text" class="moneda form-control" name="'.$namecampo.'" value="'.$valor.'" style="width:100%">';
        break;
        case 'textarea':
            $salida .='<textarea name="'.$namecampo.'" style="width:100%; resize:none" rows="2" class="form-control">'.$valor.'</textarea>';
        break;
        case 'date':
            $salida .=
            '<div class="input-icon">
                <span class="input-icon-addon">
                    <i id="iconForm" class="far fa-calendar-alt"></i>
                </span>
                <input class="form-control input-incon datepicker" placeholder="dd/mm/YYYY" name="'.$namecampo.'" value="'.$valor.'" style="width:100%">
            </div>';
        break;
        case 'time':
            $salida .='
            <div class="input-icon">
                <span class="input-icon-addon">
                    <i id="iconForm" class="far fa-clock"></i>
                </span>
                <input class="form-control input-incon timepicker" placeholder="HH:mm" name="'.$namecampo.'" value="'.$valor.'" style="width:100%">
            </div>';
        break;
        case 'select':
            if(empty($options)) $salida .= 'Campo incompleto, no se registrará';
            else{
                $salida .=
                '<div class="row" style="padding-right:20px; padding-left:20px;">
                    <select class="form-control" name="'.$namecampo.'">
                        <option value="" hidden>Seleccionar</option>';
                foreach ($options as $opcion) {
                    if($opcion['val'] == $valor)
                        $salida .= '<option value="'.$opcion['val'].'" selected>'.$opcion['mostraropt'].'</option>';
                    else
                        $salida .= '<option value="'.$opcion['val'].'">'.$opcion['mostraropt'].'</option>';
                }
                        $salida .=
                    '</select>
                </div>';
            }
        break;
        case 'select2':
            if(empty($options)) $salida .= 'Campo incompleto, no se registrará';
            else{
                $salida .= '<div class="row" style="padding-right:20px; padding-left:20px;">';
                if($multiple == 'multiple'){
                    $salida .= '<select class="form-control selector" name="'.$namecampo.'[]" style="width:100%" multiple data-placeholder="Seleccione uno ó más">';
                    $valorselect = is_array($valor) ? $valor : [];
                    foreach($options as $opcion){
                        if(in_array($opcion['val'],$valorselect))
                            $salida .= '<option value="'.$opcion['val'].'" selected>'.$opcion['mostraropt'].'</option>';
                        else
                            $salida .= '<option value="'.$opcion['val'].'">'.$opcion['mostraropt'].'</option>';
                    }
                    $salida .= '</select>';
                }else{
                    $salida .=
                    '<select class="form-control selector" name="'.$namecampo.'" style="width:100%">
                        <option value="" hidden>Seleccionar</option>';
                    foreach($options as $opcion){
                        if($opcion['val'] == $valor)
                            $salida .= '<option value="'.$opcion['val'].'" selected>'.$opcion['mostraropt'].'</option>';
                        else
                            $salida .= '<option value="'.$opcion['val'].'">'.$opcion['mostraropt'].'</option>';
                    }
                    $salida .= '</select>';
                }
                $salida .= '</div>';
            }
        break;
        case 'checkbox':
            if(empty($options)) $salida .= 'Campo incompleto, no se registrará';
            else{
                $check_order = collect($options)->sortBy('orden');
                $salida .='<div class="checkbox text-center">';
                $valorcheck = is_array($valor) ? $valor : [];
                foreach($check_order as $opcion){
                    if(in_array($opcion['val'],$valorcheck))
                        $salida .=
                        '<label>
                            <input class="checkboxid" type="checkbox" name="'. $namecampo .'[]" value="'. $opcion['val'] .'" checked>
                            <b>'.$opcion['mostraropt'].'</b>
                        </label>';
                    else
                        $salida .=
                        '<label>
                            <input class="checkboxid" type="checkbox" name="'. $namecampo .'[]" value="'. $opcion['val'] .'">
                            <b>'.$opcion['mostraropt'].'</b>
                        </label>';
                }
                $salida .='</div>';
            }
        break;
        case 'radio':
            if(empty($options)) $salida .= 'Campo incompleto, no se registrará';
            else{
                $salida .='<div class="checkbox text-center">';
                foreach($options as $opcion){
                    $checkradio =($valor == $opcion['val']) ? "checked" : "";
                    $colorradio = isset($opcion['color']) ? $opcion['color'] : "blue";
                    $hexradio = isset($opcion['hex']) ? $opcion['hex'] : "";
                    $salida .=
                    '<label>
                        <input class="'.$colorradio.' radiobuttonval" type="radio" name="'. $namecampo .'" value="'. $opcion['val'] .'___'.$hexradio.'" '.$checkradio.' >
                        <b style="color:'.$hexradio.'">'.$opcion['mostraropt'].'</b>
                    </label>';
                }
                $salida .='</div>';
            }
        break;
        default: $salida .=""; break;
    }
    $salida .='</div>';

    return $salida;
}

function mostrarArchivosST($file, $ruta_archivo, $i, $cod, $modulo = 'reports' ){
    $extension=explode('.',$file);
    $extensionNueva=end($extension);
    $extensionNueva = strtolower($extensionNueva);
    if ($extensionNueva=='png' || $extensionNueva=='jpg' || $extensionNueva=='jpeg' || $extensionNueva=='gif' || $extensionNueva=='svg') {
        return  '<a rel="modalImagen" style="text-decoration: none" href="/reports/mostrarImagen/' . $i . '" title="Ver Imagen">
                    <svg class="icon text-orange iconhover" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="15" y1="8" x2="15.01" y2="8" /><rect x="4" y="4" width="16" height="16" rx="3" /><path d="M4 15l4 -4a3 5 0 0 1 3 0l5 5" /><path d="M14 14l1 -1a3 5 0 0 1 3 0l2 2" /></svg>
                </a>';
    } elseif ($extensionNueva=='pdf') {
        return '<a href="/storage'.$ruta_archivo.$file.'" title="PDF" target="_blank">
                    <svg class="icon text-red iconhover" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="9" y1="17" x2="9" y2="12" /><line x1="12" y1="17" x2="12" y2="16" /><line x1="15" y1="17" x2="15" y2="14" /></svg>
                </a>';
    } elseif ($extensionNueva=='doc' || $extensionNueva=='docx') {
        return '<a href="/'.$modulo.'/downloadfile/'.$file.'/'.$cod.'" title="Word">
                    <svg class="icon text-primary iconhover" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><line x1="9" y1="9" x2="10" y2="9" /><line x1="9" y1="13" x2="15" y2="13" /><line x1="9" y1="17" x2="15" y2="17" /></svg>
                </a>';
    } elseif ($extensionNueva=='rar' || $extensionNueva=='zip' || $extensionNueva=='tar') {
        return '<a href="/'.$modulo.'/downloadfile/'.$file.'/'.$cod.'" title="Archivo comprimido">
                    <svg class="icon iconhover" style="color:#f7daab" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 20.735a2 2 0 0 1 -1 -1.735v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-1" /><path d="M11 17a2 2 0 0 1 2 2v2a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1v-2a2 2 0 0 1 2 -2z" /><line x1="11" y1="5" x2="10" y2="5" /><line x1="13" y1="7" x2="12" y2="7" /><line x1="11" y1="9" x2="10" y2="9" /><line x1="13" y1="11" x2="12" y2="11" /><line x1="11" y1="13" x2="10" y2="13" /><line x1="13" y1="15" x2="12" y2="15" /></svg>
                </a>';
    }elseif ($extensionNueva=='xlsx' || $extensionNueva=='xlsm' || $extensionNueva=='xls' || $extensionNueva=='csv') {
        return '<a href="/'.$modulo.'/downloadfile/'.$file.'/'.$cod.'" title="Excel">
                    <svg class="icon text-green iconhover" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M10 12l4 4m0 -4l-4 4" /></svg>
                </a>';
    }
}

function iconoArchivos($nom_archivo, $size = '100px'){
    $extension = explode('.',$nom_archivo);
    $extensionNueva = end($extension);
    if($extensionNueva=='xlsx' || $extensionNueva=='xlsm' || $extensionNueva=='xls' || $extensionNueva=='csv'){
        return '<img class="mb-2" src="/imagenes/iconoExcel.png" class="img-rounded img-responsive pull-left" style="width: '.$size.'; " alt="Sin imagen para mostdar" id="imgItem">'; }
    elseif($extensionNueva=='rar' || $extensionNueva=='zip' || $extensionNueva=='tar'){
        return '<img class="mb-2" src="/imagenes/iconoZip.png" class="img-rounded img-responsive pull-left" style="width: '.$size.'; " alt="Sin imagen para mostdar" id="imgItem">'; }
    elseif($extensionNueva=='doc' || $extensionNueva=='docx'){
        return '<img class="mb-2" src="/imagenes/iconoWord.png" class="img-rounded img-responsive pull-left" style="width: '.$size.'; " alt="Sin imagen para mostdar" id="imgItem">'; }
    elseif($extensionNueva=='pdf'){
        return '<img class="mb-2" src="/imagenes/iconoPdf.png" class="img-rounded img-responsive pull-left" style="width: '.$size.'; " alt="Sin imagen para mostdar" id="imgItem">' ; }
    else{
        return '<img class="mb-2" src="/imagenes/iconoImg.svg" class="img-rounded img-responsive pull-left" style="width: '.$size.'; " alt="Sin imagen para mostdar" id="imgItem">';
    }
}

function sliderImg(){
    $imgslider = ['01.png','02.png','03.png'];
    $salida = [];
    foreach ($imgslider as $img){
        if (Storage::exists('public/general/slider/'.$img)){
            $salida[] = asset('/storage/general/slider/'.$img);
        }
    }
    return $salida;
}

function monedaVal($valor){
    $numero=floatval(str_replace(",","",$valor));
    return $numero;
}

function getAlmacenes(){
    return [
        1 => 'Edificio Arcadia',
        2 => 'El Alto',
        3 => 'Gramadal',
        4 => 'Edifico técnico',
    ];
}

function generarCorreoGmail($nombreCompleto) {
    // Convertir el nombre completo a minúsculas y eliminar espacios
    $nombreCompleto = strtolower(str_replace(' ', '', $nombreCompleto));

    // Eliminar caracteres especiales y acentos
    $nombreNormalizado = iconv('UTF-8', 'ASCII//TRANSLIT', $nombreCompleto);

    // Eliminar cualquier carácter no alfanumérico excepto puntos
    $nombreNormalizado = preg_replace('/[^a-z0-9.]+/i', '', $nombreNormalizado);

    // Generar el correo electrónico agregando "@gmail.com"
    $correoElectronico = $nombreNormalizado . '@gmail.com';

    return $correoElectronico;
}