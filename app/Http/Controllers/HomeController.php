<?php

namespace App\Http\Controllers;

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
    public function index(Request $request, FlasherInterface $flasher){
        // $request->session()->flash('info', 'Task was successful!');

        $options = array( 'duration' => 3000, 'dismissible' =>true, 'ripple' =>true, 'position' => 'top' , 'center');
        // $flasher->addError('error message');
        // $flasher->addFlash('success', 'Data has been saved successfully!');

        $flasher->addFlash('success', 'mensaje', 'Titulo', array( 'duration' => 30000));


                // Step 1: create your notification and add options
        // $builder = $flasher->handler('toastr') // the handle() method here is optional
        // ->type('success')
        // ->message('your custom message')
        // ->priority(2)
        // ->option('timer', 5000);

        // // Step2 : Store the notification in the session
        // $builder->flash();


        Session::put('item','0.');
        return view('home');
    }
}
