<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;
use Session;

class ClientsController extends Controller
{
    public function index (Request $request){
        Session::put('item','4.');
        return view('clients.index', compact('tipoCliente','tipoC','areaC','levels','slevel'));
    }
}
