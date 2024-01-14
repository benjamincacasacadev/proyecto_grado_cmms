<?php

namespace App\Http\Controllers;

use App\Clients;
use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;
use Illuminate\Validation\Rule;
use Session;

class ClientsController extends Controller
{
    public function index (Request $request){
        Session::put('item','3.');
        return view('clients.index');
    }

    public function modalCreate(){
        return view('clients.modalCreate');
    }

    public function store(Request $request, FlasherInterface $flasher) {
        $this->validateClients($request);

        $client = new Clients();
        $client->nombre = $request->nombre;
        $client->nit = $request->nit;
        $client->tipo = $request->tipo;
        $client->caracteristicas = $request->caracteristicas;
        $client->direccion = $request->direccion;
        $client->nombre_contacto = $request->nombreContacto;
        $client->cargo_contacto = $request->cargo;
        $client->celular_contacto = $request->celular;
        $client->email_contacto = $request->email;
        $client->estado = 1;
        $client->save();

        $flasher->addFlash('success', 'Creado con éxito', 'Cliente '.$client->nombre);
        return  \Response::json(['success' => '1']);
    }

    public function tableClients(Request $request){
        $totalData = Clients::count();
        $totalFiltered = $totalData;

        $limit = empty($request->input('length')) ? 10 : $request->input('length');
        $start = empty($request->input('start')) ? 0 :  $request->input('start');

        $posts = Clients::Nombre($request->input('columns.0.search.value'))
        ->Nit($request->input('columns.1.search.value'))
        ->Tipo($request->input('columns.2.search.value'))
        ->Caracteristicas($request->input('columns.3.search.value'))
        ->Direccion($request->input('columns.4.search.value'))
        ->Contacto($request->input('columns.5.search.value'))
        ->Estado($request->input('columns.6.search.value'));

        $totalFiltered = $posts->count();
        $posts = $posts
        ->offset($start)
        ->limit($limit)
        ->orderBy('id','desc')
        ->get();

        $data = array();
        foreach ($posts as $post){
            $nestedData['nombre'] = $post->nombre;
            $nestedData['nit'] = $post->nit;
            $nestedData['tipo'] = $post->tipoLiteral;
            $nestedData['caracteristicas'] = purify(nl2br($post->caracteristicas));
            $nestedData['direccion'] = purify(nl2br($post->direccion));
            $nestedData['datosContacto'] = $post->datosContacto;
            $nestedData['estado'] = $post->estadoShow;
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

    public function validateClients(Request $request, $id = ''){
        $edit = $id != '' ? 'edit' : '';

        $nombre = 'nombre'.$edit;
        $nit = 'nit'.$edit;
        $tipo = 'tipo'.$edit;
        $caracteristicas = 'caracteristicas'.$edit;
        $direccion = 'direccion'.$edit;
        $nombreContacto = 'nombreContacto'.$edit;
        $cargo = 'cargo'.$edit;
        $celular = 'celular'.$edit;
        $email = 'email'.$edit;


        $validateNit = ['required', Rule::unique('clients')->where(function ($query) {
            $query->where('estado','1');
        })];

        // $validateNit = ['required',Rule::unique('clients')->ignore(decode($this->route('id')))->where(function ($query) {
        //     $query->where('empresa_id',empresaId())->where('activo','1')->where('tipo_identificacion','!=','07');
        // })];

        $validateArray = [
            $nombre => 'bail|required|min:2|max:255',
            $nit => $validateNit,
            $tipo => 'bail|required|max:1',
            $caracteristicas => 'nullable|min:3|max:255',
            $direccion => 'nullable|min:3|max:255',
            $nombreContacto => 'bail|required|min:3|max:100',
            $cargo => 'bail|required|min:3|max:100',
            $celular => 'bail|required|min:3|max:20',
            $email => 'bail|required|max:20|email:filter',
        ];

        $aliasArray = [
            $nombre => '<b>Nombre</b>',
            $nit => '<b>Número de NIT</b>',
            $tipo => '<b>Tipo de cliente</b>',
            $caracteristicas => '<b>Caracteristicas/Rubro</b>',
            $direccion => '<b>Dirección</b>',
            $nombreContacto => '<b>Nombre completo</b>',
            $cargo => '<b>Cargo</b>',
            $celular => '<b>Celular</b>',
            $email => '<b>Email</b>',
        ];

        return $request->validate($validateArray, [], $aliasArray);

    }
}
