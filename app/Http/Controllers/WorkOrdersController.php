<?php

namespace App\Http\Controllers;

use App\User;
use App\WorkOrders;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;
use DB;
use Session;

class WorkOrdersController extends Controller
{
    public function index(Request $request){
        $selectEstado = $request->selectEstado != null ? $request->selectEstado : '';
        $users = User::where('active','1')->get();
        Session::put('item','1.');
        return view('work_orders.index', compact('users','selectEstado'));
    }

    public function create(){
        $users = User::where('active','1')->get();
        Session::put('item','1.');
        return view('work_orders.create', compact('users'));
    }

    public function store(Request $request, FlasherInterface $flasher) {
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
            $workorder->form_id = $request->formulario;
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
        ->Activo($request->input('columns.1.search.value'))
        ->Estado($request->state)
        ->Titulo($request->input('columns.3.search.value'))
        ->Cliente($request->input('columns.4.search.value'))
        ->TecnicoId($request->input('columns.5.search.value'))
        ->Descripcion($request->input('columns.6.search.value'))
        ->Prioridad($request->input('columns.7.search.value'))
        ->Fecha($request->input('columns.8.search.value'))
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
            $nestedData['activo'] = $post->asset->cod.' '.$post->asset->name;
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
        $workorder = WorkOrders::findOrFail(decode($id));
        if(!$workorder->canEdit){
            abort(403);
        }
        $users = User::where('active','1')->get();
        return view('work_orders.modalEdit', compact('workorder','users'));
    }

    public function update(Request $request, FlasherInterface $flasher, $id) {
        $this->validateWorkorders($request, $id);

        $fechaSave = Carbon::createFromFormat('d/m/Y H:i', $request->fecha_ven);

        DB::beginTransaction();
        try {
            $workorder = WorkOrders::findOrFail(decode($id));
            $workorder->asset_id = decode($request->activo);
            $workorder->form_id = $request->formulario;
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
        $workorder = WorkOrders::findOrFail(decode($id));
        if(!$workorder->canEdit){
            abort(403);
        }
        $users = User::where('active','1')->get();
        return view('work_orders.modalDelete', compact('workorder','users'));
    }

    public function destroy(FlasherInterface $flasher, $id) {
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
}
