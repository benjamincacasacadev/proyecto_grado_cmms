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
        canPassAdminJefe();
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

    public function modalEditMaintenance($idcampo, $id){
        $form = StForms::findOrFail(decode($id));
        if($form->state != "1"){
            abort(404);
        }
        $nombre_campo = $form->maintenance[$idcampo]['mostrar'];
        $type = $form->maintenance[$idcampo]['type'];

        if($type == 'text' || $type == 'textarea' || $type == 'date' || $type == 'time' || $type == 'datetime' || $type == 'number' || $type == 'money'){
            return view("forms.maintenance.modalEditTextField", compact('nombre_campo','id','idcampo','type'));
        }elseif($type == 'radio'){
            $radioDep = 0;
            $options = isset($form->maintenance[$idcampo]['options']) ? collect($form->maintenance[$idcampo]['options'])->sortBy('orden') : [];
            foreach($form->maintenance as $campos){
                if(isset($campos['clase_padre'])){
                    $padre = explode(" ",$campos['clase_padre']);
                    if(in_array($idcampo,$padre)){
                        $radioDep = 1;
                        break;
                    }
                }
            }
            return view("forms.maintenance.modalEditRadioField", compact('nombre_campo','id','idcampo','type','radioDep','options'));
        }
        elseif($type == 'checkbox'){
            $options = isset($form->maintenance[$idcampo]['options']) ? collect($form->maintenance[$idcampo]['options'])->sortBy('ordencheck') : [];
            return view("forms.maintenance.modalEditCheckField", compact('nombre_campo','id','idcampo','type','options'));
        }
        elseif($type == 'select' || $type == 'select2'){
            $options = isset($form->maintenance[$idcampo]['options']) ? collect($form->maintenance[$idcampo]['options'])->sortBy('orden') : [];
            $multiple = isset($form->maintenance[$idcampo]['multiple']) ? $form->maintenance[$idcampo]['multiple'] : "0";
            if($multiple == 'multiple') $type = 'multiple';
            return view("forms.maintenance.modalEditSelectField", compact('nombre_campo','id','idcampo','type','options','multiple'));
        }
        elseif($type == 'serie'){
            return view("forms.maintenance.modalEditSerieField", compact('nombre_campo','id','idcampo','type'));
        }
        abort(404);
    }

    public function updateTextfield(Request $request, FlasherInterface $flasher, $idcampo, $id){
        $messages = [
            'nombretextEdit.required'  => 'El nombre de campo es obligatorio',
            'texto_tipoedit.required' => 'Debe escoger un tipo de texto válido',
        ];
        $validateArray = [
            'nombretextEdit' =>'required',
            'texto_tipoedit' =>'required',
        ];
        $request->validate($validateArray,$messages);
        $form = StForms::findOrFail(decode($id));
        if($form->state != "1"){
            return \Response::json(['success' => '1']);
        }
        $maintenance = $form->maintenance;
        if(isset($maintenance[$idcampo])){
            $contid = $maintenance[$idcampo]['container'];
            $subconte = $maintenance[$idcampo]['subcontainer'];
            $maintenance[$idcampo]['mostrar'] = $request->nombretextEdit;
            $maintenance[$idcampo]['type'] = $request->texto_tipoedit;
            $form->maintenance = $maintenance;
            $form->update();
            $flasher->addFlash('info', 'Actualizado con éxito', 'Campo');
            return  \Response::json(['success' => '1','contid'=> $contid,'subconte'=> $subconte]);
        }else
            $flasher->addFlash('error', 'Error al guardar datos', 'Hubo en problema');
        return  \Response::json(['success' => '1']);
    }

    public function updateRadiofield(Request $request, FlasherInterface $flasher, $idcampo, $id){
        $messages = [
            'nombreradioEdit.required'  => 'El nombre de campo es obligatorio',
        ];
        $validateArray = [
            'nombreradioEdit' =>'required',
            'myOptionsRadioedit.*' => 'required',
        ];
        $request->validate($validateArray,$messages);
        $myOptions = $request->myOptionsRadioedit;
        $form = StForms::findOrFail(decode($id));
        if($form->state != "1"){
            return \Response::json(['success' => '1']);
        }
        $maintenance = $form->maintenance;
        if(isset($maintenance[$idcampo])){
            $contid = $maintenance[$idcampo]['container'];
            $subconte = $maintenance[$idcampo]['subcontainer'];

            $ordradio = 1;
            $swmsg_rad = 0;
            $optradio_u = array_unique($myOptions);
            $options = [];
            $campo = $maintenance[$idcampo]['id'];
            foreach ($optradio_u as $key => $opcion) {
                if ($opcion != ""){
                    $optradio = strtolower(str_replace(" ","_",$opcion));
                    $options[$campo.'|'.$optradio]['val'] = $optradio;
                    $options[$campo.'|'.$optradio]['mostraropt'] = $opcion;
                    $options[$campo.'|'.$optradio]['orden'] = $ordradio;
                    $ordradio++;
                    $color = strtolower(str_replace(" ","_",$request->myOptionsColorEdit[$key]));
                    switch ($color) {
                        case 'rojo':
                            $options[$campo.'|'.$optradio]['color'] = 'red';
                            $options[$campo.'|'.$optradio]['hex'] = '#D54E21';
                        break;
                        case 'amarillo':
                            $options[$campo.'|'.$optradio]['color'] = 'yellow';
                            $options[$campo.'|'.$optradio]['hex'] = '#FFCC33';
                        break;
                        case 'verde':
                            $options[$campo.'|'.$optradio]['color'] = 'green';
                            $options[$campo.'|'.$optradio]['hex'] = '#008D4C';
                        break;
                        case 'azul':
                            $options[$campo.'|'.$optradio]['color'] = 'blue';
                            $options[$campo.'|'.$optradio]['hex'] = '#367FA9';
                        break;
                        case 'naranja':
                            $options[$campo.'|'.$optradio]['color'] = 'orange';
                            $options[$campo.'|'.$optradio]['hex'] = '#DE8650';
                        break;
                        case 'morado':
                            $options[$campo.'|'.$optradio]['color'] = 'purple';
                            $options[$campo.'|'.$optradio]['hex'] = '#A77A94';
                        break;
                        default:
                        break;
                    }
                }else $swmsg_rad = 1;
                if (count($optradio_u) != count($myOptions)) $swmsg_rad = 1;
            }
            $maintenance[$idcampo]['mostrar'] = $request->nombreradioEdit;
            $maintenance[$idcampo]['options'] = $options;
            $form->maintenance = $maintenance;
            $form->update();
            $flasher->addFlash('info', 'Actualizado con éxito', 'Campo');
            return  \Response::json(['success' => '1','contid'=> $contid,'subconte'=> $subconte]);
        }else
            $flasher->addFlash('error', 'Error al guardar datos', 'Hubo en problema');
        return  \Response::json(['success' => '1']);
    }

    public function updateCheckfield(Request $request, FlasherInterface $flasher, $idcampo, $id){
        $messages = [
            'nombrecheckEdit.required'  => 'El nombre de campo es obligatorio',
        ];
        $validateArray = [
            'nombrecheckEdit' =>'required',
            'myOptionsCheckedit.*' => 'required',
        ];
        $request->validate($validateArray,$messages);
        $myOptions = $request->myOptionsCheckedit;
        $form = StForms::findOrFail(decode($id));
        if($form->state != "1"){
            return \Response::json(['success' => '1']);
        }
        $maintenance = $form->maintenance;
        if(isset($maintenance[$idcampo])){
            $contid = $maintenance[$idcampo]['container'];
            $subconte = $maintenance[$idcampo]['subcontainer'];

            $options = [];
            $swmsg_check = 0;
            $optcheck_u = array_unique($myOptions);
            $ord_check = 0;
            foreach ($myOptions as $key => $opcion) {
                if($opcion != ""){
                    $save = strtolower(str_replace(" ","_",$opcion));
                    $options[$save]['val'] =$save;
                    $options[$save]['mostraropt'] = $opcion;
                    $options[$save]['ordencheck'] = $ord_check;
                    $ord_check++;
                }else $swmsg_check = 1;

                if (count($optcheck_u) != count($myOptions)) $swmsg_check = 1;
            }
            $maintenance[$idcampo]['mostrar'] = $request->nombrecheckEdit;
            $maintenance[$idcampo]['options'] = $options;
            $form->maintenance = $maintenance;
            $form->update();
            $flasher->addFlash('info', 'Actualizado con éxito', 'Campo');
            return  \Response::json(['success' => '1','contid'=> $contid,'subconte'=> $subconte]);
        }else
            $flasher->addFlash('error', 'Error al guardar datos', 'Hubo en problema');
        return  \Response::json(['success' => '1']);
    }

    public function updateSelectfield(Request $request, FlasherInterface $flasher, $idcampo, $id){
        $messages = [
            'nombreselectEdit.required'  => 'El nombre de campo es obligatorio',
            'tiposelectedit.required' => 'Debe escoger un tipo de select válido',
        ];
        $validateArray = [
            'nombreselectEdit' =>'required',
            'tiposelectedit' =>'required',
            'myOptionsSelectedit.*' => 'required',
        ];
        $request->validate($validateArray,$messages);
        $myOptions = $request->myOptionsSelectedit;
        $form = StForms::findOrFail(decode($id));
        if($form->state != "1"){
            return \Response::json(['success' => '1']);
        }
        $maintenance = $form->maintenance;
        if(!isset($maintenance[$idcampo])){
            $flasher->addFlash('error', 'Error al guardar datos', 'Hubo en problema');
            return  \Response::json(['success' => '1']);
        }

        $contid = $maintenance[$idcampo]['container'];
        $subconte = $maintenance[$idcampo]['subcontainer'];
        $ordselect = 1;
        if($request->tiposelectedit == "multiple"){
            $maintenance[$idcampo]['multiple'] = 'multiple';
            $maintenance[$idcampo]['type'] = 'select2';
        }elseif($request->tiposelectedit == "select2"){
            $maintenance[$idcampo]['type'] = 'select2';
            unset($maintenance[$idcampo]['multiple']);
        }else{
            $maintenance[$idcampo]['type'] = 'select';
            unset($maintenance[$idcampo]['multiple']);
        }

        $swmsg_select = 0;
        $optselect_u = array_unique($myOptions);
        $options = [];
        foreach ($myOptions as $key => $opcion) {
            if($opcion != ""){
                $save = strtolower(str_replace(" ","_",$opcion));
                $options[$save]['val'] = $save;
                $options[$save]['mostraropt'] = $opcion;
                $options[$save]['orden'] = $ordselect;
                $ordselect++;
            }else{
                $swmsg_select = 1;
            }
            if (count($optselect_u) != count($myOptions)){
                $swmsg_select = 1;
            }
        }
        $maintenance[$idcampo]['mostrar'] = $request->nombreselectEdit;
        $maintenance[$idcampo]['options'] = $options;
        $form->maintenance = $maintenance;

        $form->update();
        $flasher->addFlash('info', 'Actualizado con éxito', 'Campo');
        return  \Response::json(['success' => '1','contid'=> $contid,'subconte'=> $subconte]);
    }

    public function updateSeriefield(Request $request, FlasherInterface $flasher, $idcampo, $id){
        $messages = [
            'nombreSerieEdit.required'  => 'El nombre de campo es obligatorio',
        ];
        $validateArray = [
            'nombreSerieEdit' =>'required',
        ];
        $request->validate($validateArray,$messages);
        $form = StForms::findOrFail(decode($id));
        if($form->state != "1"){
            return \Response::json(['success' => '1']);
        }
        $maintenance = $form->maintenance;
        if(isset($maintenance[$idcampo])){
            $contid = $maintenance[$idcampo]['container'];
            $subconte = $maintenance[$idcampo]['subcontainer'];
            $maintenance[$idcampo]['mostrar'] = $request->nombreSerieEdit;
            $form->maintenance = $maintenance;
            $form->update();
            $flasher->addFlash('info', 'Actualizado con éxito', 'Campo');
            return  \Response::json(['success' => '1','contid'=> $contid,'subconte'=> $subconte]);
        }else
            $flasher->addFlash('error', 'Error al guardar datos', 'Hubo en problema');
        return  \Response::json(['success' => '1']);
    }

    public function modalDeleteMaintenance($idcampo, $id){
        $form = StForms::findOrFail(decode($id));
        if($form->state != "1"){
            abort(404);
        }
        $collectDep = collect($form->maintenance)->where('clase_padre','!=',null);
        $k = 0;
        $campos_dep = [];
        foreach($collectDep as $fo){
            $arrayPadre = explode(" ",$fo['clase_padre']);
            foreach($arrayPadre as $aP){
                if($aP == $idcampo){
                    $campos_dep[$k] = $fo['mostrar'];
                    $k++;
                    break;
                }
            }
        }

        $nombre_campo = $form->maintenance[$idcampo]['mostrar'];
        $mostrar = $form->maintenance[$idcampo]['mostrar'];
        return view("forms.maintenance.modalDeleteMaintenance", compact('nombre_campo','id','idcampo','mostrar','campos_dep'));
    }

    public function destroyMaintenance(FlasherInterface $flasher, $idcampo, $id){
        $form = StForms::findOrFail(decode($id));
        if($form->state != "1"){
            abort(404);
        }
        $info = $form->maintenance;
        $contid = isset($info[$idcampo]['container']) ? $info[$idcampo]['container'] : "";

        // eliminar campos dependientes
        $sw_dep = 0;
        $collectDep = collect($form->maintenance)->where('clase_padre','!=',null);
        foreach($collectDep as $fo){
            $arrayPadre = explode(" ",$fo['clase_padre']);
            foreach($arrayPadre as $aP){
                if($aP == $idcampo){
                    unset($info[ $fo['id'] ]);
                    $sw_dep = 1;
                    break;
                }
            }
        }

        unset($info[$idcampo]);
        $form->maintenance = $info;
        $form->update();
        $flasher->addFlash('error', 'Eliminado correctamente', 'Campo');
        return Redirect()->route('forms.maintenance', ['id' => $id, 'contid' => $contid]);
    }

    // ================================================================================================
    //                       GRAFICOS Y SERIES
    // ================================================================================================
    public function modalCreateSerie(Request $request, $id){
        $form = StForms::findOrFail(decode($id));
        if($form->state != "1"){
            abort(404);
        }
        $maintenance = isset($form->maintenance) ? $form->maintenance : [];
        $containers = collect($form->containers)->sortBy('orden');
        $collectserie = collect($maintenance)->where('type','serie');
        $nombre_serie = [];
        if (isset($collectserie)) {
            foreach($collectserie as $x=>$ser){
                $nombre_serie[$x] = $ser['mostrar'];
            }
        }
        return view("forms.maintenance.modalCreateSerie", compact('form','containers','nombre_serie'));
    }

    public function storeSerie(Request $request, FlasherInterface $flasher, $id){
        $form = StForms::findOrFail(decode($id));
        if($form->state != "1"){
            return \Response::json(['success' => '1']);
        }
        $maintenance = isset($form->maintenance) ? $form->maintenance : [];
        $collectserie = collect($maintenance)->where('type','serie');
        $this->validateStoreSerie($request, count($collectserie));
        // ==============================================================================================
                                    // ordenar los inputs segun se vayan generando
        $orden = 0;
        if(isset($form->maintenance)){
            foreach ($form->maintenance as $max) {
                if($max['orden']>$orden) $orden = $max['orden'];
            }
        } $orden++;

        $camposerie = $this->limpiar($request->name_new_serie);

        // ==============================================================================================
                    // Validar que el nombre de campo de SERIE sea único añadiendo index si el nombre existe
        $keynom = array(); $ki=0;
        if(isset($request->name_new_serie)){
            if(isset($form->maintenance) ){
                foreach ($form->maintenance as $key => $value) {
                    $auxname = explode("__",$value['id']);
                    $keyname = isset($auxname[1]) ? $auxname[1] : 0;
                    if($camposerie == $auxname[0]){
                        $keynom[$ki] = ++$keyname;
                        $ki++;
                    }
                }
                foreach ($form->maintenance as $key => $value) {
                    $auxname = explode("__",$value['id']);
                    if($camposerie == $auxname[0]){
                        $camposerie = $auxname[0]."__".max($keynom);
                    }
                }
                $keyindex = !empty($keynom) ? max($keynom) : 1;

                if($camposerie == 'recomendaciones' || $camposerie =='fecha_de_realizacion_inicio' || $camposerie == 'fecha_de_realizacion_final' || $camposerie == '&nro_tecnicos_time&' ){
                    $camposerie = $camposerie."__".$keyindex;
                }
            }
        }
        $campo = $this->limpiar($request->seriefield_name);
        // ==============================================================================================
        //                      Generar los campos adicionales que iran en la serie
        $swFields = $request->checkaddfields;
        if($request->seriesw == 'add_serie')    $swFields = "1";
        if($request->selectgrafico == 'serie_simple')    $swFields = "1";
        $info_main = [];
        if($swFields == "1"){
            $salidaserie = [];
            $campo_aux = $this->limpiar($request->seriefield_name);
            $collectserie = collect($form->maintenance)->where('type','serie')->where('id',$request->nombreserie);
            foreach ($collectserie as $k => $col) {
                if (count($collectserie) > 0){
                    foreach ($collectserie as $k => $col) {
                        $salidaserie[$k] = $col;
                        // ==============================================================================================
                        // Validar que el nombre de CAMPO ASOCIADO A LA SERIE sea único añadiendo index si el nombre existe
                        if( isset($col['campos']) ){
                            foreach ($col['campos'] as $key => $colser) {
                                $auxname = explode("__",$colser['id']);
                                $keyname = isset($auxname[1]) ? $auxname[1] : 0;
                                $keyname++;
                                if($campo_aux == $auxname[0]){
                                    $campo = $auxname[0]."__".$keyname;
                                }
                            }
                        }
                    }
                }
            }

            $info_main[$campo]['id'] = $campo;
            $info_main[$campo]['type'] = $request->serieinputType;
            $info_main[$campo]['mostrar'] = $request->seriefield_name;
            // Obtener el array de inputs si es que se escogio un tipo de input multiple
            switch ($request->serieinputType) {
                case 'checkbox':  $myOptions = $request->seriemyOptionsCheck;     break;
                case 'select': $myOptions = $request->seriemyOptionsSelect;    break;
                case 'text':   $myOptions = "";                                break;
                case 'radio':  $myOptions = $request->seriemyOptionsRadio;     break;
                default: break;
            }
            switch ($request->serieinputType){
                case 'radio':
                    $ordradio = 1;
                    $swmsg_rad = 0;
                    $optradio_u = array_unique($myOptions);
                    foreach ($optradio_u as $key => $opcion) {
                        if ($opcion != ""){
                            $optradio = $this->limpiar($opcion);
                            $info_main[$campo]['options'][$campo.'|'.$optradio]['val'] =$optradio;
                            $info_main[$campo]['options'][$campo.'|'.$optradio]['mostraropt'] = $opcion;
                            $info_main[$campo]['options'][$campo.'|'.$optradio]['orden'] = $ordradio;
                            $ordradio++;
                            $color = strtolower(str_replace(" ","_",$request->myOptionsColorSerie[$key]));
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
                    }


                break;
                case 'checkbox':
                    $swmsg_check = 0;
                    $optcheck_u = array_unique($myOptions);
                    $ordcheck = 1;
                    foreach ($myOptions as $key => $opcion) {
                        if($opcion != ""){
                            $save = $this->limpiar($opcion);
                            $info_main[$campo]['options'][$save]['val'] =$save;
                            $info_main[$campo]['options'][$save]['mostraropt'] = $opcion;
                            $info_main[$campo]['options'][$save]['orden'] = $ordcheck;
                            $ordcheck++;
                        }else $swmsg_check = 1;

                        if (count($optcheck_u) != count($myOptions)) $swmsg_check = 1;
                    }
                break;
                case 'select':
                    $ordselect = 1;
                    if($request->serietiposelect == "multiple"){
                        $info_main[$campo]['multiple'] = $request->serietiposelect;
                        $info_main[$campo]['type'] = 'select2';
                    }
                    elseif($request->serietiposelect == "select2") $info_main[$campo]['type'] = $request->serietiposelect;
                    else $info_main[$campo]['type'] = $request->serieinputType;

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
                    }
                break;
                case 'texto':
                    $info_main[$campo]['type'] = $request->serietexto_tipo;
                break;
                default: break;
            }
        }
        // ==============================================================================================
                        // Si se añade un campo a una serie que ya existe
        // ==============================================================================================
        if ($request->seriesw == 'add_serie') {
            $guardar = [];
            if( isset($form->maintenance[$request->nombreserie]) ){
                $collectserie = $form->maintenance[$request->nombreserie];
                $max = isset($collectserie['campos']) ? collect($collectserie['campos'])->sortBy('orden_serie') : [];
                $max = count($max)>0 ? $max->last() : [];
                $max = isset($max['orden_serie']) ? $max['orden_serie'] : "0";
                $info_main[$campo]['orden_serie'] = $max+1;

                if(isset($collectserie['campos'])){
                    $auxCampos = $collectserie['campos'];
                    $camposAsociados = array_merge($auxCampos, $info_main);
                }else{
                    $camposAsociados = $info_main;
                }
                $collectserie['campos'] = $camposAsociados;

                $idSerie = $collectserie['id'];
                $contid = $collectserie['container'] ? $collectserie['container'] : "";
                $subc = $collectserie['subcontainer'] ? $collectserie['subcontainer'] : "";
                $salidaserie[$idSerie] = $collectserie;
                $guardar = isset($form->maintenance) ? array_merge($form->maintenance, $salidaserie) : $salidaserie;
            }
        }
        // ==============================================================================================
                                            // NUEVA SERIE
        // ==============================================================================================
        else{
            $campserie = $camposerie;
            $seriemain = [];
            $seriemain[$campserie]['id'] = $campserie;
            $seriemain[$campserie]['type'] = 'serie';
            $seriemain[$campserie]['mostrar'] = $request->name_new_serie;
            $seriemain[$campserie]['orden'] = $orden;
            $seriemain[$campserie]['container'] = $request->seriecontenedorid;
            $seriemain[$campserie]['subcontainer'] = $request->subcontenedor;

            if($request->selectgrafico == "xvsy_graf"){
                $seriemain[$campserie]['nombre_eje_x'] = $request->nombre_eje_x;
                $seriemain[$campserie]['nombre_eje_y'] = $request->nombre_eje_y;
                $seriemain[$campserie]['tipo_de_grafico_xy'] = $request->tipo_grafico_xy;
                $seriemain[$campserie]['tipografico'] = $request->selectgrafico;
                if(is_array($request->nombre_eje_more) &&  count($request->nombre_eje_more)>0 ){
                    foreach($request->nombre_eje_more as $kno_em => $ejemore){
                        $seriemain[$campserie]['eje_more']['nombre_eje_more'][$kno_em] = $ejemore;
                    }
                }
                // Añadir orden campos adicionales de serie

                $salida = [];
                foreach ($seriemain as $key => $value) {
                    $salida[$key] = $value;
                    if($swFields == "1"){
                        $salida[$key]['campos'] = $info_main;
                        $salida[$key]['campos'][$campo]['orden_serie'] = 0;
                    }
                }
                $guardar = isset($form->maintenance) ? array_merge($form->maintenance, $salida) : $salida;
            }elseif($request->selectgrafico == "serie_graf"){

                $seriemain[$campserie]['valmin'] = $request->valmin;
                $seriemain[$campserie]['valmax'] = $request->valmax;
                $seriemain[$campserie]['tipografico'] = $request->selectgrafico;

                $input_texto = [];
                $input_texto['nro_x_serie']['id'] = 'nro_x_serie';
                $input_texto['nro_x_serie']['type'] = 'text';
                $input_texto['nro_x_serie']['mostrar'] = 'Numero de Series';

                $input_texto2 = [];
                $input_texto2['campos_x_serie']['id'] = 'campos_x_serie';
                $input_texto2['campos_x_serie']['type'] = 'text';
                $input_texto2['campos_x_serie']['mostrar'] = 'Campos Por Serie';

                $info_main = array_merge($info_main,$input_texto, $input_texto2 );
                $salida = [];
                foreach ($seriemain as $key => $value) {
                    $salida[$key] = $value;
                    $salida[$key]['campos'] = $info_main;
                    if($swFields == "1")    $salida[$key]['campos'][$campo]['orden_serie'] = 0;
                }
                $guardar = isset($form->maintenance) ? array_merge($form->maintenance, $salida) : $salida;
            }elseif($request->selectgrafico == "serie_simple"){
                $seriemain[$campserie]['tipografico'] = $request->selectgrafico;
                $input_texto = [];
                $input_texto['&nro_x_serie_simple&']['id'] = '&nro_x_serie_simple&';
                $input_texto['&nro_x_serie_simple&']['type'] = 'number';
                $input_texto['&nro_x_serie_simple&']['mostrar'] = $request->name_new_serie;

                $info_main = array_merge($info_main,$input_texto );
                $salida = [];
                foreach ($seriemain as $key => $value) {
                    $salida[$key] = $value;
                    $salida[$key]['campos'] = $info_main;
                    $salida[$key]['campos'][$campo]['orden_serie'] = 0;
                }
                $guardar = isset($form->maintenance) ? array_merge($form->maintenance, $salida) : $salida;

            }elseif($request->selectgrafico == "serie_multiple"){
                $seriemain[$campserie]['tipografico'] = $request->selectgrafico;
                $seriemain[$campserie]['&nombre_mult1&'] = $request->nombre_mult_1;
                $seriemain[$campserie]['&nombre_mult2&'] = $request->nombre_mult_2;

                if(is_array($request->nombre_multiple_more) &&  count($request->nombre_multiple_more)>0 ){
                    foreach($request->nombre_multiple_more as $kno_em => $ejemore){
                        $seriemain[$campserie]['&multmore&']['nombre_mult_more'][$kno_em] = $ejemore;
                    }
                }

                $input_texto = [];
                $input_texto['&nro_x_serie_mult&']['id'] = '&nro_x_serie_mult&';
                $input_texto['&nro_x_serie_mult&']['type'] = 'text';
                $input_texto['&nro_x_serie_mult&']['mostrar'] = 'Numero de Bancos';

                $input_texto2 = [];
                $input_texto2['&campos_x_serie_mult&']['id'] = '&campos_x_serie_mult&';
                $input_texto2['&campos_x_serie_mult&']['type'] = 'text';
                $input_texto2['&campos_x_serie_mult&']['mostrar'] = 'Numero de Celdas';

                $info_main = array_merge($info_main,$input_texto, $input_texto2 );
                $salida = [];
                foreach ($seriemain as $key => $value) {
                    $salida[$key] = $value;
                    $salida[$key]['campos'] = $info_main;
                    $salida[$key]['campos'][$campo]['orden_serie'] = 0;
                }
                $guardar = isset($form->maintenance) ? array_merge($form->maintenance, $salida) : $salida;

            }
            $contid = $request->seriecontenedorid;
            $subc = $request->subcontenedor;
        }
        $form->maintenance = $guardar;
        $form->update();
        $flasher->addFlash('success', 'Registrado con éxito', 'Gráfico');
        return  \Response::json(['success' => '1','contid'=> $contid,'subconte'=> $subc]);
    }

    public function validateStoreSerie( $request, $count){
        $messages = [
            'seriesw.required' => 'Debe escoger una opción válida',
            'name_new_serie.required'  => 'El campo Nombre de serie es obligatorio',
            'seriecontenedorid.required' => 'Debe escoger un contenedor válido',
            'subcontenedor.required' => 'Debe escoger un sub contenedor válido',
            'selectgrafico.required' => 'Debe escoger un tipo de serie válido',
            'valmin.required'  => 'El campo valor mínimo es obligatorio',
            'valmin.lt' => 'El campo debe ser menor a '.$request->valmax,
            'valmax.required'  => 'El campo valor máximo es obligatorio',
            'tipo_grafico_xy.required' => 'Debe escoger un tipo de gráfico válido',
            'nombre_eje_x.required'  => 'El campo Nombre de eje X es obligatorio',
            'nombre_eje_y.required'  => 'El campo Nombre de eje Y es obligatorio',
            'serieinputType.required'  => 'Debe escoger un tipo de campo válido',
            'seriefield_name.required'  => 'El campo "Nombre del Campo asignado a la Serie" es obligatorio',
            'serietexto_tipo.required'  => 'Debe escoger un tipo de texto válido',
            'serietiposelect.required'  => 'Debe escoger un tipo de select válido',
            'nombreserie.required'  => 'Debe escoger un campo serie válido',
        ];
        if($count>0)
            $validateArray = [
                'seriesw' =>'required',
            ];
        else
            $validateArray = [
                'name_new_serie' =>'required',
                'seriecontenedorid' =>'required',
                'subcontenedor' =>'required',
                'selectgrafico' =>'required',
            ];

        $validateNewSerie = [
            'name_new_serie' =>'required',
            'seriecontenedorid' =>'required',
            'subcontenedor' =>'required',
            'selectgrafico' =>'required',
        ];
        $validateAddSerie = [
            'nombreserie' =>'required',
        ];
        $validateSerieGrafica = [
            'valmin' =>'bail|required|numeric|lt:valmax',
            'valmax' =>'bail|required|numeric',
        ];
        $validateSerieXY = [
            'tipo_grafico_xy' =>'required',
            'nombre_eje_x' =>'required',
            'nombre_eje_y' =>'required',
        ];
        $validateInputType = [
            'serieinputType' => 'required',
        ];
        $validateFields = [
            'seriefield_name' => 'required',
        ];
        $validateRadio = [
            'seriemyOptionsRadio.*' => 'required',
        ];
        $validateCheck = [
            'seriemyOptionsCheck.*' => 'required',
        ];
        $validateSelect = [
            'seriemyOptionsSelect.*' => 'required',
            'serietiposelect' => 'required',
        ];
        $validateText = [
            'serietexto_tipo' => 'required',
        ];

        $swPrinc = $request->seriesw; // nueva, añadir
        $swtiposerie = $request->selectgrafico; // simple, grafica, xvsy
        $swFields = $request->checkaddfields; // check añadir campos
        $tipocampo = $request->serieinputType; // campos radio, select etc
        if($swPrinc == 'new_serie') $validateArray = array_merge($validateArray,$validateNewSerie);

        elseif($swPrinc == 'add_serie'){
            $validateArray = array_merge($validateArray,$validateAddSerie);
            $swFields = "1";
        }
        if($swtiposerie == 'serie_simple')  $swFields = "1";
        if($swtiposerie == 'serie_graf') $validateArray = array_merge($validateArray,$validateSerieGrafica);
        elseif($swtiposerie == 'xvsy_graf') $validateArray = array_merge($validateArray,$validateSerieXY);

        if($swFields == "1" ){
            $validateArray = array_merge($validateArray,$validateInputType);
            if(isset($tipocampo)) $validateArray = array_merge($validateArray,$validateFields);
            if($tipocampo == 'radio') $validateArray = array_merge($validateArray,$validateRadio);
            if($tipocampo == 'checkbox') $validateArray = array_merge($validateArray,$validateCheck);
            if($tipocampo == 'select') $validateArray = array_merge($validateArray,$validateSelect);
            if($tipocampo == 'texto') $validateArray = array_merge($validateArray,$validateText);
        }
        return $request->validate($validateArray,$messages);
    }

    // =====================================================================================================
    // =====================================================================================================
    //                                              FORMS CARTA
    // =====================================================================================================
    // =====================================================================================================
    public function indexLetter(Request $request, $id){
        $forms = StForms::findOrFail(decode($id));
        Session::put('item','5.');
        return view("forms.letter", compact('forms'));
    }

    public function storeLetter(Request $request, FlasherInterface $flasher, $id){
        // VALIDACION DE ESPACIOS
        $cartaaux = $request->letter_body;
        $request['letter_body'] = str_replace(["&nbsp;","\n","\r","\t",'<p>','</p>','<strong>','</strong>','<em>','</em>'], "", $request->letter_body);

        $messages = [
            'letter_body.required'  => 'El cuerpo de la carta es obligatorio.',
        ];
        $request->validate([
            'letter_body' =>'required'],$messages);

        $request['letter_body'] = $cartaaux;

        $form = StForms::findOrFail(decode($id));
        if($form->state != "1"){
            return  \Response::json(['success' => '1']);
        }

        $form->letter_body = $request->letter_body;
        $form->update();
        $flasher->addFlash('info', 'Modificado con éxito', 'Carta');
        return  \Response::json(['success' => '1']);
    }

    // ================================================================================================
    // FUNCIONES AJAX

    public function ajaxSubcontainer(Request $request){
        $query = $request->get('query');
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

    public function ajaxSelectForms(Request $request){
        $request['search'] = limpiarTexto($request->search,'s2');
        $array = [];
        if($request->sw == 'filter'){
            $area = (decode($request->area) > 0) ? decode($request->area) : '';
            $category = (decode($request->category) > 0) ? decode($request->category) : '';
            $forms = StForms::select('id','name')
                    ->whereIn('state',['1','2'])
                    ->Name($request->search)
                    ->TypeAS($request->typeot)
                    ->AreaId($area)
                    ->CategoryId($category)
                    ->orderBy('name')
                    ->get();

            $array['results'][0]['id'] = "t";
            $array['results'][0]['text'] = "Todos";
        }else{
            if($request->type == '2' ){
                $aux = StForms::select('id','name')->where('category_id','0')->whereIn('state',['1','2'])->orderBy('name')->get();
            }else{
                if($request->asset == '0'){
                    $aux = StForms::select('id','name')->where('category_id','0')->whereIn('state',['1','2']);
                }else{
                    $assetid = decode($request->asset);
                    $assets = StAssets::where('id',$assetid)->first();
                    $idCat = isset($assets->categories->id)? $assets->categories->id : '-1';
                    $aux = StForms::select('id','name')->where('category_id',$idCat)->whereIn('state',['1','2']);
                }
                $aux = $aux->orderBy('name')->get();
            }

            $search = $request->search;
            $forms = collect($aux)->filter(function ($form) use ($search) {
                if(isset($search) && $search != "")
                    return false !== stripos($form->name, $search);
                else
                    return $form;
            })->sortBy('name');

            $array['results'][0]['id'] = "";
            $array['results'][0]['text'] = "Seleccione una opción";
        }

        $k = 0;
        foreach($forms as $form){
            $array['results'][$k+1]['id'] = code($form->id);
            $array['results'][$k+1]['text'] = $form->name;
            $k++;
        }
        $array['pagination']['more'] = false;
        return response()->json($array);
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