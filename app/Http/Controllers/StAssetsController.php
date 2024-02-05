<?php

namespace App\Http\Controllers;

use App\StAssets;
use App\WorkOrders;
use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;
use Session;

class StAssetsController extends Controller
{
    public function index(){
        Session::put('item','2.');
        return view('assets.index');
    }

    public function show(Request $request, $id){
        $asset = StAssets::findOrFail(decode($id));
        Session::put('item','2.');
        return view('assets.show', compact('asset'));
    }

    public function modalCreate(){
        canPassAdminJefe();
        return view('assets.modalCreate');
    }

    public function store(Request $request, FlasherInterface $flasher) {
        canPassAdminJefe();
        $this->validateAssets($request);

        // Guardar los campos de activo
        $registroMaximo = StAssets::select('cod')->where('cod', 'LIKE', "%AS%")->max('cod');
        $cod = generateCode($registroMaximo,'AS000001','AS',2,6);

        $asset = new StAssets();
        $asset->cod = $cod;
        $asset->client_id = decode($request->cliente);
        $asset->nombre = $request->nombre;
        $asset->categoria = $request->categoria;
        $asset->ubicacion = $request->ubicacion;
        $asset->ciudad = $request->ciudad;
        $asset->nro_serie = $request->nro_serie;
        $asset->marca = $request->marca;
        $asset->modelo = $request->modelo;
        $asset->capacidad = $request->capacidad;
        $asset->estado = 1;
        if ($request->hasFile('fileAssets')) {
            $archivo = $request->file('fileAssets');
            $extension = $archivo->getClientOriginalExtension();
            $ext = strtolower($extension);
            $nombreConExtension = $archivo->getClientOriginalName();
            $nombreConExtension = delete_charspecial($nombreConExtension);
            $asset->attach = $cod . '_' . strtolower($nombreConExtension);

            $archivo->storeAs("public/assets/", $asset->attach);
            if($ext == 'gif' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png'){
                $size = getimagesize($archivo);
                if($size[0]<=1024 && $size[1]<=1024){
                    InterventionImage::make($archivo)->resize(function ($constraint){
                        $constraint->aspectRatio();
                    })->save(storage_path().'/app/public/assets/'.$asset->attach, 90);
                }else{
                    InterventionImage::make($archivo)->resize(1024,1024, function ($constraint){
                        $constraint->aspectRatio();
                    })->save(storage_path().'/app/public/assets/'.$asset->attach, 80);
                }
            }
        }
        $asset->save();

        $flasher->addFlash('success', 'Creado con éxito', 'Activo '.$asset->cod);
        return  \Response::json(['success' => '1']);
    }

