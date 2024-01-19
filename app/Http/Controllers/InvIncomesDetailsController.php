<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inventory;
use App\InvIncomes;
use App\InvIncomesDetails;
use App\InvParameters;
use App\InvParts;
use App\InvStocks;
use Flasher\Prime\FlasherInterface;

class InvIncomesDetailsController extends Controller
{
    public function tableDetails(Request $request){
        $idincome = $request->get('income');
        $asoc = $request->get('asociado');
        $totalData = InvIncomesDetails::where('income_id',decode($idincome))->count();
        $totalFiltered = $totalData;

        $posts = InvIncomesDetails::where('income_id',decode($idincome));
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
            $nestedData['cant'] = $post->quantity;
            $nestedData['unit_cost'] = $post->getUnitCost();

            $nestedData['location'] = $post->almacenLiteral;
            $nestedData['observation'] = purify(nl2br($post->observation));
            $nestedData['operations'] = $post->getOperations($asoc);
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
        $income = InvIncomes::findOrFail(decode($id));
        if($income->state == 1){
            $messages = [
                'item.required'         => 'Debe escoger una opción válida',
                'cantidad.required'     => 'El campo es requerido',
                'ubicacion.required'    => 'Debe escoger una opción válida',
                'observacion.min'       => 'Debe tener al menos 2 caracteres',
                'observacion.max'       => 'No debe tener más de 200 caracteres',
            ];
            $validateArray = [
                'item' =>'required',
                'cantidad' =>'required',
                'ubicacion' =>'required',
                'observacion' => 'nullable|min:2|max:100',
            ];
            $request->validate($validateArray, $messages);

            $details = new InvIncomesDetails();
            $details->income_id = decode($id);
            $details->item_id = decode($request->item);
            $details->location = $request->ubicacion;
            $details->quantity = monedaVal($request->cantidad);
            $details->observation = $request->observacion;
            $details->save();
            $flasher->addFlash('success', 'Registrado con éxito', 'Detalle');
        }
        return  \Response::json(['success' => '1']);
    }

    public function modalEdit(Request $request, $id, $sw){
        $detail = InvIncomesDetails::findOrFail(decode($id));
        if($detail->incomes->state == 1){
            return view("inventory.incomes.modalEditDetail", compact('detail','sw'));
        }
        abort(404);
    }

    public function updateDetails(Request $request, FlasherInterface $flasher, $id){
        $messages = [
            'itemedit.required'         => 'Debe escoger una opción válida',
            'cantidadedit.required'     => 'El campo es requerido',
            'ubicacionedit.required'    => 'Debe escoger una opción válida',
            'observacionedit.min'       => 'Debe tener al menos 2 caracteres',
            'observacionedit.max'       => 'No debe tener más de 200 caracteres',
        ];
        $validateArray = [
            'itemedit' =>'required',
            'cantidadedit' =>'required',
            'ubicacionedit' =>'required',
            'observacionedit' => 'nullable|min:2|max:100',
        ];
        $request->validate($validateArray, $messages);
        $detail =InvIncomesDetails::findOrFail(decode($id));
        if($detail->incomes->state == 1){
            $detail->item_id = decode($request->itemedit);
            $detail->location = $request->ubicacionedit;
            $detail->quantity = number_format($request->cantidadedit,2);
            $detail->observation = $request->observacionedit;
            $detail->update();
            $flasher->addFlash('info', 'Modificado con éxito', 'Detalle');
        }
        return  \Response::json(['success' => '1']);
    }

    public function modalItems(){
        return view("inventory.incomes.modalItems");
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

        $totalFiltered = $posts->count();
        $posts = $posts
        ->offset($start)
        ->limit($limit)
        ->orderBy($order,$dir)
        ->get();

        $data = array();
        foreach ($posts as $post){

            $routeAttach = storage_path('app/public/inventory/'.$post->attach);
            if (isset($post->attach) && file_exists($routeAttach))
                $imagen =
                '<a href="/storage/inventory/'.$post->attach.'" target="_blank">
                    <img src="/storage/inventory/thumbnail/'.$post->attach.'" style="max-width:45px" alt="Sin imagen para mostrar">
                </a>';
            else
                $imagen = '<img src="/storage/thumbnail/noimage.png" style="max-width: 45px;" >';

            $datos=
            "<span >
                <span style='color:#A6ACAF;'>Código: <span class='text-primary'>".$post->getCod()."</span><br>
                <span style='color:#A6ACAF;'>Item: </span><span class='text-dark'>".$post->title."</span><br>
                <span style='color:#A6ACAF;'>Cantidad Disponible: </span><span class='text-dark font-weight-bold' style='font-size:15px'>".$post->getQuantity()."</span>
            </span>";

            $class = $post->quantity == 0 ? 'danger' : 'primary';
            $boton = '<button type="button" class="modalbtn border border-'.$class.' btn btn-outline-'.$class.'" data-cod="'.$post->cod.' - '.$post->title.'" id="'.code($post->id).'" >Seleccionar</button>';
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

    public function modalDelete(Request $request, $id,$sw){
        $detail = InvIncomesDetails::findOrFail(decode($id));
        if($detail->incomes->state == 1){
            return view("inventory.incomes.modalDeleteDetail", compact('detail','sw'));
        }
        abort(404);
    }

    public function destroyDetails(FlasherInterface $flasher, $id){
        $detail =InvIncomesDetails::findOrFail(decode($id));
        if($detail->incomes->state == 1){
            $detail->delete();
            $flasher->addFlash('error', 'Eliminado con éxito', 'Detalle');
        }
        return redirect()->route('incomes.show',code($detail->income_id));
    }

}
