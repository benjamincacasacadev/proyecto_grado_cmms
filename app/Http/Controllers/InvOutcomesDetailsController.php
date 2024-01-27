<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\InvOutcomes;
use App\InvOutcomesDetails;
use App\InvStocks;
use Flasher\Prime\FlasherInterface;
use Illuminate\Http\Request;

class InvOutcomesDetailsController extends Controller
{
    public function tableDetails(Request $request){
        $idoutcome = $request->get('outcome');
        $totalData = InvOutcomesDetails::where('outcome_id',decode($idoutcome))->count();
        $totalFiltered = $totalData;

        $posts = InvOutcomesDetails::where('outcome_id',decode($idoutcome));
        $posts = $posts->orderBy('id')->get();
        $totalFiltered=$posts->count();
        $data = array();

        $aa = 0;
        foreach ($posts as $k=>$post) {
            $nestedData['DT_RowId'] = $post->id;
            $nestedData['orden'] = ++$aa;
            $nestedData['nro'] = ++$k;
            $nestedData['item'] = '<span class="text-primary font-weight-bold" style="font-size:15px">'. $post->items->CodAppend. '</span>';
            $nestedData['itemmodal'] = $post->items->CodAppend;
            $nestedData['cant'] = $post->checkQuantity();
            $nestedData['report'] = $post->outcomes->workorders->getCod();
            $nestedData['location'] = $post->getLocationEditable();
            $nestedData['locationmodal'] = $post->getLocations();
            $nestedData['observation'] = purify(nl2br($post->destination));
            $nestedData['operations'] = $post->getOperations();
            $nestedData['unit_cost'] = $post->getUnitCost();

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

    public function storeDetails(Request $request, FlasherInterface $flasher, $id){
        $outcome = InvOutcomes::findOrFail(decode($id));
        if($outcome->state == 1){
            $messages = [
                'item.required'         => 'Debe escoger una opción válida',
                'cantidad.required'     => 'El campo es requerido',
                'cantidad.gt'       => 'El campo cantidad debe ser mayor a 0',
                'destino.min'       => 'Debe tener al menos 2 caracteres',
                'destino.max'       => 'No debe tener más de 200 caracteres',
            ];
            $validateArray = [
                'item' =>'required',
                'cantidad' =>'required|gt:0',
                'destino' => 'required|min:2|max:100',
            ];
            $request->validate($validateArray, $messages);

            $details = new InvOutcomesDetails();
            $details->outcome_id = decode($id);
            $details->item_id = decode($request->item);
            $details->quantity = monedaVal($request->cantidad);
            $details->destination = $request->destino;
            $details->save();
            $flasher->addFlash('success', 'Creado con éxito', 'Detalle');
        }
        return  \Response::json(['success' => '1']);
    }

    public function modalEdit(Request $request, $id){
        $detail = InvOutcomesDetails::findOrFail(decode($id));
        if($detail->outcomes->state == 1){
            return view("inventory.outcomes.modalEditDetail", compact('detail'));
        }
    }

    public function updateDetails(Request $request, FlasherInterface $flasher, $id){
        $messages = [
            'itemedit.required'         => 'Debe escoger una opción válida',
            'cantidadedit.required'     => 'El campo cantidad es requerido',
            'cantidadedit.gt'     => 'El campo cantidad debe ser mayor a 0',
            'destinoedit.required'    => 'El campo destino es requerido',
        ];
        $validateArray = [
            'itemedit' =>'required',
            'cantidadedit' =>'required|gt:0',
            'destinoedit' =>'required',
        ];
        $request->validate($validateArray, $messages);

        $detail =InvOutcomesDetails::findOrFail(decode($id));
        if($detail->outcomes->state != 1){
            return  \Response::json(['success' => '1']);
        }

        $cantidad = '';
        if(isset($detail->location)){
            $cantDisp = InvStocks::selectRaw("*, SUM(incomes) as ingresos, SUM(outcomes) as egresos")
            ->where('item_id',$detail->item_id)
            ->where('location',$detail->location)
            ->groupBy('location')->first();

            $allDetails = InvOutcomesDetails::
            where('id','!=',$detail->id)
            ->where('outcome_id',$detail->outcome_id)
            ->where('item_id',$detail->item_id)
            ->where('location',$detail->location)->get();
            $disp = 0;
            foreach ($allDetails as $allDet) {
                $disp = $disp + $allDet->quantity;
            }
            $cantidad = $cantDisp->ingresos - $cantDisp->egresos - $disp;
        }

        // Borrar campo de Ubicación si es que se cambia la cantidad
        $cantAntes = $detail->quantity;

        $cantDespues = number_format($request->cantidadedit,2);
        if($cantAntes <> $cantDespues){
            if($cantidad != '' && $cantidad <= $cantDespues){
                $detail->location = null;
                $flasher->addFlash('info', 'Debe volver a establecer un almacen para el detalle', 'Información');
            }
        }
        //Actualizar campos
        $detail->item_id = decode($request->itemedit);
        $detail->quantity = monedaVal($request->cantidadedit);
        $detail->destination = $request->destinoedit;
        $detail->update();
        $flasher->addFlash('success', 'Modificado con éxito', 'Detalle');
        return  \Response::json(['success' => '1']);
    }

    public function modalDelete(Request $request, $id){
        $detail = InvOutcomesDetails::findOrFail(decode($id));
        if($detail->outcomes->state != 1){
            abort(404);
        }
        return view("inventory.outcomes.modalDeleteDetail", compact('detail'));
    }

    public function destroyDetails(FlasherInterface $flasher, $id){
        $detail =InvOutcomesDetails::findOrFail(decode($id));
        if($detail->outcomes->state != 1){
            return redirect()->route('outcomes.show',code($detail->outcome_id));
        }
        $detail->delete();
        $flasher->addFlash('error', 'Eliminado con éxito', 'Detalle');
        return redirect()->route('outcomes.show',code($detail->outcome_id));
    }

    // =============================================================================
    //                          MODAL Y LISTA DE ITEMS PARA EDIT
    // =============================================================================
    public function modalItems(){
        return view("inventory.outcomes.modalItems");
    }

    public function tableItemsEdit(Request $request){
        $columns = array(
            0 => 'cod',
            1 => 'name',
            2 => 'state',
            3 => 'operations',
        );
        $totalData = Inventory::where('active',1)->count();
        $totalFiltered = $totalData;

        $limit =( empty($request->input('length'))  ) ? $limit = 10 : $limit = $request->input('length');
        $start =( empty($request->input('start'))  ) ? $start = 0 :  $start = $request->input('start');
        $order =( empty($request->input('order.0.column')) ) ? $order = 'id' : $order = $columns[$request->input('order.0.column')];
        $dir = ( empty($request->input('order.0.dir')) ) ? $dir = 'desc' : $dir = $request->input('order.0.dir');

        $search = $request->input('search.value');
        $posts = Inventory::where('active',1)
        ->where(function ($q) use ($search){
            $q->where('cod','LIKE',"%{$search}%")
            ->orwhere('title','LIKE',"%{$search}%")
            ->orwhere('quantity','LIKE',"%{$search}%");
        });

        $totalFiltered=$posts->count();
        $posts=$posts
        ->offset($start)
        ->limit($limit)
        ->orderBy($order,$dir)
        ->get();

        $data = array();
        foreach ($posts as $post){

            $routeAttach = storage_path('app/public/inventory/'.$post->attach);
            $imagen = '<img src="/storage/thumbnail/noimage.png?'.rand().'" style="max-width: 45px;">';
            if (isset($post->attach) && file_exists($routeAttach)){
                $imagen =
                '<a href="/storage/inventory/'.$post->attach.'" target="_blank">
                    <img src="/storage/inventory/thumbnail/'.$post->attach.'" style="max-width:45px" alt="Sin imagen para mostrar">
                </a>';
            }

            $datos=
            "<span >
                <span style='color:#A6ACAF;'>Código: <span class='text-primary'>".$post->getCod()."</span><br>
                <span style='color:#A6ACAF;'>Item: </span><span class='text-dark'>".$post->title."</span><br>
                <span style='color:#A6ACAF;'>Cantidad Disponible: </span><span class='text-dark font-weight-bold' style='font-size:15px'>".$post->getQuantity()."</span>
            </span>";

            $class = $post->quantity == 0 ? 'danger' : 'primary';
            $boton = '<button type="button" class="modalbtn border border-'.$class.' btn btn-outline-'.$class.'" data-cod="'.$post->cod.' - '.$post->title.'" id="'.code($post->id).'" data-cant="'.$post->quantity.'">Seleccionar</button>';
            $nestedData['imagen'] = $imagen;
            $nestedData['datos'] = $datos;
            $nestedData['button'] = $boton;
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
    // =============================================================================
    //                          UPDATE Ubicación XEDITABLE
    // =============================================================================
    public function updateLocation(Request $request){
        $row = InvOutcomesDetails::find($request->pk);
        // Comprobar que existan items disponibles en la Ubicación seleccionada
        $detail = InvStocks::selectRaw("*, SUM(incomes) as ingresos, SUM(outcomes) as egresos")
        ->where('item_id',$row->item_id)
        ->where('location',$request->value)
        ->groupBy('location')->first();
        if(isset($detail)){
            $cant = $row->quantity;
            $disp = $detail->ingresos - $detail->egresos;
            $allDetails = InvOutcomesDetails::
            where('id','!=',$request->pk)
            ->where('outcome_id',$row->outcome_id)
            ->where('item_id',$row->item_id)
            ->where('location',$request->value)->get();
            foreach ($allDetails as $allDet) {
                $disp = $disp - $allDet->quantity;
            }

            if($cant > $disp){
                $msjerror = 'La cantidad ingresada es mayor a la cantidad disponible que tiene el material en el almacen seleccionado.<br>';
                return  \Response::json(['error' => $msjerror]);
            }

            $column_name = $request->name;
            $column_value = $request->value;
            $row->update([$column_name => $column_value]);
            return  \Response::json(['success' => '1']);
        }
        return  \Response::json(['error' => 'El almacen es requerida']);
    }
    // JSON para obtener la lista de Ubicación para  el select de xeditable
    public function locationsList($id){
        $invDetail = InvOutcomesDetails::find(decode($id));
        $details = InvStocks::selectRaw("*, SUM(incomes) as ingresos, SUM(outcomes) as egresos")->where('item_id',$invDetail->item_id)->groupBy('location')->get();
        $array = [];
        $array[0]['id'] = "X";
        $array[0]['text'] = "Seleccione una opción";
        foreach ($details as $k=>$detail){
            $allDetails = InvOutcomesDetails::
            where('outcome_id',$invDetail->outcome_id)
            ->where('item_id',$invDetail->item_id)
            ->where('location',$detail->location)->get();
            $disp = 0;
            foreach ($allDetails as $allDet) {
                $disp = $disp + $allDet->quantity;
            }
            $array[$k+1]['value'] = $detail->location;
            $array[$k+1]['text'] = $detail->almacenLiteral." ⟹ ".($detail->ingresos - $detail->egresos - $disp);
        }
        return response()->json($array);
    }

    public function order(Request $request){
        // ID DEL DETALLE DEL ITEM
        $id_det=$request->get('id_item');
        // VALIDADANDO QUE EXISTA EL ITEM
        if ($request->get('id_item')!=null) {
            // INSTANCIANDO EL DETALLE
            $detail = InvOutcomesDetails::findOrFail($id_det);
            if($detail->outcomes->empresa_id == empresaId() && $detail->outcomes->state == 1){
                $detail->order=$request->get('itemIndex');
                $detail->update();
            }
        }
    }
}
