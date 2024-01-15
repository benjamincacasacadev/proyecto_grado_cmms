<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StForms;
use App\StFormType;
use App\User;
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

    // =========================================================================================================
    // =========================================================================================================
    //                                       INFORMES DE MANTENIMIENTO
    // =========================================================================================================
    // =========================================================================================================
    public function indexMaintenance(Request $request,$id){
        $form = StForms::findOrFail(decode($id));
        $maintenance = isset($form->maintenance) ? $form->maintenance : [];
        $containers = collect($form->containers)->sortBy('orden');
        $subcontainers = $containers->first();
        $subcontainers = isset($subcontainers['subcontainer']) ? $subcontainers['subcontainer'] : [];

        $collectserie = collect($maintenance)->where('type','serie');
        $nombre_serie = [];
        if (isset($collectserie)) {
            foreach($collectserie as $x=>$ser){
                $nombre_serie[$x] = $ser['mostrar'];
            }
        }
        $estados = collect($form->form_states);
        $tecnicos = User::where('active',1)->orderBy('ap_paterno','asc')->get();

        Session::put('item','5.');
        return view("forms.maintenance.maintenance", compact('form','maintenance','containers','subcontainers','nombre_serie','estados','tecnicos'));
    }

    public function storeMaintenance(Request $request, FlasherInterface $flasher, $id){
        $messages = [
            'contenedorid.required'  => 'Debe escoger un Contenedor válido',
            'subcontenedorprinc.required'  => 'Debe escoger un Sub Contenedor válido',
            'inputType.required'  => 'Debe escoger un Tipo de Campo válido',
            'field_name.required'  => 'El campo "Nombre de Campo" es obligatorio',
            'tiposelect.required' => 'Debe escoger una opción válida',
            'texto_tipo.required' => 'Debe escoger un tipo de texto válido',
        ];

        $validateArray = [
            'contenedorid' =>'required',
            'subcontenedorprinc' =>'required',
            'inputType' => 'required',
        ];
        $validateFields = [
            'field_name' => 'required',
        ];
        $validateRadio = [
            'myOptionsRadio.*' => 'required',
            'myOptionsRadio' => 'min:2',
        ];
        $validateCheck = [
            'myOptionsCheck.*' => 'required',
            'myOptionsCheck' => 'min:2',
        ];
        $validateSelect = [
            'myOptionsSelect.*' => 'required',
            'tiposelect' => 'required',
            'myOptionsSelect' => 'min:2',
        ];
        $validateText = [
            'texto_tipo' => 'required',
        ];
        $tipocampo = $request->inputType;
        if(isset($tipocampo)) $validateArray = array_merge($validateArray,$validateFields);
        if($tipocampo == 'radio') $validateArray = array_merge($validateArray,$validateRadio);
        if($tipocampo == 'checkbox') $validateArray = array_merge($validateArray,$validateCheck);
        if($tipocampo == 'select') $validateArray = array_merge($validateArray,$validateSelect);
        if($tipocampo == 'texto') $validateArray = array_merge($validateArray,$validateText);
        $request->validate($validateArray,$messages);

        $form = StForms::findOrFail(decode($id));
        if($form->state != "1"){
            return  \Response::json(['success' => '1']);
        }
        $campo = $this->limpiar($request->field_name);

        // Para ordenar los inputs segun se vayan generando ya que al guardar en JSON no se ordena asi
        $orden = 0;
        $form_info_main = $form->maintenance;
        if(isset($form->maintenance)){
            foreach ($form->maintenance as $max) {
                if($max['orden']>$orden) $orden = $max['orden'];
            }
        } $orden++;

        // Validar que el nombre de campo sea único añadiendo un index si el nombre ya existe
        $keynom = []; $ki=0;
        if(isset($form->maintenance)){
            foreach ($form->maintenance as $key => $value) {
                $auxname = explode("__",$value['id']);
                $keyname = isset($auxname[1]) ? $auxname[1] : 0;
                $keynom[$ki] = ++$keyname;
                $ki++;
            }
            foreach ($form->maintenance as $key => $value) {
                $auxname = explode("__",$value['id']);
                if($campo == $auxname[0] ){
                    $campo = $auxname[0]."__".max($keynom);
                }
            }
        }

        // Generar el JSON para almacenarlo
        $info_main = [];
        $info_main[$campo]['id'] = $campo;
        $info_main[$campo]['type'] = $request->inputType;
        $info_main[$campo]['mostrar'] = preg_replace('/\s+/', ' ',$request->field_name);
        $info_main[$campo]['container'] = $request->contenedorid;
        $info_main[$campo]['subcontainer'] = $request->subcontenedorprinc;
        $info_main[$campo]['orden'] = $orden;
        // Obtener el array de inputs si es que se escogio un tipo de input multiple
        switch ($request->opt) {
            case 'check':  $myOptions = $request->myOptionsCheck;     break;
            case 'select': $myOptions = $request->myOptionsSelect;    break;
            case 'text':   $myOptions = "";                           break;
            case 'radio':  $myOptions = $request->myOptionsRadio;     break;
            default: return back(); break;
        }

        // Campos para radio dependientes
        $swdep=0;
        $savedepend = [];
        if( $request->inputType == 'radio'){
            $camposdep = [];
            foreach($request->all() as $reqra){
                if(!is_array($reqra)){
                    $swcampd = explode("|||",$reqra);
                    if(isset($swcampd[1])){
                        $campoDep = $swcampd[0];
                        $optPadre = $this->limpiar($swcampd[1]);
                        $camposdep[$optPadre][$campoDep] = $campoDep;
                        $swdep = 1;
                    }
                }
            }
        }
        // =====================================================================================================
        //                                Añadir a JSON segun tipo de campo
        // =====================================================================================================
        switch ($request->inputType){
            case 'radio':
                $ordradio = 1;
                $swmsg_rad = 0;
                $optradio_u = array_unique($myOptions);
                foreach ($optradio_u as $key => $opcion) {
                    if ($opcion != ""){
                        $optradio = $this->limpiar($opcion);
                        $info_main[$campo]['options'][$campo.'|'.$optradio]['val'] = $optradio;
                        $info_main[$campo]['options'][$campo.'|'.$optradio]['mostraropt'] = $opcion;
                        $info_main[$campo]['options'][$campo.'|'.$optradio]['orden'] = $ordradio;
                        $ordradio++;
                        $color = strtolower(str_replace(" ","_",$request->myOptionsColor[$key]));
                        switch ($color) {
                            case 'rojo':
                                $info_main[$campo]['options'][$campo.'|'.$optradio]['color'] = 'red';
                                $info_main[$campo]['options'][$campo.'|'.$optradio]['hex'] = '#D54E21';
                            break;
                            case 'amarillo':
                                $info_main[$campo]['options'][$campo.'|'.$optradio]['color'] = 'yellow';
                                $info_main[$campo]['options'][$campo.'|'.$optradio]['hex'] = '#FFCC33';
                            break;
                            case 'verde':
                                $info_main[$campo]['options'][$campo.'|'.$optradio]['color'] = 'green';
                                $info_main[$campo]['options'][$campo.'|'.$optradio]['hex'] = '#008D4C';
                            break;
                            case 'azul':
                                $info_main[$campo]['options'][$campo.'|'.$optradio]['color'] = 'blue';
                                $info_main[$campo]['options'][$campo.'|'.$optradio]['hex'] = '#367FA9';
                            break;
                            case 'naranja':
                                $info_main[$campo]['options'][$campo.'|'.$optradio]['color'] = 'orange';
                                $info_main[$campo]['options'][$campo.'|'.$optradio]['hex'] = '#DE8650';
                            break;
                            case 'morado':
                                $info_main[$campo]['options'][$campo.'|'.$optradio]['color'] = 'purple';
                                $info_main[$campo]['options'][$campo.'|'.$optradio]['hex'] = '#A77A94';
                            break;
                            default:
                            break;
                        }
                    }else $swmsg_rad = 1;
                    if (count($optradio_u) != count($myOptions)) $swmsg_rad = 1;
                    if ($swmsg_rad == 1) $flasher->addFlash('warning', 'Las opciones Radio con nombres vacíos o que estén repetidos NO se registraron', 'No permitido');
                }
                // =================================================================
                //                            CAMPOS DEPENDIENTES
                $dependAnidados = [];
                foreach($camposdep as $kcd=>$cdep){
                    $keyrpadre = isset($info_main[$campo]['options'][$campo.'|'.$kcd]['val'])
                                    ? $info_main[$campo]['options'][$campo.'|'.$kcd]['val']
                                    : null;
                    foreach($cdep as $campod){
                        if(isset($form->maintenance[$campod])){
                            $savedepend[$campod] = $form->maintenance[$campod];
                            $savedepend[$campod]['radiopadre_id'] = $campo.'___'.$keyrpadre;
                            $savedepend[$campod]['clase_padre'] = $campo;
                            $savedepend[$campod]['orden'] = $form->maintenance[$campod]['orden'] + 1;

                            $depenAux = collect($form->maintenance)->filter(function ($cont) use ($campod) {
                                if(isset($campod) && $campod != ""){
                                    if(isset($cont['clase_padre'])){
                                        $arrayPadre = explode(" ",$cont['clase_padre']);
                                        return in_array($campod, $arrayPadre);
                                    }
                                }
                                return [];
                            })->sortBy('orden');
                            $auxOrdDep = $form->maintenance[$campod]['orden'] + 1 + 0.01;
                            foreach($depenAux as $keyAux => $dAux){
                                $savedepend[$keyAux] = $form->maintenance[$keyAux];
                                $savedepend[$keyAux]['orden'] = $auxOrdDep;
                                $dependAnidados[$keyAux] = $form->maintenance[$keyAux];
                                $dependAnidados[$keyAux]['orden'] = $auxOrdDep;
                                $auxOrdDep = $auxOrdDep + 0.01;
                            }
                        }
                    }
                }

            break;
            case 'checkbox':
                $swmsg_check = 0;
                $optcheck_u = array_unique($myOptions);
                $ord_check = 0;
                foreach ($myOptions as $key => $opcion) {
                    if($opcion != ""){
                        $save = $this->limpiar($opcion);
                        $info_main[$campo]['options'][$save]['val'] =$save;
                        $info_main[$campo]['options'][$save]['mostraropt'] = $opcion;
                        $info_main[$campo]['options'][$save]['ordencheck'] = $ord_check;
                        $ord_check++;
                    }else $swmsg_check = 1;

                    if (count($optcheck_u) != count($myOptions)) $swmsg_check = 1;
                    if($swmsg_check == 1) $flasher->addFlash('warning', 'Las opciones con nombres vacíos o que estén repetidos NO se registraron', 'No permitido');
                }
            break;
            case 'texto':
                $info_main[$campo]['type'] = $request->texto_tipo;
            break;
            case 'select':
                $ordselect = 1;
                if($request->tiposelect == "multiple"){
                    $info_main[$campo]['multiple'] = $request->tiposelect;
                    $info_main[$campo]['type'] = 'select2';
                }
                elseif($request->tiposelect == "select2") $info_main[$campo]['type'] = $request->tiposelect;
                else $info_main[$campo]['type'] = $request->inputType;

                $swmsg_select = 0;
                $optselect_u = array_unique($myOptions);
                foreach ($myOptions as $key => $opcion) {
                    if($opcion != ""){
                        $save = $this->limpiar($opcion);
                        $info_main[$campo]['options'][$save]['val'] =$save;
                        $info_main[$campo]['options'][$save]['mostraropt'] = $opcion;
                        $info_main[$campo]['options'][$save]['orden'] = $ordselect;
                        $ordselect++;
                    }else $swmsg_select = 1;

                    if (count($optselect_u) != count($myOptions)) $swmsg_select = 1;
                    if($swmsg_select == 1) $flasher->addFlash('warning', 'Las opciones con nombres vacíos o que estén repetidos NO se registraron', 'No permitido');
                }
            break;
            default: break;
        }
        $guardar = ( isset($savedepend) && count($savedepend)>0 ) ? array_merge($savedepend, $info_main) : $info_main;
        $guardar = isset($form->maintenance) ? array_merge($form_info_main, $guardar) : $guardar;
        // ================================================================================================
        //                       CAMPOS DEPENDIENTES CON más DE UN NIVEL (ORDENAR)
        // ================================================================================================
        if($swdep == 1){
            $minDepend = collect($savedepend)->sortBy('orden')->first();
            $maxDepend = collect($savedepend)->sortBy('orden')->last();
            $ordenMin = isset($minDepend['orden']) ? $minDepend['orden'] - 1 : $orden;
            $ordenMax = isset($maxDepend['orden']) ? $maxDepend['orden'] + 1  : $orden;

            if(isset($guardar[$campo])){
                $guardar[$campo]['orden'] = $ordenMin;
            }

            $camposDepend = collect($info_main)
            ->merge(collect($savedepend)
            ->sortBy('orden'));

            $subContainersDep = collect($guardar)
            ->where('container',$request->contenedorid)
            ->where('subcontainer',$request->subcontenedorprinc)
            ->sortBy('orden');

            $subContainersDep = $subContainersDep->where('orden', '>', $ordenMin);
            $camposNoDepend = $subContainersDep->diffKeys($camposDepend)->sortBy('orden');
            $dependAnidados = collect($dependAnidados)->sortBy('orden');

            foreach($camposNoDepend as $campoNo){
                $guardar[$campoNo['id']]['orden'] = $ordenMax++;
                if(isset($campoNo['clase_padre'])){
                    foreach($camposDepend as $depend){
                        $padres = explode(" ",$campoNo['clase_padre']);
                        if(in_array($depend['id'],$padres)){
                            $guardar[$campoNo['id']]['clase_padre'] = $campo." ".$campoNo['clase_padre'];
                        }
                    }
                }
            }

            foreach($dependAnidados as $campoNo){
                if(isset($campoNo['clase_padre'])){
                    foreach($camposDepend as $depend){
                        $padres = explode(" ",$campoNo['clase_padre']);
                        if(in_array($depend['id'],$padres)){
                            $guardar[$campoNo['id']]['clase_padre'] = $campo." ".$campoNo['clase_padre'];
                        }
                    }
                }
            }
        }
        $form->maintenance = $guardar;
        $form->update();

        $contid = $request->contenedorid;
        $subconte = delete_charspecial($request->subcontenedorprinc);
        $flasher->addFlash('success', 'Registrado con éxito', 'Campo');
        return  \Response::json(['success' => '1','contid'=> $contid,'subconte'=> $subconte]);
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

    public function ajaxSelectCont(Request $request, $id){
        $form = StForms::findOrFail(decode($id));
        $aux = collect($form->containers)->sortBy('orden');

        $request['search'] = limpiarTexto($request->search,'s2');
        $search = $request->search;
        $containers = collect($aux)->filter(function ($cont) use ($search) {
            if(isset($search) && $search != ""){
                return false !== stripos($cont['mostrar'], $search);
            }
            return $cont;
        })->sortBy('orden');


        $array = [];
        $array['results'][0]['id'] = "";
        $array['results'][0]['text'] = "Seleccione una opción";
        $k = 0;
        foreach($containers as $cont){
            $array['results'][$k+1]['id'] = $cont['id'];
            $array['results'][$k+1]['text'] = $cont['mostrar'];
            $k++;
        }
        $array['pagination']['more'] = false;
        return response()->json($array);
    }

    public function ajaxSelectSubCont(Request $request, $id){
        $query = $request->container;
        $form = StForms::findOrFail(decode($id));
        $containers = collect($form->containers)->where('id',$query);
        $aux = $containers->first();
        $aux = isset($aux['subcontainer']) ? collect($aux['subcontainer'])->sortBy('orden') : collect();

        $request['search'] = limpiarTexto($request->search,'s2');
        $search = $request->search;
        $subcontainers = collect($aux)->filter(function ($subcont) use ($search) {
            if(isset($search) && $search != "")
                return false !== stripos($subcont['mostrar'], $search);
            else
                return $subcont;
        })->sortBy('orden');


        $array = [];
        $array['results'][0]['id'] = "";
        $array['results'][0]['text'] = "Seleccione una opción";
        $k = 0;
        foreach($subcontainers as $subcont){
            $array['results'][$k+1]['id'] = $subcont['val'];
            $array['results'][$k+1]['text'] = $subcont['mostrar'];
            $k++;
        }
        $array['pagination']['more'] = false;
        return response()->json($array);
    }

    public function ajaxSelectDependiente(Request $request){
        if ($request->get('query')) {
            $query = $request->get('query');
            if($query != ""){
                $stforms = StForms::where('id',decode($request->get('idform')))->first();
                $stformsjson = json_decode($stforms, true);
                $campos_array = $stformsjson['maintenance'];
                $campos_array = collect($campos_array)->sortBy('orden');
                $index = $request->get('index');
                $salida = "";
                $arrayids = []; $ai=0;
                if(isset($campos_array)){
                    foreach ($campos_array as $campos) {
                        if (isset($campos['subcontainer']) && $campos['subcontainer'] == $query && !isset($campos['radiopadre_id']) ){
                            $salida .=
                            '<label class="radioajax">
                                <input class="bluedependiente radioprueba1 grupo_'.$index.'" type="radio" name="'.$campos['id'].'" value="'.$campos['id'].'" id="grupo_'.$index.'" >
                                <span class="text-yellowdark">'.$campos['mostrar'].'</span>
                            </label><br>';
                            $arrayids[$ai] = $campos['id'];
                            $ai++;
                        }
                    }
                }
            }
            return response()->json(array('selectxd1' =>$salida, 'arrayids' => $arrayids), 200);
        }
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