<?php

namespace App\Http\Controllers;

use App\Exports\InventoryExport;
use App\FilesExport;
use App\Inventory;
use App\InvParameters;
use App\InvStocks;
use App\Jobs\exportFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;
use Flasher\Prime\FlasherInterface;
use Session;
use DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class InventoryController extends Controller
{
    public function index (Request $request){
        Session::put('item','4.0:');
        $selectEstado = $request->selectEstado != null ? $request->selectEstado : 'all';
        return view("inventory.index", compact('selectEstado'));
    }

    // TABLA INDEX DE INVENTARIO AJAX
    public function tableInventory(Request $request){
        $totalData = Inventory::count();
        $totalFiltered = $totalData;

        $limit = empty($request->input('length')) ? 10 : $request->input('length');
        $start = empty($request->input('start')) ? 0 :  $request->input('start');

        $posts = Inventory::
        Cod($request->input('columns.0.search.value'))
        ->Title($request->input('columns.1.search.value'))
        ->Description($request->input('columns.2.search.value'))
        ->Quantity($request->input('columns.3.search.value'))
        ->MinCant($request->input('columns.4.search.value'))
        ->Unit($request->input('columns.5.search.value'))
        ->State($request->get('state'));

        $totalFiltered = $posts->count();
        $posts = $posts
        ->offset($start)
        ->limit($limit)
        ->orderBy('id','desc')
        ->get();

        $data = array();
        foreach ($posts as $post){
            $nestedData['cod'] = $post->getCod();
            $nestedData['title'] = $post->title;
            $nestedData['description'] = purify(nl2br($post->description));
            $nestedData['quantity'] = $post->getQuantity();
            $nestedData['min_cant'] = $post->min_cant;
            $nestedData['unit'] = $post->unit;
            $nestedData['category'] = isset($post->categories) ? $post->categories->nombre : "<i class='text-sm'>Sin categoría</i>";
            $nestedData['location'] = isset($post->locations) ? $post->locations->nombre : "<i class='text-sm'>S/N</i>";
            $nestedData['state'] = $post->estado;
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

    // ===============================================================================
    //                             FUNCIONES CRUD
    // ===============================================================================
    public function modalCreate(){
        canPassAdminJefe();
        return view("inventory.modalCreate");
    }

    public function store(Request $request, FlasherInterface $flasher){
        canPassAdminJefe();
        $validateArray = [
            'nombre' =>'required|max:100',
            'unidad' =>'required',
            'descripcion' => 'nullable|min:2|max:255',
            'cantidadmin' => 'required',
            'fileInventory' => 'mimes:gif,jpg,jpeg,png|max:5192',
        ];
        $validateInicial = [
            'ubicacion' =>'required',
            'cantidad' =>'bail|numeric|required',
        ];

        $aliasArray = [
            'nombre' => '<b>Nombre</b>',
            'unidad' => '<b>Unidad de medidad</b>',
            'descripcion' => '<b>Descripción</b>',
            'cantidadmin' => '<b>Cantidad mínima</b>',
            'fileInventory' => '<b>Archivo adjunto</b>',
            'ubicacion' => '<b>Almacen</b>',
            'cantidad' => '<b>Cantidad</b>',
        ];

        if($request->inicial == 1){
            $validateArray = array_merge($validateArray, $validateInicial);
        }
        $request->validate($validateArray, [], $aliasArray);

        $reg_maximo = Inventory::select('cod')->where('cod', 'LIKE', "%IN%")->max('cod');
        $cod = generateCode($reg_maximo,'IN000001','IN',2,6);

        DB::beginTransaction();
        try {
            $inventory = new Inventory();
            $inventory->cod = $cod;
            $inventory->title = $request->nombre;
            $inventory->unit = $request->unidad;
            $inventory->description = $request->descripcion;
            $inventory->min_cant = monedaVal($request->cantidadmin);
            $inventory->active = 1;
            $inventory->quantity = monedaVal($request->cantidad);
                // ALMACENAMIENTO DEL ARCHIVO
                if ( $request->hasFile('fileInventory') ){
                    $archivo = $request->file('fileInventory');
                    $nombreConExtension = $archivo->getClientOriginalName();
                    $nombreConExtension = delete_charspecial($nombreConExtension);
                    $inventory->attach = $cod . '_' . strtolower($nombreConExtension);
                    $archivo->storeAs("public/inventory/", $inventory->attach);
                    $size = getimagesize($archivo);
                    if($size[0]<=1024 && $size[1]<=1024){
                        InterventionImage::make($archivo)->resize(function ($constraint){
                            $constraint->aspectRatio();
                        })->save(storage_path().'/app/public/inventory/'.$inventory->attach, 90);
                    }else{
                        InterventionImage::make($archivo)->resize(1024,1024, function ($constraint){
                            $constraint->aspectRatio();
                        })->save(storage_path().'/app/public/inventory/'.$inventory->attach, 80);
                    }

                    $archivo->storeAs("public/inventory/thumbnail/", $inventory->attach);
                    InterventionImage::make($archivo)->resize(150,150, function ($constraint){
                        $constraint->aspectRatio();
                    })->save(storage_path().'/app/public/inventory/thumbnail/'.$inventory->attach,90);

                }
            $inventory->save();
            if($request->inicial == 1){
                $stock = new InvStocks();
                $stock->item_id = $inventory->id;
                $stock->incomes = monedaVal($request->cantidad);
                $stock->origen_type = 'A0';
                $stock->origen_id = $inventory->id;
                $stock->location = $request->ubicacion;
                $stock->date = now();
                $stock->save();
            }
            $flasher->addFlash('success', 'Creado con éxito', 'Material '.$inventory->cod);
            DB::commit();
            return  \Response::json(['success' => '1']);
        }catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    public function modalEdit($id){
        canPassAdminJefe();
        $item = Inventory::findOrFail(decode($id));
        $swImg = false;
        if( isset($item->attach) && $item->attach != '' ){
            $swImg = Storage::exists("public/inventory/thumbnail/".$item->attach);
        }
        return view("inventory.modalEdit", compact('item','swImg'));
    }

    public function update(Request $request, FlasherInterface $flasher, $id){
        canPassAdminJefe();
        $validateArray = [
            'nombreedit' =>'required|max:100',
            'unidadedit' =>'required',
            'descripcionedit' => 'required',
            'cantidadminedit' => 'required',
        ];
        $validateFile = [
            'fileInventoryedit' => 'required|mimes:gif,jpg,jpeg,png,mp4|max:5192',
        ];
        if($request->cambioarchivo == 1 ){
            $validateArray = array_merge($validateArray, $validateFile);
        }

        $aliasArray = [
            'nombreedit' => '<b>Nombre</b>',
            'unidadedit' => '<b>Unidad de medidad</b>',
            'descripcionedit' => '<b>Descripción</b>',
            'cantidadminedit' => '<b>Cantidad mínima</b>',
            'fileInventoryedit' => '<b>Archivo adjunto</b>',
            'ubicacionedit' => '<b>Almacen</b>',
            'cantidadedit' => '<b>Cantidad</b>',
        ];

        $request->validate($validateArray, [], $aliasArray);

        $inventory = Inventory::findOrFail(decode($id));
        $inventory->title = $request->nombreedit;
        $inventory->unit = $request->unidadedit;
        $inventory->description = $request->descripcionedit;
        $inventory->min_cant =  number_format($request->cantidadminedit,2);
            // ALMACENAMIENTO DEL ARCHIVO
            if ( $request->hasFile('fileInventoryedit') && $request->cambioarchivo == 1 ){
                if (Storage::exists('public/inventory/'.$inventory->attach)){
                    Storage::delete('public/inventory/'.$inventory->attach);
                }
                if (Storage::exists('public/inventory/thumbnail/'.$inventory->attach)){
                    Storage::delete('public/inventory/thumbnail/'.$inventory->attach);
                }
                $archivo = $request->file('fileInventoryedit');
                $nombreConExtension = $archivo->getClientOriginalName();
                $nombreConExtension = delete_charspecial($nombreConExtension);
                $inventory->attach = $inventory->cod . '_' . strtolower($nombreConExtension);
                $archivo->storeAs("public/inventory/", $inventory->attach);
                $size = getimagesize($archivo);
                if($size[0]<=1024 && $size[1]<=1024){
                    InterventionImage::make($archivo)->resize(function ($constraint){
                        $constraint->aspectRatio();
                    })->save(storage_path().'/app/public/inventory/'.$inventory->attach, 90);
                }else{
                    InterventionImage::make($archivo)->resize(1024,1024, function ($constraint){
                        $constraint->aspectRatio();
                    })->save(storage_path().'/app/public/inventory/'.$inventory->attach, 80);
                }

                $archivo->storeAs("public/inventory/thumbnail/", $inventory->attach);
                InterventionImage::make($archivo)->resize(150,150, function ($constraint){
                    $constraint->aspectRatio();
                })->save(storage_path().'/app/public/inventory/thumbnail/'.$inventory->attach,90);
            }
        $inventory->update();
        $flasher->addFlash('info', 'Modificado con éxito', 'Material '.$inventory->cod);
        return  \Response::json(['success' => '1']);
    }

    public function updateImage(Request $request, FlasherInterface $flasher, $id){
        $messages = [
            'fileItems.required' => 'Debe adjuntar una imagen',
            'fileItems.mimes' => 'El archivo debe estar en algunos de los siguientes formatos:<br><p class="text-center"><b>gif, jpg, jpeg, png</b></p>',
            'fileItems.max' => "El archivo a subir es muy grande. El tamaño máximo admitido es de 5 MB (5192 KB).",
        ];

        $validateArray = [
            'fileItems' => 'required|mimes:gif,jpg,jpeg,png|max:5192'
        ];
        $request->validate($validateArray,$messages);
        $item = Inventory::findOrFail(decode($id));
        // ALMACENAMIENTO DEL ARCHIVO
        if ( $request->hasFile('fileItems') ){

            if (Storage::exists('public/inventory/'.$item->attach)){
                Storage::delete('public/inventory/'.$item->attach);
            }
            if (Storage::exists('public/inventory/thumbnail/'.$item->attach)){
                Storage::delete('public/inventory/thumbnail/'.$item->attach);
            }

            $archivo = $request->file('fileItems');
            $nombreConExtension = $archivo->getClientOriginalName();
            $nombreConExtension = delete_charspecial($nombreConExtension);
            $item->attach = $item->cod . '_' . strtolower($nombreConExtension);
            $archivo->storeAs("public/inventory/", $item->attach);
            $size = getimagesize($archivo);
            if($size[0]<=1024 && $size[1]<=1024){
                InterventionImage::make($archivo)->resize(function ($constraint){
                    $constraint->aspectRatio();
                })->save(storage_path().'/app/public/inventory/'.$item->attach, 90);
            }else{
                InterventionImage::make($archivo)->resize(1024,1024, function ($constraint){
                    $constraint->aspectRatio();
                })->save(storage_path().'/app/public/inventory/'.$item->attach, 80);
            }

            $archivo->storeAs("public/inventory/thumbnail/", $item->attach);
            InterventionImage::make($archivo)->resize(150,150, function ($constraint){
                $constraint->aspectRatio();
            })->save(storage_path().'/app/public/inventory/thumbnail/'.$item->attach,90);
        }
        $item->update();
        $flasher->addFlash('info', 'Adjuntada con éxito', 'Imagen de '.$item->title);
        return  \Response::json(['success' => '1']);
    }

    public function modalDelete($id){
        canPassAdminJefe();
        $item = Inventory::findOrFail(decode($id));
        return view("inventory.modalDelete", compact('item'));
    }

    public function destroy(FlasherInterface $flasher, $id){
        canPassAdminJefe();
        $item = Inventory::findOrFail(decode($id));
        if($item->getCantDetails() == 0 ){
            if (Storage::exists('public/inventory/'.$item->attach)){
                Storage::delete('public/inventory/'.$item->attach);
            }
            if (Storage::exists('public/inventory/thumbnail/'.$item->attach)){
                Storage::delete('public/inventory/thumbnail/'.$item->attach);
            }
            $item->delete();
            $flasher->addFlash('error', 'Eliminado correctamente', 'Material '.$item->cod);
        }else{
            $flasher->addFlash('error', 'El registro no se eliminó', 'Error');
        }
        return redirect()->route('inventory.index');
    }

    function changeStatus(FlasherInterface $flasher, $id, $estado){
        $inventory = Inventory::findOrFail(decode($id));
        $inventory->active = $estado == 1 ? 0 : 1;
        $inventory->update();
        if($estado == 1){
            $flasher->addFlash('error', 'Desactivado correctamente', 'Material '.$inventory->cod);
        }else{
            $flasher->addFlash('info', 'Activado correctamente', 'Material '.$inventory->cod);
        }

        return redirect()->route('inventory.index');
    }

        // ===============================================================================
    //                                    KARDEX
    // ===============================================================================
    public function kardex($id){
        canPassAdminJefe();
        $item = Inventory::findOrFail(decode($id));
        $qrcode  = "Nombre: ".$item->title."\r\nCodigo material: ".$item->cod;
        $qrcode .= "\r\nCantidad disponible: ".number_format($item->TotalItem,2,".","");
        $details = InvStocks::selectRaw("*, SUM(incomes) as ingresos, SUM(outcomes) as egresos")->where('item_id',$item->id)->groupBy('location')->get();

        Session::put('item','4.0:');
        return view("inventory.kardex", compact('item','qrcode','details'));
    }

    public function tableKardexDetails(Request $request){
        $item = $request->get('item');
        $totalData = InvStocks::where('item_id',decode($item))->groupBy('location')->count();

        $totalFiltered = $totalData;
        $limit =( empty($request->input('length'))  ) ? $limit = 10 : $limit = $request->input('length');
        $start =( empty($request->input('start'))  ) ? $start = 0 :  $start = $request->input('start');

        $posts = InvStocks::where('item_id',decode($item));
        $totalFiltered=$posts->count();
        $posts=$posts
        ->offset($start)
        ->limit($limit)
        ->orderBy('date','asc')
        ->get();

        $balance = $val_balance = 0;
        $last = '';
        $data = array();
        foreach ($posts as $k=>$post) {
            $balance += $post->incomes;
            $balance -= $post->outcomes;
            $debit = $post->incomes*$post->unit_cost;
            $credit = $post->outcomes*$post->unit_cost;
            $val_balance += $debit;
            $val_balance -= $credit;

            $nestedData['orden'] = ++$k;
            $nestedData['date'] = isset($post->date) ? date("d/m/Y",strtotime($post->date)) : "";
            $nestedData['in'] = $post->getIn();
            $nestedData['out'] = $post->getOut();
            $nestedData['balance'] = number_format($balance,2,'.','');
            $nestedData['origin'] = $post->getOrigenLink();
            $nestedData['location'] = $post->almacenLiteral;
            // Datos de kardex valorado
            $nestedData['indicator'] = $post->getIndicatorKardex($last);
            $nestedData['unit_cost'] = number_format($post->unit_cost,2,'.',',');
            $nestedData['debit'] = ($debit > 0) ?  '<span class="text-teal">'.number_format($debit,2,'.',',').'</span>' : number_format($debit,2,'.',',');
            $nestedData['credit'] = ($credit > 0) ?   '<span class="text-pink">'.number_format($credit,2,'.',',').'</span>': number_format($credit,2,'.',',');
            $nestedData['val_balance'] = '<b class="text-yellow">'.number_format($val_balance,2,'.',',').'</b>';

            $last = round($post->unit_cost,2);

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

        // ===================================================================================================
    //                                 MODAL Y TABLA PARA SELECCIONAR ITEMS
    // ===================================================================================================
    public function modalItems(){
        return view("inventory.modalItems");
    }

    public function tableItems(Request $request){
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
            $boton = '<button type="button" class="modalbtn btn border border-'.$class.' btn-outline-'.$class.'" data-cod="'.$post->cod.' - '.$post->title.'" id="'.code($post->id).'" data-cant="'.$post->quantity.'" >Seleccionar</button>';
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

}
