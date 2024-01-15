<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StForms;
use App\StFormType;
use App\WorkOrders;
use Session;
use Flasher\Prime\FlasherInterface;

class StFormController extends Controller{
    public function index(Request $request){
        $stateFilter = ($request->stateFilter != '' && $request->stateFilter != null)? $request->stateFilter : 'act';
        Session::put('item','5.');
        return view("forms.index", compact('stateFilter'));
    }

    public function tableForms(Request $request){
        $totalData = StForms::count();
        $state = $request->state;

        $totalFiltered = $totalData;
        $limit =( empty($request->input('length'))  ) ? $limit = 10 : $limit = $request->input('length');
        $start =( empty($request->input('start'))  ) ? $start = 0 :  $start = $request->input('start');

        $posts = StForms::select('id','name','type_id','category_id','check_letter','state')
        ->Name($request->input('columns.0.search.value'))
        ->Type($request->input('columns.1.search.value'))
        ->State($state);

        $totalFiltered = $posts->count();
        $posts=$posts
        ->orderBy('id','desc')
        ->offset($start)
        ->limit($limit)
        ->get();

        $data = array();
        foreach ($posts as $post){
            $nestedData['name'] = $post->name;
            $nestedData['type'] = $post->types->name;
            $nestedData['contenedores'] = $post->getLinks('container');
            $nestedData['informes'] = $post->getLinks('report');
            $nestedData['carta'] = $post->getLinks('letter');
            $nestedData['estado'] = $post->getState(1);
            $nestedData['operations']= $post->getOperations();
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

    public function modalCreate(Request $request){
        $types = StFormType::orderBy('name')->get();
        return view("forms.modalCreate", compact('types'));
    }

    public function store(Request $request, FlasherInterface $flasher) {
        $this->validateForm($request);

        $form = new StForms();
        $form->name = $request->nombre;
        $form->sigla = $request->sigla;
        $form->category_id = $request->categoria;
        $form->type_id = $request->tipo;
        $form->state = 1;
        $form->check_letter = $request->checkcarta == "si" ? 1 : 0;
        $form->save();

        $flasher->addFlash('success', 'Creado con éxito', 'Formulario '.$form->name);
        return  \Response::json(['success' => '1']);
    }

    public function modalEdit($id){
        $form = StForms::findOrFail(decode($id));
        $types = StFormType::orderBy('name')->get();
        return view("forms.modalEdit", compact('form','types'));
    }

    public function update(Request $request, FlasherInterface $flasher, $id) {
        $this->validateForm($request, $id);

        $form = StForms::findOrFail(decode($id));
        $form->name = $request->nombreedit;
        $form->sigla = $request->siglaedit;
        $form->category_id = $request->categoriaedit;
        $form->type_id = $request->tipoedit;
        $form->check_letter = $request->checkcartaedit == "si" ? 1 : 0;
        $form->update();

        $flasher->addFlash('info', 'Modificado con éxito', 'Formulario '.$form->name);
        return  \Response::json(['success' => '1']);
    }

    public function modalDelete($id){
        $form = StForms::findOrFail(decode($id));
        $count = WorkOrders::where('form_id',$form->id)->count();
        return view("forms.modalDelete", compact('form','count'));
    }

    public function destroy(FlasherInterface $flasher, $id) {
        $form = StForms::findOrFail(decode($id));
        $count = WorkOrders::where('form_id',$form->id)->count();
        if($count > 0){
            $flasher->addFlash('warning', 'Tiene órdenes de trabajo asociadas', 'No se puede eliminar al formulario '.$form->name);
            return redirect()->route('forms.index');
        }
        $form->delete();
        $flasher->addFlash('error', 'Eliminado correctamente', 'Formulario '.$form->name);
        return redirect()->route('forms.index');
    }

    public function validateForm(Request $request, $id = ''){
        $edit = $id != '' ? 'edit' : '';

        $nombre = 'nombre'.$edit;
        $sigla = 'sigla'.$edit;
        $categoria = 'categoria'.$edit;
        $tipo = 'tipo'.$edit;
        $checkcarta = 'checkcarta'.$edit;

        $validateArray = [
            $nombre => 'required|max:100',
            $sigla => 'bail|required|min:3|max:3',
            $categoria => 'required',
            $tipo => 'required',
            $checkcarta => 'required',
        ];

        $aliasArray = [
            $nombre => '<b>Nombre del formulario</b>',
            $sigla => '<b>Sigla</b>',
            $categoria => '<b>Categoria de activo</b>',
            $tipo => '<b>Tipo</b>',
            $checkcarta => '<b>Incluir carta de presentación</b>',
        ];

        return $request->validate($validateArray, [], $aliasArray);
    }

    // =====================================================================================================
    // =====================================================================================================
    //                                           CONTAINERS
    // =====================================================================================================
    // =====================================================================================================
    public function indexContainer(Request $request, $id){
        $forms = StForms::findOrFail(decode($id));
        $containers = collect($forms->containers)->sortBy('orden');
        Session::put('item','5.');
        return view("forms.containers.containers", compact('forms','containers'));
    }

    public function storeContainer(Request $request, FlasherInterface $flasher, $id){
        $form = StForms::findOrFail(decode($id));
        if($form->state != "1"){
            return  \Response::json(['success' => '1']);
        }
        $messages = [
            'contsw.required' => 'Debe escoger una opción válida.',
            'contnuevo.required' => 'El nombre de contenedor nuevo es obligatorio.',
            'contenedorid.required' => 'Debe escoger un contenedor válido.',
        ];
        $validateArray = [
            'contsw' => 'required',
        ];
        $validateContNuevo = [
            'contnuevo' => 'required',
        ];
        $validateAddSubcont = [
            'contenedorid' => 'required',
            'myOptions.*' => 'required',
        ];

        $conttype = $request->contsw;
        if($conttype == 'new_cont')  $validateArray = array_merge($validateArray,$validateContNuevo);
        if($conttype == 'add_cont')  $validateArray = array_merge($validateArray,$validateAddSubcont);

        $request->validate($validateArray,$messages);

        if($request->contsw == 'new_cont'){
            $orden = collect($form->containers)->max('orden');
            $orden = isset($orden) ? $orden + 1 : 1;

            // Validar que el nombre de Container sea único
            $campo = $this->limpiar($request->contnuevo);

            // Validar que el nombre de campo sea único añadiendo un index si el nombre ya existe
            $keynom = []; $ki=0;
            if(isset($form->containers)){
                foreach ($form->containers as $key => $value) {
                    $auxname = explode("__",$value['id']);
                    $nombreCont = $auxname[0];
                    if($nombreCont == $campo){
                        $keyname = isset($auxname[1]) ? $auxname[1] : 0;
                        $keynom[$ki] = ++$keyname;
                        $ki++;
                    }
                }
                foreach ($form->containers as $key => $value) {
                    $auxname = explode("__",$value['id']);
                    if($campo == $auxname[0] ){
                        $campo = $auxname[0]."__".max($keynom);
                    }
                }
            }
            $newCont = [];
            $newCont[$campo]['id'] = $campo;
            $newCont[$campo]['type'] = 'cont';
            $newCont[$campo]['orden'] = $orden;
            $newCont[$campo]['mostrar'] = $request->contnuevo;
            $guardar = isset($form->containers) ? array_merge($form->containers, $newCont) : $newCont;
            $contId = $newCont[$campo]['id'];
            $form->containers = $guardar;
            $form->update();
            $flasher->addFlash('success', 'Registrado con éxito', 'Contenedor ');
        }else{
            $contenedor = $form->containers;
            $contAux = isset($contenedor[$request->contenedorid]) ? $contenedor[$request->contenedorid] : [];
            $subContId = isset($contAux['subcontainer']) ? $contAux['subcontainer'] : [];
            $contId = isset($contAux['id']) ? $contAux['id'] : "";

            // Guardar todos los subcontenedores en un array
            $allSubConts = [];
            foreach($contenedor as $allCont){
                $subAux = isset($allCont['subcontainer']) ? $allCont['subcontainer'] : [];
                foreach ($subAux as $allSubs) {
                    $allSubConts[] = $allSubs;
                }
            }

            $subContNew = [];
            foreach(array_unique($request->myOptions) as $optionSub){
                $auxSubNew = $this->limpiar($optionSub);
                $keynom = []; $ki=0;
                $subContNew[$auxSubNew]['id'] = $auxSubNew;
                $subContNew[$auxSubNew]['valor'] = $optionSub;
                //// ==================================================================
                ////                  Validar que el nombre sea unico
                //// ==================================================================
                // Obtener el index mayor del subcontainer EJ 1,2,3
                foreach($allSubConts as $subc){
                    $auxname = explode("___",$subc['val']);
                    $id = $auxname[0];
                    if( $id == $auxSubNew ){
                        $keyname = isset($auxname[1]) ? $auxname[1] : 0;
                        $keynom[$ki] = ++$keyname;
                        $ki++;
                    }
                }
                // Concatenar el nuevo index al subcontainer EJ (subcont___2)
                foreach($allSubConts as $subc){
                    $auxname = explode("___",$subc['val']);
                    $id = $auxname[0];
                    if( $id == $auxSubNew ){
                        $subContNew[$auxSubNew]['id'] = $id.'___'.max($keynom);
                    }
                }
            }

            // Guardar todos los datos obtenidos en la BD
            $ordenSub = collect($subContId)->sortBy('orden')->last();
            $ordenSub = isset($ordenSub['orden']) ? $ordenSub['orden'] : 0;
            foreach($subContNew as $subV){
                $contenedor[$contId]['subcontainer'][ $subV['id'] ]['val'] = $subV['id'];
                $contenedor[$contId]['subcontainer'][ $subV['id'] ]['mostrar'] = $subV['valor'];
                $contenedor[$contId]['subcontainer'][ $subV['id'] ]['orden'] = ++$ordenSub;
            }
            $form->containers = $contenedor;
            $form->update();
            $flasher->addFlash('success', 'Registrado con éxito', 'Sub contenedor');
        }
        return  \Response::json(['success' => '1','contid'=> $contId]);
    }

    public function modalEditContainer($idcont, $id){
        $form = StForms::findOrFail(decode($id));
        if($form->state != "1"){
            return  \Response::json(['success' => '1']);
        }
        $nombre_campo = json_decode($form, true)['containers'][$idcont]['mostrar'];
        return view("forms.containers.modalEditContainer", compact('form','id','idcont','nombre_campo'));
    }

    public function modalEditSubContainer($idcont,$idsubc, $id){
        $form = StForms::findOrFail(decode($id));
        if($form->state != "1"){
            abort(404);
        }
        $nombre_campo = json_decode($form, true)['containers'][$idcont]['subcontainer'][$idsubc]['mostrar'];
        return view("forms.containers.modalEditSubContainer", compact('form','id','idcont','idsubc','nombre_campo'));
    }

    public function updateContainer(Request $request, FlasherInterface $flasher, $idcont, $id){
        $messages = [
            'nombrecontenedoredit.required'  => 'El nombre de contenedor es obligatorio',
        ];
        $validateArray = [
            'nombrecontenedoredit' =>'required',
        ];
        $request->validate($validateArray,$messages);

        $form = StForms::findOrFail(decode($id));
        if($form->state != "1"){
            return  \Response::json(['success' => '1']);
        }
        $cont = $form->containers;
        if( isset($cont[$idcont]['mostrar']) ){
            unset($cont[$idcont]['mostrar']);
            $cont[$idcont]['mostrar'] = $request->nombrecontenedoredit;
            $form->containers = $cont;
            $form->update();
            $flasher->addFlash('info', 'Actualizado con éxito', 'Contenedor');
        }else{
            $flasher->addFlash('error', 'Error al guardar los datos', 'Hubo un problema');
        }
        return  \Response::json(['success' => '1']);
    }

    public function updateSubContainer(Request $request, FlasherInterface $flasher, $idcont, $idsubc, $id){
        $messages = [
            'nombresubcontenedoredit.required'  => 'El nombre de sub contenedor es obligatorio',
        ];
        $validateArray = [
            'nombresubcontenedoredit' =>'required',
        ];

        $request->validate($validateArray,$messages);

        $form = StForms::findOrFail(decode($id));
        if($form->state != "1"){
            return  \Response::json(['success' => '1']);
        }
        $subcont = $form->containers;
        if(isset($subcont[$idcont]['subcontainer'][$idsubc])){
            unset($subcont[$idcont]['subcontainer'][$idsubc]);
            $cont = $form->containers;
            $cont[$idcont]['subcontainer'][$idsubc]['mostrar'] = $request->nombresubcontenedoredit;
            $form->containers = $cont;
            $form->update();
            $flasher->addFlash('info', 'Actualizado con éxito', 'Sub contenedor');
        }else{
            $flasher->addFlash('error', 'Error al guardar los datos', 'Hubo un problema');
        }
        return  \Response::json(['success' => '1']);
    }

    public function modalDeleteContainer($idcont,$idsubc, $id,$sw){
        $form = StForms::findOrFail(decode($id));
        if($form->state != "1"){
            abort(404);
        }
        $idc = (string)$idcont;
        $ids = (string)$idsubc;
        if ($sw == 'subc')
            $nombre_campo = json_decode($form, true)['containers'][$idc]['subcontainer'][$ids]['mostrar'];
        elseif($sw == 'cont')
            $nombre_campo = json_decode($form, true)['containers'][$idc]['mostrar'];
        return view("forms.containers.modalDeleteContainer", compact('nombre_campo','id','idcont','idsubc','sw'));
    }

    public function destroyContainer(FlasherInterface $flasher, $idcont, $idsubc, $id, $sw){
        $form = StForms::findOrFail(decode($id));
        if($form->state != "1"){
            abort(404);
        }
        if ($sw == 'subc'){
            $subcont = $form->containers;
            unset($subcont[$idcont]['subcontainer'][$idsubc]);
            $form->containers = $subcont;
            $info = isset($form->maintenance) ? $form->maintenance : [] ;
            if(count($info) > 0){
                foreach ($form->maintenance as $keymain => $main) {
                    if($main['subcontainer'] == $idsubc)
                        unset($info[$keymain]);
                }
            }
            $form->maintenance = (count($info) > 0) ? $info : null;
            $flasher->addFlash('error', 'Eliminado correctamente', 'Sub contenedor');
        }elseif($sw == 'cont'){
            $cont = $form->containers;
            unset($cont[$idcont]);
            $form->containers = $cont;

            $info = isset($form->maintenance) ? $form->maintenance : [] ;
            if(count($info) > 0){
                foreach ($form->maintenance as $keymain => $main) {
                    if($main['container'] == $idcont)
                        unset($info[$keymain]);
                }
            }
            $form->maintenance = (count($info) > 0) ? $info : null;
            $idcont = "";
            $flasher->addFlash('error', 'Eliminado correctamente', 'Contenedor');
        }
        $form->update();
        return Redirect()->route('forms.container', ['id' => $id, 'contid' => $idcont]);
    }

    // ======================================================================================================
    // ORDENAR CONTENEDORES Y SUBCONTENEDORES
    // ======================================================================================================
    public function modalOrderContainer($id){
        $form = StForms::findOrFail(decode($id));
        if($form->state != "1"){
            abort(404);
        }
        $containers = collect($form->containers)->sortBy('orden');
        return view("forms.containers.modalOrderContainer", compact('form','containers'));
    }

    public function modalOrderSubContainer($idcont,$id){
        $form = StForms::findOrFail(decode($id));
        if($form->state != "1"){
            abort(404);
        }
        $subcontainers = [];
        if(isset($form->containers[$idcont]['subcontainer']))
            $subcontainers = collect($form->containers[$idcont]['subcontainer'])->sortBy('orden');
        $nombreCont = json_decode($form, true)['containers'][$idcont]['mostrar'];
        return view("forms.containers.modalOrderSubContainer", compact('form','subcontainers','nombreCont','idcont'));
    }

    public function ajaxOrderContainer(Request $request){
        $form = StForms::findOrFail(decode($request->id_form));
        if($form->state != "1"){
            abort(404);
        }
        $idCont = $request->itemID;
        $NumOrden = $request->itemIndex;
        $containers = $form->containers;
        foreach ($containers as $key => $cont) {
            if($cont['id'] == $idCont){
                $containers[$idCont]['orden'] = $NumOrden;
            }
        }
        $form->containers = $containers;
        $form->update();
    }

    public function ajaxOrderSubContainer(Request $request){
        $form = StForms::findOrFail(decode($request->id_form));
        if($form->state != "1"){
            abort(404);
        }
        $idCont = $request->cont;
        $idSubC = $request->itemID;
        $NumOrden = $request->itemIndex;
        $containers = $form->containers;
        if(isset($form->containers[$idCont]['subcontainer'])){
            $subcontainers = $form->containers[$idCont]['subcontainer'];
            foreach($subcontainers as $subcont){
                if($subcont['val'] == $idSubC){
                    $subcontainers[$idSubC]['orden'] = $NumOrden;
                }
            }
        }
        $containers[$idCont]['subcontainer'] = $subcontainers;
        $form->containers = $containers;
        $form->update();
    }


    public function ajaxSubcontainer(Request $request){
        $query = $request->query;
        if(!isset($query)){
            $salida = $query = ""; $contsub = -1;
            return response()->json(array('selectxd1' =>$salida, 'formxd' => $query, 'contsub' => $contsub), 200);
        }
        $form = StForms::where('id',decode($request->get('idform')))->first();
        $containers = collect($form->containers)->where('id',$query);
        $subcontainers = $containers->first();

        $subcontainers = isset($subcontainers['subcontainer']) ? collect($subcontainers['subcontainer'])->sortBy('orden') : collect();
        $contsub = $subcontainers->count();
        $subcedit = $request->get('subc');
        if (!isset($subcontainers)){
            $salida = '<span>El Container no tiene Subcontainers Registrados</span>';
            return response()->json(array('selectxd1' =>$salida, 'formxd' => $query, 'contsub' => $contsub), 200);
        }
        $salida = '<option value="">Ninguno</option>';
        foreach ($subcontainers as $cont){
            $selected = $cont['val'] == $subcedit ? 'selected' : '';
            $salida .= '<option value="'.$cont['val'].'" '.$selected.'>'.$cont['mostrar'].'</option>';
        }
        return response()->json(array('selectxd1' => $salida, 'formxd' => $query, 'contsub' => $contsub), 200);
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

}