    public function tableAssets(Request $request){
        $totalData = StAssets::count();
        $totalFiltered = $totalData;

        $limit = empty($request->input('length')) ? 10 : $request->input('length');
        $start = empty($request->input('start')) ? 0 :  $request->input('start');

        $posts = StAssets::Cod($request->input('columns.0.search.value'))
        ->Nombre($request->input('columns.1.search.value'))
        ->Cliente($request->input('columns.2.search.value'))
        ->Categoria($request->input('columns.3.search.value'))
        ->Ubicacion($request->input('columns.4.search.value'))
        ->Ciudad($request->input('columns.5.search.value'))
        ->Serie($request->input('columns.6.search.value'))
        ->DatosActivo($request->input('columns.7.search.value'))
        ->Estado($request->input('columns.8.search.value'))
        ->with('cliente');

        $totalFiltered = $posts->count();
        $posts = $posts
        ->offset($start)
        ->limit($limit)
        ->orderBy('id','desc')
        ->get();

        $data = array();
        foreach ($posts as $post){
            $nestedData['cod'] = $post->getCod();
            $nestedData['nombre'] = $post->nombre;
            $nestedData['cliente'] = $post->cliente->nombre;
            $nestedData['categoria'] = $post->categoriaLiteral;
            $nestedData['ubicacion'] = $post->ubicacion;
            $nestedData['ciudad'] = $post->ciudadLiteral;
            $nestedData['serie'] = $post->nro_serie;
            $nestedData['datosContacto'] = $post->datosActivo;
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

    public function modalEdit($id){
        canPassAdminJefe();
        $asset = StAssets::findOrFail(decode($id));
        return view('assets.modalEdit', compact('asset'));
    }

    public function update(Request $request, FlasherInterface $flasher, $id){
        canPassAdminJefe();
        $this->validateAssets($request, $id);

        $asset = StAssets::findOrFail(decode($id));
        $asset->client_id = decode($request->clienteedit);
        $asset->nombre = $request->nombreedit;
        $asset->categoria = $request->categoriaedit;
        $asset->ubicacion = $request->ubicacionedit;
        $asset->ciudad = $request->ciudadedit;
        $asset->nro_serie = $request->nro_serie;
        $asset->marca = $request->marcaedit;
        $asset->modelo = $request->modeloedit;
        $asset->capacidad = $request->capacidadedit;
        $asset->estado = 1;
        if ($request->hasFile('fileAssetsedit')) {
            $rutaFile = 'public/assets/'.$asset->attach;
            if (Storage::exists($rutaFile)){
                Storage::delete($rutaFile);
            }

            $archivo = $request->file('fileAssetsedit');
            $extension = $archivo->getClientOriginalExtension();
            $ext = strtolower($extension);
            $nombreConExtension = $archivo->getClientOriginalName();
            $nombreConExtension = delete_charspecial($nombreConExtension);
            $asset->attach = $asset->cod . '_' . strtolower($nombreConExtension);

            $archivo->storeAs("public/assets/", $asset->attach);
            if($ext == 'gif' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png'){
                $size = getimagesize($archivo);
                if($size[0]<=1024 && $size[1]<=1024){
                    InterventionImage::make($archivo)->resize(function ($constraint){
                        $constraint->aspectRatio();
                    })->save(storage_path().'/app/public/assets/'.$asset->attach, 90);
                }else{
                    InterventionImage::make($archivo)->resize(1024,1024, function ($constraint){
                        $constraint->aspectRatio();
                    })->save(storage_path().'/app/public/assets/'.$asset->attach, 80);
                }
            }
        }
        $asset->update();

        $flasher->addFlash('info', 'Modificado con éxito', 'Activo '.$asset->cod);
        return  \Response::json(['success' => '1']);
    }

    public function modalDelete($id){
        canPassAdminJefe();
        $asset = StAssets::findOrFail(decode($id));
        $count = WorkOrders::where('asset_id',$asset->id)->count();
        return view('assets.modalDelete', compact('asset','count'));
    }

    public function destroy(FlasherInterface $flasher, $id){
        canPassAdminJefe();
        $asset = StAssets::findOrFail(decode($id));
        $count = WorkOrders::where('asset_id',$asset->id)->count();
        if($count > 0){
            $flasher->addFlash('warning', 'Tiene órdenes de trabajo asociadas', 'No se puede eliminar al activo '.$asset->cod);
            return redirect()->route('assets.index');
        }
        $rutaFile = 'public/assets/'.$asset->attach;
        if (Storage::exists($rutaFile)){
            Storage::delete($rutaFile);
        }
        $asset->delete();
        $flasher->addFlash('error', 'Eliminado correctamente', 'Activo '.$asset->cod);
        return redirect()->route('assets.index');
    }

    function changeEstado(FlasherInterface $flasher, $id, $estado){
        canPassAdminJefe();
        $asset = StAssets::where('id',decode($id) )->first();
        if ($estado == 1) {
            $asset->estado = '0';
            $asset->update();
            $flasher->addFlash('warning', 'Inhabilitado correctamente', 'Activo '.$asset->nombre);
        } else {
            ##PREGUNTAMOS SI ALGUIEN YA TIENE ESE NIT
            $asset_nro_iden = StAssets::where('estado','1')->where('nro_serie',$asset->nro_serie)->first();
            if($asset_nro_iden){
                $flasher->addFlash('info', 'Número de serie "'.$asset->nro_serie.'"', 'Ya existe un activo con el');
                return redirect()->route('assets.index');
            }
            $asset->estado = '1';
            $asset->update();
            $flasher->addFlash('success', 'Habilitado correctamente', 'Activo '.$asset->nombre);
        }
        return redirect()->route('assets.index');
    }

    public function validateAssets(Request $request, $id = ''){
        $edit = $id != '' ? 'edit' : '';

        $cliente = 'cliente'.$edit;
        $nombre = 'nombre'.$edit;
        $ciudad = 'ciudad'.$edit;
        $ubicacion = 'ubicacion'.$edit;
        $categoria = 'categoria'.$edit;
        $serie = 'nro_serie';
        $marca = 'marca'.$edit;
        $modelo = 'modelo'.$edit;
        $capacidad = 'capacidad'.$edit;
        $fileAssets = 'fileAssets'.$edit;

        $validateSerie = ['required', Rule::unique('st_assets')->where(function ($query) {
            $query->where('estado','1');
        })];

        if($id != ''){
            $validateSerie = ['required', Rule::unique('st_assets')->ignore(decode($id))->where(function ($query) {
                $query->where('estado','1');
            })];
        }

        $valFile = 'mimes:jpg,jpeg,png|max:2048';
        if($request->cambioarchivo == 1){
            $valFile = 'required|mimes:jpg,jpeg,png|max:2048';
        }

        $validateArray = [
            $cliente => 'required',
            $nombre => 'bail|required|min:2|max:255',
            $ciudad => 'required',
            $ubicacion => 'bail|required|min:2|max:255',
            $categoria => 'required',
            $serie => $validateSerie,
            $marca => 'bail|required|min:2|max:255',
            $modelo => 'bail|required|min:2|max:255',
            $capacidad => 'bail|required|min:2|max:255',
            $fileAssets => $valFile,
        ];

        $aliasArray = [
            $cliente => '<b>Cliente</b>',
            $nombre => '<b>Nombre de activo</b>',
            $ciudad => '<b>Ciudad</b>',
            $ubicacion => '<b>Ubicación</b>',
            $categoria => '<b>Categoria</b>',
            $serie => '<b>Número de serie</b>',
            $marca => '<b>Marca</b>',
            $modelo => '<b>Modelo</b>',
            $capacidad => '<b>Capacidad/Potencia</b>',
            $fileAssets => '<b>Archivo adjunto</b>',
        ];

        return $request->validate($validateArray, [], $aliasArray);
    }

    public function updateImage(Request $request, FlasherInterface $flasher, $id){
        $messages = [
            'fileAsset.required' => 'Debe adjuntar una imagen',
            'fileAsset.mimes' => 'El archivo debe estar en algunos de los siguientes formatos:<br><p class="text-center"><b>gif, jpg, jpeg, png</b></p>',
            'fileAsset.max' => "El archivo a subir es muy grande. El tamaño máximo admitido es de 2 MB (2048 KB).",
        ];

        $validateArray = [
            'fileAsset' => 'required|mimes:gif,jpg,jpeg,png|max:2048'
        ];
        $request->validate($validateArray,$messages);
        $asset = StAssets::findOrFail(decode($id));
        // ALMACENAMIENTO DEL ARCHIVO
        if ( $request->hasFile('fileAsset') ){

            $rutaFile = 'public/assets/'.$asset->attach;
            if (Storage::exists($rutaFile)){
                Storage::delete($rutaFile);
            }

            $archivo = $request->file('fileAsset');
            $extension = $archivo->getClientOriginalExtension();
            $ext = strtolower($extension);
            $nombre = $request->get('__renombraredit__');
            $nombre = $this->limpiar($nombre);
            $nombreConExtension = ($nombre != null && $nombre != '') ? $nombre . '.' . $extension : $archivo->getClientOriginalName();
            $nombreConExtension = delete_charspecial($nombreConExtension);

            $asset->attach = $asset->cod . '_' . strtolower($nombreConExtension);
            $archivo->storeAs("public/assets/", $asset->attach);

            if($ext == 'gif' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png'){
                $size = getimagesize($archivo);
                if($size[0]<=1024 && $size[1]<=1024){
                    InterventionImage::make($archivo)->resize(function ($constraint){
                        $constraint->aspectRatio();
                    })->save(storage_path().'/app/public/assets/'.$asset->attach, 90);
                }else{
                    InterventionImage::make($archivo)->resize(1024,1024, function ($constraint){
                        $constraint->aspectRatio();
                    })->save(storage_path().'/app/public/assets/'.$asset->attach, 80);
                }
            }
        }
        $asset->update();
        $flasher->addFlash('info', 'Modificada con éxito', 'Imagen de '.$asset->cod);
        return  \Response::json(['success' => '1']);
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

    public function listAssetsDetailsAjax(Request $request) {
        $request['search'] = limpiarTexto($request->search,'s2');

        $assets = StAssets::CodName($request->search)
        ->orderBy('cod','desc')
        ->where('estado','1')
        ->limit(20)
        ->get();

        $array = [];
        $array['results'][0]['id'] = "";
        $array['results'][0]['text'] = "Seleccione una opción";
        $array['results'][0]['info'] = "";
        foreach ($assets as $k => $asset) {
            $array['results'][$k + 1]['id'] = code($asset->id);
            $array['results'][$k + 1]['text'] = $asset->cod.' - '.$asset->nombre;
            $array['results'][$k + 1]['info'] = $asset->getInfoAssets('img');
        }
        $array['pagination']['more'] = false;
        return response()->json($array);
    }
}
