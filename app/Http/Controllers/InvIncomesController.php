<?php

namespace App\Http\Controllers;

use App\InvIncomes;
use Illuminate\Http\Request;
use Session;
use Flasher\Prime\FlasherInterface;

class InvIncomesController extends Controller
{
    public function index (Request $request){
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
        $income = InvIncomes::findOrFail(decode($id));
        $params = InvParameters::where('empresa_id',empresaId())->get();
        Session::put('item','4.2:');
        return view("inventory.incomes.show", compact('income','params'));
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
}