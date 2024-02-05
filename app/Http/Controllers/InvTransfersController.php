<?php

namespace App\Http\Controllers;

use App\InvStocks;
use App\InvTransfers;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Session;

class InvTransfersController extends Controller
{
    public function index (Request $request){
        Session::put('item','4.3:');
        canPassAdminJefe();
        $selectEstado = $request->selectEstado != null ? $request->selectEstado : 'all';
        return view("inventory.transfers.index", compact('selectEstado'));
    }

    public function tableTransfers(Request $request){
        $columns = array(
            0 => 'cod',
            1 => 'name',
            2 => 'state',
            3 => 'operations',
        );
        $totalData = InvTransfers::count();
        $totalFiltered = $totalData;

        $limit =( empty($request->input('length'))  ) ? $limit = 10 : $limit = $request->input('length');
        $start =( empty($request->input('start'))  ) ? $start = 0 :  $start = $request->input('start');
        $order =( empty($request->input('order.0.column')) ) ? $order = 'id' : $order = $columns[$request->input('order.0.column')];
        $dir = ( empty($request->input('order.0.dir')) ) ? $dir = 'desc' : $dir = $request->input('order.0.dir');

        $posts = InvTransfers::Cod($request->input('columns.0.search.value'))
        ->Date($request->input('columns.1.search.value'))
        ->Item($request->input('columns.2.search.value'))
        ->Quantity($request->input('columns.3.search.value'))
        ->Origin($request->input('columns.4.search.value'))
        ->Destination($request->input('columns.5.search.value'))
        ->Solicitado($request->input('columns.6.search.value'))
        ->State($request->get('state'));

        $totalFiltered=$posts->count();
        $posts=$posts
        ->offset($start)
        ->limit($limit)
        ->orderBy($order,$dir)
        ->get();

        $data = array();
        foreach ($posts as $post){
            $nestedData['cod'] = '<a href="/transfers/showmodal/'.code($post->id).'" rel="modalShow">'.$post->cod.'</a>';
            $nestedData['date'] = date("d/m/Y", strtotime($post->date));
            $nestedData['item'] = $post->getItemsParts();
            $nestedData['quantity'] = $post->quantity;
            $nestedData['origin'] = $post->almacenOrigenLiteral;
            $nestedData['destination'] = $post->almacenDestinoLiteral;
            $nestedData['state'] = $post->getState(true);
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
        return view("inventory.transfers.modalCreate");
    }

    public function modalShow($id){
        $transfer = InvTransfers::findOrFail(decode($id));
        return view("inventory.transfers.modalShow", compact('transfer'));
    }

    public function ajaxLocations(Request $request){
        $query = $request->get('query');
        $origen = $destino = "";
        if (isset($query) && $query != "") {
            $stocks = InvStocks::selectRaw("*, SUM(incomes) as ingresos, SUM(outcomes) as egresos")->where('item_id',decode($query))->groupBy('location')->get();
            $origen = $destino = '<option value="">Ninguno</option>';
            foreach ($stocks as $stock) {
                if ( ($stock->ingresos - $stock->egresos) > 0 ){
                    $origen .= '<option value="'.code($stock->location).'">'.$stock->almacenLiteral." ⟹ ".($stock->ingresos - $stock->egresos).'</option>';
                }
            }
            foreach (getAlmacenes() as $idAlm=> $stAll) {
                $destino .= '<option value="'.code($idAlm).'">'.$stAll.'</option>';
            }
        }
        return response()->json(array('origen' =>$origen, 'destino' =>$destino), 200);
    }

    public function store(Request $request, FlasherInterface $flasher){
        $cant = "";
        $iditem = $request->itemedit;
        $stocks = InvStocks::selectRaw("*, SUM(incomes) as ingresos, SUM(outcomes) as egresos")
        ->where('item_id',decode($iditem))->groupBy('location')->get();
        foreach($stocks as $stock){
            if($stock->location == decode($request->origentransf)){
                $cant = $stock->ingresos - $stock->egresos;
                break;
            }
        }

        $messages = [
            'itemedit.required' => 'Debe escoger un material válido.',
            'origentransf.required' => 'El campo ubicación de origen es obligatorio.',
            'destinotransf.required' => 'El campo ubicación de destino es obligatorio.',
            'partedit.required' => 'Debe escoger una herramienta válida.',
            'origentransfparts.required' => 'El campo ubicación de origen es obligatorio.',
            'destinotransfparts.required' => 'El campo ubicación de destino es obligatorio.',
            'destinotransf.different' => 'La ubicación de destino debe ser diferente a la ubicación de origen.',
            'destinotransfparts.different' => 'La ubicación de destino debe ser diferente a la ubicación de origen.',
            'cantidadtransf.required' => 'El campo cantidad es obligatorio.',
            'cantidadtransf.lte' => 'La cantidad a traspasar debe ser menor o igual a la cantidad disponible en la ubicación origen <b>('.$cant.')</b>.',
        ];
        $validateArray = [
            'cantidadtransf' =>'bail|required',
            'observaciontransf'=>'nullable|min:2|max:255',
            'itemedit' => 'required',
            'origentransf' =>'required',
            'destinotransf' =>'required|different:origentransf',
        ];

        $validateCant = [
            'cantidadtransf' =>'bail|required|lte:'.$cant,
        ];

        if($cant != ""){
            $validateArray = array_merge($validateArray, $validateCant);
        }

        $request->validate($validateArray, $messages);
        $reg_maximo = InvTransfers::select('cod')->where('cod', 'LIKE', "%TR%")->max('cod');
        $cod = generateCode($reg_maximo,'TR000001','TR',2,6);
        $transfer = new InvTransfers();
        $transfer->cod = $cod;
        $transfer->item_id = decode($iditem);
        $transfer->origin_location = decode($request->origentransf);
        $transfer->destination_location = decode($request->destinotransf);
        $transfer->quantity = monedaVal($request->cantidadtransf);
        $transfer->solicitado_id = userId();
        $transfer->date = now();
        $transfer->state = 1;
        $transfer->observation = $request->observaciontransf;
        $transfer->save();
        $flasher->addFlash('success', 'Pendiente de autorización', 'Traspaso registrado con éxito');
        return  \Response::json(['success' => '1']);
    }

    public function modalState(Request $request, $id){
        $transfer = InvTransfers::findOrFail(decode($id));
        if($transfer->state == 1){
            $stocks = InvStocks::selectRaw("*, SUM(incomes) as ingresos, SUM(outcomes) as egresos")->where('item_id',$transfer->item_id)->groupBy('location')->get();
            $swdisp = $total = 0;
            foreach ($stocks as $stock){
                if($stock->location == $transfer->origin_location && ($stock->ingresos - $stock->egresos) < $transfer->quantity ){
                    $swdisp = 1;
                    $total = $stock->ingresos - $stock->egresos;
                    break;
                }
            }

            return view("inventory.transfers.modalState", compact('stocks','transfer','swdisp','total'));
        }else abort(404);
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

        if($request->checkstate == '0') $validateArray = array_merge($validateArray,$validateAnul);
        $request->validate($validateArray, $messages);

        $transfer = InvTransfers::findOrFail(decode($id));
        DB::beginTransaction();
        try {
            if($transfer->state == 1){
                $stocks = InvStocks::selectRaw("*, SUM(incomes) as ingresos, SUM(outcomes) as egresos")->where('item_id',$transfer->item_id)->groupBy('location')->get();
                $swdisp = $total = 0;
                foreach ($stocks as $stock){
                    if($stock->location == $transfer->origin_location && ($stock->ingresos - $stock->egresos) < $transfer->quantity ){
                        $swdisp = 1;
                        $total = $stock->ingresos - $stock->egresos;
                        break;
                    }
                }
                $transfer->state = $request->checkstate;
                $transfer->message = $request->motivo;
                $transfer->autorizado_id = userId();
                $transfer->update();
                if($swdisp == 0 && $transfer->state == 2){
                    // Actualizar la tabla de stocks
                    // Origen
                    $stock = new InvStocks();
                    $stock->item_id = $transfer->item_id;
                    $stock->outcomes = $transfer->quantity;
                    $stock->origen_type = 'A3';
                    $stock->origen_id = $transfer->id;
                    $stock->location = $transfer->origin_location;
                    $stock->date = now();
                    $stock->save();
                    // Destino
                    $stock = new InvStocks();
                    $stock->item_id = $transfer->item_id;
                    $stock->incomes = $transfer->quantity;
                    $stock->origen_type = 'A3';
                    $stock->origen_id = $transfer->id;
                    $stock->location = $transfer->destination_location;
                    $stock->date = now();
                    $stock->save();
                    $flasher->addFlash('success', 'Autorizado con éxito', 'Traspaso');
                }elseif($transfer->state == 0){
                    $flasher->addFlash('error', 'Anulado correctamente', 'Traspaso');
                }else{
                    $flasher->addFlash('warning', 'La cantidad a traspasar <b>'. $transfer->quantity .'</b> sobrepasa a la cantidad disponible en la ubicación de origen "'. $transfer->origins->nombre .'" <b>'. number_format($total,2,".","") .'</b>.', 'No puede autorizar el traspaso');
                }
            }
            DB::commit();
            return  \Response::json(['success' => '1']);
        }catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
}
