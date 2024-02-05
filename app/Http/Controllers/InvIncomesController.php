<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\InvIncomes;
use App\InvIncomesDetails;
use App\InvStocks;
use Illuminate\Http\Request;
use Session;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\DB;

class InvIncomesController extends Controller
{
    public function index (Request $request){
        canPassAdminJefe();
        $selectEstado = $request->selectEstado != null ? $request->selectEstado : '';
        Session::put('item','4.2:');
        return view("inventory.incomes.index", compact('selectEstado'));
    }

    public function tableIncomes(Request $request){
        $columns = array(
            0 => 'cod',
            1 => 'name',
            2 => 'state',
            3 => 'operations',
        );
        $totalData = InvIncomes::count();
        $totalFiltered = $totalData;

        $limit =( empty($request->input('length'))  ) ? $limit = 10 : $limit = $request->input('length');
        $start =( empty($request->input('start'))  ) ? $start = 0 :  $start = $request->input('start');
        $order =( empty($request->input('order.0.column')) ) ? $order = 'COD' : $order = $columns[$request->input('order.0.column')];
        $dir = ( empty($request->input('order.0.dir')) ) ? $dir = 'desc' : $dir = $request->input('order.0.dir');

        $posts = InvIncomes::Cod($request->input('columns.0.search.value'))
        ->Date($request->input('columns.1.search.value'))
        ->Origin($request->input('columns.2.search.value'))
        ->Observation($request->input('columns.4.search.value'))
        ->Solicitado($request->input('columns.5.search.value'))
        ->State($request->get('state'));

        $totalFiltered = $posts->count();
        $posts = $posts
        ->offset($start)
        ->limit($limit)
        ->orderBy($order,$dir)
        ->get();

        $data = array();
        foreach ($posts as $post){
            $nestedData['cod'] = $post->getCod();
            $nestedData['date'] = date("d/m/Y", strtotime($post->date));
            $nestedData['origin'] = $post->getOrigin();
            $nestedData['reason'] =  purify(nl2br($post->reason));
            $nestedData['cant'] = $post->getCantDetails();
            $nestedData['observation'] = purify(nl2br($post->observation));
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

    public function store(Request $request, FlasherInterface $flasher){
        $validateArray = [
            'origen' =>'required|max:100',
            'observacion' =>'nullable|min:2|max:1000',
        ];
        $aliasArray = [
            'origen' => '<b>Origen</b>',
            'observacion' => '<b>Observación</b>',
        ];

        $request->validate($validateArray, [], $aliasArray);


        $reg_maximo = InvIncomes::select('cod')->where('cod', 'LIKE', "%NI%")->max('cod');
        $cod = generateCode($reg_maximo,'NI000001','NI',2,6);
        $incomes = new InvIncomes();
        $incomes->cod = $cod;
        $incomes->origin = $request->origen;
        $incomes->observation = $request->observacion;
        $incomes->ingresado_id = userId();
        $incomes->state = 1;
        $incomes->date = now();
        $incomes->save();

        $urlReload = '/incomes/'.code($incomes->id);
        $flasher->addFlash('success', 'Creada con éxito', 'Nota de ingreso '.$incomes->cod);
        return  \Response::json(['success' => '1','urlReload' => $urlReload]);
    }

    public function show($id){
        canPassAdminJefe();
        $income = InvIncomes::findOrFail(decode($id));
        Session::put('item','4.2:');
        return view("inventory.incomes.show", compact('income'));
    }

    public function modalEdit($id){
        $income = InvIncomes::findOrFail(decode($id));
        if($income->state == 1){
            return view("inventory.incomes.modalEditIncome", compact('income'));
        }
        abort(404);
    }

    public function update(Request $request, FlasherInterface $flasher, $id){
        $validateArray = [
            'origenedit' =>'required|max:100',
            'observacionedit' =>'nullable|min:2|max:1000',
        ];
        $aliasArray = [
            'origenedit' => '<b>Origen</b>',
            'observacionedit' => '<b>Observación</b>',
        ];
        $request->validate($validateArray, [], $aliasArray);

        $incomes = InvIncomes::findOrFail(decode($id));
        if($incomes->state == 1){
            $incomes->origin = $request->origenedit;
            $incomes->observation = $request->observacionedit;
            $incomes->save();
            $flasher->addFlash('info', 'Modificada con éxito', 'Nota de ingreso '.$incomes->cod);
        }
        return  \Response::json(['success' => '1']);
    }

    public function modalDelete($id){
        $income = InvIncomes::findOrFail(decode($id));
        if($income->state == 1){
            return view("inventory.incomes.modalDeleteIncome", compact('income'));
        }
        abort(404);
    }

    public function destroy(FlasherInterface $flasher, $id){
        $income = InvIncomes::findOrFail(decode($id));
        if($income->state == 1 && $income->getCantDetails() == 0 ){
            $income->delete();
            $flasher->addFlash('error', 'Eliminada correctamente', 'Nota de ingreso '.$income->cod);
        }else{
            $flasher->addFlash('warning', 'No se eliminó el registro', 'Error');
        }
        return redirect()->route('incomes.index');
    }

    public function modalState(Request $request, $id){
        $income = InvIncomes::findOrFail(decode($id));
        if($income->state == 1){
            return view("inventory.incomes.modalState", compact('income'));
        }
        abort(404);
    }

    public function updateState(Request $request, FlasherInterface $flasher, $id){

        $incomes = InvIncomes::findOrFail(decode($id));

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

        $incomes->state = $request->checkstate;
        $incomes->message = $request->motivo;
        $incomes->update();


        DB::beginTransaction();
        try {
            if($incomes->state == 2){
                $flasher->addFlash('success', 'Validada con éxito', 'Nota de ingreso '.$incomes->cod);
                $details = InvIncomesDetails::where('income_id',$incomes->id)->orderBy('id')->get();
                foreach ($details as $k=>$detail) {
                    $item = Inventory::find($detail->item_id);
                    if(isset($item)){
                        // Actualizar la tabla de stocks (Ubicación)
                        $stock = new InvStocks();
                        $stock->item_id = $detail->item_id;
                        $stock->incomes = $detail->quantity;
                        $stock->origen_type = 'A1';
                        $stock->origen_id = $detail->id;
                        $stock->location = $detail->location;
                        $stock->date = now();
                        $stock->save();
                        $item->quantity = $item->quantity + $detail->quantity;
                        $item->update();
                    }
                }
                $flasher->addFlash('success', 'Retirados correctamente', 'Cantidad de items '.$incomes->cod);
            }
            elseif($incomes->message == 0){
                $flasher->addFlash('error', 'Anulada correctamente', 'Nota de ingreso '.$incomes->cod);
            }
            DB::commit();
            return  \Response::json(['success' => '1']);
        }catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
}
