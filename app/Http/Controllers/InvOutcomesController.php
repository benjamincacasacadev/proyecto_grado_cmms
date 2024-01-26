<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\InvOutcomes;
use App\InvOutcomesDetails;
use App\InvStocks;
use App\User;
use App\WorkOrders;
use Carbon\Carbon;
use Flasher\Laravel\Facade\Flasher;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class InvOutcomesController extends Controller
{
    public function index (Request $request){
        $selectEstado = $request->selectEstado != null ? $request->selectEstado : 'all';
        Session::put('item','4.0:2|');
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
        if($workorder->estado != "P"){
            $flasher->addFlash('warning', 'La orden de trabajo seleccionada no esta en estado pendiente', 'No se pudo crear la solicitud');
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
            $outcome->autorizador_id = decode($request->autorizadoredit);
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
