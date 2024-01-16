<?php

namespace App\Http\Controllers;

use App\WorkOrders;
use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;
use Session;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request){

                // =============================================================================================
        //                                 Grafico Órdenes de trabajo
        // =============================================================================================
        $cWO = WorkOrders::
        selectRaw('COUNT(id) as total,
        sum(case when estado = "P" then 1 else 0 end) AS pendiente,
        sum(case when estado = "E" then 1 else 0 end) AS progreso,
        sum(case when estado = "R" then 1 else 0 end) AS revision,
        sum(case when estado = "T" then 1 else 0 end) AS terminado,
        sum(case when estado = "S" then 1 else 0 end) AS en_pausa,
        sum(case when estado = "C" then 1 else 0 end) AS en_correccion,
        sum(case when estado = "X" then 1 else 0 end) AS anulado')
        ->first();

        $nameArr = [
            'Pendientes',
            'En progreso',
            'En pausa',
            'En revisión',
            'En corrección',
            'Anulado',
            'Terminados',
        ];
        $valArr = [
            $cWO->pendiente,
            $cWO->progreso,
            $cWO->en_pausa,
            $cWO->revision,
            $cWO->en_correccion,
            $cWO->anulado,
            $cWO->terminado,
        ];
        $colorArr = [
            '#edb66a',
            '#337AB7',
            '#f76707',
            '#8ebde2',
            '#ae3ec9',
            '#d63939',
            '#66c474',
        ];

        $arrayData = [];
        foreach ($nameArr as $k => $array) {
            $arrayData[$k]['name'] = $array;
            $arrayData[$k]['y'] = (int)$valArr[$k];
            $arrayData[$k]['color'] = $colorArr[$k];
        }

        $jsonDataOT = json_encode($arrayData);

        Session::put('item','0.');
        return view('home', compact('jsonDataOT'));
    }
}
