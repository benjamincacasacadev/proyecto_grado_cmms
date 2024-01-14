<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StForms;
use App\StFormType;
use App\WorkOrders;
use Session;
use Illuminate\Validation\Rule;
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








}