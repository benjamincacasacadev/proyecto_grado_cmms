<?php

use App\User;
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
    return roleId() == 1 || roleId() == 2 || roleId() == 3 ? true : false;
}

function canPass(){
    if(!permisoAdmin()){
        return abort(403);
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