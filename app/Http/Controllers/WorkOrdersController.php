<?php

namespace App\Http\Controllers;

use App\StAttach;
use App\StForms;
use App\User;
use App\WorkOrders;
use App\WoTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;
use DB;
use Session;
use iio\libmergepdf\Merger;
use iio\libmergepdf\Driver\TcpdiDriver;
use Illuminate\Support\Facades\File;
use View;
use PDF;

class WorkOrdersController extends Controller
{
    public function index(Request $request){
        $selectEstado = $request->selectEstado != null ? $request->selectEstado : '';
        $users = User::where('active','1')->get();
        Session::put('item','1.');
        return view('work_orders.index', compact('users','selectEstado'));
    }

    public function show(Request $request, $id){
        $workorder = WorkOrders::where('id',decode($id))->with('asset.cliente')->first();
        if(!isset($workorder)){
            abort(404);
        }
        permisoAdminOTs(decode($id));
        $users = User::where('active','1')->get();
        Session::put('item','1.');
        return view('work_orders.show', compact('workorder'));
    }

    public function create(){
        canPassAdminJefe();
        $users = User::where('active','1')->get();
        $forms = StForms::get();
        Session::put('item','1.');
        return view('work_orders.create', compact('users','forms'));
    }

    public function store(Request $request, FlasherInterface $flasher) {
        canPassAdminJefe();
        $this->validateWorkorders($request);
        // Guardar los campos de activo
        $registroMaximo = WorkOrders::select('cod')->where('cod', 'LIKE', "%OT%")->max('cod');
        $cod = generateCode($registroMaximo,'OT000001','OT',2,6);

        $fechaSave = Carbon::createFromFormat('d/m/Y H:i', $request->fecha_ven);

        DB::beginTransaction();
        try {
            $workorder = new WorkOrders();
            $workorder->cod = $cod;
            $workorder->asset_id = decode($request->activo);
            $workorder->form_id = decode($request->formulario);
            $workorder->titulo = $request->titulo;
            $workorder->fecha = $fechaSave;
            $workorder->prioridad = $request->prioridad;
            $workorder->descripcion = $request->descripcion;
            $workorder->emergencia = $request->emergency;
            $workorder->estado = 'P';
            if ($request->hasFile('fileWO')) {
                $archivo = $request->file('fileWO');
                $extension = $archivo->getClientOriginalExtension();
                $ext = strtolower($extension);
                $nombreConExtension = $archivo->getClientOriginalName();
                $nombreConExtension = delete_charspecial($nombreConExtension);
                $workorder->attach = $cod . '_' . strtolower($nombreConExtension);

                $archivo->storeAs("public/workorders/", $workorder->attach);
                if($ext == 'gif' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png'){
                    $size = getimagesize($archivo);
                    if($size[0]<=1024 && $size[1]<=1024){
                        InterventionImage::make($archivo)->resize(function ($constraint){
                            $constraint->aspectRatio();
                        })->save(storage_path().'/app/public/workorders/'.$workorder->attach, 90);
                    }else{
                        InterventionImage::make($archivo)->resize(1024,1024, function ($constraint){
                            $constraint->aspectRatio();
                        })->save(storage_path().'/app/public/workorders/'.$workorder->attach, 80);
                    }
                }
            }
            $workorder->save();

            // Buscar y eliminar técnicos duplicados
            $resp = $request->tecresponsable != null ? $request->tecresponsable : '';
            $techhAddArray = $request->asignados != null ? $request->asignados : [];
            $pos = array_search($resp, $techhAddArray);
            if( in_array($resp, $techhAddArray) ){
                unset($techhAddArray[$pos]);
            }
            // Armar array para tabla pivote con los técnicos a cargo
            $array = [$resp];
            $array = array_merge($array, $techhAddArray);
            // Borrar técnicos duplicados y reordenar
            $array = array_unique($array);
            $new_array = array_values($array);

            // Adjuntar campo responsable al array de técnicos
            $sync_data = [];
            for($i = 0; $i < count($new_array); $i++){
                if($i == 0){
                    $sync_data[$new_array[$i]] = ['responsable' => 1];
                }else{
                    $sync_data[$new_array[$i]] = ['responsable' => 0];
                }
            }

            // Guardar técnicos en tabla pivote
            $workorder->usuarios()->sync($sync_data);

            $flasher->addFlash('success', 'Creada con éxito', 'Orden de trabajo '.$workorder->cod);
            DB::commit();
            return  \Response::json(['success' => '1']);
        }catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    public function tableWorkorders(Request $request){
        $totalData = WorkOrders::count();
        $totalFiltered = $totalData;

        $limit = empty($request->input('length')) ? 10 : $request->input('length');
        $start = empty($request->input('start')) ? 0 :  $request->input('start');

        $posts = WorkOrders::Cod($request->input('columns.0.search.value'))
        ->AssetId($request->assetId)
        ->Activo($request->input('columns.1.search.value'))
        ->Estado($request->state)
        ->Titulo($request->input('columns.3.search.value'))
        ->Cliente($request->input('columns.4.search.value'))
        ->TecnicoId($request->input('columns.5.search.value'))
        ->Descripcion($request->input('columns.6.search.value'))
        ->Prioridad($request->input('columns.7.search.value'))
        ->Fecha($request->input('columns.8.search.value'))
        ->PermisoVer()
        ->with('asset.cliente');

        $totalFiltered = $posts->count();
        $posts = $posts
        ->offset($start)
        ->limit($limit)
        ->orderBy('id','desc')
        ->get();

        $data = array();
        foreach ($posts as $post){
            $nestedData['cod'] = $post->getCod();
            $nestedData['activo'] = '<b>'.$post->asset->getCod().' <br> '.$post->asset->nombre.'</b>';
            $nestedData['estado'] = $post->getEstado(1);
            $nestedData['titulo'] = $post->titulo;
            $nestedData['cliente'] = $post->asset->cliente->nombre;
            $nestedData['tecnicos'] = $post->getAvatars(3);
            $nestedData['descripcion'] = $post->descripcion;
            $nestedData['prioridad'] = $post->getPrioridad();
            $nestedData['fecha'] = $post->getFecha();
            $nestedData['operations'] = $post->getOperations();
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        echo json_encode($json_data);
    }

    public function modalEdit(Request $request, $id){
        canPassAdminJefe();
        $workorder = WorkOrders::findOrFail(decode($id));
        $forms = StForms::get();
        if(!$workorder->canEdit){
            abort(403);
        }
        $users = User::where('active','1')->get();
        return view('work_orders.modalEdit', compact('workorder','users','forms'));
    }

    public function update(Request $request, FlasherInterface $flasher, $id) {
        canPassAdminJefe();
        $this->validateWorkorders($request, $id);

        $fechaSave = Carbon::createFromFormat('d/m/Y H:i', $request->fecha_ven);

        DB::beginTransaction();
        try {
            $workorder = WorkOrders::findOrFail(decode($id));
            $workorder->asset_id = decode($request->activo);
            $workorder->form_id = decode($request->formulario);
            $workorder->titulo = $request->titulo;
            $workorder->fecha = $fechaSave;
            $workorder->prioridad = $request->prioridad;
            $workorder->descripcion = $request->descripcion;
            $workorder->emergencia = $request->emergency;

            $workorder->update();

            // Buscar y eliminar técnicos duplicados
            $resp = $request->tecresponsable != null ? $request->tecresponsable : '';
            $techhAddArray = $request->asignados != null ? $request->asignados : [];
            $pos = array_search($resp, $techhAddArray);
            if( in_array($resp, $techhAddArray) ){
                unset($techhAddArray[$pos]);
            }
            // Armar array para tabla pivote con los técnicos a cargo
            $array = [$resp];
            $array = array_merge($array, $techhAddArray);
            // Borrar técnicos duplicados y reordenar
            $array = array_unique($array);
            $new_array = array_values($array);

            // Adjuntar campo responsable al array de técnicos
            $sync_data = [];
            for($i = 0; $i < count($new_array); $i++){
                if($i == 0){
                    $sync_data[$new_array[$i]] = ['responsable' => 1];
                }else{
                    $sync_data[$new_array[$i]] = ['responsable' => 0];
                }
            }
            // Guardar técnicos en tabla pivote
            $workorder->usuarios()->sync($sync_data);

            $flasher->addFlash('info', 'Modificada con éxito', 'Orden de trabajo '.$workorder->cod);
            DB::commit();
            return  \Response::json(['success' => '1']);
        }catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    public function modalDelete(Request $request, $id){
        canPassAdminJefe();
        $workorder = WorkOrders::findOrFail(decode($id));
        if(!$workorder->canEdit){
            abort(403);
        }
        $users = User::where('active','1')->get();
        return view('work_orders.modalDelete', compact('workorder','users'));
    }

    public function destroy(FlasherInterface $flasher, $id) {
        canPassAdminJefe();
        $workorder = WorkOrders::findOrFail(decode($id));
        if(!$workorder->canEdit){
            $flasher->addFlash('warning', 'Debido al estado del informe', 'No se puede eliminar '.$workorder->cod);
            return redirect()->route('workorders.index');
        }

        $rutaFile = 'public/workorders/'.$workorder->attach;
        if (Storage::exists($rutaFile)){
            Storage::delete($rutaFile);
        }
        $workorder->usuarios()->sync([]);
        $workorder->delete();

        $flasher->addFlash('error', 'Eliminada correctamente', 'Orden de trabajo '.$workorder->cod);
        return redirect()->route('workorders.index');
    }

    public function updateImage(Request $request, FlasherInterface $flasher, $id){
        $messages = [
            'fileWO.required' => 'Debe adjuntar un archivo',
            'fileWO.mimes' => 'El archivo debe estar en algunos de los siguientes formatos:<br><p class="text-center"><b>gif, jpg, jpeg, png</b></p>',
            'fileWO.max' => "El archivo a subir es muy grande. El tamaño máximo admitido es de 5 MB (5192 KB).",
        ];

        $validateArray = [
            'fileWO' => 'required|mimes:gif,jpg,jpeg,png,pdf,mp4|max:5192'
        ];
        $request->validate($validateArray,$messages);
        $workorder = WorkOrders::findOrFail(decode($id));

        // VALIDACION DE ESTADO TERMINADO Y ANULADO
        if ($workorder->estado == 'T' || $workorder->estado == 'X'){
            return  \Response::json(['alerta' => '1','mensaje' => 'error']);
        }
        // ALMACENAMIENTO DEL ARCHIVO
        if ($request->hasFile('fileWO')) {
            $rutaborrar = 'public/workorders/'.$workorder->attach;
            if (Storage::exists($rutaborrar)){
                Storage::delete($rutaborrar);
            }
            $archivo = $request->file('fileWO');
            $ext = $archivo->getClientOriginalExtension();
            $ext = strtolower($ext);
            $nombre = $archivo->getClientOriginalName();
            $nombre = delete_charspecial($nombre);
            $workorder->attach = $workorder->cod . '_' . strtolower($nombre);
            $archivo->storeAs("public/workorders/", $workorder->attach);
            if($ext == 'gif' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png'){
                $size = getimagesize($archivo);
                if($size[0]<=1024 && $size[1]<=1024){
                    InterventionImage::make($archivo)->resize(function ($constraint){
                        $constraint->aspectRatio();
                    })->save(storage_path().'/app/public/workorders/'.$workorder->attach, 90);
                }else{
                    InterventionImage::make($archivo)->resize(1024,1024, function ($constraint){
                        $constraint->aspectRatio();
                    })->save(storage_path().'/app/public/workorders/'.$workorder->attach, 80);
                }
            }
        }
        $workorder->update();
        $flasher->addFlash('info', 'Modificada con éxito', 'Imagen de '.$workorder->cod);
        return  \Response::json(['success' => '1']);
    }

    public function report(Request $request, $id){
        permisoAdminOTs(decode($id));
        $workorder = WorkOrders::findOrFail(decode($id));

        $formulario = $workorder->forms;

        $form = isset($formulario->maintenance) ? $formulario->maintenance : [];
        $containers = collect($formulario->containers)->sortBy('orden');
        $campos_carta = $formulario->letter;
        $check_formCarta = $formulario->check_letter;

        $datosguardados = $workorder->info_general;
        $tecnicos = User::where('active',1)->orderBy('ap_paterno','asc')->get();

        // Cantidad de archivos adjuntos del informe
        $archivos = $workorder->attachesReport;
        $totalArchivos = $archivos->count();

        // Tiempo de trabajo
        $arrayInterval = $workorder->timeElapsed;

        $horas = $arrayInterval['h'];
        $mins = $arrayInterval['m'];
        $segs = $arrayInterval['s'];

        Session::put('item','1.');
        return view('work_orders.report', compact('workorder','formulario','form','containers','datosguardados','check_formCarta','campos_carta','tecnicos','horas','mins','segs','totalArchivos'));
    }

    public function initTimeWork(FlasherInterface $flasher, $id){
        permisoAdminOTs(decode($id));
        $workorder = WorkOrders::findOrFail(decode($id));
        if ($workorder->estado != 'P' ){
            return Redirect()->route('reports.show', ['id' => $id]);
        }
        $workorder->init_work_date = now();
        $workorder->estado = 'E';
        $workorder->update();
        // GUARDANDO EL INICIO DEL TRABAJO
        $woTime = new WoTime();
        $woTime->work_order_id = $workorder->id;
        $woTime->init_work_date = now();
        $woTime->save();

        $flasher->addFlash('success', 'Iniciado correctamente', 'Tiempo de trabajo');
        return Redirect()->route('reports.show', ['id' => $id]);
    }

    public function timeRangeStore(Request $request, FlasherInterface $flasher, $id){
        $workorder = WorkOrders::findOrFail(decode($id));

        if(permisoAdminOTs($workorder->id, true) == 'ajax'){
            return  \Response::json(['alerta' => '2', 'mensaje' => '403 - No permitido']);
        };

        $fechaActual = Carbon::now();
        if($request->sw == 1){
            if ($workorder->estado != 'E'){
                return  \Response::json(['alerta' => '1']);
            }
            $wotime =  $workorder->workTimes->where('end_work_date',null)->first();
            if($wotime != null && $wotime->init_work_date->lt($fechaActual) ){
                $wotime->end_work_date = $fechaActual->subSeconds(1);
                $wotime->description = $request->motivo;
                $wotime->update();

                $workorder->estado = 'S';
                $workorder->update();

                $estado = $workorder->getEstado(6);
                $flasher->addFlash('info', 'Orden de trabajo en pausa', 'CAMBIO DE ESTADO');
                return  \Response::json(['success' => '1','estado' => $estado]);
            }else{
                $msjError = 'No se guardo la fecha';
                return  \Response::json(['alerta' => '2', 'mensaje' => $msjError]);
            }
        }elseif($request->sw == 2){
            if ($workorder->estado != 'S'){
                return  \Response::json(['alerta' => '1']);
            }
            // TIEMPO TRANSCURRIDO DE TRABAJO
            $arrayTime = $workorder->timeElapsed;
            $woTime = new WoTime();
            $woTime->work_order_id = $workorder->id;
            $woTime->init_work_date = now();
            $woTime->save();
            // PONER EN PROGRESO LA OT Y EL REPORTE
            $workorder->estado = 'E';
            $workorder->update();

            $estado = $workorder->getEstado(6);
            $flasher->addFlash('info', 'Orden de trabajo en progreso', 'CAMBIO DE ESTADO');
            return  \Response::json(['success' => '2','arrayTime' =>$arrayTime,'estado' =>$estado]);
        }
    }

    // ====================================================================================================
    // GUARDAR DATOS DE REPORTE
    // ====================================================================================================
    public function updateReport(Request $request, FlasherInterface $flasher, $id){
        permisoAdminOTs(decode($id));

        $workorder = WorkOrders::findOrFail(decode($id));

        if($workorder->estado == 'T'){
            return Redirect()->route('reports.show', ['id' => $id]);
        }

        if(!$workorder->reportEnabled){
            return Redirect()->route('reports.show', ['id' => $id]);
        }

        $idrep = $id;
        $container = $request->get('&&contenedor_id&&');
        $respuestas = [];
        $field = $request->all();
        unset ($field['&&contenedor_id&&']);
        unset ($field['_token']);
        foreach ($field as $key => $valor) {
            $aux = explode("|",$key);
            $id = $aux[0];
            switch ($id) {
                case '&serie&': // SERIE MxN
                    $nroserie = isset($aux[3]) ? $aux[3] : "";
                    $nombreserie = isset($aux[1]) ? $aux[1] : "";
                    $nombrecamposerie = isset($aux[2]) ? $aux[2] : "";
                    $seriegenerada = isset($aux[4]) ? $aux[4] : "";
                    $keyserie = $nombreserie."|".$nroserie;
                    $keyseriegen = $nombrecamposerie."_".$seriegenerada;
                    if(is_array($valor)){
                        $respuestas[$keyserie][$nombrecamposerie]['id'] = $nombrecamposerie;
                        $respuestas[$keyserie][$nombrecamposerie]['valor'] = $valor;
                    }else{
                        $valorguard = explode("___",$valor);
                        $colorhex = isset($valorguard[1]) ? $valorguard[1] : "";
                        if($nombrecamposerie == '&seriegener&'){
                            $respuestas[$keyserie][$keyseriegen]['id'] = $keyseriegen;
                            $respuestas[$keyserie][$keyseriegen]['valor'] = $valorguard[0];
                        }else{
                            $respuestas[$keyserie][$nombrecamposerie]['id'] = $nombrecamposerie;
                            $respuestas[$keyserie][$nombrecamposerie]['valor'] = $valorguard[0];
                            if ($colorhex != '') {
                                $respuestas[$keyserie][$nombrecamposerie]['hex'] = $colorhex;
                            }
                        }
                    }
                break;
                case '&serie_xy&': // SERIE XY
                    $nombreserie = isset($aux[1]) ? $aux[1] : "";
                    $nombrecamposerie = isset($aux[2]) ? $aux[2] : "";
                    if(is_array($valor)){
                        $respuestas[$nombreserie][$nombrecamposerie]['id'] = $nombrecamposerie;
                        $respuestas[$nombreserie][$nombrecamposerie]['valor'] = $valor;
                    }else{
                        $valorguard = explode("___",$valor);
                        $colorhex = isset($valorguard[1]) ? $valorguard[1] : "";
                        $respuestas[$nombreserie][$nombrecamposerie]['id'] = $nombrecamposerie;
                        $respuestas[$nombreserie][$nombrecamposerie]['valor'] = $valorguard[0];
                        if ($colorhex != '') {
                            $respuestas[$nombreserie][$nombrecamposerie]['hex'] = $colorhex;
                        }
                    }
                break;
                case '&grafXY&': // GRAFICO XY
                    $nombreserie = isset($aux[1]) ? $aux[1] : "";
                    $nombrecamposerie = isset($aux[2]) ? $aux[2] : "";
                    if(is_array($valor) && count($valor)>0){
                        if(count($valor) > 100){
                            return Redirect()->route('reports.show', ['id' => $idrep, 'contid' => $container]);
                        }
                        foreach($valor as $cl=>$val){
                            // if(!isset($val)) unset($valor[$cl]);
                        }
                    }
                    $respuestas[$nombreserie][$nombrecamposerie]['id'] = $nombrecamposerie;
                    $respuestas[$nombreserie][$nombrecamposerie]['valor'] = $valor;
                break;
                default: // TODOS LOS DEMAS CAMPOS
                    if($id != '&checkcarta&'){
                        $swradiodep = explode("||",$key);
                        $swradiopadredep = isset($swradiodep[2]) ? $swradiodep[2] : "";
                        $swradiodep = isset($swradiodep[1]) ? $swradiodep[1] : "";
                        $respuestas[$id]['id'] = $id;
                        $respuestas[$id]['valor'] = $valor;
                        if($swradiodep != ""){
                            $iddep = $swradiopadredep.'|'.$swradiodep.'|'.$id;
                            $respuestas[$iddep]['id'] = $id;
                            $respuestas[$iddep]['valor'] = $valor;
                            $respuestas[$iddep]['radioid'] = $swradiodep;
                        }
                        // COLOR RADIO
                        if(!is_array($valor)){
                            $auxcolor_radio = explode("___",$valor);
                            if(isset($auxcolor_radio[1]) && $auxcolor_radio[1] != "")
                            $respuestas[$id]['hex'] = isset($auxcolor_radio[1]) ? $auxcolor_radio[1] : "";
                        }

                        $keyserie = explode("|",$key);
                        $keyserie = isset($keyserie[1]) ? $keyserie[1] : "";
                        if($keyserie == '&nro_x_serie_simple&'){
                            if($valor > 100){
                                return Redirect()->route('reports.show', ['id' => $idrep, 'contid' => $container]);
                            }
                        }
                        if($keyserie == 'campos_x_serie' ){
                            if($valor > 80){
                                return Redirect()->route('reports.show', ['id' => $idrep, 'contid' => $container]);
                            }
                            $respuestas[$id]['campos_x_serie'] = $valor;
                        }
                        if($keyserie == 'nro_x_serie'){
                            if($valor > 80){
                                return Redirect()->route('reports.show', ['id' => $idrep, 'contid' => $container]);
                            }
                            $respuestas[$id]['nro_x_serie'] = $valor;
                        }
                    }
                break;
            }
        }
        $chc = '&checkcarta&';
        $respuestas['&checkcarta&'] = isset($request->$chc) ? 1 : 0;
        if($request->$chc != null){
            $workorder->letter_for = (isset($request['&carta&|for']))? $request['&carta&|for'] : '';
            $workorder->letter_copy = (isset($request['&carta&|copy']))? $request['&carta&|copy'] : '';
            $workorder->letter_reference = (isset($request['&carta&|reference']))? $request['&carta&|reference'] : '';
            $workorder->letter_body = (isset($request['&carta&|body']))? $request['&carta&|body'] : '';
        }
        $workorder->info_general = $respuestas;
        $workorder->update();
        $flasher->addFlash('success', 'Actualizado correctamente', 'Informe '.$workorder->cod);

        return Redirect()->route('reports.show', ['id' => $idrep, 'contid' => $container]);
    }

    // =========================================================================================================
    // =========================================================================================================
    //                                       ARCHIVOS
    // =========================================================================================================
    // =========================================================================================================
    public function storeFile(Request $request, FlasherInterface $flasher){
        $messages = [
            'archivo.required' => 'Debe subir un archivo.',
            'imagen.required' => 'Debe subir un archivo.',
            'imagen.max' => "El tamaño de la imagen no debe superar los 15 MB.",
            'archivo.max' => "El tamaño del archivo no debe superar los 5 MB.",
            'titulo.required' => 'el campo Título del Archivo es obligatorio.',
            'tipo_archivo.*' => 'Debe seleccionar el tipo de archivo.'
        ];
        if($request->tipo_archivo == 'i'){
            $val_img = 'required|max:15192|mimes:gif,jpg,jpeg,png';
            $val_file = 'nullable';
        }else{
            $val_img = 'nullable';
            $val_file = 'required|max:5192|mimes:pdf,txt,doc,docx,zip,rar,qz,xls,xlsx';
        }
        $request->validate([
            'imagen' => $val_img,
            'archivo' => $val_file,
            'titulo' => 'required',
            'tipo_archivo' => 'required|regex:~(^[ia]{1})$~',
        ],$messages);

        $workorder = WorkOrders::findOrFail($request->idModulo);

        if(permisoAdminOTs($workorder->id, true) == 'ajax'){
            return  \Response::json(['alerta' => '2', 'mensaje' => '403 - No permitido']);
        };

        // Informes en revision solo pueden ser editados por los que tengan permiso de validar informes
        if($workorder->estado == 'T'){
            return  \Response::json(['alerta' => '2', 'mensaje' => 'error']);
        }

        if(!$workorder->reportEnabled){
            return  \Response::json(['alerta' => '2', 'mensaje' => 'error']);
        }
        // Añadiendo el Archivo
        if($request->tipo_archivo == 'i'){
            $file = $request->file('imagen');
        }else{
            $file = $request->file('archivo');
        }
        if($file!=null){
            $ext = $file->getClientOriginalExtension();
            $ext = strtolower($ext);

            if(isset($request->renombrar))
                $name = $this->limpiar($request->renombrar).'.'.$ext;
            else
                $name = $file->getClientOriginalName();

            $name = str_replace(array("_"),"-",$name);
            $name = str_replace(" ","_",$name);
            $archivo = $workorder->cod."_".time()."_".delete_char_file($name);
            $attach = StAttach::where('work_order_id',$workorder->id)->get();
            $ordenmax = 0;
            foreach($attach as $att){
                if($att->orden > $ordenmax){
                    $ordenmax = $att->orden;
                }
            }
            $ordenmax++;
            $saveAttachname = isset($request->titulo) ? $request->titulo : $name;

            $saveAttach = new StAttach();
            $saveAttach->work_order_id = $workorder->id;
            $saveAttach->path = $archivo;
            $saveAttach->nombre = $saveAttachname;
            $saveAttach->orden = $ordenmax;
            $saveAttach->save();

            $file->storeAs('public/reports/'.$workorder->cod, $archivo);
            if($ext == 'gif' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png'){
                $size = getimagesize($file);
                if($size[0]<=1024 && $size[1]<=1024){
                    InterventionImage::make($file)
                    ->orientate()
                    ->resize(function ($constraint){
                        $constraint->aspectRatio();
                    })->save(storage_path().'/app/public/reports/'.$workorder->cod.'/'.$archivo, 90);
                }else{
                    InterventionImage::make($file)
                    ->orientate()
                    ->resize(1024,1024, function ($constraint){
                        $constraint->aspectRatio();
                    })->save(storage_path().'/app/public/reports/'.$workorder->cod.'/'.$archivo, 80);
                }
            }
        }
        $flasher->addFlash('success', 'Agregado con éxito', 'Archivo');
        return  \Response::json(['success' => '1']);
    }

    public function tableFile(Request $request){
        $idrep = $request->get('report');

        $workorder = WorkOrders::findOrFail($idrep);
        // Informes en revision solo pueden ser editados por los que tengan permiso de validar informes
        $swValidate = permisoAdminJefe() || permisoTecnico();

        $archivos = $workorder->attachesReport;

        $totalData = $archivos->count();
        $totalFiltered = $totalData;

        $posts = $archivos->sortBy('orden');
        $totalFiltered=$posts->count();
        $data = array();

        $aa = 0;
        foreach ($posts as $post) {
            $arch = $post->path;
            $carpeta = storage_path().'/app/public/reports/'.$workorder->cod.'/';
            $url = $carpeta.$arch;
            $size_file = is_file($url) ? round(filesize($url)/1000,2)." Kb" : "";
            $repEnabled = $workorder->reportEnabled;
            $mostrar = mostrarArchivosST($post->path, '/reports/'.$workorder->cod.'/', code($post->id), $workorder->cod);
            $adjuntar = $msgpopover = "";
            if ( $this->endsWith($post->path,'.pdf') && $repEnabled && $swValidate){
                if($post->flag == 1){
                    $adjuntar =
                    '<a p-4 href="/reports/attachFile/'.code($post->id).'" data-toggle="popover" data-content="<span style=\'font-size:10px;color:#D73925\'><b>Quitar Archivo del <br> Informe en PDF</b></span>">
                        <svg class="icon text-red iconhover" style="width:22px;height:22px;" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5" /><path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5" /><line x1="16" y1="21" x2="16" y2="19" /><line x1="19" y1="16" x2="21" y2="16" /><line x1="3" y1="8" x2="5" y2="8" /><line x1="8" y1="3" x2="8" y2="5" /></svg>
                    </a>';
                    $msgpopover = '<a data-toggle="popover" data-content="<span style=\'font-size:10px\'><b>Archivo Adjuntado en <br> el Informe en PDF</b></span>"><i class="fas fa-paperclip" style="padding-left:5px;color:#008D4C;" ></i></a>';
                }else{
                    $adjuntar =
                    '<a p-4 href="/reports/attachFile/'.code($post->id).'" data-toggle="popover" data-content="<span style=\'font-size:10px;color:#008d4c\'><b>Adjuntar Archivo al <br>Informe en PDF</b></span>" >
                        <svg class="icon text-green iconhover" style="width:22px;height:22px;" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5" /><path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5" /></svg>
                    </a>';
                }
            }

            $descargar =
            '<a href="/reports/downloadfile/'.$post->path.'/'.$workorder->cod.'" title="Descargar">
                <svg class="icon text-muted iconhover" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19 18a3.5 3.5 0 0 0 0 -7h-1a5 4.5 0 0 0 -11 -2a4.6 4.4 0 0 0 -2.1 8.4" /><line x1="12" y1="13" x2="12" y2="22" /><polyline points="9 19 12 22 15 19" /></svg>
            </a>';

            $eliminar = "";
            if ($repEnabled && $swValidate){
                $eliminar =
                '<a rel="modalEliminarArchivo" href="/reports/deleteModalFile/'. code($post->id) .'/'.$workorder->cod.'">
                    <svg class="icon text-muted iconhover" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="7" x2="20" y2="7" /><line x1="10" y1="11" x2="10" y2="17" /><line x1="14" y1="11" x2="14" y2="17" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                </a>';
            }

            $nestedData['DT_RowId'] =$post->id;
            $nestedData['orden'] = ++$aa ;
            $nestedData['nombre'] ="<div class='textedit classEditable' data-type='text' data-pk='$post->id' data-name='nombre'>".$post->nombre.$msgpopover."</div>";
            $nestedData['tamanio'] =$size_file;
            $nestedData['opciones'] = $adjuntar.$mostrar.$descargar.$eliminar;
            $data[] = $nestedData;
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data,
            );

        echo json_encode($json_data);
    }

    public function orderFile(Request $request){
        $att_ant=StAttach::find($request->prev_item);
        $att=StAttach::findOrFail($request->id_item);

        if(isset($att_ant->orden)){
            StAttach::where('work_order_id',$request->report)
            ->where('orden','>', $att_ant->orden)
            ->update(['orden' => DB::raw('orden+1'),'updated_at' =>now()]);
            $newOrden = $att_ant->orden+1;
        }else{
            StAttach::where('work_order_id',$request->report)
            ->update(['orden' => DB::raw('orden+1'),'updated_at' =>now()]);;
            $newOrden = 1;
        }
        $att->orden = $newOrden;
        $att->update();
    }

    public function modalShowFile($id){
        $attach = StAttach::findOrFail(decode($id));

        $workorder = WorkOrders::findOrFail($attach->work_order_id);

        return view("work_orders.reports.modalShowFile", compact('attach','workorder'));
    }

    public function modalDeleteFile($id,$cod){
        $attach = StAttach::findOrFail(decode($id));
        return view("work_orders.reports.modalDeleteFile", compact('attach','cod'));
    }

    public function updateNombreArchivo(Request $request){
        $test = StAttach::find($request->pk);
        $column_name = $request->name;
        $column_value = $request->value;
        $test->update([$column_name => $column_value]);
        return response()->json([ 'code'=>200], 200);
    }

    public function downloadFile($archivo, $cod){
        $url = 'storage/reports/'.$cod.'/'.$archivo;
        $name = explode("_",$archivo);
        $name_down = "";
        foreach($name as $k=>$n){
            if($k>=2) $name_down .=$n."_";
        }
        $name_down = substr($name_down,0,-1);
        if (Storage::exists('public/reports/'.$cod.'/'.$archivo))
            return response()->download($url,$name_down);
        else
            return abort(404);
    }

    public function destroyFile(FlasherInterface $flasher, $id, $cod){
        $attach = StAttach::findOrFail($id);
        $workorder = WorkOrders::findOrFail($attach->work_order_id);
        // Informes en revision solo pueden ser editados por los que tengan permiso de validar informes
        if($workorder->estado == 'T'){
            return Redirect()->route('reports.show', ['id' => code($workorder->id), 'contid' => '__archivos__']);
        }
        if(!$workorder->reportEnabled){
            return Redirect()->route('reports.show', ['id' => code($workorder->id), 'contid' => '__archivos__']);
        }
        $ruta = '/st/reports/'.$cod.'/';

        $archivo = $attach->path;
        if (Storage::exists('public/reports/'.$cod.'/'.$archivo)){
            Storage::delete('public/reports/'.$cod.'/'.$archivo);
        }
        $attach->delete();
        $flasher->addFlash('error', 'Eliminado correctamente', 'Archivo');
        return Redirect()->route('reports.show', ['id' => code($workorder->id), 'contid' => '__archivos__']);
    }

    public function attachFile(FlasherInterface $flasher, $id){
        $attach = StAttach::findOrFail(decode($id));

        $workorder = WorkOrders::findOrFail($attach->work_order_id);
        // Informes en revision solo pueden ser editados por los que tengan permiso de validar informes
        if($workorder->estado == 'T'){
            return back();
        }

        if(!$workorder->reportEnabled){
            return back();
        }
        if ($attach->flag == 1 ){
            $attach->flag = 0;
            $flasher->addFlash('error', 'Quitado del informe correctamente', 'Archivo');
        }else{
            $attach->flag = 1;
            $flasher->addFlash('success', 'Adjuntado al informe correctamente', 'Archivo');
        }
        $attach->update();
        $contid = "__archivos__";
        $idrep = code($attach->work_order_id);
        return Redirect()->route('reports.show', ['id' => $idrep, 'contid' => $contid]);
    }

    // =========================================================================================================
    // =========================================================================================================
    //                                       EXPORT
    // =========================================================================================================
    // =========================================================================================================
    public function export(Request $request, $id){
        $workorder = WorkOrders::findOrFail(decode($id));
        $formulario = StForms::findOrFail($workorder->form_id);
        $campos_array = isset($formulario->maintenance) ? $formulario->maintenance : [] ;
        $campos_carta = collect($formulario->letter)->sortBy('orden');
        $containers = collect($formulario->containers)->sortBy('orden');
        $datosguardados = $workorder->info_general;

        $carta = isset($datosguardados['&carta&']) ? $datosguardados['&carta&'] : [];
        $checkcarta = isset($datosguardados['&checkcarta&']) ? $datosguardados['&checkcarta&'] : [];
        // CAMPOS DE CARTA QUE SON FIJOS
        $paracarta = isset($workorder->letter_for) ? $workorder->letter_for : null;
        $copiacarta = isset($workorder->letter_copy) ? $workorder->letter_copy : null;
        $refcarta = isset($workorder->letter_reference) ? $workorder->letter_reference : null;
        $cuerpocarta = isset($workorder->letter_body) ? $workorder->letter_body : null;

        // Tecnico Responsable
        $users_work = DB::table('user_work_orders')->where('work_orders_id',$workorder->id)->where('responsable',1)->first();
        $user = isset($users_work->user_id) ? User::where('id',$users_work->user_id)->first() : null;

        // Imagenes que se adjuntaran al PDF
        $images = StAttach::select('id','path','nombre')
        ->where('work_order_id',$workorder->id)
        ->where(function ($q){
            $q->orwhere('path','like',"%.png")
            ->orwhere('path','like',"%.jpeg")
            ->orwhere('path','like',"%.jpg")
            ->orwhere('path','like',"%.gif");
        })->orderBy('orden')->get();
        $imageslast = $images->last();

        // GUARDAR BROWSERSHOT
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $localhost_sw = strpos($actual_link,'127.0.0.1');
        define('CHART_DIR', 'storage/chartreports');
        if (!is_dir(CHART_DIR)){
            mkdir(CHART_DIR, 0777, true);
        }

        $datos_graficoxy = $stringhighchart = "";
        $arrayimages = [];
        if (count($campos_array)>0){
            $camposinput = collect($campos_array)->sortBy('orden');
            foreach($camposinput as $key=>$campo) {
                if($campo['type'] == 'serie'){
                    $tipo_grafico = isset($campo['tipografico']) ? $campo['tipografico'] : "";
                    if ( $tipo_grafico == "xvsy_graf" ){
                        $nombreejex = str_replace(" ","_",$campo["nombre_eje_x"]);
                        $nombreejey = str_replace(" ","_",$campo["nombre_eje_y"]);
                        $datos_ejey = isset($datosguardados [$campo["id"]] [$nombreejey]['valor']) ? $datosguardados [$campo["id"]] [$nombreejey]['valor'] : [];
                        $datos_ejex = isset($datosguardados [$campo["id"]] [$nombreejex]['valor']) ? $datosguardados [$campo["id"]] [$nombreejex]['valor'] : [];
                        $seriegen_x = "&grafXY&|".$campo["id"]."|".$nombreejex."[]";
                        $seriegen_y = "&grafXY&|".$campo["id"]."|".$nombreejey."[]";
                        $seriegen_x_fijo = "&grafXY&|".$campo["id"]."|".$nombreejex."|&fijo&[]";
                        $ejex_aux = 0;
                        $dataname_seriegen_y = delete_charspecial($seriegen_y);
                        $dataname_seriegen_x = delete_charspecial($seriegen_x);
                        if(count($datos_ejey) == 0){
                        }else{
                            foreach ($datos_ejey as $keje=>$_ejey) {
                                if(isset($datos_ejex[$keje]))
                                    $datos_graficoxy .= "[".$datos_ejex[$keje]." , ".$_ejey."],";
                            }
                        }
                        $camponame = delete_charspecial($campo['id']);
                        $nombrecont_chart_xy = $camponame."__".$campo['type'];
                        $tipo_graf_xy = isset($campo['tipo_de_grafico_xy']) ? $campo['tipo_de_grafico_xy'] : "";
                        switch ($tipo_graf_xy) {
                            case 'grafico_barras':  $tipoGrafHighXY = 'column';  break;
                            case 'grafico_area':    $tipoGrafHighXY = 'area';    break;
                            default:    $tipoGrafHighXY = 'spline';  break;
                        }

                        // Si el grafico no tiene datos
                        if($datos_graficoxy != ''){
                            $tituloChart = str_replace(" ","____",$campo['mostrar']);
                            $tituloEjeXChart = str_replace(" ","____",$campo["nombre_eje_x"]);
                            $tituloEjeYChart = $campo["nombre_eje_y"];
                            $salidascript_xy = highchartXY('containerprueba', $tituloChart, $tituloEjeXChart, $tituloEjeYChart, $datos_graficoxy, $tipoGrafHighXY);
                            $salidascript_xy = base64_encode(str_replace(" ","",$salidascript_xy));
                            $htmlChartAdditional = view('work_orders.reports.browsershot', ['script_serie' => $salidascript_xy])->render();
                            $nombrearchivo = 'storage/chartreports/1__'.$workorder->id."__".$nombrecont_chart_xy.'.png';
                            $arrayimages[] = '1__'.$workorder->id.'__'.$camponame."__".$campo['type'].'.png';
                            if ($localhost_sw === false) {
                                Browsershot::html($htmlChartAdditional)->select('#containerprueba')->save($nombrearchivo);
                            }
                        }
                        $datos_graficoxy = "";

                    }elseif($tipo_grafico == "serie_graf"){
                        $nroXserie = isset($datosguardados[$campo['id']]['nro_x_serie']) ? $datosguardados[$campo['id']]['nro_x_serie'] : 0;
                        $campoXserie = isset($datosguardados[$campo['id']]['campos_x_serie']) ? $datosguardados[$campo['id']]['campos_x_serie'] : 0;
                        for ($ca = 1; $ca <= $nroXserie; $ca++){
                            $datosserie = isset( $datosguardados[$campo['id']."|".$ca] ) ? $datosguardados[$campo['id']."|".$ca]  : null;
                            $caaux = $nroXserie; $saux = 1;
                            $name_min = "&serie&|".$campo["id"]."|&minimo&|".$ca;
                            $name_max = "&serie&|".$campo["id"]."|&maximo&|".$ca;
                            $procedValMin = isset($campo['valmin']) && $campo['valmin'] != "" ? $campo['valmin'] : 0;
                            $procedValMax = isset($campo['valmax']) && $campo['valmax'] != "" ? $campo['valmax'] : 0;
                            $valmin = isset($datosserie["&minimo&"]['valor']) && $datosserie["&minimo&"]['valor'] != "" ? $datosserie["&minimo&"]['valor'] : $procedValMin;
                            $valmax = isset($datosserie["&maximo&"]['valor']) && $datosserie["&maximo&"]['valor'] != "" ? $datosserie["&maximo&"]['valor'] : $procedValMax;
                            $countDatosHigh = 0;
                            for ($s = 1; $s <= $campoXserie; $s++){
                                $datosseriegen = isset($datosserie['&seriegener&_'.$s]['valor']) ? $datosserie['&seriegener&_'.$s]['valor'] : "";
                                if($datosseriegen != ""){
                                    $stringhighchart .= "[".$saux.", ".$caaux.", ".$datosseriegen."],"; // Datos Grafico
                                    $countDatosHigh++;
                                }
                                if($saux % 6 == 0){ $caaux--; $saux=0; } $saux++; // Cantidad de Datos por FIla Grafico
                            }
                            $camponame_serie = delete_charspecial($campo['id']);
                            $nombrecont_chart = $camponame_serie."__".$campo['type']."__".$ca;
                            $arrayaux[$ca] =  $stringhighchart;
                            // Si el grafico no tiene datos
                            if($countDatosHigh > 0){
                                $salidascriptarray = highchartHeatMap('containerprueba', 'null', $arrayaux[$ca], $valmax, $valmin, $countDatosHigh);
                                $salidascriptarray = base64_encode(str_replace(" ","",$salidascriptarray));
                                $stringhighchart = "";
                                $htmlChartAdditional = view('work_orders.reports.browsershot', ['script_serie' => $salidascriptarray])->render();
                                $nombrearchivo = 'storage/chartreports/1__'.$workorder->id."__".$nombrecont_chart.'.png';
                                $arrayimages[] = '1__'.$workorder->id.'__'.$campo['id']."__".$campo['type']."__".$ca.'.png';
                                if ($localhost_sw === false) {
                                    Browsershot::html($htmlChartAdditional)->select('#containerprueba')->save($nombrearchivo);
                                }
                            }
                        }
                    }
                }
            }
        }
        // técnicos A CARGO
        $responsable = User::whereHas('pivotUsers', function ($query) use($workorder) {
            $query->where('work_orders_id',$workorder->wo_id)->where('responsable',1);
        })->first();

        $workorderuser = User::whereHas('pivotUsers', function ($query) use($workorder) {
            $query->where('work_orders_id',$workorder->wo_id)->where('responsable',0);
        })->get();

        $asociados = [];
        foreach($workorderuser as $wouser){
            $asociados[] = trim(userFullName($wouser->id));
        }

        // Mostrar fecha
        $intervalActual = $workorder->timeElapsed['interval'];
        $arrayDateInterval = $workorder->timeElapsed;
        $textDate = '';
        if (isset($arrayDateInterval['h']) &&  $arrayDateInterval['h'] > 1) {
            $textDate .= $arrayDateInterval['h'] .' horas ';
        } elseif(isset($arrayDateInterval['h']) &&  $arrayDateInterval['h'] == 1) {
            $textDate .= '1 hora ';
        }
        if (isset($arrayDateInterval['m']) &&  $arrayDateInterval['m'] > 1) {
            $textDate .= $arrayDateInterval['m'] .' minutos ';
        } elseif(isset($arrayDateInterval['m']) &&  $arrayDateInterval['m'] == 1) {
            $textDate .= '1 minuto ';
        }
        if (isset($arrayDateInterval['s']) &&  $arrayDateInterval['s'] > 1) {
            $textDate .= $arrayDateInterval['s'] .' segundos ';
        } elseif(isset($arrayDateInterval['s']) &&  $arrayDateInterval['s'] == 1) {
            $textDate .= '1 segundo ';
        }

        $workTimes = $workorder->workTimes;
        $countWoTimes = count($workorder->workTimes);
        $woInterval = $workorder->timeElapsed;

        // Adjuntar encabezado y pie de Página al PDF
        $header = View::make('pdf.header')->render();
        $footer = View::make('pdf.footer')->render();
        $pdf = PDF::setOption('header-html', $header)
        ->setOption('footer-html', $footer)
        ->setOption('margin-top',30)->setOption('margin-bottom',20)
        ->setOption('footer-right', 'Página [page] de [toPage]')
        ->setOption('viewport-size', '1024x768' );
        $pdf->setOption('enable-javascript', true);
        $pdf->setOption('javascript-delay', 1000);
        $pdf->setOption('enable-smart-shrinking', true);
        $pdf->setOption('no-stop-slow-scripts', true);

        // return view('work_orders.reports.pdf', compact('workorder','formulario','campos_array','containers','datosguardados','user','carta','checkcarta','paracarta','copiacarta','refcarta','cuerpocarta','images','imageslast','responsable','asociados','textDate','workTimes','countWoTimes','woInterval'));

        $pdf->loadView('work_orders.reports.pdf', compact('workorder','formulario','campos_array','containers','datosguardados','user','carta','checkcarta','paracarta','copiacarta','refcarta','cuerpocarta','images','imageslast','responsable','asociados','textDate','workTimes','countWoTimes','woInterval'));

        // REVISAR CUANTOS PDFs ADJUNTOS HAY
        $countattach = StAttach::select('path')->where('work_order_id',$workorder->id)->where('flag',1)->count();
        if($countattach > 0 ){
            // Guardar temporalmente el archivo a adjuntar
            define('PDFMAIL_DIR', storage_path('app/public/pdfattach'));
            if (!is_dir(PDFMAIL_DIR)){
                mkdir(PDFMAIL_DIR, 0777, true);
            }
            $nombrepdf = $workorder->cod.'_'.userId().'.pdf';
            // Verificar si el archivo existe para que no salgan errores
            if (Storage::exists("public/pdfattach/".$nombrepdf)){
                Storage::delete("public/pdfattach/".$nombrepdf);
            }
            // Guardar archivo en la ruta indicada
            $pdf->save(PDFMAIL_DIR.'/'.$nombrepdf);
            $pdfPath = PDFMAIL_DIR.'/'.$nombrepdf;
            // Unir PDFs
            $pdfatt = StAttach::select('path')->where('work_order_id',$workorder->id)->where('flag',1)->get();
            $documentos = [];
            foreach($pdfatt as $ii=>$pda){
                if ( $this->endsWith($pda->path,'.pdf') ) $documentos[$ii] = $pda->path;
            }
            $combinador = new Merger(new TcpdiDriver);
            $combinador->addFile($pdfPath);
            if( count($documentos) > 0 ){
                if (Storage::exists("public/anexos.pdf")){
                    $anexos = storage_path().'/app/public/anexos.pdf';
                    $combinador->addFile($anexos);
                }
            }
            foreach($documentos as $doc){
                if (Storage::exists("public/reports/".$workorder->cod."/".$doc)){
                    $ruta = storage_path().'/app/public/reports/'.$workorder->cod."/".$doc.'';
                    $combinador->addFile($ruta);
                }
            }
            $nombreArchivo = 'Informe_'.$workorder->cod.'.pdf';
            $salida = $combinador->merge();

            header("Content-type:application/pdf");
            header("Content-disposition: inline; filename=$nombreArchivo");
            header("content-Transfer-Encoding:binary");
            header("Accept-Ranges:bytes");
            echo $salida;
            File::delete($pdfPath);
        }else{
            // CAMBIAR NOMBRE DE PDF
            $defPdf = 'Informe_'.$workorder->cod;
            $extPdf = '.pdf';
            $nameExportPdf = $defPdf.$extPdf;
            return $pdf->stream($nameExportPdf);
        }
    }

    // =========================================================================================================
    // =========================================================================================================
    //                                       TIEMPOS DE TRANAJO
    // =========================================================================================================
    // =========================================================================================================
    public function modalDuration(Request $request, $id){
        $workorder = WorkOrders::findOrFail(decode($id));
        $workTimes = $workorder->workTimes;
        $countWoTimes = count($workorder->workTimes);
        $woInterval = $workorder->timeElapsed;
        return view("work_orders.modalDuration", compact('workorder','workTimes','countWoTimes','woInterval'));
    }

    // ==========================================================================================
    //                                       ENVIAR INFORMES
    // ==========================================================================================

    public function modalSendRevision($id, $swC){
        $workorder = WorkOrders::findOrFail(decode($id));
        // Tiempo de trabajo
        $arrayInterval = $workorder->timeElapsed;
        $horas = $arrayInterval['h'];
        $mins = $arrayInterval['m'];
        $segs = $arrayInterval['s'];

        return view("work_orders.reports.modalInformeEnviar", compact('workorder','horas','mins','segs','swC'));
    }

    public function SendRevision(FlasherInterface $flasher, $id, $swC){
        permisoAdminOTs(decode($id));
        $workorder = WorkOrders::findOrFail(decode($id));

        // VALIDACION DE ESTADO DEL REPORTE
        if ($workorder->estado != 'E' && $workorder->estado != 'S' && $workorder->estado != 'C' ){
            return Redirect()->route('reports.show', ['id' => $id]);
        }

        DB::beginTransaction();
        try {
            // Actualizar orden de trabajo
            $swPause = $workorder->estado == 'S' ? '1' : '0'; // sw pause
            $workorder->historial .= '<br><b>[Enviado por '.userFullName(userId()).']</b> en fecha '.date('d/m/Y').' a la(s) '.date('H:i').'.';
            $workorder->estado = 'R';
            $workorder->update();

            // Tiempo de trabajo
            if($swPause == '0'){
                $timePendiente = $workorder->workTimes->where('end_work_date',null)->first();
                if($timePendiente != null){
                    $timePendiente->end_work_date = now();
                    $timePendiente->update();
                }
            }

            $flasher->addFlash('success', 'Enviar a revisión con éxito', 'Informe '.$workorder->cod);
            DB::commit();
            return Redirect()->route('reports.show', ['id' => $id]);
        } // termina el try
        catch (\Exception $e) {
            DB::rollback();
            DB::commit();
            return  \Response::json(['alerta' => '1', 'mensaje' => $e->getMessage()]);

        }
    }

    public function validateRevision(FlasherInterface $flasher, $id){
        $workorder = WorkOrders::findOrFail(decode($id));
        $workorder->estado = 'T';
        $workorder->update();

        $flasher->addFlash('success', 'Terminado con éxito', 'Informe '.$workorder->cod);
        return Redirect()->route('reports.show', ['id' => $id]);
    }

    public function rejectRevision(FlasherInterface $flasher, Request $request,$id){
        if( !isset($request->textrechazo)){
            $contid = '__archivos__';
            $flasher->addFlash('error', 'EL campo motivo es obligatorio', 'No permitido');

            return Redirect()->route('reports.show', ['id' => $id, 'contid' => $contid]);
        }

        $workorder = WorkOrders::findorFail(decode($id));
        $workorder->estado = 'C';
        $workorder->historial .= '<br><b>[Rechazado por '.userFullName(userId()).']</b> en fecha '.date('d/m/Y').' a la(s) '.date('H:i').'. <b>Motivo: </b> '.$request->textrechazo;
        $workorder->update();

        $contid = '__archivos__';
        $flasher->addFlash('error', 'Rechazado correctamente', 'Informe '.$workorder->cod);
        return Redirect()->route('reports.show', ['id' => $id, 'contid' => $contid]);
    }

    public function validateWorkorders(Request $request, $id = ''){
        $activo = 'activo';
        $emergency = 'emergency';
        $titulo = 'titulo';
        $formulario = 'formulario';
        $fecha_ven = 'fecha_ven';
        $prioridad = 'prioridad';
        $descripcion = 'descripcion';
        $tecresponsable = 'tecresponsable';
        $asignados = 'asignados';
        $fileWO = 'fileWO';

        $validateArray = [
            $activo => 'required',
            $emergency => 'nullable',
            $titulo => 'required|max:100',
            $formulario => 'nullable',
            $fecha_ven => 'required|date_format:d/m/Y H:i|after:today',
            $prioridad => 'required',
            $descripcion => 'required|max:1000',
            $tecresponsable => 'required',
            $asignados => 'nullable',
            $fileWO => 'mimes:gif,jpg,jpeg,png,pdf,mp4|max:5192',
        ];

        $aliasArray = [
            $activo => '<b>Activo</b>',
            $emergency => '<b>Emergencia</b>',
            $titulo => '<b>Título</b>',
            $formulario => '<b>Formulario</b>',
            $fecha_ven => '<b>Fecha programada de mantenimiento</b>',
            $prioridad => '<b>Prioridad</b>',
            $descripcion => '<b>Descripción</b>',
            $tecresponsable => '<b>Técnico responsable</b>',
            $asignados => '<b>Técnicos adicionales</b>',
            $fileWO => '<b>Archivo adjunto</b>',
        ];

        return $request->validate($validateArray, [], $aliasArray);
    }

    function endsWith($string, $endString) {
        $len = strlen($endString);
        if ($len == 0)  return true;
        return (substr($string, -$len) === $endString);
    }

    public function limpiar($string){
        $salida = preg_replace('/\s+/', ' ',$string);
        $salida = trim($salida);
        $salida = strtolower(str_replace(" ","_",$salida));
        $salida = is_numeric($salida) ? "_".$salida : $salida;
        $salida = delete_char_file($salida);
        $salida = cleanAll($salida);
        return $salida;
    }

    public function listWorkOrdersAjax(Request $request) {
        $estados = $request->estados;
        $request['search'] = limpiarTexto($request->search,'s2');

        $workorders = WorkOrders::
        CodTitle($request->search)
        ->PermisoVer()
        ->searchEstado($estados)
        ->orderBy('cod','desc')
        ->limit(20)
        ->get();
        $array = [];
        $array['results'][0]['id'] = '';
        $array['results'][0]['text'] = 'Seleccionar una opción';
        foreach ($workorders as $k => $wo) {
            $array['results'][$k + 1]['id'] = code($wo->id);
            $array['results'][$k + 1]['text'] = $wo->cod.' - '.$wo->titulo;
        }
        $array['pagination']['more'] = false;
        return response()->json($array);
    }
}
