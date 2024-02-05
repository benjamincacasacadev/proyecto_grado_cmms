<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\InvOutcomes;
use App\InvOutcomesDetails;
use App\InvStocks;
use App\WorkOrders;
use Carbon\Carbon;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class InvOutcomesController extends Controller
{
    public function index (Request $request){
        $selectEstado = $request->selectEstado != null ? $request->selectEstado : '';
        Session::put('item','4.1:');
        return view("inventory.outcomes.index", compact('selectEstado'));
    }

    public function tableOutcomes(Request $request){
        $totalData = InvOutcomes::count();
        $totalFiltered = $totalData;

        $limit = empty($request->input('length')) ? 10 : $request->input('length');
        $start = empty($request->input('start'))  ? 0  : $request->input('start');

        $posts = InvOutcomes::
        Cod($request->input('columns.0.search.value'))
        ->Date($request->input('columns.1.search.value'))
        ->Reason($request->input('columns.2.search.value'))
        ->WorkOrders($request->input('columns.3.search.value'))
        ->DeliveryDate($request->input('columns.4.search.value'))
        ->PermisoVerOTs()
        ->State($request->get('state'))
        ->with('workorders');

        $totalFiltered = $posts->count();
        $posts = $posts
        ->offset($start)
        ->limit($limit)
        ->orderBy('id', 'desc')
        ->get();

        $data = array();
        foreach ($posts as $post){
            $nestedData['cod'] = $post->getCod();
            $nestedData['date'] = date("d/m/Y", strtotime($post->date));
            $nestedData['reason'] = $post->reason;
            $nestedData['workorders'] = isset($post->workorders) ? $post->workorders->getCod() : "";
            $nestedData['delivery_date'] = date("d/m/Y", strtotime($post->delivery_date));
            $nestedData['state'] = $post->getState(true);
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

    public function modalCreate(){
        return view("inventory.outcomes.modalCreate");
    }

    public function store(Request $request, FlasherInterface $flasher){
        $validateArray = [
            'ordentrabajo' =>'required',
            'motivo' =>'required|max:100',
            'fecha' =>'required|date_format:d/m/Y',
            'observacion' =>'nullable|max:255',
        ];

        $aliasArray = [
            'ordentrabajo' => '<b>Orden de trabajo asociada</b>',
            'motivo' => '<b>Motivo</b>',
            'fecha' => '<b>Fecha de entrega</b>',
            'observacion' => '<b>Observación</b>',
        ];

        $request->validate($validateArray, [], $aliasArray);

        $workorder = WorkOrders::findOrFail(decode($request->ordentrabajo));
        $estNoPerm = ['P','E','S'];
        if(!in_array($workorder->estado, $estNoPerm)){
            $flasher->addFlash('warning', 'La orden de trabajo seleccionada no esta en estado permitido', 'No se pudo crear la solicitud');
            return  \Response::json(['success' => '1']);
        }

        $fechaSave = Carbon::createFromFormat('d/m/Y', $request->fecha);

        $reg_maximo = InvOutcomes::select('cod')->where('cod', 'LIKE', "%PE%")->max('cod');
        $cod = generateCode($reg_maximo,'PE000001','PE',2,6);
        $outcome = new InvOutcomes();
        $outcome->cod = $cod;
        $outcome->wo_id = decode($request->ordentrabajo);
        $outcome->reason = $request->motivo;
        $outcome->solicitado_id = userId();
        $outcome->date = now();
        $outcome->delivery_date = $fechaSave;
        $outcome->observation = $request->observacion;
        $outcome->state = 1;
        $outcome->save();

        $urlReload = '/outcomes/show/'.code($outcome->id);
        $flasher->addFlash('success', 'Creada con éxito', 'Solicitud '.$outcome->cod);
        return  \Response::json(['success' => '1','urlReload' => $urlReload]);
    }

    public function modalEdit($id){
        $outcome = InvOutcomes::findOrFail(decode($id));
        if($outcome->state == 1){
            return view("inventory.outcomes.modalEditOutcome", compact('outcome'));
        }
    }

    public function update(Request $request,FlasherInterface $flasher, $id){
        $validateArray = [
            'ordentrabajoedit' =>'required',
            'motivoedit' =>'required|max:100',
            'fechaedit' =>'required|date_format:d/m/Y',
            'observacionedit' =>'nullable|max:255',
        ];

        $aliasArray = [
            'ordentrabajoedit' => '<b>Orden de trabajo asociada</b>',
            'motivoedit' => '<b>Motivo</b>',
            'fechaedit' => '<b>Fecha de entrega</b>',
            'observacionedit' => '<b>Observación</b>',
        ];

        $request->validate($validateArray, [], $aliasArray);

        $outcome = InvOutcomes::findOrFail(decode($id));

        $workorder = WorkOrders::findOrFail(decode($request->ordentrabajoedit));
        if($workorder->estado != "P"){
            $flasher->addFlash('warning', 'La orden de trabajo seleccionada no esta en estado pendiente', 'No se pudo crear la solicitud');
            return  \Response::json(['success' => '1']);
        }

        if($outcome->state == 1){
            $fechaUpdate = Carbon::createFromFormat('d/m/Y', $request->fechaedit);

            $outcome->wo_id = decode($request->ordentrabajoedit);
            $outcome->reason = $request->motivoedit;
            $outcome->delivery_date = $fechaUpdate;
            $outcome->observation = $request->observacionedit;
            $outcome->update();
            $flasher->addFlash('info', 'Modificada con éxito', 'Solicitud '.$outcome->cod);
        }
        return  \Response::json(['success' => '1']);
    }

    public function modalDelete($id){
        $outcome = InvOutcomes::findOrFail(decode($id));
        if($outcome->state == 1){
            return view("inventory.outcomes.modalDeleteOutcome", compact('outcome'));
        }
    }

    public function destroy(FlasherInterface $flasher, $id){
        $outcome = InvOutcomes::findOrFail(decode($id));
        if($outcome->state == 1 && $outcome->getCantDetails() == 0 ){
            $outcome->delete();
            $flasher->addFlash('error', 'Eliminada correctamente', 'Solicitud '.$outcome->cod);
        }else{
            $flasher->addFlash('warning', 'Revise los datos y vuelva a intentarlo', 'No se eliminó el registro');
        }
        return redirect()->route('outcomes.index');
    }

    public function show($id){
        $outcome = InvOutcomes::findOrFail(decode($id));
        permisoAdminOTs($outcome->wo_id);
        Session::put('item','4.1:');
        return view("inventory.outcomes.show", compact('outcome'));
    }

        // =============================================================================
    //                          CAMBIO DE ESTADO PEDIDOS
    // =============================================================================
    public function modalState($id){
        $outcome = InvOutcomes::findOrFail(decode($id));
        if($outcome->state != 1){
            abort(404);
        }
        // Verificar si existe la cantidad necesaria en inventario
        $swcant = $swloc = 0;
        $details = InvOutcomesDetails::where('outcome_id',$outcome->id)->get();
        foreach($details as $detail){
            if($detail->quantity > $detail->items->quantity)    $swcant = 1;
            if(!isset($detail->location) || $detail->location = "") $swloc = 1;
        }
        return view("inventory.outcomes.modalState", compact('outcome','swcant','swloc'));
    }

    public function updateState(Request $request, FlasherInterface $flasher, $id){
        $messages = [
            'checkstate.required' => 'Debe escoger una opción válida',
        ];
        $validateArray = [
            'checkstate' =>'required',
        ];
        $validateAnul = [
            'motivo' =>'required',
        ];
        if($request->checkstate == '0'){
            $validateArray = array_merge($validateArray,$validateAnul);
        }
        $request->validate($validateArray, $messages);

        $outcomes = InvOutcomes::findOrFail(decode($id));
        $outcomes->state = $request->checkstate;

        // Verificar si existe la cantidad necesaria en inventario
        $swcant = $swloc = 0;
        $details = InvOutcomesDetails::where('outcome_id',$outcomes->id)->get();
        foreach($details as $detail){
            if($detail->quantity > $detail->items->quantity)    $swcant = 1;
            if(!isset($detail->location) || $detail->location = "") $swloc = 1;
        }

        DB::beginTransaction();
        try {
            if($swcant == 0 && $swloc == 0){
                $outcomes->message = $request->motivo;
                $outcomes->update();
                if($outcomes->state == 2){
                    $flasher->addFlash('success', 'Validada con éxito', 'Solicitud '.$outcomes->cod);
                    $details = InvOutcomesDetails::where('outcome_id',$outcomes->id)->get();
                    foreach ($details as $detail) {
                        $item = Inventory::find($detail->item_id);
                        if(isset($item)){
                            // Actualizar la tabla de stocks (Ubicación)
                            $stock = new InvStocks();
                            $stock->item_id = $detail->item_id;
                            $stock->outcomes = $detail->quantity;
                            $stock->origen_type = 'A2';
                            $stock->origen_id = $detail->id;
                            $stock->location = $detail->location;
                            $stock->date = now();
                            $stock->save();
                            // Actualizar cantidad de items
                            $item->quantity = $item->quantity - $detail->quantity;
                            $item->update();
                        }
                    }
                    $flasher->addFlash('success', 'Retirados correctamente', 'Cantidad de materiales seleccionados');
                }elseif($outcomes->state == 0){
                    $flasher->addFlash('error', 'Anulada correctamente', 'Solicitud '.$outcomes->cod);
                }
            }elseif($outcomes->state == 0){
                $outcomes->message = $request->motivo;
                $outcomes->update();
                $flasher->addFlash('error', 'Anulada correctamente', 'Solicitud '.$outcomes->cod);

            }else{
                $flasher->addFlash('warning', 'No se puede cambiar de estado', 'Error');
            }
            DB::commit();
            return  \Response::json(['success' => '1']);
        }catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    public function ajaxClientWorkorders(Request $request){
        $clientename = "Sin cliente";
        $fecha = "Sin fecha";
        $query = $request->get('query');
        if ($request->get('query') && $query != "") {
            $workorder = WorkOrders::find(decode($query));
            $clientename = isset($workorder->asset->cliente->nombre) ? $workorder->asset->cliente->nombre : "Sin cliente";
            $fecha = isset($workorder->fecha) ? date("d/m/Y",strtotime($workorder->fecha)) : "Sin fecha";
        }
        return response()->json(array('cliente' =>$clientename,'fecha'=>$fecha), 200);
    }
}
